<?php
namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
// use Actions\CreateAction;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        /** @var User $user */
        if (! $user->can('create Batch')) {
            return [];
        }

        return [
            Action::make('New Batch')
                ->action(function () {
                    if (Course::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Courses Available')
                            ->body('Please create a course before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.batches.create');
                    }
                }),
        ];
    }
}