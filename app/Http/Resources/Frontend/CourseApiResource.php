<?php

namespace App\Http\Resources\Frontend;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\BatchApiResource;

class CourseApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $batches = BatchApiResource::collection($this->batches);

        return [
            'id' => (string) $this->id,
            'course_slug' => $this->course_slug,
            'attributes' => [
                'course_name' => $this->course_name,
                'course_thumbnail' => (string) $this->course_thumbnail,
                'course_price' => $this->course_price,
                'category' => $this->category,
                'course_description' => $this->course_description,
                'course_installable' => $this->course_installable,
                'course_installment_price' => $this->course_installment_price,
                'course_down_payment' => $this->course_down_payment,
                'months' => $this->months,
                'monthly_payment_amount' => $this->monthly_payment_amount,
            ],
            'latest_batch' => new BatchApiResource($this->batches->first()),
            'batches' => $batches,

        ];
    }
}