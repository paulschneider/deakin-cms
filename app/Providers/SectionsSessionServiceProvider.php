<?php namespace App\Providers;

use App\Http\Session\SectionsSession;
use Illuminate\Support\ServiceProvider;

class SectionsSessionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {}
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
        $this->app->singleton('SectionsSession', function ($app) {
            return new SectionsSession();
        });
    }
}
