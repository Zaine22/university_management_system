<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterCreate()
    {
        $this->record->update(['slug' => Str::slug($this->record['name'])]);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    // protected function getCreateFormAction(): Actions\Action
    // {
    //     return parent::getCreateFormAction()
    //         ->submit(null)
    //         ->requiresConfirmation()
    //         ->action(function () {
    //             $this->closeActionModal();
    //             $this->create();
    //         });
    // }

}