<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print Invoice')->button()
                ->action(fn () => InvoiceResource::printInvoice($this->record)),
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            // Action::make('Transaction')->button()
            //     ->action(fn() => InvoiceResource::printInvoice($this->record)),
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
