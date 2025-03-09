<?php
namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (string) $this->id,
            'transactionID' => $this->transactionID,
            'attributes'    => [
                'fees'           => $this->fees,
                'payment_method' => $this->payment_method,
                'status'         => $this->status,
                'amount'         => $this->amount,
                'due_date'       => $this->due_date,
            ],
        ];
    }
}
