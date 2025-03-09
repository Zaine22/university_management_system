<?php

namespace App\Filament\Resources\GradingRuleResource\Pages;

use App\Filament\Resources\GradingRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGradingRule extends ViewRecord
{
    protected static string $resource = GradingRuleResource::class;

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
