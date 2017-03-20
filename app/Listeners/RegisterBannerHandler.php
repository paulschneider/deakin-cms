<?php namespace App\Listeners;

use App\Events\RegisterBanner;
use App\Banners\BannersManager;

class RegisterBannerHandler
{
    /**
     * Banner manager
     *
     * @var App\Banners\BannersManager;
     */
    protected $banner;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(BannersManager $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Handle the event.
     *
     * @param  RegisterBanner $event
     * @return void
     */
    public function handle(RegisterBanner $event)
    {
        $this->banner->registerMethod($event->class, $event->method, $event->name, $event->template, $event->args, $event->after);
    }
}
