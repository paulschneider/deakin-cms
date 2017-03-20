<?php
namespace App\Http\Controllers\FrontEnd;

use Illuminate\Support\Facades\Request;
use Entrust;
use GlobalJs;
use Variable;
use GlobalClass;
use App\Banners\BannersManager;
use App\Http\Controllers\Controller;
use App\Repositories\PagesRepository;

class PagesController extends Controller
{
    /**
     * The page repository
     *
     * @var PagesRepository
     */
    protected $page;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PagesRepository $page)
    {
        $this->page = $page;
    }

    /**
     * Display the specified resource.
     *
     * @param  int           $page_id The page id
     * @param  BannerManager $banner  The banner manager
     * @return Response
     */
    public function show($page_id, BannersManager $manager)
    {
        $filter = [];

        // Strip revisions from slug if user cant access.
        if (Entrust::can('admin.pages.get')) {
            $filter['filter'] = false;
        } else {
            Request::merge(['revision' => null]);
        }

        $page    = $this->page->findOrFail($page_id, $filter);
        $options = ['attachments' => ['meta_social_image' => 'large']];

        // Set the revision for admins.
        if (Entrust::can('admin.pages.get') && Request::get('revision')) {
            $page->setRevision(Request::get('revision'));
        }

        $page->appendExtras($options);

        if ($page->revision->hasForm()) {
            Variable::putTemp('no-dynamic-cache', true);
        }

        $template      = 'frontend.pages.show';
        $sections      = $page->revision->sectionableRenderSections();
        $banner        = $page->revision->getBannerData();
        $related_links = $page->revision->related_links;
        $classes       = GlobalClass::getAll();
        $js            = GlobalJs::getAll();

        unset($page->section_fields);

        $variables = compact('page', 'sections', 'banner', 'related_links', 'page_id', 'template', 'classes', 'js');

        $bannerAttributes = [
            'title'   => $page->revision->title,
            'summary' => $page->revision->summary,
        ];

        $variables['banner'] = $manager->getBannerImage($variables['banner'], $bannerAttributes);

        // For meta on the higher level
        $variables['entity'] = $variables['page'];

        // Add all the classes
        GlobalClass::addAll($variables['classes']);

        // Add all the js
        GlobalJs::addAll($variables['js']);

        return view($variables['template'], $variables);
    }
}
