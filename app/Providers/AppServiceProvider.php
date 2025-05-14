<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
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
        FilamentColor::register([
            'tomato' => Color::rgb('rgb(255, 99, 71)'),
            'custom-purple-900' => Color::hex('#3E0055'),
            'custom-purple-700' => Color::hex('#700099'),
            'custom-purple-500' => Color::hex('#A200DD'),
            'custom-purple-300' => Color::hex('#A31BDD'),
        ]);
    }
}
