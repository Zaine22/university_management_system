<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers\StudentRelationManager;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Subject;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Batch Detail')
                    ->schema([
                        Select::make('course_id')
                            ->relationship('course', 'name')
                            ->label('Select Course')
                            ->required()
                            ->preload()
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function (Set $set) {
                                $set('batch', '');
                            }),

                        TextInput::make('batch')
                            ->label('Enter Batch')
                            ->prefix('Batch-')
                            ->disabled(fn(Get $get) => $get('course_id') === null)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get) {
                                $courseID = $get('course_id');

                                $set('code', 'RIBC-' . $courseID . '-' . Str::studly($state));

                                $courseName = Course::find($courseID)->name;

                                $set('name', $courseName . '  Batch - ' . Str::studly($state));
                            })
                            ->numeric(),

                        TextInput::make('code')
                            ->dehydrated()
                            ->readOnly()
                            ->required(),

                        TextInput::make('name')
                            ->dehydrated()
                            ->readOnly()
                            ->required(),
                    ])->columns(2),

                Section::make()
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Enter Course Start Date')
                            ->required(),
                        TextInput::make('duration')
                            ->label('Enter Course Duration Months')
                            ->required(),
                        TextInput::make('total_section_count')
                            ->label('Enter Total Course Sections')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                    ])->columns(3),

                Section::make()
                    ->schema([
                        Select::make('subject_ids')
                            ->options(Subject::all()->pluck('name', 'id'))
                            ->label('Select Subjects')
                            ->preload()
                            ->multiple()
                            ->columnSpanFull()
                            ->required(),

                        RichEditor::make('description')
                            ->label('Batch Description')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('thumbnail')
                            ->directory('images/batch_thumbnails')
                            ->columnSpanFull()
                            ->required()
                            ->openable()
                            ->previewable()
                            ->downloadable()
                            ->image()
                            ->imageEditor(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('code')
                    ->label('Batch Code')
                    ->alignRight(),
                TextColumn::make('course.name')
                    ->label('Course Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Course Description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->limit(25)
                    ->html(),
                TextColumn::make('batch')
                    ->prefix('Batch-')
                    ->label('Batch')
                    ->searchable()
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('start_date')
                    ->label('Starting Date')
                    ->date('Y-M-d')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('duration')
                    ->label('Duration')
                    ->suffix(' months')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignRight(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make(),
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
            StudentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'view'   => Pages\ViewBatch::route('/{record}'),
            'edit'   => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}