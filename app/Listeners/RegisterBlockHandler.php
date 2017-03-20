<?php namespace App\Listeners;

use App\Blocks\BlockManager;
use App\Events\RegisterBlock;

class RegisterBlockHandler
{
    /**
     * Block manager
     *
     * @var App\Blocks\BlockManager;
     */
    protected $block;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(BlockManager $block)
    {
        $this->block = $block;
    }

    /**
     * Handle the event.
     *
     * @param  RegisterBlock $event
     * @return void
     */
    public function handle(RegisterBlock $event)
    {
        $this->block->registerMethod($event->class, $event->method, $event->name, $event->template, $event->args, $event->after);
    }
}
