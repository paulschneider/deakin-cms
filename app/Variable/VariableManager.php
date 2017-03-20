<?php namespace App\Variable;

/*
 * @file
 * Variable related functionality
 */
use App\Models\Variable;
use Cache;

class VariableManager
{
    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'variable';

    /**
     * Instance of Variable
     * @var (Variable)
     */
    protected $variable;

    /**
     * The time a variable call should be cached
     */
    protected $cache_time;

    /**
     * Temp variables.
     * @var (Array)
     */
    protected $temp;

    /**
     * Constructor
     *
     * @param (Variable) $variable The instance of variable
     */
    public function __construct(Variable $variable)
    {
        $this->variable   = $variable;
        $this->cache_time = config('cache.variable_cache');
    }

    /**
     * Get the variable value
     *
     * @param  String $name    The name of the variable
     * @param  Mixed  $default The default value if it does not exist
     * @return Mixed  The mixed value
     */
    public function get($name, $default = '')
    {
        $exists = Cache::tags(self::CACHE_PREFIX)->get($name);

        if (!$exists) {
            if ($exists = $this->find($name)) {
                $this->saveCache($name, $exists);
            }
        }

        if ($exists) {
            return is_object($exists) ? unserialize($exists->value) : unserialize($exists);
        }

        return $default;
    }

    /**
     * Set a variable
     *
     * @param  String   $name  The variable name
     * @param  Mixed    $value The variable value
     * @return Variable The Variable
     */
    public function set($name, $value)
    {
        // Check if it exists
        $exists = $this->find($name);
        $value  = serialize($value);

        if ($exists) {
            $exists->value = $value;
            $exists->save();
        } else {
            $exists = Variable::create(['name' => $name, 'value' => $value]);
        }

        $this->saveCache($name, $value);

        return $exists;
    }

    /**
     * Delete the variable from the database and cache
     *
     * @param  String $name    The name of the variable
     * @return Boolea Response from cache forget
     */
    public function delete($name)
    {
        $exists = $this->find($name);

        if ($exists) {
            $exists->destroy($exists->id);
        }

        return Cache::tags(self::CACHE_PREFIX)->forget($name);
    }

    /**
     * Find the named variable
     *
     * @param  String   $name The variable name
     * @return Variable The variable object
     */
    protected function find($name)
    {
        return $this->variable->where('name', $name)->first();
    }

    /**
     * Save the value in the cache
     *
     * @param  String $name  The name of the variable
     * @param  Mixed  $value The value
     * @return Bool   If it is saved
     */
    protected function saveCache($name, $value)
    {
        return Cache::tags(self::CACHE_PREFIX)->put($name, $value, $this->cache_time);
    }

    /**
     * Store something for the life of execution.
     * @param  string   $key   A key
     * @param  mixed    $value SAny value
     * @return $value
     */
    public function putTemp($key, $value)
    {
        $this->temp[$key] = $value;
        return $value;
    }

    /**
     * Store something for the life of execution.
     * @param  string   $key   A key
     * @param  mixed    $value SAny value
     * @return $value
     */
    public function getTemp($key)
    {
        return isset($this->temp[$key]) ? $this->temp[$key] : null;
    }
}
