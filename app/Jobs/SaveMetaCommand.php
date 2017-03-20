<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SaveMetaCommand;
use App\Repositories\MetaRepository;
use Illuminate\Queue\SerializesModels;

class SaveMetaCommand extends Job
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
    public function handle(MetaRepository $meta)
    {
        $meta->updateForEntity($this->data, $this->entity);
    }
}
