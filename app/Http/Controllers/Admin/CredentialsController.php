<?php
namespace App\Http\Controllers\Admin;

use URL;
use Flash;
use Datatables;
use App\Blocks\BlockManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Repositories\CredentialsRepository;
use App\Http\Requests\Admin\CredentialFormRequest;

class CredentialsController extends Controller
{
    /**
     * The credential repository
     *
     * @var CredentialsRepository
     */
    protected $credential;

    /**
     * Dependency Injection
     *
     * @param CredentialsRepository $credential The instance of CredentialsRepository
     */
    public function __construct(CredentialsRepository $credential)
    {
        $this->credential = $credential;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.credentials.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $options = ['filter' => false];

        $credentials = $this->credential->query($options)
                            ->join('credential_revisions', 'credentials.revision_id', '=', 'credential_revisions.id')
                            ->select([
                                'credentials.*',
                                'credential_revisions.title as title',
                                \DB::raw("IF(credentials.online,'Published','Unpublished') as published"),
                            ]);

        return Datatables::of($credentials)
            ->addColumn('revisions', function ($credential) {
                return link_to_route('admin.credentials.revisions.index', 'Revisions', $credential->id, ['class' => 'btn btn-outline btn-primary btn-xs']);
            })
            ->addColumn('edit', function ($credential) {
                return link_to_route('admin.credentials.edit', 'Edit', $credential->id, ['class' => 'btn btn-outline btn-success btn-xs']);
            })
            ->addColumn('delete', function ($credential) {
                return link_to_route('admin.credentials.delete', 'Delete', $credential->id, ['class' => 'btn btn-outline btn-danger btn-xs']);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  BlockManager $blocks The instance of block manager
     * @return Response
     */
    public function create(BlockManager $block)
    {
        $blocks = $block->getTypeOptions(['widget', 'form']);

        return view('admin.credentials.create', compact('blocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CredentialFormRequest $request)
    {
        $credential = $this->credential->saveRevision($request);

        if ($preview = $credential->getPreview()) {
            return redirect()->route('admin.credentials.show', [$credential->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        return redirect()->route('admin.credentials.edit', $credential->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $credential_id
     * @return Response
     */
    public function show($credential_id)
    {
        $revision_id = Request::get('revision');

        $credential = $this->credential
                           ->findOrFail($credential_id, ['filter' => false])
                           ->setRevision($revision_id)
                           ->appendExtras();

        return redirect()->route('frontend.dynamic.slug', [$credential->slug, 'revision' => $revision_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int          $credential_id The credential id
     * @param  BlockManager $blocks        The instance of block manager
     * @return Response
     */
    public function edit($credential_id, BlockManager $block)
    {
        $revision_id = Request::get('revision');

        $credential = $this->credential
                           ->findOrFail($credential_id, ['filter' => false, ['with' => ['revision', 'revision.related_links']]])
                           ->setRevision($revision_id)
                           ->appendExtras();

        $blocks = $block->getTypeOptions(['widget', 'form']);

        foreach ($credential->draftSchedules as $schedule) {
            if ($schedule->entity_id != $revision_id) {
                Flash::warning('This credential has another scheduled draft set to go online. You should be editing that revision instead.');
                break;
            }
        }

        return view('admin.credentials.edit', compact('credential', 'meta', 'blocks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $credential_id
     * @return Response
     */
    public function update($credential_id, CredentialFormRequest $request)
    {
        $revision_id = Request::get('revision');

        $credential = $this->credential->saveRevision($request, $credential_id, ['filter' => false], $revision_id);

        if ($preview = $credential->getPreview()) {
            return redirect()->route('admin.credentials.show', [$credential->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        $this->credential->clearCache($credential_id);

        return redirect()->route('admin.credentials.edit', [$credential_id, 'revision' => $credential->revision_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $credential_id The credential id
     * @return Response
     */
    public function delete($credential_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.credentials.destroy', $credential_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this credential?',
                'title'        => 'Delete Credential',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $credential_id
     * @return Response
     */
    public function destroy($credential_id)
    {
        $this->credential->delete($credential_id);

        Flash::success('Deleted.');

        $this->credential->clearCache($credential_id);

        return redirect()->route('admin.credentials.index');
    }
}
