<?php
namespace App\Banners;

class StaticRender extends Render
{
    /**
     * Render the banner ad
     *
     * @param  array    $options
     * @return string
     */
    public function render($options)
    {
        if (!$this->banner->id) {
            return null;
        }

        $this->banner->appendMeta();

        $view = view('frontend.banners.static-banner', ['banner' => $this->banner, 'options' => $options]);

        return $view->render();
    }
}
