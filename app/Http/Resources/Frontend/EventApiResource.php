<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventApiResource extends JsonResource
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
            'event_slug' => $this->event_slug,
            'attributes' => [
                'event_name' => $this->event_name,
                'event_description' => $this->event_description,
                'event_image' => $this->event_image,
                'event_date' => $this->event_date,
                'event_place' => $this->event_place,
            ],
        ];
    }
}