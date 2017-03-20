<?php namespace App\Providers;

use App\Alias\AliasManager;
use App\Models\Alias;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AliasManager', function ($app) {
            return new AliasManager(new Alias);
        });
    }
}
