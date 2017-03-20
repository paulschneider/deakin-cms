<?php
namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CronOnline extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take an entity online by Scheduled Task.';

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

        $model = app()->make($type);

        $entity = $model->findOrFail($id);

        DB::table($model->getTable())
            ->where('id', '=', $entity->id)
            ->update(['online' => true]);

        // Dispatch to the search engine to update.
        if (method_exists($entity, 'bootSearchable')) {
            $entity->searchable();
        }

        $this->info('CronOnline fired for job ' . $type . ' : ' . $id);
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
