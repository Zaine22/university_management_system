<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('New teacher')
                ->action(function () {
                    if (Employee::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Employee Available')
                            ->body('Please create an Employee before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.teachers.create');
                    }
                }),
        ];
    }
}