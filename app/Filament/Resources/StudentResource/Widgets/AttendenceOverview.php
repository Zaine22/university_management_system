<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Filament\Resources\StudentResource\Pages\ViewStudent;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class AttendenceOverview extends BaseWidget
{
    public ?Model $record = null;

    // use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ViewStudent::class;
    }

    protected function getStats(): array
    {
        $studentId = $this->record->id;
        $enrollments = $this->record->enrollments;
        $stats = [];

        foreach ($enrollments as $enrollment) {
            $batch = $enrollment->batch_id;
            $total_section = $enrollment->batch->total_section_count;

            $attendanceCount = Attendance::where('batch_id', $batch)->count();

            $present_attendanceCount = Attendance::where('batch_id', $batch)->whereJsonContains('present_students', $studentId)->count();
            $absent_attendanceCount = Attendance::where('batch_id', $batch)->whereJsonContains('absent_students', $studentId)->count();
            $leave_attendanceCount = Attendance::where('batch_id', $batch)->whereJsonContains('leave_students', $studentId)->count();

            $attendancePercentage = $attendanceCount > 0 ? ($present_attendanceCount / $attendanceCount) * 100 : 0;

            $stats[] = Stat::make($enrollment->batch->name, $attendanceCount)
                ->description('Total Section Count')
                ->color('info');
            $stats[] = Stat::make($enrollment->batch->name, $present_attendanceCount.' | '.$absent_attendanceCount.' | '.$leave_attendanceCount)
                ->description('Present | Absent | Leave')
                ->color('info');
            $stats[] = Stat::make($enrollment->batch->name, number_format($attendancePercentage, 2).'%')
                ->description('Attendance Percentage')
                ->color('info');
        }

        return $stats;
    }
}