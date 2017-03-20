<?php
namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CronDraftOnline extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:draft-online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take an entity draft online by Scheduled Task.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $type = $this->option('type');
        $id   = $this->option('id');

        if (!$id || !$type) {
            return;
        }

        $model    = app()->make($type);
        $revision = $model->with('entity')->findOrFail($id);

        $parentUpdate = [];

        $old_id = $revision->entity->revision_id;

        if ($old_id != $id) {
            // Set this revision to current.
            DB::table($model->getTable())
                ->where('id', '=', $id)
                ->update(['status' => config('revision.status_current')]);

            // Change old current to archive.
            DB::table($model->getTable())
                ->where('id', '=', $old_id)
                ->update(['status' => config('revision.status_archive')]);

            $parentUpdate['revision_id'] = $id;
        }

        // Set online and save.
        $parentUpdate['online'] = true;

        DB::table($revision->entity->getTable())
            ->where('id', '=', $revision->entity->id)
            ->update($parentUpdate);

        // Dispatch to the search engine to update.
        if (method_exists($revision->entity, 'bootSearchable')) {
            $revision->entity->searchable();
        }

        $this->info('CronDraftOnline fired for job ' . $type . ' : ' . $id);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', null, InputOption::VALUE_REQUIRED, 'The scheduled task job type.', null],
            ['id', null, InputOption::VALUE_REQUIRED, 'The scheduled task job id.', null],
        ];
    }
}
