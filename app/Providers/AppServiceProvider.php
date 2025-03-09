<?php
namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->isSuperAdmin() ? true : null;
        });
        Table::configureUsing(function (Table $table): void {
            $table
                ->paginationPageOptions([20])
                ->searchOnBlur();
        });
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Academic'),
                NavigationGroup::make()
                    ->label('Management'),
                NavigationGroup::make()
                    ->label('Payment'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog'),
            ]);
        });

    }
}