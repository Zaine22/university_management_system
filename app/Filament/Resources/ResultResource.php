<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Models\Exam;
use App\Models\Result;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->schema([
                        Select::make('exam_id')
                            ->label('Exam')
                            ->live()
                            ->options(Exam::all()->pluck('examId', 'id'))
                            ->searchable(),

                        Select::make('exam_id')
                            ->label('Exam Title')
                            ->options(Exam::all()->pluck('title', 'id'))
                            ->live(),
                    ])->columns(2),

                Section::make()
                    ->schema([
                        Select::make('student_id')
                            ->label('Student')
                            ->live()
                            ->options(function ($get) {
                                $examId = $get('exam_id');
                                if ($examId) {
                                    $exam = Exam::find($examId);
                                    if ($exam && $exam->batch) {
                                        return $exam->batch->students->pluck('name', 'id');
                                    }
                                }

                                return [];
                            })
                            ->searchable(),

                        Toggle::make('is_present')
                            ->inline(false),

                        TextInput::make('marks')
                            ->label('Marks')
                            ->live(),

                        TextInput::make('grade')
                            ->label('Grade')
                            ->live()
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('exam.examId')
                    ->alignRight(),
                TextColumn::make('exam.batch.name')
                    ->searchable(),
                TextColumn::make('exam.subject.name')
                    ->searchable(),
                TextColumn::make('student.name')
                    ->searchable(),
                TextColumn::make('marks')
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('grade')
                    ->sortable(),
                TextColumn::make('exam.start_date_time')
                    ->sortable()
                    ->label('Exam Date')
                    ->alignCenter()
                    ->date('Y-M-d'),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('Batch')
                    ->relationship('exam.batch', 'name'),
            ])
            ->actions([
                ActionGroup::make([
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
            'index' => Pages\ListResults::route('/'),
            'view' => Pages\ViewResult::route('/{record}'),
            // 'edit' => Pages\EditResult::route('/{record}/edit'),
        ];
    }
}