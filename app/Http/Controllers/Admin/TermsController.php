<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TermFormRequest;
use App\Repositories\TermsRepository;
use App\Repositories\VocabulariesRepository;
use Flash;
use Illuminate\Http\Request;
use URL;

class TermsController extends Controller
{
    /**
     * The term repository
     *
     * @var TermsRepository
     */
    protected $term;

    /**
     * The vocabulary repository
     *
     * @var VocabulariesRepository
     */
    protected $vocabulary;

    /**
     * Dependency Injection
     *
     * @param VocabulariesRepository $vocabulary The instance of VocabulariesRepository
     */
    public function __construct(TermsRepository $term, VocabulariesRepository $vocabulary)
    {
        $this->term       = $term;
        $this->vocabulary = $vocabulary;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function index($vocabulary_id)
    {
        $options    = ['filter' => false, 'with' => ['terms']];
        $vocabulary = $this->vocabulary->findOrFail($vocabulary_id, $options);
        $terms      = $vocabulary->terms()->paginate(15);

        return view('admin.terms.index', compact('vocabulary', 'terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function create($vocabulary_id)
    {
        $options    = ['filter' => false];
        $vocabulary = $this->vocabulary->findOrFail($vocabulary_id, $options);

        return view('admin.terms.create', compact('vocabulary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int             $vocabulary_id
     * @param  TermFormRequest $request
     * @return Response
     */
    public function store($vocabulary_id, TermFormRequest $request)
    {
        $term = $this->term->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.vocabularies.terms.index', [$vocabulary_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $vocabulary_id
     * @param  int        $term_id
     * @return Response
     */
    public function show($vocabulary_id, $term_id)
    {
        $term = $this->term->findOrFail($term_id, ['with' => ['vocabulary']]);

        return view('admin.vocabularies.terms.show', compact('term'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $vocabulary_id
     * @param  int        $term_id
     * @return Response
     */
    public function edit($vocabulary_id, $term_id)
    {
        $term       = $this->term->findOrFail($term_id, ['with' => ['vocabulary']]);
        $vocabulary = $term->vocabulary;

        return view('admin.terms.edit', compact('term', 'vocabulary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $vocabulary_id
     * @param  int        $term_id
     * @return Response
     */
    public function update($vocabulary_id, $term_id, TermFormRequest $request)
    {
        $term = $this->term->save($request, $term_id);

        Flash::success('Saved.');

        return redirect()->route('admin.vocabularies.terms.index', [$vocabulary_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $vocabulary_id The page id
     * @param  int        $term_id
     * @return Response
     */
    public function delete($vocabulary_id, $term_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.vocabularies.terms.destroy', $vocabulary_id, $term_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this term?',
                'title'        => 'Delete Term',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $vocabulary_id
     * @param  int        $term_id
     * @return Response
     */
    public function destroy($vocabulary_id, $term_id)
    {
        $this->term->delete($term_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.vocabularies.terms.index', [$vocabulary_id]);
    }
}
