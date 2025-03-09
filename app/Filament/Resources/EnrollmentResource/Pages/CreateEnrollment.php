<?php
namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Enums\PaymentMethod;
use App\Events\EnrollmentCreated;
use App\Filament\Resources\EnrollmentResource;
use App\Jobs\EnrollmentMailJob;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    public function afterCreate()
    {
        $transaction   = reset($this->data['payment']['Transaction']);
        $paymentMethod = $transaction['payment_method'];

        if ($paymentMethod === PaymentMethod::TwoPaymentMethod->value) {
            // Do this for "cash + otherPayment"
            $this->record->twoPaymentMethods($this->data);
            EnrollmentMailJob::dispatch($this->record->student, $this->record);
            event(new EnrollmentCreated($this->record->student, $this->record));

        } else {
            $this->record->getEnrollment($this->data['installment_id'], $this->data['total_discount_amount']);

        }

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}