<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\MenusRepository;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MenusLinksRepository;

class SaveEntityLinkCommand extends Job
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
        $this->entity = &$entity;
    }

    /**
     * Handle the command
     *
     * @param SaveMenuLinkCommand $command The command
     */
    public function handle(MenusRepository $menus, MenusLinksRepository $links)
    {
        if (!array_get($this->data, 'create', false) || !array_get($this->data, 'id', false)) {
            if (empty($this->entity->link->id)) {
                return;
            } else {
                $menu_id   = $this->entity->link->menu_id;
                $parent_id = $this->entity->link->parent_id;
                $title     = $this->entity->link->title;
                $online    = $this->entity->link->online;
            }
        } else {
            $menu_id   = array_get($this->data, 'id', 1);
            $parent_id = array_get($this->data, 'parent', null);
            $title     = array_get($this->data, 'title', $this->entity->title);
            $online    = array_get($this->data, 'online', 0);
        }

        // Fallback.
        $this->data['id']        = $menu_id;
        $this->data['parent_id'] = $parent_id;
        $this->data['title']     = $title;
        $this->data['online']    = $online;

        $menu = $menus->findOrFail($menu_id, ['filter' => false]);

        $slug = explode('/', $this->entity->slug);
        $slug = end($slug);

        // Generate a new route
        $link = $links->pathAuto($title, $slug, $menu, $parent_id, $online);

        // Save or update or no action
        // Triggers a save() on the MenuLink.
        // Which in turn will fire off SaveEntityLinkBackwardsCommandHandler to handle all connections.
        $link = $links->saveLink($this->entity, $link);
        $link->saveMeta($link, $this->data);

        // Set the entity link_id and slug to match link
        $this->entity->link_id = $link->id;
        $this->entity->slug    = $link->route;
    }
}
