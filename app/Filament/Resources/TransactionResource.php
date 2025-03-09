<?php
namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Payment';

    protected static ?string $pollingInterval = '10s';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'processing')->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'success';
    }

    // disable to create new transaction
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
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
                        // Select::make('payment_method')
                        //     ->options(PaymentMethod::class)
                        //     ->label('Payment Method')
                        //     ->reactive() // Make it reactive to listen for changes
                        //     ->afterStateUpdated(fn(callable $set) => $set('transaction_ss', null)),
                        // FileUpload::make('transaction_ss')
                        //     ->required()
                        //     ->label('Transaction Approval')
                        //     ->image()
                        //     ->imageEditor()
                        //     ->directory('images/transactions')
                        //     ->previewable()
                        //     ->downloadable(),

                        Section::make()
                            ->schema([
                                Select::make('payment_method')
                                    ->options(PaymentMethod::class)
                                    ->label('Payment Method')
                                    ->reactive() // Make it reactive to listen for changes
                                    ->afterStateUpdated(fn(callable $set) => $set('transaction_proof', null)),

                                Section::make('Two Payment Methods')
                                    ->description('Prevent abuse by limiting the number of requests per period')
                                    ->visible(fn(callable $get) => $get('payment_method') === PaymentMethod::TwoPaymentMethod->value)
                                    ->schema([
                                        Select::make('payment_method_1')
                                            ->options(PaymentMethod::optionsIncludingCash())
                                            ->label('Payment Method 1')
                                            ->reactive() // Make it reactive to listen for changes
                                            ->afterStateUpdated(fn(callable $set) => $set('transaction_proof', null)),
                                        TextInput::make('first_payment_method_amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->required(),
                                        FileUpload::make('first_transaction_proof')
                                            ->label('Transaction Screenshot')
                                            ->image()
                                            ->imageEditor()
                                            ->previewable()
                                            ->downloadable()
                                            ->deletable(false)
                                            ->columnSpanFull()
                                            ->directory('images/transactions')
                                            ->required(fn(callable $get) => $get('payment_method_1') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                            ->visible(fn(callable $get) => $get('payment_method_1') !== PaymentMethod::Cash->value),
                                        Select::make('payment_method_2')
                                            ->options(PaymentMethod::filteredCases())
                                            ->label('Payment Method 2')
                                            ->reactive() // Make it reactive to listen for changes
                                            ->afterStateUpdated(fn(callable $set) => $set('transaction_proof', null)),
                                        TextInput::make('second_payment_method_amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->required(),
                                        FileUpload::make('second_transaction_proof')
                                            ->label('Transaction Screenshot')
                                            ->image()
                                            ->imageEditor()
                                            ->previewable()
                                            ->downloadable()
                                            ->deletable(false)
                                            ->columnSpanFull()
                                            ->directory('images/transactions')
                                            ->required(fn(callable $get) => $get('payment_method_2') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                            ->visible(fn(callable $get) => $get('payment_method_2') !== PaymentMethod::Cash->value),
                                    ])->columns(2),

                                FileUpload::make('transaction_proof')
                                    ->label('Transaction Screenshot')
                                    ->image()
                                    ->imageEditor()
                                    ->previewable()
                                    ->downloadable()
                                    ->deletable(false)
                                    ->directory('images/transactions')
                                    ->required(fn(callable $get) => $get('payment_method') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                    ->visible(
                                        fn(callable $get) => ! in_array($get('payment_method'), [
                                            PaymentMethod::Cash->value,
                                            PaymentMethod::TwoPaymentMethod->value,
                                        ])
                                    ),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('transactionID')
                    ->label('Transaction ID')
                    ->searchable()
                    ->alignRight(),
                TextColumn::make('payment.enrollment.student.name')
                    ->label('Student Name')
                    ->searchable(),
                TextColumn::make('payment.enrollment.batch.name')
                    ->searchable()
                    ->limit(15),
                TextColumn::make('amount')
                    ->money('MMK')
                    ->alignRight(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('due_date')
                    ->date('Y-M-d')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('payment.payment_type')
                    ->label('Payment Type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('course')
                    ->relationship('payment.enrollment.batch', 'name'),
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
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->except(['No']),
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
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view'   => Pages\ViewTransaction::route('/{record}'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}