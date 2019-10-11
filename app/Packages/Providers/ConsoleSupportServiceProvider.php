<?php

namespace App\Packages\Providers;

use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider as BaseProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Database\MigrationServiceProvider;

class ConsoleSupportServiceProvider extends BaseProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
