<?php

namespace App\Filament\Resources\CertificateTemplateResource\Pages;

use App\Filament\Resources\CertificateTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCertificateTemplate extends EditRecord
{
    protected static string $resource = CertificateTemplateResource::class;

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
