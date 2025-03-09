<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamResultsImport implements ToModel, WithHeadingRow
{
    protected int $examId;

    public function __construct(int $examId)
    {
        $this->examId = $examId;
    }

    public function model(array $row)
    {
        // Find the related student
        $student = Student::where('register_no', $row['student_id'])->first();

        if ($student) {
            // Retrieve all results for the given exam and student
            $results = Result::where('exam_id', $this->examId)
                ->where('student_id', $student->id)
                ->get();

            if ($results->isNotEmpty()) {
                // Update each result entry
                foreach ($results as $result) {
                    $result->update([
                        'marks' => $row['marks'],
                        'grade' => $row['grade'],
                        'is_present' => strtolower($row['is_present']) === '1' ? 1 : 0,
                    ]);
                }
            }
        }

        // No new records created; just return null
        return null;

    }
}
