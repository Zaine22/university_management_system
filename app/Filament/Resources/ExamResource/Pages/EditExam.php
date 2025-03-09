<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\Result;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditExam extends EditRecord
{
    protected static string $resource = ExamResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            
            Action::make('Submit marks')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->submitted();
                    Notification::make()
                        ->title('Submission Successful')
                        ->body('The marks has been submitted successfully.')
                        ->success()
                        ->send();
                })
                ->after(function () {
                    return redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('Import Exam Results')
                ->button()
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']),
                ])
                ->action(function (array $data) {
                    Result::excelImport($this->record->id, $data['file']);
                    Notification::make()
                        ->title('Success')
                        ->body('Exam results imported successfully! Please SUBMIT to save the results!')
                        ->success()
                        ->send();
                }),

            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}