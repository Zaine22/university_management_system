<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()->isSuperAdmin() || auth()->user()->isAdmin();
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Approved Transaction (mmk)',
                number_format(Transaction::query()
                    ->where('status', 'approved')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount'))),

            Stat::make('Pending Transactions (mmk)',
                number_format(Transaction::query()
                    ->whereIn('status', ['new', 'processing'])
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount'))),

            Stat::make('Total Transaction (mmk)',
                number_format(Transaction::query()
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount'))),
        ];
    }
}