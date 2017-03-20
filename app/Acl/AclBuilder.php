<?php namespace App\Acl;

use DB;
use Cache;
use App\Models\Menu;
use App\Models\MenuLink;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class AclBuilder
{
    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'acl';

    /**
     * The time a acl call should be cached
     */
    protected $cache_time;

    /**
     * A router reflection of findRoute
     */
    protected $findRoute;

    /**
     * The app router instance.
     */
    protected $router;

    public function __construct()
    {
        $this->cache_time = config('cache.acl_cache');
        $this->findRoute  = new \ReflectionMethod('Illuminate\Routing\Router', 'findRoute');
        $this->findRoute->setAccessible(true);
        $this->router = app()->make('Illuminate\Routing\Router');
    }

    /**
     * Get the deepest ACL string to test against.
     * @param  Resuest        $request
     * @param  Route          $route
     * @return string|false
     */
    public function deepest(Request $request, Route $route)
    {
        // Try going deep into the action
        if (!$aclString = $this->getString($request, $route, true)) {
            // Try the top level on the controller
            if (!$aclString = $this->getString($request, $route, false)) {
                return false;
            } else {
                return $aclString;
            }
        }

        return $aclString;
    }

    /**
     * Build an ACL suggestion for a URL.
     * Looks at the URL backwards to find the internal route.
     *
     * @param  MenuLink      $link
     * @param  string        $cachekey A cache key to cache against
     * @return string|null
     */
    public function suggestLink(MenuLink $link, $cachekey)
    {
        $slug = str_replace('/', '_', $link->route);
        $slug = str_slug($slug);
        $slug .= $cachekey;

        $result = Cache::tags(self::CACHE_PREFIX)->get('suggestion:' . $slug);

        if (!$result) {
            $request = Request::create($link->route, 'GET');

            try {
                if ($route = $this->findRoute->invoke($this->router, $request)) {
                    $result = $this->deepest($request, $route) ?: null;
                }
            } catch (\Exception $e) {
                // Avoid the router throwing an exception on not found for links.
            }

            if (empty($result)) {
                $result = 'no.suggestion';
            }
            Cache::tags(self::CACHE_PREFIX)->put('suggestion:' . $slug, $result, $this->cache_time);
        }

        return (($result == 'no.suggestion') ? null : $result);
    }

    /**
     * Build an ACL string based on requests.
     * @param  Request      $request
     * @param  Route        $route
     * @param  boolean      $action    Build an action string. (EG admin.pages.get.show)
     * @return string|false Built ACL string
     */
    protected function getString(Request $request, Route $route, $action = false)
    {
        if (empty($route)) {
            return false;
        }

        $controller = $route->getActionName();

        preg_match('/([a-z]+)\\\([a-z]+)Controller\@([a-z]+)/i', $controller, $matches);

        if (empty($matches[2])) {
            return false;
        }

        $acl = (object) [
            'namespace' => strtolower($matches[1]),
            'resource'  => strtolower($matches[2]),
            'action'    => strtolower($matches[3]),
        ];

        $method = null;

        switch ($request->method()) {
            case 'POST':
                $method = 'post';
                break;
            case 'PUT':
            case 'PATCH':
                $method = 'patch';
                break;
            case 'DELETE':
                $method = 'delete';
                break;
            default:
                $method = 'get';
                break;
        }

        if ($method) {
            if (!$permissions = Cache::tags(self::CACHE_PREFIX)->get('permissions')) {
                $permissions = DB::table('permissions')->pluck('name');
                Cache::tags(self::CACHE_PREFIX)->put('permissions', $permissions, $this->cache_time);
            }

            $string = $acl->namespace . '.' . $acl->resource . '.' . $method . ($action ? ('.' . $acl->action) : null);

            if ($permissions->get($string)) {
                return $string;
            }
        }

        return false;
    }
}
