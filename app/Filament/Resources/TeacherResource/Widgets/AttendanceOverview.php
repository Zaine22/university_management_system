<?php

namespace App\Filament\Resources\TeacherResource\Widgets;

use App\Filament\Resources\TeacherResource\Pages\ViewTeacher;
use App\Models\Attendance;
use App\Models\Batch;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class AttendanceOverview extends BaseWidget
{
    public ?Model $record = null;

    // use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ViewTeacher::class;
    }

    protected function getStats(): array
    {
        $attendances = Attendance::where('teacher_id', $this->record->id)
            ->get()
            ->groupBy('batch_id');

        $stats = [Stat::make('Total attendance per month - '.Carbon::now()->format('F'), Attendance::where('teacher_id', $this->record->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count())];

        foreach ($attendances as $batchId => $attendanceGroup) {
            $batch = Batch::find($batchId);
            $stats[] = Stat::make('Attendance per batch', $attendanceGroup->count())
                ->description($batch->batch_name)
                ->color('info');
        }

        return $stats;
    }
}
