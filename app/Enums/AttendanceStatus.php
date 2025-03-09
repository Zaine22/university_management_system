<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AttendanceStatus: string implements HasColor, HasIcon, HasLabel
{
    case Present = 'present';

    case Absent = 'absent';

    case Leave = 'leave';

    public function getLabel(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::Absent => 'Absent',
            self::Leave => 'Leave',

        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Present => 'success',
            self::Absent => 'danger',
            self::Leave => 'warning',

        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Present => 'heroicon-m-sparkles',
            self::Absent => 'heroicon-m-hand-raised',
            self::Leave => 'heroicon-m-exclamation-triangle',

        };
    }
}
