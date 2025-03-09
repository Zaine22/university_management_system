<?php

namespace App\Filament\Resources\ExamResource\Pages;

use Filament\Actions;
use App\Models\Result;
use App\Filament\Resources\ExamResource;
use Filament\Resources\Pages\ViewRecord;

class ViewExam extends ViewRecord
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Export Exam')->button()
                ->action(fn () => Result::excelExport($this->record->id)),
            Actions\Action::make('back')
                ->label('Back')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
