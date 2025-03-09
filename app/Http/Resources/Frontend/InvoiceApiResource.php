<?php
namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\TransactionApiResource;

class InvoiceApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'invoiceID'    => $this->invoiceID,

            'attributes'   => [
                'enrollment_id' => $this->enrollment_id,
                'date'          => $this->date,
            ],
            'transactions' => TransactionApiResource::collection($this->transactions),
        ];
    }
}