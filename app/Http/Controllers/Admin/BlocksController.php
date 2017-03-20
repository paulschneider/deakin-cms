<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlockFormRequest;
use App\Repositories\BlocksRepository;
use BlockManager;
use Flash;
use Tax;
use URL;

class BlocksController extends Controller
{
    /**
     * The blocks repository
     *
     * @var BlocksRepository
     */
    protected $block;

    /**
     * Dependency Injection
     *
     * @param BlocksRepository $block The instance of BlocksRepository
     */
    public function __construct(BlocksRepository $block)
    {
        $this->block = $block;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $options = ['filter' => false];
        $blocks  = $this->block->paginate(15, $options);

        return view('admin.blocks.index', compact('blocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $block_types = config('blocks.blocks');

        return view('admin.blocks.create', compact('block_types'));
    }

    /**
     * Create a type of block
     *
     * @param  string  $type The type of block to create
     * @return mixed
     */
    public function createType($type)
    {
        $block      = BlockManager::blockInfo($type);
        $methods    = BlockManager::methods(true);
        $regions    = config('blocks.regions');
        $categories = Tax::vocabularyOptions('block-categories');

        return view("{$block['admin_template']}_create", compact('block', 'methods', 'regions', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(BlockFormRequest $request)
    {
        $block = $this->block->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.blocks.edit', $block->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $block_id
     * @return Response
     */
    public function show($block_id)
    {
        $block = $this->block->findOrFail($block_id);

        return view('admin.blocks.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $block_id
     * @return Response
     */
    public function edit($block_id)
    {
        $block      = $this->block->findOrFail($block_id)->addBlockFields();
        $info       = BlockManager::blockInfo($block->type);
        $methods    = BlockManager::methods(true);
        $regions    = config('blocks.regions');
        $categories = Tax::vocabularyOptions('block-categories');

        return view("{$info['admin_template']}_edit", compact('block', 'info', 'methods', 'regions', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $block_id
     * @return Response
     */
    public function update($block_id, BlockFormRequest $request)
    {
        $block = $this->block->save($request, $block_id);

        Flash::success('Saved.');

        return redirect()->route('admin.blocks.edit', $block->id);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $block_id The block id
     * @return Response
     */
    public function delete($block_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.blocks.destroy', $block_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this block?',
                'title'        => 'Delete Block',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $block_id
     * @return Response
     */
    public function destroy($block_id)
    {
        $this->block->delete($block_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.blocks.index');
    }
}
