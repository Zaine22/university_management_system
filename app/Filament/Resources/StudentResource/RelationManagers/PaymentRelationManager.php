<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentRelationManager extends RelationManager
{
    protected static ?string $title = 'Payment';

    protected static string $relationship = 'enrollments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment_type')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('payment.paymentID')->label('Payment ID'),
                TextColumn::make('payment.payment_price')->label('Payment'),
                TextColumn::make('payment.status')->badge()->label('Status'),
                TextColumn::make('payment.payment_type')->label('Payment Type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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