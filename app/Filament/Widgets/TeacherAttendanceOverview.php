<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Batch;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TeacherAttendanceOverview extends BaseWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();

        return $user->isTeacher() || $user->isSuperAdmin();
    }

    protected function getStats(): array
    {
        // $this->record->id
        if (auth()->user()->teacher?->id) {
            $attendances = Attendance::where('teacher_id', auth()->user()->teacher->id)
                ->get()
                ->groupBy('batch_id');

            $stats = [Stat::make('Total Section Per Month', Attendance::where('teacher_id', auth()->user()->teacher->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count())
                ->description(Carbon::now()->format('F Y'))
                ->color('info')];

            foreach ($attendances as $batchId => $attendanceGroup) {
                $batch = Batch::find($batchId);

                // Check if $batch is not null before proceeding
                if ($batch) {
                    $stats[] = Stat::make('Attendance Per Batch', $attendanceGroup->count())
                        ->description($batch->name)
                        ->color('info');
                }
            }

            return $stats;

        } else {
            $stats = [Stat::make('Total Section Per Month', Attendance::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get()->count())
                ->description(Carbon::now()->format('F Y'))
                ->color('info')];

            return $stats;

        }

    }
}