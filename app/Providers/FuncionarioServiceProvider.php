<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FuncionarioService;

class FuncionarioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FuncionarioService::class, function ($app) {
            return new FuncionarioService();
        });
    }

    public function boot()
    {
        //
    }
}