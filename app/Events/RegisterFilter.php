<?php namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class RegisterFilter extends Event
{
    use SerializesModels;

    /**
     * The class
     *
     * @var string
     */
    public $class;

    /**
     * The method
     *
     * @var string
     */
    public $method;

    /**
     * The args if required
     *
     * @var array
     */
    public $options;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($class, $method, $options = [])
    {
        $this->class   = $class;
        $this->method  = $method;
        $this->options = $options;
    }
}
