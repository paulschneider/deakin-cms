<?php
namespace App\Http\Composers;

use Illuminate\Contracts\View\View;

class MenusComposer
{
    /**
     * Compose the view
     */
    public function compose(View $view)
    {
        $name = $view->getName();

        $builder = app()->make('MenuBuilder');

        switch ($name) {
            case 'admin.layouts.master':
                $builder->bootMenus();
                break;
            case 'frontend.layouts.page':
                $builder->bootMenus(['main', 'quick_links']);
                break;
            case 'frontend.common.footer':
                $builder->bootMenus(['copyright', 'footer']);
                break;
            case 'common.breadcrumbs':
                $builder->bootMenus(['main']);
                $view->with('breadcrumbs', $builder->getBreadcrumbs(['main']));
                break;
        }
    }
}
