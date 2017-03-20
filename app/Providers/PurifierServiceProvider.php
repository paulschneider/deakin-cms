<?php

namespace App\Providers;

use Mews\Purifier\PurifierServiceProvider as MewsProvider;

class PurifierServiceProvider extends MewsProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/purifier.php',
            'mews.purifier'
        );

        $this->app->bind('purifier', function ($app) {
            return new \App\Filters\PurifyFilter($app['files'], $app['config']);
        });
    }
}
