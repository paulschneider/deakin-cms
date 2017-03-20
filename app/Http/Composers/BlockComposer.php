<?php namespace App\Http\Composers;

use App\Blocks\BlockManager;
use Illuminate\Contracts\View\View;

class BlockComposer
{
    /**
     * The instance of block manager
     *
     * @var BlockManager
     */
    protected $manager;

    /**
     * Constuctor
     */
    public function __construct(BlockManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Compose the view
     */
    public function compose(View $view)
    {
        $view->with('blocks', $this->manager->regions());
    }
}
