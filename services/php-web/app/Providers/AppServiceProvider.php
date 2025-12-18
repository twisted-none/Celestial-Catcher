<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <-- ШАГ 1: ДОБАВЬТЕ ЭТУ СТРОКУ

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
        Paginator::useBootstrapFive(); // <-- ШАГ 2: ДОБАВЬТЕ ЭТУ СТРОКУ
    }
}