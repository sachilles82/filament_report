<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::registerScripts([app(Vite::class)('resources/filament/filament-turbo.js')]);
        Filament::registerScripts([app(Vite::class)('resources/filament/filament-stimulus.js')]);
    }
}
