<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Models\Invoice;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = Invoice::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transactionID'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // ...
            ]);
    }
}
