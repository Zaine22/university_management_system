<?php
namespace App\Filament\Resources;

use App\Filament\Resources\GradingRuleResource\Pages;
use App\Models\GradingRule;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradingRuleResource extends Resource
{
    protected static ?string $model = GradingRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('total_marks')
                            ->required()
                            ->numeric(),
                    ])->columns(2),
                Grid::make('')
                    ->schema([
                        Repeater::make('grade_rules')
                            ->schema([
                                TextInput::make('min')->label('Mark From')->required(),
                                TextInput::make('max')->label('Mark To')->required(),
                                TextInput::make('grade')->required(),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('name'),
                // TextColumn::make('grade_rules'),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                //
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
            'index'  => Pages\ListGradingRules::route('/'),
            'create' => Pages\CreateGradingRule::route('/create'),
            'view'   => Pages\ViewGradingRule::route('/{record}'),
            'edit'   => Pages\EditGradingRule::route('/{record}/edit'),
        ];
    }
}