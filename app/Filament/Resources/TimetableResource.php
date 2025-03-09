<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Timetable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
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

class TimetableResource extends Resource
{
    protected static ?string $model = Timetable::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->isTeacher()) {
            return parent::getEloquentQuery()->where('teacher_id', auth()->user()->teacher->id);
        }

        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()->schema([
                    Select::make('teacher_id')
                        ->relationship('teacher', 'name')
                        ->required()
                        ->preload()
                        ->searchable(),
                    Select::make('batch_id')
                        ->label('Batch')
                        ->options(Batch::all()->pluck('code', 'id'))
                        ->required()
                        ->live()
                        ->preload()
                        ->searchable()
                        ->native(false),
                    Select::make('subject_id')
                        ->label('Subject')
                        ->required()
                        ->preload()
                        ->live()
                        ->disabled(function (Get $get) {
                            $batchId = $get('batch_id');
                            $batch   = Batch::find($batchId);
                            if ($batchId === null) {
                                return true;
                            }
                            if ($batch === null || is_null($batch->subject_ids)) {
                                return true;
                            }

                            return false;
                        })
                        ->options(function (Get $get) {
                            $batch = Batch::find($get('batch_id'));
                            if ($batch) {
                                return Subject::whereIn('id', $batch->subject_ids)->pluck('name', 'id');
                            }

                            return [];
                        }),
                    Select::make('chapter_id')
                        ->label('Chapter')
                        ->preload()
                        ->live()
                        ->disabled(function (Get $get) {
                            $subjectId = $get('subject_id');
                            $subject   = Subject::find($subjectId);
                            if ($subjectId === null) {
                                return true;
                            }
                            if ($subject === null || is_null($subject->chapter_ids)) {
                                return true;
                            }

                            return false;
                        })
                        ->options(function (Get $get) {
                            $subject = Subject::find($get('subject_id'));
                            if ($subject && $subject->chapter_ids !== null) {
                                return Chapter::whereIn('id', $subject->chapter_ids)->pluck('name', 'id');
                            }

                            return [];
                        }),
                    Grid::make()
                        ->schema([
                            DateTimePicker::make('starts_at')
                                ->required()
                                ->seconds(false),
                            DateTimePicker::make('ends_at')
                                ->required()
                                ->seconds(false),
                        ]),

                ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('teacher.name')
                    ->searchable(),
                TextColumn::make('batch.code')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->searchable()
                    ->label('Subject'),
                TextColumn::make('starts_at')
                    ->date('Y-M-d H:i')
                    ->label('Start')
                    ->alignCenter(),
                TextColumn::make('ends_at')
                    ->date('Y-M-d H:i')
                    ->label('End')
                    ->alignCenter(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('Batch')
                    ->relationship('batch', 'name'),
                SelectFilter::make('Teacher')
                    ->relationship('teacher', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->action(function ($record) {
                            // Delete related rollcalls
                            foreach ($record->attendances as $attendance) {
                                $attendance->rollcalls()->delete();
                            }
                            // Delete related attendances
                            $record->attendances()->delete();

                            $record->delete();
                        })
                        ->modalHeading(fn($record) => 'Do you want to DELETE enrollment?')
                        ->modalDescription(fn($record) => 'This action will also permanently delete Attendances and Rollcalls related to this enrollment. Are you sure you want to proceed?'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTimetables::route('/'),
            'create' => Pages\CreateTimetable::route('/create'),
            'view'   => Pages\ViewTimetable::route('/{record}'),
            'edit'   => Pages\EditTimetable::route('/{record}/edit'),
        ];
    }
}