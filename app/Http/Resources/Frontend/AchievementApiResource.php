<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'certificateID' => $this->certificateID,
            'student' => $this->student,
            'batch' => $this->batch,
            'certificates' => $this->certificates,

        ];
    }
}