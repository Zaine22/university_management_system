<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Events\PublicNoti;
use App\Filament\Resources\EventResource;
use App\Jobs\EventMailJob;
use App\Models\Batch;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterCreate()
    {
        $this->record->update(['slug' => Str::slug($this->record['name'])]);

        if ($this->record->batch_ids) {
            $Batches = Batch::whereIn('id', $this->record->batch_ids)->get();
            foreach ($Batches as $batch) {
                $enrollments = $batch->enrollments;
                foreach ($enrollments as $enrollment) {
                    $email = $enrollment->student->mail;
                    EventMailJob::dispatch($email, $this->record);
                }
            }
        } else {
            $usersWithValidEmails = User::whereNotNull('email')->where('email', '!=', '')->get();
            foreach ($usersWithValidEmails as $user) {
                $email = $user->email;
                EventMailJob::dispatch($email, $this->record);
            }
        }

        broadcast(new PublicNoti($this->record));
    }
}