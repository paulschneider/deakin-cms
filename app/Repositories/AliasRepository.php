<?php
namespace App\Repositories;

use Auth;
use Cache;
use Session;
use Variable;
use Attachment;
use Illuminate\Http\Request;

class AliasRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Set the cache tags to be flushed on updates and deleted
     *
     * @var array
     */
    protected $cache_tags = ['url', 'menus'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Alias';
    }

    /**
     * Check if a Request can be cached.
     * @param  Request   $request
     * @return boolean
     */
    public function canCache(Request $request)
    {
        $path  = data_get($_SERVER, 'REQUEST_URI', $request->getRequestUri());
        $split = explode('?', $path);
        $first = reset($split);
        $first = ltrim($first, '/');

        foreach (config('cache.exclusions') as $except) {
            if ($request->is($except)) {
                Variable::putTemp('no-dynamic-cache', true);
                return false;
            }
        }

        if ($request->isMethod('GET') && !$request->ajax() && !Session::has('flash_notification.message') && Auth::guest() && !Variable::getTemp('no-dynamic-cache')) {
            return true;
        }

        return false;
    }

    /**
     * Generate a unique cache key for alias caching and dynamic controller.
     * @param  Request  $request
     * @return string
     */
    public function getCacheKey(Request $request)
    {
        $session = Session::all();
        $session = array_except($session, ['_token', '_previous']);
        $session = serialize($session);
        $input   = $request->all();
        $input   = serialize($input);
        $method  = $request->method();
        $path    = $request->path();

        return md5($path . $method . $input . $session);
    }

    /**
     * Get the gat for the cache
     * @param  mixed    $entity The resolved entity
     * @return string
     */
    public function getCacheTag($entity)
    {
        return $entity->getTable() . ':' . $entity->id;
    }

    /**
     * Resolve a url to an entity.
     * @param  string $slug
     * @return mixed  The reoslved entity
     */
    public function resolve($slug)
    {
        // Try things in the menu that use EntityLinkInterface first
        if ($url = $this->checkEntityLinks($slug)) {
            return (object) $url;
        }

        // Try Articles, they dont connect to menus but have slugs
        if ($url = $this->checkArticles($slug)) {
            return (object) $url;
        }

        // Try the Aliases model
        if ($alias = $this->checkAliasModel($slug)) {
            return $alias;
        }

        return null;
    }

    /**
     * Return an actual URL by slug
     * @param  string  $slug A dynamic url, eg /about
     * @return array
     */
    public function checkEntityLinks($slug)
    {
        $result = Cache::tags('url')->remember(md5($slug) . '.entity', config('cache.url_cache'), function () use ($slug) {
            $interfaces = Cache::tags('registry')->get('registered-inferfaces');
            if (!empty($interfaces['EntityLinkInterface'])) {
                foreach ($interfaces['EntityLinkInterface'] as $model) {
                    $orm = app()->make($model);

                    if ($entity = $orm->findBySlug($slug, ['filter' => false])) {
                        $explode    = explode('\\', $model);
                        $controller = str_plural(strtolower(end($explode)));

                        $url = $controller . '/' . $entity->id;

                        break;
                    }
                }
            }

            return (empty($url) ? null : ['alias' => $url, 'entity' => $entity, 'redirect' => false]);
        });

        return $result;
    }

    /**
     * Check Articles and dispatch them ccordingly.
     * @param  string  $slug A full URL
     * @return array
     */
    public function checkArticles($slug)
    {
        $result = Cache::tags('url')->remember(md5($slug) . '.articles', config('cache.url_cache'), function () use ($slug) {
            $parts = explode('/', $slug);
            $end   = end($parts);

            $comparison = route('frontend.articles.slug', $end, false);
            $comparison = trim($comparison, '/');

            if ($comparison !== \Request::path()) {
                return null;
            }

            $repo = app()->make('App\Repositories\ArticlesRepository');

            if ($entity = $repo->findBy('slug', $end, ['filter' => false])) {
                $url = route('frontend.articles.show', $entity->id);
                return ['alias' => $url, 'entity' => $entity, 'redirect' => false];
            }

            return null;
        });

        return $result;
    }

    /**
     * Check the Aliasable table for an alias.
     * @param  string  $slug
     * @return Alias
     */
    public function checkAliasModel($slug)
    {
        $self = &$this;

        $alias = Cache::tags('url')->remember(md5($slug) . '.model', config('cache.url_cache'), function () use ($self, $slug) {
            return $self->findBy('alias', $slug);
        });

        return $alias;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function checkAttachment($slug)
    {
        $url = Cache::tags('url')->remember(md5($slug) . '.attachment', config('cache.url_cache'), function () use ($slug) {
            if ($attachment = Attachment::findBySlug($slug)) {
                $url = $attachment->file->url();
            }
            return (empty($url) ? null : $url);
        });

        return $url;
    }
}
