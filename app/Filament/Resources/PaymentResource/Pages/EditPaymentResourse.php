<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Jobs\PaymentMailJob;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentResourse extends EditRecord
{
    protected static string $resource = PaymentResource::class;

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
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function afterSave(): void
    {
        // parent::afterSave();
        // Call handleRecordUpdate() after saving the record
        // PaymentResource::handleRecordUpdate($this->record);

        $paymentId = $this->data['id'];
        $transactions = Transaction::where('payment_id', $paymentId)->get();
        if (
            $transactions->every(function ($transaction) {
                $transaction->status === 'approved';
            })
        ) {
            $paymentId->update(['status' => 'approved']);
        }

        if ($this->record->status == 'approved') {
            PaymentMailJob::dispatch($this->record->enrollment->student, $this->record);
        }
    }
}
