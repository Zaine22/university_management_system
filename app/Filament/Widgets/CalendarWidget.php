<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TimetableResource;
use App\Models\Timetable;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.widgets.calendar-widget';
    public function fetchEvents(array $fetchInfo): array
    {
        if (auth()->user()->isTeacher()) {
            return Timetable::query()
                ->where('starts_at', '>=', $fetchInfo['start'])
                ->where('ends_at', '<=', $fetchInfo['end'])
                ->where('teacher_id', auth()->user()->teacher->id)
                ->get()
                ->map(
                    fn (Timetable $timetable) => [
                        'title' => $timetable->teacher->name,
                        'start' => $timetable->starts_at,
                        'end' => $timetable->ends_at,
                        'url' => TimetableResource::getUrl(name: 'view', parameters: ['record' => $timetable]),
                        'shouldOpenUrlInNewTab' => false,
                    ]
                )
                ->all();
        } else {
            return Timetable::query()
                ->where('starts_at', '>=', $fetchInfo['start'])
                ->where('ends_at', '<=', $fetchInfo['end'])
                ->get()
                ->map(
                    fn (Timetable $timetable) => [
                        'title' => $timetable->teacher->name,
                        'start' => $timetable->starts_at,
                        'end' => $timetable->ends_at,
                        'url' => TimetableResource::getUrl(name: 'view', parameters: ['record' => $timetable]),
                        'shouldOpenUrlInNewTab' => false,
                    ]
                )
                ->all();
        }
    }

    public static function canView(): bool
    {
        return false;
    }

    protected function viewAction(): Action
    {
        return ViewAction::make();
    }
}