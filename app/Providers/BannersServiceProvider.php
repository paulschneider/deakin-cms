<?php
namespace App\Providers;

use App\Events\RegisterBanner;
use App\Banners\BannersManager;
use Illuminate\Support\ServiceProvider;

class BannersServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // If you change options, you need to re-save the page.
        // Its a bit of a shit.
        // It hashes the array the has for caching purposes.
        $options = ['name' => 'example-banner', 'options' => ['text' => 'Optional data']];

        event(new RegisterBanner('App\Banners\BannersManager', 'getCustom', 'Example Callback Banner', 'frontend.banners.example-banner', $options));

        // If you change options, you need to re-save the page.
        // Its a bit of a shit.
        // It hashes the array the has for caching purposes.
        $options = ['name' => 'news-banner', 'options' => ['text' => 'News and resources']];

        event(new RegisterBanner('App\Banners\BannersManager', 'getCustom', 'News and resources', 'frontend.banners.news-banner', $options));

        // Register in some gradient banners.
        $colors = config('sections.section_colors');

        foreach ($colors as $css => $color) {
            $options = ['name' => 'gradient-' . $css, 'options' => ['class' => $css, 'image-element' => true]];

            event(new RegisterBanner('App\Banners\BannersManager', 'getCustom', 'Gradient ' . $color, 'frontend.banners.gradient-banner', $options));
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('BannersManager', function ($app) {
            $banner = $this->app->make('App\Repositories\BannersRepository');
            $images = $this->app->make('App\Repositories\BannersImagesRepository');

            return new BannersManager($banner, $images);
        });
    }
}
