<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimetableApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'attributes' => [
                'tacher' => $this->teacher->teacher_name,
                'batch' => $this->batch->batch_name,
                'course' => $this->batch->course->course_name,
                'course_sections' => $this->batch->course_sections,
                'course_description' => $this->batch->course->course_description,
                'course_start_date' => $this->batch->course_start_date,
                'course_duration' => $this->batch->course_duration,
                'subject' => $this->subject ? $this->subject->subject_name : 'NULL',
                'starts_at' => $this->starts_at,
                'ends_at' => $this->ends_at,
            ],
        ];
    }
}