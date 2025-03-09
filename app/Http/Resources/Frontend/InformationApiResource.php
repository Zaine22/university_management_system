<?php

namespace App\Http\Resources\Frontend;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chapters = $this->chapter_ids == null ? [] : Chapter::whereIn('id', $this->chapter_ids)->pluck('chapter_name')->toArray();

        return [
            'id' => $this->id,
            'exam_id' => $this->examId,
            'attributes' => [
                'exam_title' => $this->exam_title,
                'batch' => $this->batch->batch_name,
                'grading_rule' => $this->gradingRule->rule_name,
                'subject' => $this->subject->subject_name,
                'chapter_names' => $chapters,
                'start_date_time' => $this->start_date_time,
                'end_date_time' => $this->end_date_time,
            ],
        ];
    }
}