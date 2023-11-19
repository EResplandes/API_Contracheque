<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ContrachequeService;

class ContrachequeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ContrachequeService::class, function ($app) {
            return new ContrachequeService();
        });
    }

    public function boot()
    {
        //
    }
}