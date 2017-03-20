<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Ip
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        $ip = $request->ip();

        if (in_array($ip, config('ip.allowed', []))) {
            return $next($request);
        }

        $longIp = ip2long($ip);

        foreach (config('ip.allowed_ranges', []) as $range) {
            list($low, $high) = $range;

            $low  = ip2long($low);
            $high = ip2long($high);

            if ($longIp <= $high && $low <= $longIp) {
                return $next($request);
            }
        }

        throw new AccessDeniedHttpException('IP Address intercepted by middleware. IP is not allowed. ' . $ip);
    }
}
