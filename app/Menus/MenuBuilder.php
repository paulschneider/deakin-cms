<?php
namespace App\Menus;

use Auth;
use Cache;
use Entrust;
use Request;
use App\Models\Menu;
use App\Acl\AclBuilder;
use App\Repositories\MenusRepository;
use App\Repositories\MenusLinksRepository;
use Illuminate\Database\Eloquent\Collection;
use Caffeinated\Menus\Facades\Menu as MenuFacade;

class MenuBuilder
{
    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'menus';

    protected $cache_roles = null;

    /**
     * The menus
     *
     * @var App\Repositories\MenusRepository
     */
    protected $menus;

    /**
     * The links
     *
     * @var App\Repositories\MenusLinksRepository
     */
    protected $links;

    /**
     * The ACL logic.
     *
     * @var App\Acl\AclBuilder
     */
    protected $acl;

    /**
     * The time a taxonomy call should be cached
     */
    protected $cache_time;

    /**
     * A cache of entity link ids.
     *
     * @var array
     */
    protected $entity_links;

    /**
     * Override the URL thats active
     * @var string
     */
    protected $fake_url;

    /**
     * Inject the dependencies
     *
     * @param TermsRepository        $term       The term repository
     * @param VocabulariesRepository $vocabulary The vocabulary repository
     */
    public function __construct(MenusRepository $menus, MenusLinksRepository $links)
    {
        $this->menus = $menus;
        $this->links = $links;
        $this->acl   = new AclBuilder;

        $this->cache_time = config('cache.menu_cache');

        $roles             = Auth::guest() ? [] : Auth::user()->roles->pluck('id')->toArray();
        $this->cache_roles = 'roles:' . implode('.', $roles);
    }

    /**
     * Called on boot of the MenusServiceProvider.
     *
     * @param array   $names The names of the menus to add
     * @param boolean $force Force reload
     */
    public function bootMenus($names = [], $force = false)
    {
        $self = &$this;

        $menus = $this->getMenuDb($names, $force);

        foreach ($menus as $menu) {
            $stub = 'menu:tree:' . $menu->id . $this->cache_roles;

            if ($force) {
                Cache::tags(self::CACHE_PREFIX)->forget($stub);
            }

            $tree = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($menu, $self) {
                return $self->getTree($menu->links);
            });

            $inserted = MenuFacade::make($menu->stub, function ($build) use ($tree, $force) {
                $this->getMenuTree($build, $tree, null, $force);
            });

            $this->checkFakeMenus($inserted);
        }

