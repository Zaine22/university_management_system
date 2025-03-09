<?php

namespace App\Filament\Resources\RollcallResource\Pages;

use App\Filament\Resources\RollcallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRollcall extends EditRecord
{
    protected static string $resource = RollcallResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
