<?php

namespace App\Filament\Resources\GradingRuleResource\Pages;

use App\Filament\Resources\GradingRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGradingRule extends EditRecord
{
    protected static string $resource = GradingRuleResource::class;

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

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
