<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Traits\FormatNrc;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    use FormatNrc;

    protected static string $resource = EmployeeResource::class;

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
        return $this->getResource()::getUrl('index');
    }

    public function afterSave()
    {
        $nrcFormatted = self::staticFormatNrc($this->data['nrcs_n'], $this->data);
        // dd($nrcFormatted);
        $this->record->update(['employee_nrc' => $nrcFormatted]);
    }
}
