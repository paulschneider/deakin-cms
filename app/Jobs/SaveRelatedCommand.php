<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SaveRelatedCommand;
use Illuminate\Queue\SerializesModels;
use App\Repositories\RelatedRepository;

class SaveRelatedCommand extends Job
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
     * @param SaveRelatedCommand $command The command
     */
    public function handle(RelatedRepository $related)
    {
        $data   = $this->data;
        $entity = $this->entity;

        if (empty($data['related_form'])) {
            return;
        }

        $removing = [];

        if ($entity->related_links->count()) {
            $removing = $entity->related_links->pluck('id')->all();
        }

        if (!empty($data['related_links'])) {
            foreach ($data['related_links'] as $weight => $link) {
                $row = array_except($link, ['id']);

                $row['related_id']   = $entity->id;
                $row['related_type'] = get_class($entity);
                $row['weight']       = $weight;

                if (empty($row['link_id'])) {
                    $row['link_id'] = null;
                }

                if (empty($row['icon_id'])) {
                    $row['icon_id'] = null;
                }

                if (empty($row['link_id']) && empty($row['external_url'])) {
                    continue;
                }

                if (!empty($link['id'])) {
                    $related->update($row, $link['id']);

                    $key = array_search($link['id'], $removing);

                    if (null !== $key) {
                        unset($removing[$key]);
                    }
                } else {
                    if (!empty($row['link_id']) || !empty($row['external_url'])) {
                        $related->create($row);
                    }
                }
            }
        }

        // Clean out results.
        if (!empty($removing)) {
            foreach ($removing as $id) {
                $related->delete($id);
            }
        }
    }
}
