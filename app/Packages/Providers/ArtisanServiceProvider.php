<?php

namespace App\Packages\Providers;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as BaseProvider;

use App\Packages\Console\ControllerMakeCommand;

class ArtisanServiceProvider extends BaseProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('command.controller.make', function ($app) {
            return new ControllerMakeCommand($app['files']);
        });
    }
}
