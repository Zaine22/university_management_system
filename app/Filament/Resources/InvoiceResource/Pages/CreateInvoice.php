<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    public function afterCreate()
    {
        $a = $this->record->transaction_ids;
        $arrayLength = count($a);
        $transaction_ids = [];
        for ($x = 0; $x < $arrayLength; $x++) {
            $transaction_ids[] = $a[$x]['transaction_ids'];
        }
        $this->record->update(['transaction_ids' => $transaction_ids]);

        foreach ($transaction_ids as $transaction) {
            Transaction::find($transaction)->update(['status' => 'approved']);
        }
    }
}
