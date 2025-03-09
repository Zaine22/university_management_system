<?php

namespace App\Exports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamResultExport implements FromArray, WithColumnWidths, WithHeadings
{
    use Exportable;

    protected int $ID;

    public function __construct(int $ID)
    {
        $this->ID = $ID;
    }

    public function headings(): array
    {
        // Custom column names
        return [
            'No',
            'Exam ID',
            'Student ID',
            'Student Name',
            'Marks',
            'Grade',
            'Is Present',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 20,
            'D' => 30,
        ];
    }

    public function array(): array
    {
        $results = Result::where('exam_id', $this->ID)->get();

        return $results->map(function ($result, $index) {
            return [
                'No' => $index + 1,
                'Exam ID' => $result->exam->examId,
                'Student ID' => $result->student->register_no,
                'Student Name' => $result->student->student_name,
                'Marks' => $result->marks,
                'Grade' => $result->grade,
                'Is Present' => $result->is_present,
            ];
        })->toArray();
    }
}
