<?php namespace App\Providers;

use App\Attachments\AttachmentManager;
use App\Events\RegisterFilter;
use Illuminate\Support\ServiceProvider;

class AttachmentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        event(new RegisterFilter('attachments', 'App\Attachments\AttachmentManager', 'filter'));
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
        $this->app->singleton('AttachmentManager', function ($app) {
            $attachment = $app->make('App\Repositories\AttachmentsRepository');
            return new AttachmentManager($attachment);
        });
    }
}
