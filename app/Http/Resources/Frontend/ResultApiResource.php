<?php

namespace App\Http\Resources\Frontend;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chapters = $this->exam->chapter_ids == null ? [] : Chapter::whereIn('id', $this->exam->chapter_ids)->pluck('chapter_name')->toArray();

        return [
            'id' => $this->id,
            'student_id' => $this->student->id,
            'student' => $this->student->student_name,
            'student_register_no' => $this->student->register_no,
            'attribute' => [
                'exam_id' => $this->exam->examId,
                'exam_title' => $this->exam->exam_title,
                'batch' => $this->exam->batch->batch_name,
                'subject' => $this->exam->subject->subject_name,
                'chapter_names' => $chapters,
                'grade' => $this->grade,
                'is_present' => $this->is_present,
            ],
        ];
    }
}