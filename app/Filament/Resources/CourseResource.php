<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\InstallmentsRelationManager;
use App\Models\Course;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 2;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->label('Course Name')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                })
                                ->required(),
                            Select::make('category')
                            // ->multiple()
                                ->preload()
                                ->required()
                                ->options([
                                    'Bachelor'    => 'Bachelor',
                                    'Diploma'     => 'Diploma',
                                    'Certificate' => 'Certificate',
                                    'Master'      => 'Master',
                                ]),

                            Fieldset::make('Course Fee Detail')
                                ->schema([
                                    TextInput::make('price')
                                        ->label('Course Price')
                                        ->required()
                                        ->numeric()
                                        ->suffix('MMK')
                                        ->minValue('100000'),

                                    Toggle::make('installable')
                                        ->label('Course Installment Avalidable?')
                                        ->inline(false)
                                        ->live()
                                        ->label('Installable'),

                                    Section::make('Installment_form')
                                        ->description('Installment Preparation Form')
                                        ->hidden(fn(Get $get): bool => ! $get('course_installable'))
                                        ->schema([
                                            TextInput::make('installment_price')
                                                ->label('Course Installation Price')
                                                ->integer()
                                                ->required(),
                                            TextInput::make('down_payment')
                                                ->label('Course Down Payment Price')
                                                ->integer()
                                                ->required(),
                                            TextInput::make('months')
                                                ->integer()
                                                ->required()
                                                ->label('Total Installment Months'),
                                            TextInput::make('monthly_payment_amount')
                                                ->integer()
                                                ->required()
                                                ->label('Monthly Payment Amount'),
                                        ])->columns(2),
                                ])->columns(2),

                            FileUpload::make('thumbnail')
                                ->directory('images/thumbnails')
                                ->image()
                                ->imageEditor()
                                ->columnSpanFull()
                                ->required()
                                ->previewable()
                                ->downloadable(),

                            RichEditor::make('description')
                                ->label('Course Description')
                                ->columnSpanFull()
                                ->required(),
                        ])->columns(2),
                ]
            );
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
                TextColumn::make('name')
                    ->label('Course Name')
                    ->limit(25)
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    }),
                TextColumn::make('price')
                    ->label('Course Price')
                    ->money('MMK')
                    ->alignRight(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(25)
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('installable')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('installment_price')
                    ->label('Installment Price')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('down_payment')
                    ->label('Installment Down Payment')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('months')
                    ->label('Installment Total Month')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('monthly_payment_amount')
                    ->label('Installment Monthly Payment')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                Filter::make('Installment Plan')
                    ->query(fn(Builder $query): Builder => $query->where('course_installable', true)),
                Filter::make('Not Installment Plan')
                    ->query(fn(Builder $query): Builder => $query->where('course_installable', false)),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->action(function ($record) {
                            if ($record->batches->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('This can not be deleted!')
                                    ->body('You must delete all batches related to this student before proceeding.')
                                    ->send();

                                return;
                            }

                            $record->delete();

                            Notification::make()
                                ->success()
                                ->title('Deleted successfully!')
                                ->body('Course deleted successfully.')
                                ->send();
                        }),
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
                            ->withFilename(fn($resource) => $resource::getLabel())
                            ->except(['No', 'thumbnail', 'installable'])
                            ->withColumns([
                                Column::make('name')->heading('Course Name')->width(100),
                                Column::make('price')->heading('Course Price')->format(NumberFormat::FORMAT_CURRENCY_EUR_INTEGER),
                                Column::make('description')->heading('Description'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InstallmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view'   => Pages\ViewCourse::route('/{record}'),
            'edit'   => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}