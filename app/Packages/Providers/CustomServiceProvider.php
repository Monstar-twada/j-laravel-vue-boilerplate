<?php

namespace App\Packages\Providers;

use App\Packages\Console\ControllerMakeCommand;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
        *
        */
        //$this->commands('App\Packages\Console\ControllerMakeCommand');
        $this->app->singleton('ControllerMakeCommand', function ($app) {
            return new ControllerMakeCommand($app['files']);
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
