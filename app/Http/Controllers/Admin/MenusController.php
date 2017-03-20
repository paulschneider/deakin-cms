<?php namespace App\Http\Controllers\Admin;

use URL;
use Flash;
use Menus;
use App\Http\Controllers\Controller;
use App\Repositories\MenusRepository;
use App\Repositories\MenusLinksRepository;
use App\Http\Requests\Admin\MenuFormRequest;
use App\Http\Requests\Admin\SortFormRequest;

class MenusController extends Controller
{
    /**
     * The menu repository
     *
     * @var MenusRepository
     */
    protected $menu;

    /**
     * The links repository
     *
     * @var MenusLinksRepository
     */
    protected $links;

    /**
     * Dependency Injection
     *
     * @param MenusRepository      $menu  The instance of MenusRepository
     * @param MenusLinksRepository $links The instance of MenusLinksRepository
     */
    public function __construct(MenusRepository $menu, MenusLinksRepository $links)
    {
        $this->menu  = $menu;
        $this->links = $links;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menus = $this->menu->paginate(15, ['filter' => false, 'scopes' => ['editable']]);

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MenuFormRequest $request)
    {
        $menu = $this->menu->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.menus.edit', $menu->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function show($menu_id)
    {
        $menu = $this->menu->findOrFail($menu_id);

        return view('admin.pages.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function edit($menu_id)
    {
        $menu = $this->menu->findOrFail($menu_id, ['filter' => false, 'scopes' => ['editable']]);

        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function update($menu_id, MenuFormRequest $request)
    {
        $menu = $this->menu->save($request, $menu_id, ['filter' => false, 'scopes' => ['editable']]);

        Flash::success('Saved.');

        return redirect()->route('admin.menus.edit', $menu->id);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $menu_id The menu id
     * @return Response
     */
    public function delete($menu_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.menus.destroy', $menu_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this menu?',
                'title'        => 'Delete Menu',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function destroy($menu_id)
    {
        $this->menu->delete($menu_id, ['filter' => false, 'scopes' => ['editable']]);

        Flash::success('Deleted.');

        return redirect()->route('admin.menus.index');
    }

    /**
     * Show the menu sorting layout.
     * @param  int        $menu_id the menu id
     * @return Response
     */
    public function sort($menu_id)
    {
        $menu = $this->menu->findOrFail($menu_id, ['filter' => false, 'scopes' => ['editable']]);

        $tree = Menus::getTree($menu->links, null, null, 0, false, true);

        return view('admin.menus.sort', compact('menu', 'tree'));
    }

    /**
     * Show the menu sorting layout.
     * @param  int        $menu_id the menu id
     * @return Response
     */
    public function sortSubmit($menu_id, SortFormRequest $request)
    {
        $this->menu->clearCache();

        $menu = $this->menu->findOrFail($menu_id, ['filter' => false, 'scopes' => ['editable']]);

                                            // Submit to the repository for handling.
        $data   = $request->only('serial'); // Weird with get()
        $serial = $data['serial'];

        foreach ($menu->links as $link) {
            $items = array_where($serial, function ($value, $key) use ($link) {
                return $link->id == $value['id'];
            });

            foreach ($items as $item) {
                $update = [
                    'weight'    => (int) $item['weight'],
                    'parent_id' => (empty($item['parent']) ? null : (int) $item['parent']),
                ];

                // Sneak into the DB.
                \DB::table('menus_links')->where('id', '=', $link->id)->update($update);
            }
        }

        // Trigger off the ORM after a reload.
        $menu = $this->menu->findOrFail($menu_id, ['filter' => false]);

        $reloaded = clone $menu->links;
        foreach ($reloaded as $link) {
            $link->save();
        }

        $this->menu->clearCache();

        if ($request->ajax()) {
            return ['success' => 'Menu sorted'];
        } else {
            Flash::success('Menu sorted.');
            return redirect()->route('admin.menus.index');
        }
    }
}
