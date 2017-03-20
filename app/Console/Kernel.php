<?php
namespace App\Console;

use Cron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Inspire',
        'App\Console\Commands\PermissionsBuild',
        'App\Console\Commands\CronOnline',
        'App\Console\Commands\CronOffline',
        'App\Console\Commands\CronDraftOnline',
        'App\Console\Commands\Sitemap',
        'App\Console\Commands\CacheSingle',
        'App\Console\Commands\CacheOpcache',
        \Themsaid\RoutesPublisher\RoutesPublisherCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        try {
            Cron::setJobs($schedule);
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesnt exist yet. Run a migrate.
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
