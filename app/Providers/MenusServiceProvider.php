<?php
namespace App\Providers;

use App\Menus\MenuBuilder;
use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * Caffeinated Menus will automatically register your menu as a view composer (prepending your defined menu slug with menu_) that is accessible from all your views. Rendering your menu is simple from within your blade view files:
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.layouts.master', 'App\Http\Composers\MenusComposer');
        view()->composer('frontend.layouts.page', 'App\Http\Composers\MenusComposer');
        view()->composer('frontend.common.footer', 'App\Http\Composers\MenusComposer');
        view()->composer('common.breadcrumbs', 'App\Http\Composers\MenusComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MenuBuilder', function ($app) {
            $menus = $this->app->make('App\Repositories\MenusRepository');
            $links = $this->app->make('App\Repositories\MenusLinksRepository');

            return new MenuBuilder($menus, $links);
        });
    }
}
