<?php
namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use App\Models\Chapter;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('New Subject')
                ->action(function () {
                    if (Chapter::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Chapter Available')
                            ->body('Please create a chapter before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.subjects.create');
                    }
                }),
        ];
    }
}