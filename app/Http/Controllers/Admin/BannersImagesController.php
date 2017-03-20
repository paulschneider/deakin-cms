<?php namespace App\Http\Controllers\Admin;

use App\Banners\BannersManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerImageFormRequest;
use App\Repositories\BannersImagesRepository;
use App\Repositories\BannersRepository;
use Banners;
use Flash;
use Illuminate\Http\Request;
use Response;
use URL;

class BannersImagesController extends Controller
{
    /**
     * The image repository
     *
     * @var BannersImagesRepository
     */
    protected $image;

    /**
     * The banner repository
     *
     * @var BannersRepository
     */
    protected $banner;

    /**
     * Dependency Injection
     *
     * @param BannersRepository $banner The instance of BannersRepository
     */
    public function __construct(BannersImagesRepository $image, BannersRepository $banner)
    {
        $this->image  = $image;
        $this->banner = $banner;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int        $banner_id
     * @return Response
     */
    public function index($banner_id)
    {
        $options = ['filter' => false, 'with' => ['images']];
        $banner  = $this->banner->findOrFail($banner_id, $options);
        $images  = $banner->images()->paginate(15);

        return view('admin.banners.images.index', compact('banner', 'images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  integer        $banner_id The banner id
     * @param  BannersManager $manager   The banner manager
     * @return Response
     */
    public function create($banner_id, BannersManager $manager)
    {
        $banner  = $this->banner->findOrFail($banner_id, ['filter' => false]);
        $parents = Banners::getOptions($banner);
        $methods = $manager->methods(true, 'None');

        return view('admin.banners.images.create', compact('banner', 'parents', 'methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int                    $banner_id
     * @param  BannerImageFormRequest $request
     * @return Response
     */
    public function store($banner_id, BannerImageFormRequest $request)
    {
        $image = $this->image->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.banners.images.edit', [$banner_id, $image->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $banner_id
     * @param  int        $image_id
     * @return Response
     */
    public function show($banner_id, $image_id)
    {
        $image = $this->image->findOrFail($image_id, ['with' => ['banner'], 'filter' => false])->appendMeta();

        return view('admin.banners.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer        $banner_id The banner id
     * @param  integer        $image_id  The image id
     * @param  BannersManager $manager   The banner manager
     * @return Response
     */
    public function edit($banner_id, $image_id, BannersManager $manager)
    {
        $image  = $this->image->findOrFail($image_id, ['with' => ['banner'], 'filter' => false])->appendMeta();
        $banner = $image->banner;

        $parents = Banners::getOptions($banner, $image->id);
        $methods = $manager->methods(true, 'None');

        return view('admin.banners.images.edit', compact('image', 'banner', 'parents', 'methods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $banner_id
     * @param  int        $image_id
     * @return Response
     */
    public function update($banner_id, $image_id, BannerImageFormRequest $request)
    {
        $image = $this->image->save($request, $image_id, ['filter' => false]);

        Flash::success('Saved.');

        return redirect()->route('admin.banners.images.edit', [$banner_id, $image_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $banner_id  The page id
     * @param  int        $image_id
     * @return Response
     */
    public function delete($banner_id, $image_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.banners.images.destroy', $banner_id, $image_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this image?',
                'title'        => 'Delete Link',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $banner_id
     * @param  int        $image_id
     * @return Response
     */
    public function destroy($banner_id, $image_id)
    {
        $this->image->delete($image_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.banners.images.index', [$banner_id]);
    }

    /**
     * Ajax callback to return the options for a dropdown.
     * @param  int  $banner_id A banner id.
     * @param  int  $image_id  Optionally pass a image ID if the item cant be nested under itself.
     * @return Ajax response
     */
    public function getOptions($banner_id, $image_id = null)
    {
        $banner = $this->banner->findOrFail($banner_id, ['with' => ['images'], 'filter' => false]);

        $options = Banners::getOptions($banner);
        $result  = [];

        foreach ($options as $key => $value) {
            $result[] = ["key" => $key, "value" => $value];
        }

        return $result;
    }

    /**
     * Get the banner image via ajax
     *
     * @param  int      $banner_id The banner id
     * @return string
     */
    public function ajaxImage($banner_id)
    {
        $banner = $this->image->find($banner_id);

        if ($banner) {
            if (empty($banner->attachment->file)) {
                return Response::json(['error' => 'Banner has no image attached'], 400);
            }
            return Response::json(['banner' => $banner->attachment->file->url()], 200);
        }

        return Response::json(['error' => 'Template not found'], 400);
    }
}
