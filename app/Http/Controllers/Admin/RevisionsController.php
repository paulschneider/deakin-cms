<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class RevisionsController extends Controller
{
    /**
     * Set by child constructor
     * @var Repository
     */
    protected $repository = null;

    /**
     * Allow previews
     * @var string
     */
    protected $preview = true;

    /**
     * Display a listing of the resource.
     *
     * @param  int        $menu_id
     * @return Response
     */
    public function index($entity_id)
    {
        $options = ['filter' => false];
        $entity  = $this->repository->findOrFail($entity_id, $options);

        $with = ['user'];

        $model = app()->make($this->repository->model());

        $revisions = $entity->revisions()->with($with)->where('status', '!=', 'preview')->paginate(15, ['*']);

        $back    = $this->admin_route . '.index';
        $revert  = $this->admin_route . '.edit';
        $show    = $this->admin_route . '.show';
        $preview = $this->preview;

        return view('admin.revisions.index', compact('entity', 'revisions', 'back', 'show', 'revert', 'preview'));
    }
}
