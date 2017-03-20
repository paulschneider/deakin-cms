<?php namespace App\Providers;

use App\Registry\GlobalClass;
use App\Registry\GlobalJs;
use Illuminate\Support\ServiceProvider;

class GlobalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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
        $this->app->singleton('GlobalClass', function ($app) {
            return new GlobalClass;
        });

        $this->app->singleton('GlobalJs', function ($app) {
            return new GlobalJs;
        });
    }
}
