<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Departments: string implements HasColor, HasIcon, HasLabel
{
    case HR = 'HR';

    case Finance = 'Finance';

    case Office = 'Office';

    case Teacher = 'Teacher';

    case ATS = 'ATS';

    case Stuff = 'Stuff';

    case Property = 'Property';

    public function getLabel(): string
    {
        return match ($this) {
            self::HR => 'HR',
            self::Finance => 'Finance',
            self::Office => 'Office',
            self::Teacher => 'Teacher',
            self::ATS => 'ATS',
            self::Stuff => 'Stuff',
            self::Property => 'Property',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::HR => 'danger',
            self::Finance => 'gray',
            self::Office => 'primary',
            self::Teacher => 'success',
            self::ATS => 'info',
            self::Stuff => 'warning',
            self::Property => 'indigo',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::HR => 'heroicon-m-user-group',
            self::Finance => 'heroicon-m-currency-dollar',
            self::Office => 'heroicon-m-briefcase',
            self::Teacher => 'heroicon-m-academic-cap',
            self::ATS => 'heroicon-m-code-bracket',
            self::Stuff => 'heroicon-m-user',
            self::Property => 'heroicon-m-building-office-2',
        };
    }
}
