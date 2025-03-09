<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterCreate()
    {
        $email = $this->record->email;

        $student = Student::where('mail', $email)->first();

        if ($student) {
            $student->update(['user_id' => $this->record->id]);
        }
    }
}