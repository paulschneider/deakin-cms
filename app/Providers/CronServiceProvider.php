<?php namespace App\Providers;

use App\Cron\CronManager;
use Illuminate\Support\ServiceProvider;

class CronServiceProvider extends ServiceProvider
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
        $this->app->singleton('CronManager', function ($app) {
            return new CronManager($this->app->make('App\Repositories\CronJobsRepository'));
        });
    }
}
