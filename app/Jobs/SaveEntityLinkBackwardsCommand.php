<?php

namespace App\Jobs;

use DB;
use Cache;
use Event;
use App\Jobs\Job;
use App\Models\MenuLink;
use App\Events\AliasWasChanged;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MenusLinksRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveEntityLinkBackwardsCommand extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * The entity
     *
     * @var Eloquent\Model
     */
    public $entity;

    /**
     * The links repo
     *
     * @var MenusLinksRepository
     */
    public $links;

    /**
     * Create a new command instance.
     *
     * @param  array          $data    The data array
     * @param  Eloquest\Model &$entity The entity
     * @return void
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Handle the command
     *
     * @param SaveMenuLinkCommand $command The command
     */
    public function handle(MenusLinksRepository $links)
    {
        $this->links = $links;
        $this->updateLink($this->entity);
    }

    /**
     * Called when a MenuLink is saved.
     * Recureive function to update anything undert this menu.
     * @param  MenuLink $saved
     * @return void
     */
    protected function updateLink($saved)
    {
        // Entity = Link.
        $id         = $saved->id;
        $interfaces = Cache::tags('registry')->get('registered-inferfaces');

        // Update all child links and process them.
        $children = $this->getChildren($id);

        // Dont mess with the URL if the parent is null.
        if (empty($saved->parent_id) || stristr($saved->route, 'http') || in_array($saved->menu_id, config('links.no_restructure'))) {
            //do nothing
        } else {
            $slug = explode('/', $saved->route);
            $slug = end($slug);
            // Generate a new route
            $newLink      = $this->links->pathAuto($saved->title, $slug, $saved->menu, $saved->parent_id, $saved->online);
            $saved->route = $newLink->route;
        }

        $hasItem = false;

        // Cycle through anything that may link to this link.
        if (!empty($interfaces['EntityLinkInterface'])) {
            foreach ($interfaces['EntityLinkInterface'] as $model) {
                $orm   = app()->make($model);
                $items = $orm->where('link_id', '=', $id)->get();

                // Sneak into the DB behind the ORM.
                DB::table($orm->getTable())->where('link_id', '=', $id)->update(['slug' => $saved->route]);

                foreach ($items as $item) {
                    $hasItem = true;
                    if ($item->slug != $saved->route) {
                        Event::fire(new AliasWasChanged($item, $item->slug, $saved->route));
                    }
                }
            }
        }

        if ($hasItem) {
            // Sneak update
            DB::table('menus_links')->where('id', '=', $saved->id)->update(['route' => $saved->route]);
        }

        foreach ($children as $link) {
            $this->updateLink($link);
        }
    }

    /**
     * Build an array of links for parents.
     * This is SQL intensive. Use for building paths only.
     *
     * @param  $id
     * @return array
     */
    protected function getChildren($id)
    {
        return MenuLink::where('parent_id', '=', $id)->with('menu')->get();
    }
}
