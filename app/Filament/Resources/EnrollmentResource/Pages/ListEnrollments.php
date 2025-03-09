<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Batch;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('New Enrollment')
                ->action(function () {
                    if (Batch::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Batch Available')
                            ->body('Please create a batch before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.enrollments.create');
                    }
                }),
        ];
    }
}
