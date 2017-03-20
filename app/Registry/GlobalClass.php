<?php namespace App\Registry;

/**
 * @file
 * All the Global Class related functionality
 */
class GlobalClass
{
    /**
     * The registered classes
     *
     * @var array
     */
    protected static $classes = [];

    /**
     * Add one or more classes against an element
     *
     * @param string $element The element to register the classes against
     * @param mixed  $classes Array or string of the classes to add
     */
    public function add($element, $classes)
    {
        // Initialize the register
        if (!isset(static::$classes[$element]) || !is_array(static::$classes[$element])) {
            static::$classes[$element] = [];
        }

        if (!is_array($classes)) {
            $classes = explode(' ', $classes);
        }

        static::$classes[$element] = array_merge(static::$classes[$element], $classes);
        static::$classes[$element] = array_unique(static::$classes[$element]);
    }

    /**
     * Output the classes registerd against an element
     *
     * @param  string   $element The element whos classes should be outputted
     * @param  boolean  $space   If a space should be put before the string
     * @return string
     */
    public function output($element, $space = false)
    {
        if (!empty(static::$classes[$element])) {
            $space = $space ? ' ' : '';
            return $space . implode(static::$classes[$element], ' ');
        }

        return '';
    }

    /**
     * Output the classes for an element wrapping it with class
     *
     * @param  string   $element The element classes to output
     * @param  boolean  $space   If a space should be put before the string
     * @return string
     */
    public function outputWithAttribute($element, $space = false)
    {
        $output = $this->output($element);
        if (!empty($output)) {
            $space  = $space ? ' ' : '';
            $output = $space . 'class="' . $output . '"';
        }

        return $output;
    }

    /**
     * Remove a class registred agains an element
     *
     * @param string $element The element identifier
     * @param mixed  $classes The classes that need to be removed
     */
    public function remove($element, $classes)
    {
        if (!empty(static::$classes[$element]) && is_array(static::$classes[$element])) {
            if (!is_array($classes)) {
                $classes = explode(' ', $classes);
            }

            foreach ($classes as $class) {
                if (($key = array_search($class, static::$classes[$element])) !== false) {
                    unset(static::$classes[$element][$key]);
                }
            }
        }
    }

    /**
     * Check if a class exists in the array
     *
     * @param  string    $element The element
     * @param  string    $class   The class
     * @return boolean
     */
    public function has($element, $class)
    {
        if (($key = array_search($class, static::$classes[$element])) !== false) {
            return true;
        }
    }

    /**
     * Get the classes for the element. Useful for caching
     *
     * @param  string  $element The element
     * @return array
     */
    public function get($element)
    {
        if (!empty(static::$classes[$element])) {
            return static::$classes[$element];
        }

        return [];
    }

    /**
     * Get all the classes
     *
     * @return array
     */
    public function getAll()
    {
        return static::$classes;
    }

    /**
     * Add an array of multiple elments
     *
     * @param array $elements The array of elements
     */
    public function addAll($elements)
    {
        foreach ($elements as $element => $classes) {
            $this->add($element, $classes);
        }
    }
}
