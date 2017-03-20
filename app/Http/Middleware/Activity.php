<?php namespace App\Http\Middleware;

use Cache;
use Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Activity
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
        if ($this->auth->guest()) {
            return $next($request);
        }

        $user = $this->auth->getUser();
        $unix = time();

        // Limit hammering.
        if ($request->segment(1) == 'admin' && $request->method() == 'GET' && !$request->ajax()) {
            $current = Cache::tags('active_users')->get('activity', []);

            // Remove other people after lapse.
            foreach ($current as $key => $item) {
                if ($item['when'] < Carbon::now()->subMinutes(config('ip.activity_watch'))) {
                    unset($current[$key]);
                }
            }

            $current['user:' . $user->id] = [
                'email' => $user->email,
                'when'  => Carbon::now(),
                'ip'    => $request->ip(),
                'unix'  => time(),
            ];

            uasort($current, function ($a, $b) {
                return $a['unix'] < $b['unix'];
            });

            // Nuke it in 30 mins
            Cache::tags('active_users')->put('activity', $current, config('ip.activity_watch'));
        }

        return $next($request);
    }
}
