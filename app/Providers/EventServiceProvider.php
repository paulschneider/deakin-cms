<?php

namespace App\Providers;

use App\Events\RegisterFilter;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\RegisterBlock'       => [
            'App\Listeners\RegisterBlockHandler',
        ],
        'App\Events\RegisterTokenFilter' => [
            'App\Listeners\RegisterTokenFilterHandler',
        ],
        'App\Events\RegisterFilter'      => [
            'App\Listeners\RegisterFilterHandler',
        ],
        'App\Events\RegisterBanner'      => [
            'App\Listeners\RegisterBannerHandler',
        ],
        'App\Events\AliasWasChanged'     => [
            'App\Listeners\AliasWasChangedHandler',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        event(new RegisterFilter('icons', 'App\Repositories\IconsRepository', 'injectIcons'));
        event(new RegisterFilter('youtube', 'App\Filters\YoutubeFilter', 'styleIframes'));
        event(new RegisterFilter('trim', 'App\Filters\TrimFilter', 'trim'));
        event(new RegisterFilter('figures', 'App\Filters\FigureFilter', 'figureLinks'));
    }
}
