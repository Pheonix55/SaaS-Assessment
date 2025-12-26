<?php

namespace App\Providers;

use Illuminate\Support\Facades\{Broadcast, Gate};
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use App\Models\Company;
use App\Observers\CompanyObserver;
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
    public function boot()
    {
        Cashier::useCustomerModel(Company::class);
        Company::observe(CompanyObserver::class);

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('SUPER_ADMIN')) {
                return true;
            }
        });
        Broadcast::routes([
            'middleware' => ['auth:sanctum'],
        ]);

        require base_path('routes/channels.php');
    }
}
