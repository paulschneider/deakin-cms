<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'calculator/build',
        'rampart-lock',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next);
    }

    /**
     * Add the CSRF token to the response cookies.
     * Changed to secure only.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \Illuminate\Http\Response   $response
     * @return \Illuminate\Http\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        $config = config('session');

        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN',
                $request->session()->token(),
                time() + 60 * 120,
                $config['path'],
                $config['domain'],
                $config['secure'],
                false
            )
        );

        return $response;
    }
}
