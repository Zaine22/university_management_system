<?php
namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Models\Batch;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTimetables extends ListRecords
{
    protected static string $resource = TimetableResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        /** @var User $user */
        if (! $user->can('create Timetable')) {
            return [];
        }

        return [
            Action::make('New timetable')
                ->action(function () {
                    if (Batch::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Batch Available')
                            ->body('Please create a batch before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.timetables.create');
                    }
                }),
        ];
    }
}