<?php namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class RegisterTokenFilter extends Event
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
     * The token
     *
     * @var string
     */
    public $token;

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
    public function __construct($class, $method, $token, $options = [])
    {
        $this->class   = $class;
        $this->method  = $method;
        $this->token   = $token;
        $this->options = $options;
    }
}
