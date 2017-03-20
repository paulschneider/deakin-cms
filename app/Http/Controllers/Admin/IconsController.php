<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IconFormRequest;
use App\Repositories\IconsRepository;
use Flash;
use Response;

class IconsController extends Controller
{
    /**
     * The icon repository
     *
     * @var IconsRepository
     */
    protected $icon;

    /**
     * Dependency Injection
     *
     * @param IconsRepository $icon The instance of IconsRepository
     */
    public function __construct(IconsRepository $icon)
    {
        $this->icon = $icon;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $icons = $this->icon->paginate(15, ['with' => ['image']], ['*']);

        return view('admin.icons.index', compact('icons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.icons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(IconFormRequest $request)
    {
        $icon = $this->icon->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.icons.edit', $icon->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $icon_id
     * @return Response
     */
    public function show($icon_id)
    {
        $icon = $this->icon->findOrFail($icon_id, ['with' => ['image']]);

        return view('admin.icons.show', compact('icon'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $icon_id
     * @param  int        $revision_id
     * @return Response
     */
    public function edit($icon_id)
    {
        $icon = $this->icon->findOrFail($icon_id, ['with' => ['image']]);

        return view('admin.icons.edit', compact('icon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $icon_id
     * @param  int        $revision_id
     * @return Response
     */
    public function update($icon_id, IconFormRequest $request)
    {
        $icon = $this->icon->findOrFail($icon_id, ['with' => ['image']]);

        $this->icon->save($request, $icon);

        Flash::success('Saved.');

        return redirect()->route('admin.icons.edit', [$icon->id, 'revision' => $icon->revision_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $icon_id The icon id
     * @return Response
     */
    public function delete($icon_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.icons.destroy', $icon_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => \URL::previous(),
                'question'     => 'Are you sure you want to delete this icon?',
                'title'        => 'Delete icon',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $icon_id
     * @return Response
     */
    public function destroy($icon_id)
    {
        $this->icon->delete($icon_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.icons.index');
    }

    /**
     * Show an iframe of all the available icons
     *
     * @return Response
     */
    public function iframe()
    {
        // Get all the icons
        $icons = $this->icon->all();

        return view('admin.icons.iframe', compact('icons'));
    }

    /**
     * Show the font awesome picker
     *
     * @return Response
     */
    public function fontawesome()
    {
        $icons = config('icons.fontawesome');

        return view('admin.icons.fontawesome', compact('icons'));
    }

    /**
     * Get the icon information
     *
     * @param  integer  $icon_id The icon id
     * @return string
     */
    public function wysiwyg($icon_id)
    {
        $icon = $this->icon->find($icon_id, ['with' => ['image']]);

        if ($icon) {
            return Response::json(['icon' => $icon], 200);
        }

        return Response::json(['error' => 'Icon not found'], 400);
    }
}
