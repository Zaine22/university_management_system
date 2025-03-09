<?php

namespace App\Filament\Resources\CertificateTemplateResource\Pages;

use App\Filament\Resources\CertificateTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificateTemplate extends ViewRecord
{
    protected static string $resource = CertificateTemplateResource::class;

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
