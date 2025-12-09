<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
        // Configura Carbon para usar español
        Carbon::setLocale('es');
        
        // Si tu base de datos te da error de "key too long", descomenta esto:
        // Schema::defaultStringLength(191); 
    }
}
