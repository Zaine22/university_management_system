<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use Filament\Actions;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TimetableResource;
use App\Http\Controllers\TimetableController;

class ViewTimetable extends ViewRecord
{
    protected static string $resource = TimetableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print Timetable')->button()
                ->action(fn () => TimetableController::timetable($this->record)),

            Actions\Action::make('back')
                ->label('Back')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}