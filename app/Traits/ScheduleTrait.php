<?php
namespace App\Traits;

use App\Models\Meta;
use App\Jobs\SaveScheduleCommand;
use Illuminate\Support\Facades\Request;

trait ScheduleTrait
{
    public static function bootScheduleTrait()
    {
        // static::saved(function ($model) {
        //     $model->saveEntitySchedule($model);
        // });

        static::deleting(function ($model) {
            $model->deleteEntitySchedule($model);
        });
    }

    /**
     * Save the meta
     * Called after a revisionSave().
     */
    public function saveEntitySchedule($model, $data = [])
    {
        $data = $data ?: Request::all();

        // Remove the schedule form the input.
        Request::merge(['schedule' => []]);

        dispatch(new SaveScheduleCommand($data, $model));
    }

    /**
     * Delete the schedule
     */
    protected function deleteEntitySchedule($model)
    {
        $model->schedule()->delete();
        $model->relatedSchedules()->delete();
    }

    /**
     * The relationship to the meta
     *
     * @return Eloquent\Relationship
     */
    public function schedule()
    {
        return $this->morphMany('App\Models\CronJob', 'entity');
    }

    /**
     * Related schedules.
     * @return Eloquent\Relationship
     */
    public function relatedSchedules($entity_id = null)
    {
        if ($entity_id) {
            return $this->hasMany('App\Models\CronJob', 'parent_id')
                        ->where('parent_type', '=', get_class($this))
                        ->where('entity_id', '=', $entity_id);
        } else {
            return $this->hasMany('App\Models\CronJob', 'parent_id')
                        ->where('parent_type', '=', get_class($this));
        }
    }

    /**
     * Combine the relationships into one collection/
     * @return Collection
     */
    public function allSchedules($entity_id = null)
    {
        if (!empty($entity_id)) {
            $a = $this->schedule()->get();
            $b = $this->relatedSchedules($entity_id)->get();
            return $a->merge($b);
        } else {
            return $this->schedule;
        }
    }

    /**
     * Helper function to get any pending draft online commends.
     * @return Eloquent\Relationship
     */
    public function draftSchedules()
    {
        return $this->hasMany('App\Models\CronJob', 'parent_id')
                    ->where('parent_type', '=', get_class($this))
                    ->where('entity_id', '!=', $this->revision_id)
                    ->where('command', 'LIKE', 'cron:draft-online%');
    }
}
