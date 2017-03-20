<?php
namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Repositories\AliasRepository;
use Cache;
use Redirect;
use Request;
use Response;
use Route;
use Variable;

class DynamicController extends Controller
{
    private $cache_time;

    private $alias;

    /**
     * Inject the dependencies
     */
    public function __construct(AliasRepository $alias)
    {
        $this->alias      = $alias;
        $this->cache_time = config('cache.page_cache'); // 30 second protection hot-cache.
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function dynamic($slug = null)
    {
        $slug = $slug ?: Request::path();

        if ($resolved = $this->alias->resolve($slug)) {
            if ($resolved->redirect) {
                return Redirect::to($resolved->redirect, 301);
            }

            // Make a new request.
            $request = Request::create($resolved->alias, Request::method());
            $request->merge(Request::all());

            $cachable = false;
            $cacheTag = $this->alias->getCacheTag($resolved->entity);
            $cacheKey = $this->alias->getCacheKey($request);

            if ($this->alias->canCache($request)) {
                $cachable = true;

                if ($html = Cache::tags($cacheTag)->get($cacheKey)) {
                    // Hot exit.
                    if (!env('APP_DEBUG', false)) {
                        return response($html);
                    }
                }
            }

            $dispatch = Route::dispatch($request);
            $result   = $dispatch->getContent();

            // The dispatch may alter the Variable.
            if (Variable::getTemp('no-dynamic-cache')) {
                $cachable = false;
            }

            $response = response($result);

            if ($cachable) {
                Cache::tags($cacheTag)->put($cacheKey, $result, $this->cache_time);
            } else {
                header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
                header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");   // Date in the past
            }

            return $response;
        }

        // Nothing found.
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function attachment($slug)
    {
        // Check attachments first.
        $url = $this->alias->checkAttachment($slug);

        if (!empty($url)) {
            return Redirect::to($url, 301);
        }

        // Check the aliases.
        if ($resolved = $this->alias->resolve($slug)) {
            if ($resolved->redirect) {
                return Redirect::to($resolved->redirect, 301);
            }
        }

        // Nothing found.
        abort(404);
    }
}
