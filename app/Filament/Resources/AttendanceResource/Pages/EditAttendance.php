<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit')
                ->requiresConfirmation()
                ->action(fn () => AttendanceResource::submitted($this->record))
                ->after(function () {
                    return redirect($this->getResource()::getUrl('index'));
                }),

            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function afterSave()
    {
        AttendanceResource::submitted($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
