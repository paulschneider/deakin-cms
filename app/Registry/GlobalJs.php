<?php namespace App\Registry;

/**
 * @file
 * All the Global Js related functionality
 */
class GlobalJs
{
    /**
     * The registered js files
     *
     * @var array
     */
    protected static $files = [];

    /**
     * Add a js files in a section
     *
     * @param string  $section The section to register the files against
     * @param string  $js      The js file to add
     * @param integer $weight  Optional weight of the file
     */
    public function add($section, $js, $weight = 0)
    {
        // Initialize the register
        if (!isset(static::$files[$section]) || !is_array(static::$files[$section])) {
            static::$files[$section] = [];
        }

        static::$files[$section][$js] = $weight;
    }

    /**
     * Output the js files registerd for a section
     *
     * @param  string   $section The section whos files should be outputted
     * @return string
     */
    public function output($section)
    {
        $output = '';
        if (!empty(static::$files[$section])) {
            $ordered = [];
            foreach (static::$files[$section] as $file => $weight) {
                $ordered[$weight][] = $file;
            }
            ksort($ordered);
            foreach ($ordered as $files) {
                foreach ($files as $file) {
                    $output .= '<script src="' . $file . '"></script>' . "\n";
                }
            }
        }

        return $output;
    }

    /**
     * Remove a js file from a section
     *
     * @param string $section The section identifier
     * @param mixed  $js      The file that need to be removed
     */
    public function remove($section, $js)
    {
        if (array_key_exists($js, static::$files[$section])) {
            unset(static::$files[$section][$js]);
        }
    }

    /**
     * Get all the files
     *
     * @return array
     */
    public function getAll()
    {
        return static::$files;
    }

    /**
     * Add an array of multiple files
     *
     * @param array $sections The array of sections
     */
    public function addAll($sections)
    {
        foreach ($sections as $section => $files) {
            foreach ($files as $file => $weight) {
                $this->add($section, $file, $weight);
            }
        }
    }
}
