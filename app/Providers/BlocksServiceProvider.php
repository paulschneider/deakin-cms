<?php
namespace App\Providers;

use App\Blocks\BlockManager;
use App\Events\RegisterBlock;
use Event;
use Illuminate\Support\ServiceProvider;

class BlocksServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Latest News
        event(new RegisterBlock('App\Repositories\ArticlesRepository', 'latestArticles', 'News: Latest', 'frontend.blocks.articles-latest', [], null));
        // News Index
        event(new RegisterBlock('App\Repositories\ArticlesRepository', 'allArticles', 'News: All', 'frontend.blocks.articles-all', [], null));
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('BlockManager', function ($app) {
            $block = $app->make('App\Repositories\BlocksRepository');
            return new BlockManager($block);
        });

        // Add the blocks to all pages
        $this->app->make('view')->composer('frontend.layouts.master', 'App\Http\Composers\BlockComposer');
    }
}
