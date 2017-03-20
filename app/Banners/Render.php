<?php namespace App\Banners;

use App\Models\BannerImage;

abstract class Render
{
    /**
     * The banner to render.
     * @var Banner
     */
    protected $banner;

    /**
     * Construct a banner call.
     * @param Banner $banner
     */
    public function __construct(BannerImage $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Render the banner ad
     *
     * @param  array    $options
     * @return string
     */
    public function render($options)
    {
    }
}
