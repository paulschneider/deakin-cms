<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Repositories\AliasRepository;

class CacheControl
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    private $alias;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, AliasRepository $alias)
    {
        $this->auth  = $auth;
        $this->alias = $alias;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->alias->canCache($request)) {
            $expires = (60 * 60);
            $response->setMaxAge($expires)->setPrivate();
        }

        return $response;
    }
}
