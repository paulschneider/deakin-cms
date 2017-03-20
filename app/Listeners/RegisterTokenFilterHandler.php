<?php namespace App\Listeners;

use App\Filters\FilterManager;
use App\Events\RegisterTokenFilter;

class RegisterTokenFilterHandler
{
    /**
     * Instance of FilterManager
     * @var App\Filters\FilterManager
     */
    protected $filter;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(FilterManager $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Handle the event.
     *
     * @param  RegisterTokenFilter $event
     * @return void
     */
    public function handle(RegisterTokenFilter $event)
    {
        $this->filter->registerToken($event->class, $event->method, $event->token, $event->options);
    }
}
