<?php
namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\GradingRule;
use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        /** @var User $user */
        if (! $user->can('create Exam')) {
            return [];
        }

        return [
            Action::make('New exam')
                ->action(function () {
                    if (Subject::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Subject Available')
                            ->body('Please create a subject before proceeding.')
                            ->send();

                        return redirect()->back();
                    } elseif (GradingRule::count() === 0) {
                        Notification::make()
                            ->warning()
                            ->title('No Grading Rule Available')
                            ->body('Please create a grading rule before proceeding.')
                            ->send();

                        return redirect()->back();
                    } else {
                        return redirect()->route('filament.admin.resources.exams.create');
                    }
                }),
        ];
    }
}