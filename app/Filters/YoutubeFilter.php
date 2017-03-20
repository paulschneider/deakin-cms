<?php
namespace App\Filters;

class YoutubeFilter
{
    /**
     * Inject the iframe styles into the source
     *
     * @param  string   $source  The html source
     * @param  array    $options The options array
     * @return string
     */
    public function styleIframes($source, $options)
    {
        $source = preg_replace('/<iframe([^>]+youtube[^>]+)>/si', '<iframe class="embed-responsive-item" $1>', $source);

        return $source;
    }
}
