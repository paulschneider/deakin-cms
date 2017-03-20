<?php namespace App\Listeners;

use App\Events\RegisterFilter;
use App\Filters\FilterManager;

class RegisterFilterHandler
{
    /**
     * Instance of TokenManager
     *
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
     * @param  RegisterFilter $event
     * @return void
     */
    public function handle(RegisterFilter $event)
    {
        $this->filter->registerFilter($event->class, $event->method, $event->options);
    }
}
