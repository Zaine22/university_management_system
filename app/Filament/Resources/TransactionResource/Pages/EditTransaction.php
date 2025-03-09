<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\PaymentMethod;
use App\Events\TransactionStatus;
use App\Filament\Resources\TransactionResource;
use App\Jobs\PaymentMailJob;
use App\Jobs\TransactionMailJob;
use App\Models\Payment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['record' => $this->getRecord()]);
    }

    protected function afterSave(): void
    {
        event(new TransactionStatus($this->record->payment->enrollment->student, $this->record, $this->record->status));

        $payment = Payment::find($this->data['payment_id']);
        $payment_type = Payment::find($this->data['payment_id'])->payment_type;

        // TwoPayment Method
        if ($this->data['payment_method'] === PaymentMethod::TwoPaymentMethod->value) {
            $this->record->twoPaymentTransactionMethods($this->data, $this->record);
        }

        if ($this->data['status'] === 'approved' && $payment_type === 'cashdown') {
            $payment->update(['status' => 'approved']);
        }
        if ($this->data['status'] === 'approved' && $payment_type === 'installment') {
            $payment->update(['status' => 'processing']);
        }
        if ($payment->transactions()->where('status', '!=', 'approved')->count() == 0) {
            $payment->update(['status' => 'approved']);
            PaymentMailJob::dispatch($this->record->payment->enrollment->student, $this->record->payment);
        }

        if ($this->record->status == 'approved') {
            TransactionMailJob::dispatch($this->record->payment->enrollment->student, $this->record);
        }

        event(new TransactionStatus($this->record->payment->enrollment->student, $this->record, $this->record->status));

    }
}
