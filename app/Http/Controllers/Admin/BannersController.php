<?php namespace App\Http\Controllers\Admin;

use URL;
use Flash;
use Banners;
use App\Http\Controllers\Controller;
use App\Repositories\BannersRepository;
use App\Http\Requests\Admin\SortFormRequest;
use App\Http\Requests\Admin\BannerFormRequest;

class BannersController extends Controller
{
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
    public function __construct(BannersRepository $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $banners = $this->banner->paginate(15, ['filter' => false]);

        return view('admin.banners.index', compact('banners', 'page_banner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(BannerFormRequest $request)
    {
        $banner = $this->banner->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.banners.edit', $banner->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $banner_id
     * @return Response
     */
    public function edit($banner_id)
    {
        $banner = $this->banner->findOrFail($banner_id, ['filter' => false]);

        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $banner_id
     * @return Response
     */
    public function update($banner_id, BannerFormRequest $request)
    {
        $banner = $this->banner->save($request, $banner_id, ['filter' => false]);

        Flash::success('Saved.');

        return redirect()->route('admin.banners.edit', $banner->id);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $banner_id The banner id
     * @return Response
     */
    public function delete($banner_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.banners.destroy', $banner_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this banner?',
                'title'        => 'Delete Banner',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $banner_id
     * @return Response
     */
    public function destroy($banner_id)
    {
        $this->banner->delete($banner_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.banners.index');
    }

    /**
     * Show the banner sorting layout.
     * @param  int        $banner_id the banner id
     * @return Response
     */
    public function sort($banner_id)
    {
        $banner = $this->banner->findOrFail($banner_id, ['filter' => false]);

        $tree = Banners::getTree($banner->images, null, null, 0, false);

        return view('admin.banners.sort', compact('banner', 'tree'));
    }

    /**
     * Show the banner sorting layout.
     * @param  int        $banner_id the banner id
     * @return Response
     */
    public function sortSubmit($banner_id, SortFormRequest $request)
    {
        $banner = $this->banner->findOrFail($banner_id, ['filter' => false]);

                                            // Submit to the repository for handling.
        $data   = $request->only('serial'); // Weird with get()
        $serial = $data['serial'];

        foreach ($banner->images as $image) {
            $items = array_where($serial, function ($value, $key) use ($image) {
                return $image->id == $value['id'];
            });

            foreach ($items as $item) {
                $image->weight    = (int) $item['weight'];
                $image->parent_id = $item['parent'] ?: null;
                $image->save();
            }
        }

        $this->banner->clearCache();

        if ($request->ajax()) {
            return ['success' => 'Banner sorted'];
        } else {
            Flash::success('Banner sorted.');
            return redirect()->route('admin.banners.index');
        }
    }
}
