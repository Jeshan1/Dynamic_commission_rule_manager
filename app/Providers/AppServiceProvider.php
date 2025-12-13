<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repository\CommissionRuleRepository;
use App\Http\Interfaces\CommissionInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CommissionInterface::class, CommissionRuleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
