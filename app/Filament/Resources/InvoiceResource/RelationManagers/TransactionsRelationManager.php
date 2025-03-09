<?php
namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Enums\TransactionStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('amount')->label('Transaction Amount'),
                        ToggleButtons::make('status')
                            ->inline()
                            ->options(TransactionStatus::class)
                            ->required(),
                        FileUpload::make('transaction_proof')->label('Transaction Approval')->image()->directory('images/transactions')->previewable()->downloadable(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('transactionID')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('transactionID')->label('Transaction ID'),
                ImageColumn::make('transaction_proof')->label('Transaction Screenshot'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('MMK'),
                TextColumn::make('status')->badge(),
                TextColumn::make('due_date')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}