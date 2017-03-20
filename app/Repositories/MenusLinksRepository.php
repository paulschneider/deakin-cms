<?php

namespace App\Repositories;

use Cache;
use Event;
use App\Models\MenuLink;
use App\Events\AliasWasChanged;

class MenusLinksRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = ['online'];

    /**
     * Set the cache tags to be flushed on updates and deleted
     *
     * @var array
     */
    protected $cache_tags = ['menus', 'url'];

    /**
     * Nullable values will default to null if an empty string.
     *
     * @var array
     */
    protected $nullable = ['parent_id'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\MenuLink';
    }

    /**
     * Filter the query
     *
     * @param Eloquent\Model $query The query
     */
    protected function filter(&$query)
    {
        $query->where('online', '=', 1);
    }

    /**
     * [pathAuto description]
     * @param  string  $title     Title of the link
     * @param  string  $slug      Slug of the page.
     * @param  Menu    $menu_id   The machine name of the menu to insert into.
     * @param  integer $parent_id The MenuLink ID of the parent.
     * @return Link
     */
    public function pathAuto($title, $slug, $menu, $parent_id = null, $online = 1)
    {
        // Insert a link.
        $link            = new MenuLink;
        $link->parent_id = $parent_id;
        $link->menu_id   = $menu->id;
        $link->title     = $title;
        $link->online    = $online;

        // Get parents
        $parent = $this->getParent($link);

        if (!empty($parent)) {
            $link->route = $parent->route . '/' . $slug;
        } else {
            $link->route = $slug;
        }

        return $link;
    }

    /**
     * Build an array of links for parents.
     * This is SQL intensive. Use for building paths only.
     *
     * @param  MenuLink $link
     * @return array
     */
    protected function getParent(MenuLink $link)
    {
        if (!empty($link->parent_id)) {
            return MenuLink::where('id', '=', $link->parent_id)->first();
        }

        return null;
    }

    /**
     * Called when saving an attached entity. EG a page.
     * NOT called when saving a link directlt.
     * Refer to SaveEntityLinkBackwardsCommandHandler for that.
     *
     * @param  mixed      $entity page, mews etc
     * @param  MenuLink   $link
     * @return MenuLink
     */
    public function saveLink($entity, $link)
    {
        $attributes = $link->getAttributes();

        if (!empty($entity->link->id)) {
            // Reload the live link.
            $link     = $entity->link;
            $changed  = false;
            $original = clone $link;

            foreach ($attributes as $key => $attr) {
                if ($link->{$key} != $attr) {
                    $changed      = true;
                    $link->{$key} = $attr;
                }
            }

            if (empty($link->parent_id)) {
                $link->parent_id = null;
            }

            if ($changed) {
                $link->save();
            }

            if ($original->route != $link->route) {
                Event::fire(new AliasWasChanged($entity, $original->route, $link->route));
            }
        } else {
            $link = $this->saveWithData($attributes, null, ['filter' => false]);
        }

        return $link;
    }
}
