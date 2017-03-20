<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AliasFormRequest;
use App\Repositories\AliasRepository;
use Datatables;
use Flash;
use URL;

class AliasesController extends Controller
{
    /**
     * The alias repository
     *
     * @var AliasRepository
     */
    protected $alias;

    /**
     * Dependency Injection
     *
     * @param AliasRepository $alias The instance of AliasRepository
     */
    public function __construct(AliasRepository $alias)
    {
        $this->alias = $alias;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.aliases.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $options = ['filter' => false];
        $aliases = $this->alias->query($options);

        return Datatables::of($aliases)
            ->addColumn('redirect', function ($alias) {
                return parse_url($alias->redirect)['path'];
            })
            ->addColumn('delete', function ($alias) {
                return link_to_route('admin.aliases.delete', 'Delete', $alias->id, ['class' => 'btn btn-outline btn-danger btn-xs']);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.aliases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AliasFormRequest $request)
    {
        $alias = $this->alias->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.aliases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $alias_id
     * @return Response
     */
    public function show($alias_id)
    {
        return view('admin.pages.show', compact('alias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $alias_id
     * @return Response
     */
    public function edit($alias_id)
    {
        $alias = $this->alias->findOrFail($alias_id, ['filter' => false]);

        return view('admin.aliases.edit', compact('alias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $alias_id
     * @return Response
     */
    public function update($alias_id, BannerFormRequest $request)
    {
        $alias = $this->alias->save($request, $alias_id, ['filter' => false]);

        Flash::success('Saved.');

        return redirect()->route('admin.aliases.index');
    }

    /**
     * Not restful delete url
     *
     * @param  int        $alias_id The alias id
     * @return Response
     */
    public function delete($alias_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.aliases.destroy', $alias_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this alias?',
                'title'        => 'Delete Banner',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $alias_id
     * @return Response
     */
    public function destroy($alias_id)
    {
        $this->alias->delete($alias_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.aliases.index');
    }
}
