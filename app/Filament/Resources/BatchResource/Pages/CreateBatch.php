<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBatch extends CreateRecord
{
    protected static string $resource = BatchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function afterCreate()
    {
        $this->record->update(['slug' => Str::slug($this->record->name)]);
    }
}