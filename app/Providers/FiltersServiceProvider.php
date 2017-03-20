<?php namespace App\Providers;

use App\Events\RegisterTokenFilter;
use App\Filters\FilterManager;
use Event;
use Illuminate\Support\ServiceProvider;

class FiltersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        event(new RegisterTokenFilter('App\Filters\FilterManager', 'test', '[[test]]'));
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FilterManager', function ($app) {
            return new FilterManager();
        });
    }
}
