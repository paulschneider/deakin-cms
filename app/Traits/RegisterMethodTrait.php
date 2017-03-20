<?php namespace App\Traits;

trait RegisterMethodTrait
{
    /**
     * The registered methods from other classes
     *
     * @var array
     */
    static $methods = [];

    /**
     * Return a given method signature
     *
     * @param  string  $signature The signature of the method
     * @return mixed
     */
    public function method($signature)
    {
        return array_get(static::$methods, $signature);
    }

    /**
     * Run a method
     *
     * @param  array   $method The method signature
     * @return array
     */
    public function runMethod($method)
    {
        // Allow method to accept string to get the method
        if (is_string($method)) {
            $method = $this->method($method);
        }

        if (empty($method)) {
            return null;
        }

        // Run the method
        $class = \App::make($method['class']);
        return call_user_func_array([$class, $method['method']], $method['arguments']);
    }

    /**
     * The methods that are registered
     *
     * @param  boolean $options  If it should pass back options
     * @param  mixed   $optional If an optional option should be added
     * @return array
     */
    public function methods($options = false, $optional = false)
    {
        if ($options) {
            $methods = [];
            if ($optional) {
                $methods[null] = $optional;
            }

            foreach (static::$methods as $method => $data) {
                $methods[$method] = $data['name'];
            }

            return $methods;
        }
        return static::$methods;
    }

    /**
     * Register methods
     *
     * @param string $class     The name of the app class
     * @param string $method    The method to call in that class
     * @param string $name      The name of the method
     * @param string $template  The template to render
     * @param string $arguments The arguments
     * @param string $after     The method to call after
     */
    public function registerMethod($class, $method, $name, $template, $arguments, $after = null)
    {
        $signature = "{$class}.{$method}";

        if (!empty($arguments)) {
            $serialized = serialize($arguments);
            $signature .= "." . md5($serialized);
        }

        static::$methods[$signature] = compact('class', 'method', 'name', 'template', 'arguments', 'after');
    }
}
