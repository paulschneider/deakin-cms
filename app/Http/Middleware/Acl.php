<?php namespace App\Http\Middleware;

use App\Acl\AclBuilder;
use Closure;
use Entrust;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Acl
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The Acl implementation.
     *
     * @var AclBuilder
     */
    protected $acl;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, AclBuilder $acl)
    {
        $this->auth = $auth;
        $this->acl  = $acl;
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
        // Try going deep into the action
        if (!$aclString = $this->acl->deepest($request, $request->route())) {
            return $next($request);
        }

        if (Entrust::can($aclString)) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('Access not allowed');
    }
}
