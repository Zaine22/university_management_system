<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attributes' => [
                'subject_name' => $this->subject_name,
                'subject_description' => $this->subject_description,
                'subject_thumbnail' => $this->subject_thumbnail,
                'subject_mark' => $this->subject_mark,
            ],
        ];
    }
}