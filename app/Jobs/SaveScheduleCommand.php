<?php

namespace App\Jobs;

use Carbon;
use App\Jobs\Job;
use App\Cron\CronSchedule;
use Illuminate\Queue\SerializesModels;

class SaveScheduleCommand extends Job
{
    use SerializesModels;

    /**
     * The data array
     * @var array
     */
    public $data;

    /**
     * The entity
     *
     * @var Eloquent\Model
     */
    public $entity;

    /**
     * Create a new command instance.
     *
     * @param  array          $data    The data array
     * @param  Eloquest\Model &$entity The entity
     * @return void
     */
    public function __construct($data, &$entity)
    {
        $this->data   = $data;
        $this->entity = $entity;
    }

    /**
     * Handle the command
     *
     * @param SaveMetaCommand $command The command
     */
    public function handle(CronSchedule $cron)
    {
        $data   = $this->data;
        $entity = $this->entity;

        if (!empty($data['schedule'])) {
            // Wipe all
            $entity->schedule()->forceDelete();

            foreach ($data['schedule'] as $row) {
                if (!empty($row['command_base']) && !empty($row['time']) && !empty($row['date'])) {
                    // Check if this needs to be deferred.
                    if ($this->handleChild($row, $entity, $data, $cron) !== false) {
                        return;
                    }

                    $when = Carbon::createFromFormat('d/m/Y h:i A', $row['date'] . ' ' . $row['time']);
                    $cron->schedule($entity, $when, $row['command_base'], null, false);
                }
            }
        } elseif (!empty($data['schedule_form'])) {
            // Not enabled, wipe all
            $cron->cleanup($entity);
        }
    }

    /**
     * Revision schedules for example need to be saved.
     * @param  array     $row    The config for this schedule
     * @param  mixed     $entity The parent entity
     * @param  array     $data   The input of the form
     * @return boolean
     */
    private function handleChild($row, $entity, $data, $cron)
    {
        $relationship = config('schedule.relationship.' . $row['command_base'], null);
        $constraints  = config('schedule.relationship_constraints.' . $row['command_base'], []);

        if ($relationship) {
            $model = get_class($entity);

            $options = [
                'parent_type' => $model,
                'parent_id'   => $entity->id,
            ];

            $relative = $entity->{$relationship};
            $entity->relatedSchedules($relative->id)->forceDelete();

            if (isset($constraints[$relationship]) && $entity->online) {
                foreach ($constraints[$relationship] as $key => $value) {
                    if ($relative->{$key} != $value) {
                        return;
                    }
                }
            }

            $when = Carbon::createFromFormat('d/m/Y h:i A', $row['date'] . ' ' . $row['time']);
            $cron->schedule($relative, $when, $row['command_base'], $options, false);

            return;
        }

        return false;
    }
}
