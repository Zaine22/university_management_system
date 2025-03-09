<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Employee;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStudentChartData(): array
    {
        $data = Student::whereDate('created_at', '>', Carbon::now()->subDays(7))->pluck('id')->toArray();

        return $data;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', Student::query()->count())
                ->description('enrolled students in educational program.')
                ->descriptionIcon('heroicon-m-academic-cap', IconPosition::Before)
                ->chart($this->getStudentChartData())
                ->color('success'),
            Stat::make('Total Courses', Course::query()->count())
                ->descriptionIcon('heroicon-m-book-open', IconPosition::Before)
                ->description('Count of educational courses offered.')
                ->chart([17, 20, 14, 60, 14, 20, 12])
                ->color('success'),
            Stat::make('Total Employee', Employee::query()->count())
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->description('Aggregate count of institute employees.')
                ->color('success')
                ->chart([17, 20, 40, 50, 14, 13, 60]),
        ];
    }
}
