<?php
namespace App\Cron;

use App\Repositories\CronJobsRepository;
use Clockwork;
use Illuminate\Console\Scheduling\Schedule;

class CronSchedule
{
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
        $this->jobs = $jobs;
    }

    /**
     * Set a task on an entity.
     * For example: Go offline.
     *
     * @param  Mixed    $entity  What to do it to.
     * @param  DateTime $when    When to do this command.
     * @param  string   $command The artisan command to run.
     * @param  array    $options An array of options to pass to the cron command. EG: '--something=here' would be ['--something=here']
     * @param  booldan  $replace Replace existing commands that match.
     * @param  booldan  $once    Run only once.
     * @return The      command used.
     */
    public function schedule($entity, $when, $command, $options = [], $replace = true, $once = true)
    {
        if (!array_key_exists($command, config('schedule.allowed'))) {
            return false;
        }

        if (empty($command)) {
            return false;
        }

        $entity_type = get_class($entity);

        $existing = null;
        if ($replace) {
            $existing = $entity->schedule()->where('command', 'LIKE', $command . '%')->first();
        }

        $data = [];

        $parent_id = null;
        if (isset($options['parent_id'])) {
            $parent_id = $options['parent_id'];
            unset($options['parent_id']);
        }

        $parent_type = null;
        if (isset($options['parent_type'])) {
            $parent_type = $options['parent_type'];
            unset($options['parent_type']);
        }

        $options[] = '--id=' . $entity->id;
        $options[] = '--type="' . $entity_type . '"';

        $options = (empty($options) ? null : ' ' . implode(' ', $options));

        $data = [
            'entity_id'   => $entity->id,
            'entity_type' => $entity_type,
            'parent_id'   => $parent_id,
            'parent_type' => $parent_type,
            'command'     => $command . $options,
            'online'      => true,
            'once'        => $once,
            'min'         => $when->format('i'),
            'hour'        => $when->format('H'),
            'day_month'   => $when->format('d'),
            'month'       => $when->format('n'),
            'day_week'    => '*',
            'year'        => $once ? $when->format('Y') : null,
        ];

        if (!$existing) {
            $job = $this->jobs->create($data);
        } else {
            $job = $this->jobs->update($data, $existing->id);
        }

        if (config('schedule.only_one.' . $command, false)) {
            $query = $this->jobs->query();

            if ($parent_type) {
                $query->where('parent_type', '=', $parent_type)
                      ->where('parent_id', '=', $parent_id)
                      ->where('command', 'LIKE', $command . '%')
                      ->where('id', '!=', $job->id)
                      ->delete();
            } else {
                $query->where('entity_type', '=', $parent_type)
                      ->where('entity_id', '=', $parent_id)
                      ->where('command', 'LIKE', $command . '%')
                      ->where('id', '!=', $job->id)
                      ->delete();
            }
        }

        return $command;
    }
}
