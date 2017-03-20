<?php
namespace App\Http\Controllers\FrontEnd;

use Illuminate\Support\Facades\Request;
use Entrust;
use GlobalJs;
use Variable;
use GlobalClass;
use App\Banners\BannersManager;
use App\Http\Controllers\Controller;
use App\Repositories\CredentialsRepository;

class CredentialsController extends Controller
{
    /**
     * The credential repository
     *
     * @var CredentialsRepository
     */
    protected $credential;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CredentialsRepository $credential)
    {
        $this->credential = $credential;
    }

    /**
     * Browse Credentials & Degrees credential.
     * @return view
     */
    public function index(BannersManager $manager)
    {
        $classes = GlobalClass::getAll();
        $js      = GlobalJs::getAll();

        $variables = compact('classes', 'js');

        $bannerAttributes = [
            'title'   => 'Deakin Professional Practice Credentials',
            'summary' => '',
        ];

        $variables['banner'] = $manager->render(config('banners.default_banner'), null, $bannerAttributes);

        return view('frontend.browse-credentials', $variables);
    }

    /**
     * Display the specified resource.
     *
     * @param  int           $credential_id The credential id
     * @param  BannerManager $banner        The banner manager
     * @return Response
     */
    public function show($credential_id, BannersManager $manager)
    {
        $filter = [];

        // Strip revisions from slug if user cant access.
        if (Entrust::can('admin.credentials.get')) {
            $filter['filter'] = false;
        } else {
            Request::merge(['revision' => null]);
        }

        $credential = $this->credential->findOrFail($credential_id, $filter);
        $options    = ['attachments' => ['meta_social_image' => 'large']];

        // Set the revision for admins.
        if (Entrust::can('admin.credentials.get') && Request::get('revision')) {
            $credential->setRevision(Request::get('revision'));
        }

        $credential->appendExtras($options);

        if ($credential->revision->hasForm()) {
            Variable::putTemp('no-dynamic-cache', true);
        }

        $template      = 'frontend.credentials.show';
        $sections      = $credential->revision->sectionableRenderSections();
        $banner        = $credential->revision->getBannerData();
        $related_links = $credential->revision->related_links;
        $classes       = GlobalClass::getAll();
        $js            = GlobalJs::getAll();

        unset($credential->section_fields);

        $variables = compact('credential', 'sections', 'banner', 'related_links', 'credential_id', 'template', 'classes', 'js');

        $bannerAttributes = [
            'title'   => 'Credentials and Degrees',
            'summary' => null,
        ];

        $variables['banner'] = $manager->getBannerImage($variables['banner'], $bannerAttributes);

        // For meta on the higher level
        $variables['entity'] = $variables['credential'];

        // Add all the classes
        GlobalClass::addAll($variables['classes']);

        // Add all the js
        GlobalJs::addAll($variables['js']);

        return view($variables['template'], $variables);
    }
}
