<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\InvoiceResource;
use App\Http\Controllers\InvoiceController;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print Invoice')->button()
                ->action(fn () => InvoiceController::printInvoice($this->record)),

            Actions\Action::make('back')
                ->label('Back')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}