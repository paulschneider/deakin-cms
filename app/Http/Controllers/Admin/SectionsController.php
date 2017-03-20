<?php namespace App\Http\Controllers\Admin;

use App\Blocks\BlockManager;
use App\Http\Controllers\Controller;
use Response;
use stdClass;

class SectionsController extends Controller
{
    /**
     * The instance of block manager
     * @var BlockManager
     */
    protected $block;

    /**
     * Constructor
     * @param BlockManager $block The block manager
     */
    public function __construct(BlockManager $block)
    {
        $this->block = $block;
    }

    /**
     * Get a new section template
     *
     * @param  string   $type    The type of tempalte to get
     * @param  int      $counter The counter
     * @return string
     */
    public function template($type, $counter = 0)
    {
        // Check if it's a defined template
        $section_info = config('sections.sections');
        $options      = config('sections.options');
        if (!empty($section_info[$type])) {
            $section = [
                'id'       => null,
                'template' => $type,
                'fields'   => new stdClass,
                'info'     => $section_info[$type],
            ];

            // Set null for convenience
            foreach (array_keys($section_info[$type]['fields']) as $field) {
                $section['fields']->{$field} = null;
            }

            $blocks = $this->block->getTypeOptions(['widget', 'form']);

            // Render the template
            $output = view(
                'admin.sections.list-item',
                compact('counter', 'section', 'section_info', 'options', 'blocks')
            )->render();

            return Response::json(['template' => $output], 200);
        }

        return Response::json(['error' => 'Template not found'], 400);
    }

    /**
     * Get the rendered block
     *
     * @param  int      $block_id The block id
     * @return string
     */
    public function blockRender($block_id)
    {
        // 'Render the block'
        return $this->block->getBlockContent((int) $block_id, false, null, '', true);
    }
}
