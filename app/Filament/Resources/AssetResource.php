<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers\ItemsRelationManager;
use App\Models\Assets;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Assets::class;

    protected static ?string $navigationIcon = 'heroicon-m-swatch';

    // protected static ?string $navigationGroup = 'Assets Management';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {

        $lastAssetNo = Assets::latest()->first();
        $lastNumber  = $lastAssetNo ? (int) str_replace('RI-FF-', '', $lastAssetNo->asset_id) : 0;
        $newAssetrNo = 'RI-FF-' . ($lastNumber + 1);

        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Item Name')
                        ->required(),
                    TextInput::make('asset_id')
                        ->default($newAssetrNo)
                        ->label('Asset ID')
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    TextInput::make('count')
                        ->label('Total')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpanFull(),
                    RichEditor::make('description')
                        ->label('Description')
                        ->required()
                        ->columnSpanFull(),
                    FileUpload::make('photos')
                        ->label('Item Photos')
                        ->previewable()
                        ->openable()
                        ->downloadable()
                        ->multiple()
                        ->hint('Maximum 4 files can be upload')
                        ->maxFiles(4)
                        ->directory('images/assets_photos')
                        ->acceptedFileTypes(['application/pdf', 'image/*'])
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                ImageColumn::make('photos')
                    ->stacked()
                    ->alignCenter(),
                TextColumn::make('name')
                    ->label('Name')->sortable()->searchable()->alignLeft(),
                TextColumn::make('asset_id')
                    ->label('AssetID')->sortable()->searchable()->alignRight(),
                TextColumn::make('count')
                    ->label('Total')->sortable()->alignRight(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading(fn($record) => 'Do you want to DELETE enrollment?')
                        ->modalDescription(fn($record) => 'This action will also permanently delete Item Resources related to this enrollment. Are you sure you want to proceed?'),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit'   => Pages\EditAsset::route('/{record}/edit'),
            'view'   => Pages\ViewAsset::route('/{record}'),
        ];
    }
}