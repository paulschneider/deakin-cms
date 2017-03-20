<?php
namespace App\Http\Controllers\Admin;

use URL;
use Flash;
use Datatables;
use App\Blocks\BlockManager;
use App\Http\Controllers\Controller;
use App\Repositories\PagesRepository;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\PageFormRequest;

class PagesController extends Controller
{
    /**
     * The page repository
     *
     * @var PagesRepository
     */
    protected $page;

    /**
     * Dependency Injection
     *
     * @param PagesRepository $page The instance of PagesRepository
     */
    public function __construct(PagesRepository $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.pages.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $options = ['filter' => false];

        $pages = $this->page->query($options)
                      ->join('page_revisions', 'pages.revision_id', '=', 'page_revisions.id')
                      ->select([
                          'pages.*',
                          'page_revisions.title as title',
                          \DB::raw("IF(pages.online,'Published','Unpublished') as published"),
                      ]);

        return Datatables::of($pages)
            ->addColumn('revisions', function ($page) {
                return link_to_route('admin.pages.revisions.index', 'Revisions', $page->id, ['class' => 'btn btn-outline btn-primary btn-xs']);
            })
            ->addColumn('edit', function ($page) {
                return link_to_route('admin.pages.edit', 'Edit', $page->id, ['class' => 'btn btn-outline btn-success btn-xs']);
            })
            ->addColumn('delete', function ($page) {
                return link_to_route('admin.pages.delete', 'Delete', $page->id, ['class' => 'btn btn-outline btn-danger btn-xs']);
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

        return view('admin.pages.create', compact('blocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PageFormRequest $request)
    {
        $page = $this->page->saveRevision($request);

        if ($preview = $page->getPreview()) {
            return redirect()->route('admin.pages.show', [$page->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        return redirect()->route('admin.pages.edit', $page->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $page_id
     * @return Response
     */
    public function show($page_id)
    {
        $revision_id = Request::get('revision');

        $page = $this->page
                     ->findOrFail($page_id, ['filter' => false])
                     ->setRevision($revision_id)
                     ->appendExtras();

        return redirect()->route('frontend.dynamic.slug', [$page->slug, 'revision' => $revision_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int          $page_id The page id
     * @param  BlockManager $blocks  The instance of block manager
     * @return Response
     */
    public function edit($page_id, BlockManager $block)
    {
        $revision_id = Request::get('revision');

        $page = $this->page
                     ->findOrFail($page_id, ['filter' => false, ['with' => ['revision', 'revision.related_links']]])
                     ->setRevision($revision_id)
                     ->appendExtras();

        $blocks = $block->getTypeOptions(['widget', 'form']);

        foreach ($page->draftSchedules as $schedule) {
            if ($schedule->entity_id != $revision_id) {
                Flash::warning('This page has another scheduled draft set to go online. You should be editing that revision instead.');
                break;
            }
        }

        return view('admin.pages.edit', compact('page', 'meta', 'blocks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $page_id
     * @return Response
     */
    public function update($page_id, PageFormRequest $request)
    {
        $revision_id = Request::get('revision');

        $page = $this->page->saveRevision($request, $page_id, ['filter' => false], $revision_id);

        if ($preview = $page->getPreview()) {
            return redirect()->route('admin.pages.show', [$page->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        $this->page->clearCache($page_id);

        return redirect()->route('admin.pages.edit', [$page_id, 'revision' => $page->revision_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $page_id The page id
     * @return Response
     */
    public function delete($page_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.pages.destroy', $page_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this page?',
                'title'        => 'Delete Page',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $page_id
     * @return Response
     */
    public function destroy($page_id)
    {
        $this->page->delete($page_id);

        Flash::success('Deleted.');

        $this->page->clearCache($page_id);

        return redirect()->route('admin.pages.index');
    }
}
