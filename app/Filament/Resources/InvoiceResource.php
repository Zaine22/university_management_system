<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers\TransactionsRelationManager;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Payment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Invoice Information')
                    ->schema([
                        TextInput::make('invoiceID')
                            ->label('Invice ID')
                            ->default('INV-'.random_int(1000000000, 9999999999))
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        DatePicker::make('date')
                            ->default(now()),
                    ]),
                // Repeater::make('transaction_ids')
                //     ->schema([
                //         Select::make('transaction_ids')
                //             ->label('Transaction ID')
                //             ->options(Transaction::query()->where('status', 'new')->pluck('transactionID', 'id')->toArray()),
                //     ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('invoiceID')
                    ->label('Invoice ID')
                    ->alignRight(),
                TextColumn::make('enrollment.enrollmentID')
                    ->label('Enrollment ID')
                    ->alignRight(),
                TextColumn::make('enrollment.student.name')
                    ->label('Student Name'),
                TextColumn::make('enrollment.batch.name')
                    ->label('Student Name'),
                TextColumn::make('date')
                    ->date('Y-M-d H:i')
                    ->alignCenter(),
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

                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}/view'),
            // 'transaction' => Pages\ViewTransactions::route('/{record}/transactions'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            StudentRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    public static function printInvoice(Invoice $invoice)
    {
        // $workerName = str($invoice->worker_name)->replace(' ', '')->headline();
        $invoiceDate = $invoice->date;
        $workerName = ' ';
        $fileName = "invoice_{$invoiceDate}_{$workerName}.pdf";
        $total = 0;

        $remainingAmount = 0;
        foreach ($invoice->transactions as $transaction) {
            if ($transaction->status !== 'approved') {
                $remainingAmount += $transaction->amount;
            }
        }

        $paidAmount = 0;
        foreach ($invoice->transactions as $transaction) {
            if ($transaction->status == 'approved') {
                $paidAmount += $transaction->amount;
            }
        }

        $pdf = Pdf::loadView('invoice', compact('invoice', 'remainingAmount', 'paidAmount'));

        return response()->streamDownload(fn () => print ($pdf->output()), $fileName);
    }
}