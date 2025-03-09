<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers\MarkRelationManager;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Exam;
use App\Models\GradingRule;
use App\Models\Subject;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $lastExamNo = Exam::latest()->first();
        $lastNumber = $lastExamNo ? (int) str_replace('RIEXAM-', '', $lastExamNo->register_no) : 499;
        $newExamNo  = 'RIEXAM-' . ($lastNumber + 1);

        return $form
            ->schema([Section::make()->schema([

                TextInput::make('title')
                    ->label('Exam Title')
                    ->required(),

                TextInput::make('examId')
                    ->label('Exam ID')
                    ->default($newExamNo)
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Select::make('grading_rule_id')
                    ->options(GradingRule::all()->pluck('name', 'id'))
                    ->required()
                    ->label('Grading Rule'),

                Fieldset::make('Exam Details')
                    ->schema([
                        Select::make('batch_id')
                            ->label('Batch')
                            ->live()
                            ->options(Batch::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('subject_id')
                            ->label('Subject')
                            ->preload()
                            ->live()
                            ->required()
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
                        Select::make('chapter_ids')
                            ->label('Chapter')
                            ->multiple()
                            ->preload()
                            ->live()
                            ->hidden(function (Get $get) {
                                $subjectId = $get('subject_id');
                                $subject   = Subject::find($subjectId);
                                if ($subject === null) {
                                    return true;
                                }
                                if ($subject === null || is_null($subject->chapter_ids)) {
                                    return true;
                                }

                                return false;
                            })
                            ->options(function (Get $get) {
                                $subject = Subject::find($get('subject_id'));
                                if ($subject) {
                                    return Chapter::whereIn('id', $subject->chapter_ids)->pluck('name', 'id');
                                }

                                return [];
                            })
                            ->columnSpanFull(),
                    ])->columns(2),

                Grid::make()
                    ->schema([
                        DateTimePicker::make('start_date_time')
                            ->seconds(false)
                            ->required(),

                        DateTimePicker::make('end_date_time')
                            ->seconds(false)
                            ->required(),
                    ]),
            ])->columns(2)]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('examId')
                    ->label('Exam ID')
                    ->searchable()
                    ->alignRight(),
                TextColumn::make('title')
                    ->searchable()
                    ->label('Exam Title'),
                TextColumn::make('batch.name')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->searchable(),
                TextColumn::make('start_date_time')
                    ->sortable()
                    ->date('Y-M-d H:i')
                    ->alignCenter(),
                TextColumn::make('end_date_time')
                    ->sortable()
                    ->date('Y-M-d H:i')
                    ->alignCenter(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('Batch')
                    ->relationship('batch', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading(fn($record) => 'Do you want to DELETE exam?')
                        ->modalDescription(fn($record) => 'This action will also permanently delete all results related to this exam. Are you sure you want to proceed?'),
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
            MarkRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'view'   => Pages\ViewExam::route('/{record}'),
            'edit'   => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}