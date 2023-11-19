<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmpresaService;

class EmpresaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(EmpresaService::class, function ($app) {
            return new EmpresaService();
        });
    }

    public function boot()
    {
        //
    }
}