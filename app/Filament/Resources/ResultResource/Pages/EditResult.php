<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Result;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResult extends EditRecord
{
    protected static string $resource = ResultResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction(),
            $this->getSaveFormAction(),
        ];
    }

    protected function afterCreate(): void
    {
        $result = Result::find($this->data['id']);
        $marks = $result->marks;

        if ($marks >= 90 && $marks <= 100) {
            $result->update(['grade' => 'S']);
        } elseif ($marks >= 80 && $marks < 90) {
            $result->update(['grade' => 'A']);
        } elseif ($marks >= 70 && $marks < 80) {
            $result->update(['grade' => 'B']);
        } elseif ($marks >= 60 && $marks < 70) {
            $result->update(['grade' => 'C']);
        } elseif ($marks >= 50 && $marks < 60) {
            $result->update(['grade' => 'D']);
        } else {
            $result->update(['grade' => 'F']);
        }
    }
}
