<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Filament\Resources\StudentResource\Pages\ListStudents;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class StudentOverview extends BaseWidget
{
    public ?Model $record = null;

    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListStudents::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', $this->getPageTableQuery()->count()),
        ];
    }
}
