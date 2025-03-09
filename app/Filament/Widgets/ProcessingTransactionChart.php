<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProcessingTransactionChart extends BaseWidget
{
    protected static ?string $heading = 'Processing Transactions';

    protected static ?int $sort = 3;

    protected array|string|int $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isSuperAdmin() || auth()->user()->isAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->where('status', 'processing')
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('transactionID')
                    ->label('Transaction ID')
                    ->searchable(),
                TextColumn::make('payment.enrollment.student.name')
                    ->label('Student Name')
                    ->searchable(),
                TextColumn::make('payment.enrollment.batch.name')
                    ->searchable(),
                TextColumn::make('payment.payment_type')
                    ->label('Payment Type')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('MMK'),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->date()
                    ->label('Transaction Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->url(fn (Transaction $record): string => TransactionResource::getUrl('view', ['record' => $record])),
                    Tables\Actions\Action::make('edit')
                        ->url(fn (Transaction $record): string => TransactionResource::getUrl('edit', ['record' => $record])),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ]);
    }
}