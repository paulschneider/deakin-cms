<?php
namespace App\Filters;

class FigureFilter
{
    /**
     * Swap a tags to be inside figures instead of outside.
     *
     * @param  string   $source  The html source
     * @param  array    $options The options array
     * @return string
     */
    public function figureLinks($source, $options)
    {
        $source = preg_replace('/(<a[^>]+>)([\s]+)?(<figure[^>]+>)(.*?)(<\/figure>)([\s]+)?(<\/a>)/si', '$3$1$4$7$5', $source);

        return $source;
    }
}
