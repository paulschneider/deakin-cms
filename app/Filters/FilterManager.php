<?php

namespace App\Filters;

use Purifier;

class FilterManager
{
    /**
     * The registered filters from other classes
     *
     * @var array
     */
    static $filters = [];

    /**
     * The registered tokens from other classes
     *
     * @var array
     */
    static $tokens = [];

    /**
     * Register the token filters for the content
     *
     * @param string $class   The name of the app class
     * @param string $method  The method to call in the class
     * @param string $token   The token
     * @param array  $options Optional args
     */
    public function registerToken($class, $method, $token, $options = [])
    {
        static::$tokens[$token] = compact('class', 'method', 'options');
    }

    /**
     * Register the filters
     *
     * @param  string $name    The unique identifiery
     * @param  string $class   The name of the class
     * @param  string $method  The method name
     * @param  array  $options The array of options
     * @return void
     */
    public function registerFilter($name, $class, $method, $options = [])
    {
        static::$filters[$name] = compact('class', 'method', 'options');
    }

    /**
     * Filter the content with registered tokens
     *
     * @param  string   $content The content
     * @param  array    $flags   Flags to send the filter at runtime
     * @return string
     */
    public function filter($content, $flags = [])
    {
        $content = str_replace('&nbsp;', ' ', $content);
        $content = preg_replace('~\x{00a0}~siu', ' ', $content);

        if (!empty($flags['purifier'])) {
            $content = Purifier::clean($content, $flags['purifier']);
        }

        foreach (static::$tokens as $token => $filter) {
            preg_match_all('/' . preg_quote($token, '/') . '/i', $content, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $match) {
                    $content = $this->replace($filter, $content, $token, $flags, $filter['options']);
                }
            }
        }

        if (!empty($flags['filter']) && is_array($flags['filter'])) {
            foreach ($flags['filter'] as $name) {
                if (array_key_exists($name, static::$filters)) {
                    $content = $this->runFilter(static::$filters[$name], $content, $flags);
                }
            }
        }

        return $content;
    }

    /**
     * Run a filter
     *
     * @param  array    $filter  The filter data
     * @param  string   $content The content
     * @param  array    $flags   array
     * @return string
     */
    public function runFilter($filter, $content, $flags = [])
    {
        $class   = \App::make($filter['class']);
        $content = $class->{$filter['method']}($content, $flags);

        return $content;
    }

    /**
     * Replace the content with a registered method
     *
     * @param  array    $filter  The filter to replace with
     * @param  string   $content The content to replace
     * @param  string   $token   The token
     * @param  array    $flags   Optional flags passed at time of render
     * @param  array    $options Optional args set at time of event registration
     * @return string
     */
    protected function replace($filter, $content, $token, $flags = [], $options = [])
    {
        $class = \App::make($filter['class']);

        $replace = '';
        if ($string = $class->{$filter['method']}($content, $token, $flags, $options)) {
            $replace = $string;
        }

        return str_replace($token, $replace, $content);
    }

    /**
     * Delete this later, this is a test
     *
     * @return string
     */
    public function test()
    {
        return '<span>Test</span>';
    }
}
