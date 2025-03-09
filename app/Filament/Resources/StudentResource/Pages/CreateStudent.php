<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    // hide "create & create another" button in form
    protected static bool $canCreateAnother = false;

    // hide "create" button in form
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->visible(true);
    }

    protected static string $resource = StudentResource::class;

    // public function beforeCreate()
    // {
    //     dd($this->data);
    // }

    public function afterCreate()
    {
        Student::createData($this->data, $this->record);
    }
    // public function beforeCreate()
    // {
    //     dd($this->data);
    // }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
