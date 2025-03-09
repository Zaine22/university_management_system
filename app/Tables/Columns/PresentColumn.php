<?php

namespace App\Tables\Columns;

use App\Models\Attendance;
use App\Models\Student;
use Filament\Tables\Columns\Column;

class PresentColumn extends Column
{
    protected string $view = 'tables.columns.present-column';

    // public function getData($record)
    // {
    //     $stats[] = [
    //         'present_count' => 6,
    //         'leave_count' => 3,
    //         'absent_count' => 2,
    //     ];

    //     return $stats;
    // }

    // if (!$student) {
    //     return [];
    // }

    public function getData($studentId): array
    {
        $student = Student::find($studentId);

        $enrollments = $student->enrollments;
        $stats = [];

        foreach ($enrollments as $enrollment) {
            $batchId = $enrollment->batch_id;
            $batchName = $enrollment->batch->batch_name;

            $presentCount = Attendance::where('batch_id', $batchId)->whereJsonContains('present_students', $studentId)->count();
            $absentCount = Attendance::where('batch_id', $batchId)->whereJsonContains('absent_students', $studentId)->count();
            $leaveCount = Attendance::where('batch_id', $batchId)->whereJsonContains('leave_students', $studentId)->count();

            $stats[] = [
                'present_count' => $presentCount,
                'leave_count' => $leaveCount,
                'absent_count' => $absentCount,
                'batch_name' => $batchName,
            ];
        }

        return $stats;
    }
}
