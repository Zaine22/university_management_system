<?php

namespace App\Http\Resources\Frontend;


use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\SubjectApiResource;

class BatchApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subjects = SubjectApiResource::collection($this->getSubjects());

        $course = Course::find($this->course_id);

        return [
            'id' => (string) $this->id,
            'batch_slug' => $this->batch_slug,
            'batch_thumbnail' => $this->batch_thumbnail,
            'subjects' => $subjects,
            'course' => $course->course_name,
            'course_slug' => $course->course_slug,
            'attributes' => [
                'batch' => $this->batch,
                'course_batch_code' => $this->course_batch_code,
                'batch_name' => $this->batch_name,
                'batch_thumbnail' => $this->batch_thumbnail,
                'course_sections' => $this->course_sections,
                'course_description' => $this->course_description,
                'course_start_date' => $this->course_start_date,
                'course_duration' => $this->course_duration,
            ],
        ];
    }
}