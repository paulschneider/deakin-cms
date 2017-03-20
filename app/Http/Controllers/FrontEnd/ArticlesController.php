<?php
namespace App\Http\Controllers\FrontEnd;

use App\Banners\BannersManager;
use App\Http\Controllers\Controller;
use App\Repositories\ArticlesRepository;
use Entrust;
use GlobalClass;
use GlobalJs;
use Illuminate\Support\Facades\Request;
use Menus;
use Variable;

class ArticlesController extends Controller
{
    /**
     * The article repository
     *
     * @var ArticlesRepository
     */
    protected $article;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ArticlesRepository $article)
    {
        $this->article = $article;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return redirect(Variable::get('site.news.page', '/'));
    }

    /**
     * Display the specified resource.
     *
     * @param  integer        $article_id The aticle id
     * @param  BannersManager $manager    The banner mananger
     * @return Response
     */
    public function show($article_id, BannersManager $manager)
    {
        // Make the parent the active item based on URL.
        Menus::setActiveUrlSegment(-1);

        $filter = [];

        // Strip revisions from slug if user cant access.
        if (Entrust::can('admin.articles.get')) {
            $filter['filter'] = false;
        } else {
            Request::merge(['revision' => null]);
        }

        $article = $this->article->findOrFail($article_id, $filter);
        $options = ['attachments' => ['meta_social_image' => 'large']];

        // Set the revision for admins.
        if (Entrust::can('admin.articles.get') && Request::get('revision')) {
            $article->setRevision(Request::get('revision'));
        }

        $article->appendExtras($options);

        $banner = $article->revision->getBannerData();

        $related_links = $article->revision->related_links;
        $classes       = GlobalClass::getAll();
        $js            = GlobalJs::getAll();

        $variables = compact('article', 'banner', 'related_links', 'article_id', 'classes', 'js');

        $bannerAttributes = [
            'title'   => $article->revision->title,
            'summary' => $article->revision->summary,
        ];

        $variables['banner'] = $manager->getBannerImage($variables['banner'], $bannerAttributes);

        // For meta on the higher level
        $variables['entity'] = $variables['article'];

        // Add all the classes
        GlobalClass::addAll($variables['classes']);

        // Add all the js
        GlobalJs::addAll($variables['js']);

        return view('frontend.articles.show', $variables);
    }

    /**
     * Legacy news support
     *
     * @param  string     $yyyy-mm-dd
     * @param  string     $slug
     * @return Response
     */
    public function legacy($date, $slug)
    {
        return redirect()->route('frontend.articles.slug', $slug);
    }
}
