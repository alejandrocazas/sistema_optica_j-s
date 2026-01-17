<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        Schema::defaultStringLength(191);

        // CONFIGURACIÓN DE IDIOMA
        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
    // Forzar HTTPS en producción para que carguen los estilos
    if($this->app->environment('production')) {
        URL::forceScheme('https');
    }
    }

}
