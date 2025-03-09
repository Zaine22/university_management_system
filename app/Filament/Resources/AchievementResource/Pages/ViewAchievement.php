<?php

namespace App\Filament\Resources\AchievementResource\Pages;

use Filament\Actions;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AchievementResource;
use App\Http\Controllers\AchievementController;

class ViewAchievement extends ViewRecord
{
    protected static string $resource = AchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Certificate')->button()
                ->action(fn () => AchievementController::printCertificate($this->record))
                ->visible(fn () => $this->record->generate_certificate ? true : false),

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