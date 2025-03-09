<?php
namespace App\Filament\Resources;

use App\Enums\Departments;
use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'Assets Management';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('employee_id')
                        ->label('Employee')
                        ->relationship('employee', 'employee_name')
                        ->required(),
                    Select::make('department')
                        ->preload()
                        ->required()
                        ->options(Departments::class),
                    RichEditor::make('description')
                        ->label('Description')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('assetable.name')
                    ->label('Asset')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item_id')
                    ->label('Item ID')
                    ->searchable()
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('employee.employee_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('department')
                    ->options(Departments::class)
                    ->label('Department'),
                // SelectFilter::make('asset')
                //     ->relationship('assetable', 'name')
                //     ->label('Asset'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->action(function ($record) {
                            Item::deleteItem($record);
                        }),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit'   => Pages\EditItem::route('/{record}/edit'),
            'view'   => Pages\ViewItem::route('/{record}'),
        ];
    }
}