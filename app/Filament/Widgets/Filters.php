<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;

class Filters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.filters';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                DatePicker::make('Filter From')
                                    ->live()
                                    ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateFromDate', from: $state)),
                                DatePicker::make('Filter To')
                                    ->live()
                                    ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateToDate', to: $state)),
                            ]),
                    ]),
            ]);
    }
}
