<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Total Transaction per month. ';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()->isSuperAdmin() || auth()->user()->isAdmin();
    }

    public Carbon $fromDate;

    public Carbon $toDate;

    #[On('updateFromDate')]
    public function updateFromDate(string $from): void
    {
        $this->fromDate = Carbon::parse($from);
        $this->updateChartData();
    }

    #[On('updateToDate')]
    public function updateToDate(string $to): void
    {
        $this->toDate = Carbon::parse($to);
        $this->updateChartData();
    }

    protected function getData(): array
    {
        $fromDate = $this->fromDate ?? Carbon::now()->startOfYear();
        $toDate = $this->toDate ?? Carbon::now()->endOfYear();
        $data = Trend::query(Transaction::query()->where('status', 'approved'))
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('F')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}