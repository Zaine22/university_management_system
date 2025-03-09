<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $processing_transactions = $this->transactions->where('status', 'processing')->count();
        $paid_transactions = $this->transactions->where('status', 'paid')->count();

        // $warning_due_date = $this->transactions->where('status', 'new')->first()->due_date;
        return [
            'id' => $this->id,
            'paymentID' => $this->paymentID,
            'attributes' => [
                'status' => $this->status,
                'invoioce_id' => $this->invoioce_id,
                'enrollment_id' => $this->enrollment_id,
                'student_id' => $this->student_id,
                'payment_type' => $this->payment_type,
                'payment_price' => $this->payment_price,
            ],
            'processing_transactions' => $processing_transactions,
            'paid_transactions' => $paid_transactions,
            // 'next_transaction_payment_date' => $warning_due_date,
            // "transactions" => TransactionApiResource::collection($this->transactions),
        ];
    }
}