<?php
namespace App\Http\Controllers\Admin;

use URL;
use Flash;
use Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Repositories\ArticlesRepository;
use App\Http\Requests\Admin\ArticleFormRequest;

class ArticlesController extends Controller
{
    /**
     * The article repository
     *
     * @var ArticlesRepository
     */
    protected $article;

    /**
     * Dependency Injection
     *
     * @param ArticlesRepository $article The instance of ArticlesRepository
     */
    public function __construct(ArticlesRepository $article)
    {
        $this->article = $article;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.articles.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $options = ['filter' => false];

        $articles = $this->article->query($options)
                         ->join('article_revisions', 'articles.revision_id', '=', 'article_revisions.id')
                         ->select([
                             'articles.*',
                             'article_revisions.title as title',
                             \DB::raw("IF(articles.online,'Published','Unpublished') as published"),
                         ]);

        return Datatables::of($articles)
            ->addColumn('revisions', function ($article) {
                return link_to_route('admin.articles.revisions.index', 'Revisions', $article->id, ['class' => 'btn btn-outline btn-primary btn-xs']);
            })
            ->addColumn('edit', function ($article) {
                return link_to_route('admin.articles.edit', 'Edit', $article->id, ['class' => 'btn btn-outline btn-success btn-xs']);
            })
            ->addColumn('delete', function ($article) {
                return link_to_route('admin.articles.delete', 'Delete', $article->id, ['class' => 'btn btn-outline btn-danger btn-xs']);
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
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ArticleFormRequest $request)
    {
        $article = $this->article->saveRevision($request);

        if ($preview = $article->getPreview()) {
            return redirect()->route('admin.articles.show', [$article->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        return redirect()->route('admin.articles.edit', $article->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $article_id
     * @return Response
     */
    public function show($article_id)
    {
        $revision_id = Request::get('revision');

        $article = $this->article
                        ->findOrFail($article_id, ['filter' => false])
                        ->setRevision($revision_id)
                        ->appendExtras();

        return redirect()->route('frontend.articles.slug', [$article->slug, 'revision' => $revision_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $article_id
     * @param  int        $revision_id
     * @return Response
     */
    public function edit($article_id)
    {
        $revision_id = Request::get('revision');

        $article = $this->article
                        ->findOrFail($article_id, ['filter' => false])
                        ->setRevision($revision_id)
                        ->appendExtras();

        foreach ($article->draftSchedules as $schedule) {
            if ($schedule->entity_id != $revision_id) {
                Flash::warning('This article has another scheduled draft set to go online. You should be editing that revision instead.');
                break;
            }
        }

        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $article_id
     * @param  int        $revision_id
     * @return Response
     */
    public function update($article_id, ArticleFormRequest $request)
    {
        $revision_id = Request::get('revision');

        $article = $this->article->saveRevision($request, $article_id, ['filter' => false], $revision_id);

        if ($preview = $article->getPreview()) {
            return redirect()->route('admin.articles.show', [$article->id, 'revision' => $preview->id]);
        }

        Flash::success('Saved.');

        $this->article->clearCache($article_id);

        return redirect()->route('admin.articles.edit', [$article->id, 'revision' => $article->revision_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $article_id The article id
     * @return Response
     */
    public function delete($article_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.articles.destroy', $article_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this article?',
                'title'        => 'Delete Article',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $article_id
     * @return Response
     */
    public function destroy($article_id)
    {
        $this->article->delete($article_id);

        Flash::success('Deleted.');

        $this->article->clearCache($article_id);

        return redirect()->route('admin.articles.index');
    }
}
