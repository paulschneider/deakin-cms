<?php namespace App\Events;

use App\Events\Event;

class RegisterMethod extends Event
{
    /**
     * The class that the method should be called on
     *
     * @var string
     */
    public $class;

    /**
     * The method to call
     *
     * @var string
     */
    public $method;

    /**
     * The name of the method for display
     *
     * @var string
     */
    public $name;

    /**
     * Any arguments that are needed for the method
     *
     * @var array
     */
    public $args;

    /**
     * Method to run after the method
     *
     * @var string
     */
    public $after;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($class, $method, $name, $template, $args = [], $after = null)
    {
        $this->class    = $class;
        $this->method   = $method;
        $this->name     = $name;
        $this->template = $template;
        $this->args     = $args;
        $this->after    = $after;
    }
}
