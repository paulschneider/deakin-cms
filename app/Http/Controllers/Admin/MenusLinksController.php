<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuLinkFormRequest;
use App\Repositories\MenusLinksRepository;
use App\Repositories\MenusRepository;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Menus;
use URL;

class MenusLinksController extends Controller
{
    /**
     * The link repository
     *
     * @var MenusLinksRepository
     */
    protected $link;

    /**
     * The menu repository
     *
     * @var MenusRepository
     */
    protected $menu;

    /**
     * Dependency Injection
     *
     * @param MenusLinksRepository $link The instance of MenusLinksRepository
     * @param MenusRepository      $menu The instance of MenusRepository
     */
    public function __construct(MenusLinksRepository $link, MenusRepository $menu)
    {
        $this->link = $link;
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function index($menu_id)
    {
        $options = ['filter' => false, 'with' => ['links'], 'scopes' => ['editable']];
        $menu    = $this->menu->findOrFail($menu_id, $options);

        return view('admin.menus.links.index', compact('menu'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData($menu_id)
    {
        $options = ['filter' => false, 'with' => ['links'], 'scopes' => ['editable']];
        $menu    = $this->menu->findOrFail($menu_id, $options);
        $links   = $menu->links();

        return Datatables::of($links)
            ->addColumn('edit', function ($link) {
                return link_to_route('admin.menus.links.edit', 'Edit', [$link->menu_id, $link->id], ['class' => 'btn btn-outline btn-success btn-xs']);
            })
            ->addColumn('delete', function ($link) {
                return link_to_route('admin.menus.links.delete', 'Delete', [$link->menu_id, $link->id], ['class' => 'btn btn-outline btn-danger btn-xs']);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function create($menu_id)
    {
        $menu = $this->menu->findOrFail($menu_id, ['filter' => false, 'scopes' => ['editable']]);

        $parents = Menus::getOptions($menu);

        return view('admin.menus.links.create', compact('menu', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int                 $menu_id
     * @param  MenuLinkFormRequest $request
     * @return Response
     */
    public function store($menu_id, MenuLinkFormRequest $request)
    {
        $link = $this->link->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.menus.links.edit', [$menu_id, $link->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $menu_id
     * @param  int        $link_id
     * @return Response
     */
    public function show($menu_id, $link_id)
    {
        $link = $this->link->findOrFail($link_id, ['with' => ['menu'], 'filter' => false])->appendMeta();

        return view('admin.menus.links.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $menu_id
     * @param  int        $link_id
     * @return Response
     */
    public function edit($menu_id, $link_id)
    {
        $link = $this->link->findOrFail($link_id, ['with' => ['menu'], 'filter' => false])->appendMeta();
        $menu = $link->menu;

        $parents = Menus::getOptions($menu, $link->id);

        return view('admin.menus.links.edit', compact('link', 'menu', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $menu_id
     * @param  int        $link_id
     * @return Response
     */
    public function update($menu_id, $link_id, MenuLinkFormRequest $request)
    {
        $link = $this->link->save($request, $link_id, ['filter' => false]);

        Flash::success('Saved.');

        return redirect()->route('admin.menus.links.edit', [$menu_id, $link_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $menu_id   The page id
     * @param  int        $link_id
     * @return Response
     */
    public function delete($menu_id, $link_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.menus.links.destroy', $menu_id, $link_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this link?',
                'title'        => 'Delete Link',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $menu_id
     * @param  int        $link_id
     * @return Response
     */
    public function destroy($menu_id, $link_id)
    {
        $this->link->delete($link_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.menus.links.index', [$menu_id]);
    }

    /**
     * Ajax callback to return the options for a dropdown.
     * @param  int  $menu_id   A menu id.
     * @param  int  $link_id   Optionally pass a link ID if the item cant be nested under itself.
     * @return Ajax response
     */
    public function getOptions($menu_id, $link_id = null)
    {
        $menu = $this->menu->findOrFail($menu_id, ['with' => ['links'], 'filter' => false, 'scopes' => ['editable']]);

        $options = Menus::getOptions($menu, $link_id);
        $result  = [];

        foreach ($options as $key => $value) {
            $result[] = ["key" => $key, "value" => $value];
        }

        return $result;
    }
}
