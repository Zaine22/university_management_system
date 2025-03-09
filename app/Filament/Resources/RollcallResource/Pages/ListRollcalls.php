<?php

namespace App\Filament\Resources\RollcallResource\Pages;

use App\Filament\Resources\RollcallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRollcalls extends ListRecords
{
    protected static string $resource = RollcallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
