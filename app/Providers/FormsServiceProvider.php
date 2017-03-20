<?php
namespace App\Providers;

use Event;
use App\Events\RegisterBlock;
use Illuminate\Support\ServiceProvider;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        event(new RegisterBlock('App\Forms\FormHandler', 'getForm', 'Contact Form', 'frontend.blocks.contact-form', ['name' => 'contact'], null));
        event(new RegisterBlock('App\Forms\FormHandler', 'getForm', 'Newsletter Form', 'frontend.blocks.newsletter-form', ['name' => 'newsletter'], null));
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
    }
}
