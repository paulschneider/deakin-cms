<?php
namespace App\Filters;

class TrimFilter
{
    /**
     * Inject the iframe styles into the source
     *
     * @param  string   $source  The html source
     * @param  array    $options The options array
     * @return string
     */
    public function trim($source, $options)
    {
        return truncate_html($source, $options['length'], '&hellip;');
    }
}
