<?php
namespace App\Traits;

trait BannerTrait
{
    /**
     * Instance of BannerManager
     *
     * @var App\Banners\BannersManager
     */
    protected $bannersManager;

    /**
     * Get the BannerManager
     *
     * @return App\Banners\BannersManager
     */
    protected function getBannersManager()
    {
        // Lazy instantiation
        if (empty($this->bannersManager)) {
            $this->bannersManager = app()->make('App\Banners\BannersManager');
        }

        return $this->bannersManager;
    }

    /**
     * Get the banner field name. Gives a chance to override the field name
     *
     * @return string
     */
    public function getBannerData()
    {
        $field = null;
        if (empty($this->bannerField)) {
            $field = 'meta_banner';
        } else {
            $field = $this->bannerField;
        }

        return $this->{$field};
    }

    /**
     * Get the banner image for the entity
     *
     * @return string
     */
    public function bannerImage()
    {
        $data = $this->getBannerData();

        if (!empty($data['id'])) {
            $manager = $this->getBannersManager();

            $options = ['template' => 'frontend.common.static-banner'];

            if (!empty($this->title)) {
                $options['title'] = $this->title;
            }

            return $manager->getBannerImage($data, $options);
        }
    }
}
