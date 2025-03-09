<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Installment Plan Name')
                    ->required(),
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('installment_price'),
                Tables\Columns\TextColumn::make('down_payment'),
                Tables\Columns\TextColumn::make('months'),
                Tables\Columns\TextColumn::make('monthly_payment_amount'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
