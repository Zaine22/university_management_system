<?php

namespace App\Filament\Resources;

use App\Enums\PaymentStatus;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\PaymentResource\RelationManagers\TransactionsRelationManager;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Payment';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                        TextInput::make('payment_price')
                            ->label('Payment Amount'),
                        Select::make('payment_type')
                            ->options([
                                'cash_down' => 'Cash_down',
                                'installment' => 'Installment',
                            ]),
                        ToggleButtons::make('status')
                            ->inline()
                            ->options(PaymentStatus::class)
                            ->required()
                            ->label('Payment Type'),
                    ]),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('paymentID')->label('Payment ID')
                    ->alignRight(),
                TextColumn::make('enrollment.student.name')->searchable(),
                TextColumn::make('enrollment.batch.name')->searchable(),
                TextColumn::make('status')->badge()->alignCenter(),
                TextColumn::make('payment_price')->label('Payment')->alignRight(),
                TextColumn::make('payment_type')->sortable(),
                TextColumn::make('created_at')
                    ->date('d-m-Y')
                    ->label('Payment Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('course')
                    ->relationship('enrollment.batch', 'name'),
                Filter::make('Cash Down')
                    ->query(fn (Builder $query): Builder => $query->where('payment_type', 'cash_down')),
                Filter::make('Installment')
                    ->query(fn (Builder $query): Builder => $query->where('payment_type', 'installment')),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'from '.Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'until '.Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading(fn ($record) => 'Do you want to DELETE enrollment?')
                        ->modalDescription(fn ($record) => 'This action will also permanently delete Transaction related to this enrollment. Are you sure you want to proceed?'),
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
            StudentRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    protected static function handleRecordUpdate($record)
    {
        if ($record->transactions()->fristOrFail()->status === 'processing') {
            $record->update(['status' => 'processing']);
        }

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentResourses::route('/'),
            'create' => Pages\CreatePaymentResourse::route('/create'),
            'view' => Pages\ViewPaymentResourse::route('/{record}'),
            'edit' => Pages\EditPaymentResourse::route('/{record}/edit'),
        ];
    }
}