<?php
namespace App\Banners;

use App\Models\BannerImage;

class MethodRender extends Render
{
    /**
     * The template to render
     *
     * @var string
     */
    protected $template;

    /**
     * The variables to render
     *
     * @var array
     */
    protected $variables;

    /**
     * Construct a banner call.
     *
     * @param Banner $banner    The banner
     * @param string $template  The template
     * @param array  $variables The variables for the render
     */
    public function __construct(BannerImage $banner, $template, $variables)
    {
        parent::__construct($banner);
        $this->template  = $template;
        $this->variables = $variables;
    }

    /**
     * Render the banner ad
     *
     * @param  array    $options The options
     * @return string
     */
    public function render($options)
    {
        if (!$this->banner->id) {
            return null;
        }

        if (empty($this->variables)) {
            return null;
        }

        $options = array_merge_recursive($this->variables, ['banner' => $this->banner, 'options' => $options]);

        $view = view($this->template, $options);

        return $view->render();
    }
}
