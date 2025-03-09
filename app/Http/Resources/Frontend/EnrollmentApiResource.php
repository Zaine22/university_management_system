<?php
namespace App\Http\Resources\Frontend;

use App\Http\Resources\Frontend\InvoiceApiResource;
use App\Http\Resources\Frontend\PaymentApiResource;
use App\Http\Resources\Frontend\TransactionApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => (string) $this->id,
            'enrollmentID' => (string) $this->enrollmentID,
            'payment'      => new PaymentApiResource($this->payment),
            'transactions' => TransactionApiResource::collection($this->transactions),
            'invoice'      => new InvoiceApiResource($this->invoice),
            'attributes'   => [
                'has_installment_plan'       => $this->has_installment_plan,
                'enrollment_payment_amount'  => $this->enrollment_payment_amount,
                'discount_percentage'        => $this->discount_percentage,
                'discounted_payment_amount'  => $this->discounted_payment_amount,
                'additional_discount_amount' => $this->additional_discount_amount,
                'total_payment_amount'       => $this->total_payment_amount,
            ],
        ];
    }
}
