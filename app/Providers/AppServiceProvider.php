<?php
namespace App\Providers;

use Cache;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias('FormField', 'App\Forms\FormField');
        AliasLoader::getInstance()->alias('Carbon', 'Carbon\Carbon');

        // File registry for quick lookup for interfaces
        if (!Cache::tags('registry')->has('registered-inferfaces')) {
            // Doesn't need app/
            $registry = app()->make('App\Registry\Registry')->map(
                ['EntityLinkInterface'],
                ['Models']
            );

            Cache::tags('registry')->forever('registered-inferfaces', $registry);
        }

        if (env('APP_ENV', 'local') !== 'local') {
            \DB::connection()->disableQueryLog();
        }

        Validator::extend('recaptcha', 'App\Validators\Recaptcha@validateRecaptcha');
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
        if (class_exists("LockFinder\Providers\RampartServiceProvider")) {
            app()->register("LockFinder\Providers\RampartServiceProvider");
        }
    }
}