        return $this;
    }

    /**
     * Do this in a callback so i can re-use it.
     *
     * @param  array        $names The names to inlclude
     * @param  boolean      $force Force reload
     * @return Collection
     */
    public function getMenuDb($names = [], $force = false)
    {
        $self = &$this;

        $stub = 'all' . $this->cache_roles;

        if (!empty($names)) {
            $stub = implode('.', $names);
        }

        if ($force) {
            Cache::tags(self::CACHE_PREFIX)->forget($stub);
        }

        $menus = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($self, $names) {
            $options = ['with' => ['links', 'links.meta']];
            // If names are specified, then only return those items
            if (!empty($names)) {
                return $self->menus->query($options)->whereIn('stub', $names)->get();
            }
            // Otherwise return all menus
            return $self->menus->all($options);
        });

        return $menus;
    }

    /**
     * Called on boot after bootMenus
     * Will hunt the menus for active trails.
     *
     * @param mixed  $names Can be a string or an array of favourable menus.
     * @param string $uri   Can overide the uri.
     */
    public function getBreadcrumbs($names, $uri = null)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $current = $names[0];
        $uri     = $uri ?: \Request::url();

        $stub = 'crumbs:' . $current . ':' . md5($uri) . $this->cache_roles;

        $trails = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($current) {
            $trails = [];

            if ($menu = MenuFacade::get($current)) {
                $links = $menu->active();

                foreach ($links as $k => $link) {
                    $active = ($k == (count($links) - 1) ? 'active' : null);

                    $trails[] = (object) [
                        'url'    => $link->url(),
                        'title'  => $link->title,
                        'class'  => ($active ? 'active' : null),
                        'active' => $active,
                    ];
                }
            }

            return $trails;
        });

        unset($names[0]);
        $names = array_values($names);

        if (empty($trails) && count($names)) {
            return $this->getBreadcrumbs($names);
        }

        return $trails;
    }

    /**
     * Return a menu trail dynamically.
     * Used by search. ore ad-hoc and not required.
     *
     * @param  string  $current The menu to use
     * @param  string  $slug    The URL to find.
     * @return array
     */
    public function getBreadcrumbBySlug($current, $slug)
    {
        $menu   = MenuFacade::get($current);
        $trails = [];

        $found = false;

        foreach ($menu->items as $item) {
            $this->setInactive($item);
        }

        foreach ($menu->items as $item) {
            if ($item->link->path['url'] == $slug) {
                $this->setActive($item);
                $found = true;
            }
        }

        if ($found) {
            $links = $menu->active();

            foreach ($links as $k => $link) {
                $active = ($k == (count($links) - 1) ? 'active' : null);

                $trails[] = (object) [
                    'url'    => $link->url(),
                    'title'  => $link->title,
                    'class'  => ($active ? 'active' : null),
                    'active' => $active,
                ];
            }
        }

        return $trails;
    }

    /**
     * Build the links in the menu tree,
     * @param  array      &$build MenuFacade colsure
     * @param  array      $tree   Collection of links
     * @param  array      $active Build array of active menu objects.
     * @param  MenuFacade $parent A build inserted menu item
     * @return void
     */
    public function getMenuTree(&$build, $tree, $parent = null, $force = false)
    {
        if (empty($tree)) {
            return null;
        }

        $ssl = Request::isSecure();

        foreach ($tree as $branch) {
            $stub = 'link:' . $branch['link']->id . $this->cache_roles;

            if ($force) {
                Cache::tags(self::CACHE_PREFIX)->forget($stub);
            }

            $link = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($branch) {
                return $branch['link']->appendMeta();
            });

            if (!$link->online) {
                continue;
            }

            // Allow links in the admin menu with children to display as a # link.
            if (!$branch['access'] && !empty($branch['children']) && in_array($link->menu_id, config('links.all_ignore'))) {
                $url = '#';
            } elseif (!$branch['access']) {
                continue;
            } else {
                $url = $link->route;
            }

            // Dont link this up. Go to root.
            if ($url == 'home') {
                $url = '';
            }

            $inserting = $build->add($link->title, ['url' => $url, 'secure' => $ssl]);

            if (!empty($link->meta_target)) {
                $inserting->attribute('target', $link->meta_target);
            }

            if (!empty($link->meta_rel)) {
                $inserting->attribute('rel', $link->meta_rel);
            }

            if (!empty($link->meta_class)) {
                $inserting->attribute('class', $link->meta_class);
            }

            if (!empty($link->meta_id)) {
                $inserting->attribute('id', $link->meta_id);
            }

            if (!empty($link->meta_description)) {
                $inserting->data('description', $link->meta_description);
            }

            if (!empty($link->meta_icon)) {
                $inserting->icon($link->meta_icon);
            }

            if ($inserting->data('active')) {
                $this->setActive($inserting);
            } else {
                $inserting->data('li_class', '');
            }

            $inserting->data('depth', $branch['depth']);
            $inserting->data('weight', $link->weight);

            // Recursive build.
            if (!empty($branch['children'])) {
                $this->getMenuTree($inserting, $branch['children'], null, $force);
            }
        }

        return $this;
    }

    /**
     * Function to return the menu options list for a dropdown select.
     * @param  int    $stub      The menu stub.
     * @param  int    $ignore_id Ignore a branch under an id.
     * @param  string $empty     Empty text
     * @return array  A built array of options.
     */
    public function getOptions(Menu $menu, $ignore_id = null, $empty = 'No parent')
    {
        $stub = 'options.' . $menu->id . $this->cache_roles;

        $options = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($menu) {
            $tree    = $this->getTree(clone $menu->links);
            $options = [];

            if (!empty($tree)) {
                $tree = $this->getFlat($tree);
                foreach ($tree as $branch) {
                    $link = $branch['link'];

                    if (!in_array($menu->id, config('links.no_restructure'))) {
                        if (!$entity_link = $this->identifyEntities($link->id)) {
                            continue;
                        }
                    }

                    $options[$link->id] = str_pad('', $branch['depth'] - 1, '-') . $link->title;
                }
            }

            return $options;
        });

        foreach ($options as $id => $value) {
            if ($id == $ignore_id) {
                unset($options[$id]);
            }
        }

        return ['' => $empty] + $options;
    }

    /**
     * Get everything in the website.
     * @param  array   $ignore_ids
     * @param  string  $empty
     * @return array
     */
    public function getAllOptions($ignore_ids = [], $empty = 'No parent')
    {
        $stub = 'options.all.ignore.' . md5(json_encode($ignore_ids)) . $this->cache_roles;

        $options = Cache::tags(self::CACHE_PREFIX)->remember($stub, $this->cache_time, function () use ($empty, $ignore_ids) {
            $options = [];

            $menus = $this->getMenuDb();

            foreach ($menus as $menu) {
                if (!in_array($menu->id, $ignore_ids)) {
                    $branch = $this->getOptions($menu, null, $empty);
                    unset($branch['']);
                    $options = $options + [$menu->title => $branch];
                }
            }

            return $options;
        });

        return ['' => $empty] + $options;
    }

    /**
     * Helper function to get a menu.
     * @param  int   $menu_id The menu stub.
     * @return array A built array of options.
     */
    public function getMenu($menu_id)
    {
        return $this->menus->findOrFail($menu_id);
    }

    /**
     * Function to return the menu options list for a dropdown select.
     * @param  int   $stub      The menu stub.
     * @param  int   $ignore_id Ignore a branch under an id.
     * @return array A built array of options.
     */
    public function getMenus()
    {
        $options = ['' => 'No menu'];

        foreach ($this->menus->all(['scopes' => ['editable']]) as $menu) {
            $options[$menu->id] = $menu->title;
        }

        return $options;
    }

    /**
     * Build a tree of links from a collection.
     * @param  Collection $links     [description]
     * @param  int        $ignore_id Items to ignore.
     * @param  int        $root      Set recursivly.
     * @param  integer    $depth     Set recursivly.
     * @param  boolean    $acl       Use the ACL?
     * @return array
     */
    public function getTree(Collection $links, $ignore_id = null, $root = null, $depth = 0, $acl = true, $identify_entities = false)
    {
        $return = [];
        ++$depth;

        foreach ($links as $key => $link) {
            if ($link->id && $ignore_id && $ignore_id == $link->id) {
                continue;
            }

            $access = true;

            if ($acl) {
                if ($suggestion = $this->acl->suggestLink($link, $this->cache_roles)) {
                    if (!Entrust::can($suggestion)) {
                        $access = false;
                    }
                }
            }

            if ($link->parent_id == $root) {
                unset($links[$key]);
                $entity_link = null;

                if ($identify_entities) {
                    $entity_link = $this->identifyEntities($link->id);
                }

                $kids = $this->getTree($links, $ignore_id, $link->id, $depth, $acl, $identify_entities);

                $return[] = [
                    'link'        => $link,
                    'depth'       => $depth,
                    'entity_link' => $entity_link,
                    'children'    => $kids,
                    'access'      => $access,
                ];
            }
        }

        return empty($return) ? null : $return;
    }

    /**
     * Flatten out a tree, keeping its depth index.
     * @param  array   $tree
     * @param  array   &$flat_array
     * @return array
     */
    public function getFlat(array $tree, array &$flat_array = [])
    {
        foreach ($tree as $link) {
            $flat_array[] = [
                'link'  => $link['link'],
                'depth' => $link['depth'],
            ];

            if (!empty($link['children'])) {
                $this->getFlat($link['children'], $flat_array);
            }
        }

        return $flat_array;
    }

    /**
     * Check backwards and see if anything is assigned to this link.
     * @param  int       $link_id
     * @return boolean
     */
    public function identifyEntities($link_id)
    {
        $interfaces = Cache::tags('registry')->get('registered-inferfaces');

        if (empty($this->entity_links)) {
            $this->entity_links = [];

            if (!empty($interfaces['EntityLinkInterface'])) {
                foreach ($interfaces['EntityLinkInterface'] as $model) {
                    $orm                = app()->make($model);
                    $items              = $orm->where('link_id', '>', 0)->pluck('link_id')->all();
                    $this->entity_links = array_merge($items, $this->entity_links);
                }
            }
        }

        return in_array($link_id, $this->entity_links);
    }

    /**
     * Used to fake the menu active state.
     * @param string $current The menu you want to fool.
     * @param int    $segment How many steps back you want to go. eg -1.
     */
    public function setActiveUrlSegment($segment)
    {
        $url = Request::path();

        // Going up.
        $bits = explode('/', $url);
        $trim = array_slice($bits, 0, $segment);

        $slug = implode('/', $trim);

        $this->fake_url = $slug;
    }

    /**
     * Helper function for setActiveUrlSegment & checkFakeMenus
     * @param Item $item [description]
     */
    private function setActive($item)
    {
        $item->data('li_class', 'active');
        $item->activate();

        $class = $item->attribute('class');

        if (!stristr($class, 'active')) {
            $item->attribute('class', $class . ' active');
        }

        if ($item->parent) {
            $parent = $item->builder->whereId($item->parent)->first();
            $this->setActive($parent);
        }
    }

    /**
     * Helper function for setActiveUrlSegment & checkFakeMenus
     * Non resursive function to deactivate all links in a menu collection/
     * @param Item $item
     */
    private function setInactive($item)
    {
        $item->data('li_class', '');
        $item->data('active', false);
        $class = $item->attribute('class');
        $item->attribute('class', str_replace('active', '', $class));
    }

    /**
     * Called by BootMenus.
     * @param  [type] $name           [description]
     * @param  [type] &$menu          [description]
     * @return [type] [description]
     */
    private function checkFakeMenus(&$menu)
    {
        if ($this->fake_url) {
            // Deactivate All.
            foreach ($menu->items as $item) {
                $this->setInactive($item);
            }

            foreach ($menu->items as $item) {
                if ($item->link->path['url'] == $this->fake_url) {
                    $this->setActive($item);
                }
            }
        }
    }
}
