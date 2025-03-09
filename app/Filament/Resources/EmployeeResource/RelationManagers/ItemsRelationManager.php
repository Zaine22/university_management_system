<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Enums\Departments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected function canCreate(): bool
    {
        return false; // Disable the create button
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Name')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('assetable.name')
                    ->label('Asset')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item_id')
                    ->label('Item ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.employee_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->options(Departments::class)
                    ->label('Department'),
                // SelectFilter::make('asset')
                //     ->relationship('assetable', 'name')
                //     ->label('Asset'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
