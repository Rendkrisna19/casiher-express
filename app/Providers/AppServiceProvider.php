<?php

namespace App\Providers;

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
    // Memaksa Imagick menggunakan Ghostscript yang sudah terdeteksi di laptop ACER Anda
    putenv('GS_PROG=C:\PROGRA~1\gs\gs10.06.0\bin\gswin64c.exe');
}
}
