<?php namespace App\Http\Controllers\Admin;

use Tax;
use URL;
use Flash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SortFormRequest;
use App\Repositories\VocabulariesRepository;
use App\Http\Requests\Admin\VocabularyFormRequest;

class VocabulariesController extends Controller
{
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
    public function __construct(VocabulariesRepository $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $options      = ['filter' => false];
        $vocabularies = $this->vocabulary->paginate(15, $options);

        return view('admin.vocabularies.index', compact('vocabularies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.vocabularies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(VocabularyFormRequest $request)
    {
        $vocabulary = $this->vocabulary->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.vocabularies.edit', $vocabulary->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function show($vocabulary_id)
    {
        $vocabulary = $this->vocabulary->findOrFail($vocabulary_id, ['with' => ['meta']])->appendMeta();

        return view('admin.pages.show', compact('vocabulary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function edit($vocabulary_id)
    {
        $vocabulary = $this->vocabulary->findOrFail($vocabulary_id);

        return view('admin.vocabularies.edit', compact('vocabulary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function update($vocabulary_id, VocabularyFormRequest $request)
    {
        $vocabulary = $this->vocabulary->save($request, $vocabulary_id);

        Flash::success('Saved.');

        return redirect()->route('admin.vocabularies.edit', $vocabulary->id);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $vocabulary_id The page id
     * @return Response
     */
    public function delete($vocabulary_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.vocabularies.destroy', $vocabulary_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this vocabulary?',
                'title'        => 'Delete Vocabulary',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $vocabulary_id
     * @return Response
     */
    public function destroy($vocabulary_id)
    {
        $this->vocabulary->delete($vocabulary_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.vocabularies.index');
    }

    /**
     * Show the menu sorting layout.
     * @param  int        $menu_id the menu id
     * @return Response
     */
    public function sort($vocabulary_id)
    {
        $vocabulary = $this->vocabulary->findOrFail($vocabulary_id, ['filter' => false]);

        $tree = Tax::getTree($vocabulary->terms);

        return view('admin.vocabularies.sort', compact('vocabulary', 'tree'));
    }

    /**
     * Show the menu sorting layout.
     * @param  int        $menu_id the menu id
     * @return Response
     */
    public function sortSubmit($vocabulary_id, SortFormRequest $request)
    {
        $vocabulary_id = $this->vocabulary->findOrFail($vocabulary_id, ['filter' => false]);

                                            // Submit to the repository for handling.
        $data   = $request->only('serial'); // Weird with get()
        $serial = $data['serial'];

        foreach ($vocabulary_id->terms as $term) {
            $items = array_where($serial, function ($value, $key) use ($term) {
                return $term->id == $value['id'];
            });

            foreach ($items as $item) {
                $term->weight    = (int) $item['weight'];
                $term->parent_id = $item['parent'] ?: null;
                $term->save();
            }
        }

        $this->vocabulary->clearCache();

        if ($request->ajax()) {
            return ['success' => 'Vocabulary sorted'];
        } else {
            Flash::success('Vocabulary sorted.');
            return redirect()->route('admin.vocabularies.index');
        }
    }
}
