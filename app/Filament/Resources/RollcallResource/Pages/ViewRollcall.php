<?php

namespace App\Filament\Resources\RollcallResource\Pages;

use App\Filament\Resources\RollcallResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRollcall extends ViewRecord
{
    protected static string $resource = RollcallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
