<?php namespace App\Cron;

use App\Repositories\CronJobsRepository;
use Cache;
use DateTime;
use Illuminate\Console\Scheduling\Schedule;

class CronManager
{
    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'cron';

    /**
     * The time a taxonomy call should be cached
     */
    protected $cache_time;

    /**
     * The instance of CronJobsRepository
     *
     * @var App\Repositories\CronJobsRepository
     */
    protected $jobs;

    /**
     * Add the instance of batch
     */
    public function __construct(CronJobsRepository $jobs)
    {
        $this->jobs       = $jobs;
        $this->cache_time = config('cache.cron_cache');
    }

    /**
     * Set the jobs onto the schedule.
     * @param Schedule $schedule Created in Kernel.php
     */
    public function setJobs(Schedule $schedule)
    {
        $self = &$this;

        $jobs = Cache::tags(self::CACHE_PREFIX)->remember('cron:jobs', $this->cache_time, function () use ($self) {
            return $self->jobs->all();
        });

        foreach ($jobs as $job) {
            if ($job->once && $job->year != date('Y')) {
                continue;
            }
            $schedule->command($job->command)->name('schedule_' . $job->id)->withoutOverlapping()->evenInMaintenanceMode()->cron($job->schedule);
        }

        // Schedule every minute without overlapping.
        $schedule->call(function () use ($self) {
            $self->cleanupOnce();
        })->name('schedule_cleanup')->evenInMaintenanceMode()->withoutOverlapping();
    }

    /**
     * Cleanup run once items.
     */
    public function cleanupOnce()
    {
        $jobs = $this->jobs->all();
        $now  = new DateTime;

        foreach ($jobs as $job) {
            if ($job->year) {
                if ($job->date <= $now && $job->once == true) {
                    $job->forceDelete();
                }
            }
        }
    }
}
