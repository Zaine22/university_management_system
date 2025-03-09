<?php
namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\User;
use App\Traits\FormatNrc;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTeacher extends CreateRecord
{
    use FormatNrc;

    protected static string $resource = TeacherResource::class;

    // hide "create & create another" button in form
    protected static bool $canCreateAnother = false;

    // hide "create" button in form
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->visible(true);
    }

    protected function afterCreate()
    {

        DB::transaction(function () {
            $nrcFormatted = $this->staticFormatNrc($this->data['nrcs_n'], $this->data);

            $this->record->update([
                'nrc'  => $nrcFormatted,
                'slug' => Str::slug($this->record->name),
                'user_id'      => $this->record->employee->user_id]);
        });

        $user = User::find($this->record->employee->user_id);
        $user->assignRole('Teacher');

    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}