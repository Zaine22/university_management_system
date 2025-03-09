<?php

namespace App\Filament\Resources\RollcallResource\Pages;

use App\Filament\Resources\RollcallResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRollcall extends CreateRecord
{
    protected static string $resource = RollcallResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
