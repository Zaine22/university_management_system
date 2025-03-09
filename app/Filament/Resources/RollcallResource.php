<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\RollcallResource\Pages;
use App\Models\Rollcall;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class RollcallResource extends Resource
{
    protected static ?string $model = Rollcall::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->isTeacher()) {
            return $query->whereHas('rollcallable', function ($query) {
                $query->whereHas('timetable', function ($query) {
                    $query->where('teacher_id', auth()->user()->teacher->id);
                });
            });
        }

        return $query->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    DateTimePicker::make('date'),
                    Select::make('student_id')
                        ->relationship('student', 'name'),
                    // Select::make('status')
                    //     ->options(AttendanceStatus::class),
                    ToggleButtons::make('status')
                        ->inline()
                        ->options(AttendanceStatus::class)
                        ->required(),
                    RichEditor::make('reason')->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('date')
                    ->date('Y-M-d')
                    ->alignCenter(),
                TextColumn::make('student.name'),
                TextColumn::make('rollcallable.batch.name'),
                TextColumn::make('rollcallable.subject.name'),
                TextColumn::make('status')
                    ->alignCenter(),
                TextColumn::make('rollcallable.timetable.starts_at')
                    ->date('H:i')
                    ->label('Start Time')
                    ->alignCenter(),
                TextColumn::make('rollcallable.timetable.ends_at')
                    ->date('H:i')
                    ->label('End Time')
                    ->alignCenter(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->visible(fn ($record) => ($record ? $record->rollcallable->submitted : 1) === 0)->color('info'),
                    Tables\Actions\ViewAction::make(),

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
            'index' => Pages\ListRollcalls::route('/'),
            'create' => Pages\CreateRollcall::route('/create'),
            'edit' => Pages\EditRollcall::route('/{record}/edit'),
            'view' => Pages\ViewRollcall::route('/{record}/view'),
        ];
    }
}