<?php
namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Batch;
use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()->schema([
                    TextInput::make('name')->label('Event Name')
                        ->live(onBlur: true)
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation !== 'create') {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        })
                        ->columnSpan('full'),
                    DatePicker::make('date')
                        ->label('Event Date')
                        ->required(),
                    TextInput::make('place')
                        ->label('Event Place'),
                    RichEditor::make('description')
                        ->label('Description')
                        ->required()
                        ->columnSpanFull(),
                    FileUpload::make('images')
                        ->directory('images/eventImages')
                        ->columnSpanFull()
                        ->multiple()
                        ->previewable()
                        ->downloadable(),
                ])->columns(2),

                Fieldset::make('Send mail to specific batches')
                    ->schema([
                        Select::make('batch_ids')
                            ->options(Batch::all()->pluck('code', 'id'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->label('Batch'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                ImageColumn::make('images')
                    ->label('Images')
                    ->wrap()
                    ->stacked()
                    ->alignCenter(),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('place', 'like', "%{$search}%");
                    }),
                TextColumn::make('date')
                    ->date('Y-M-d')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('place'),
                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(30)
                    ->searchable()
                    ->html(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view'   => Pages\ViewEvent::route('/{record}'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}