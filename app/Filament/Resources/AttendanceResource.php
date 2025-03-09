<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers\RollcallsRelationManager;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Timetable;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 6;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->isTeacher()) {
            return $query->whereHas('timetable', function ($query) {
                $query->where('teacher_id', auth()->user()->teacher->id);
            });
        }

        return $query->withoutGlobalScopes();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        DatePicker::make('date'),
                        // Select::make('timetable_id')
                        //     ->relationship('timetable', 'id'),
                        Placeholder::make('starts_at')
                            ->content(function ($record) {
                                $timetable = $record->timetable_id;
                                $start     = Timetable::where('id', $timetable)->pluck('starts_at')->first();

                                return $start;
                            }),

                        Placeholder::make('ends_at')
                            ->content(function ($record) {
                                $timetable = $record->timetable_id;
                                $end       = Timetable::where('id', $timetable)->pluck('ends_at')->first();

                                return $end;
                            }),
                    ])->columns(3),

                Section::make()
                    ->schema([
                        Select::make('batch_id')
                            ->relationship('batch', 'name')
                            ->live(),
                        Select::make('subject_id')
                            ->live()
                            ->preload()
                            ->options(function (Get $get) {
                                $batch = Batch::find($get('batch_id'));
                                if ($batch) {
                                    return Subject::whereIn('id', $batch->subject_ids)->pluck('name', 'id');
                                }

                                return [];
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('date')
                    ->date('Y-M-d')
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('batch.code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timetable.teacher.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timetable.starts_at')
                    ->date('H:i')
                    ->label('Start Time')
                    ->alignCenter(),
                TextColumn::make('timetable.ends_at')
                    ->date('H:i')
                    ->label('End Time')
                    ->alignCenter(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('Teacher')
                    ->relationship('timetable.teacher', 'name'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->visible(fn($record) => $record->submitted === 0)->color('info'),

                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->except(['No']),
                    ]),
            ]);
    }

    public static function submitted(Attendance $attendance)
    {

        $present = $attendance->rollcalls()->where('status', 'present')->pluck('student_id')->toArray();

        // $presentStudents = Student::whereIn('id', $present)->get();

        $absent = $attendance->rollcalls()->where('status', 'absent')->pluck('student_id')->toArray();
        $leave  = $attendance->rollcalls()->where('status', 'leave')->pluck('student_id')->toArray();

        $attendance->update([
            'submitted'        => true,
            'teacher_id'       => $attendance->timetable->teacher_id,
            'present_students' => $present,
            'absent_students'  => $absent,
            'leave_students'   => $leave,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RollcallsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit'   => Pages\EditAttendance::route('/{record}/edit'),
            'view'   => Pages\ViewAttendance::route('/{record}/view'),
        ];
    }
}