<?php
namespace App\Blocks;

use View;
use Cache;
use Config;
use Filter;
use Request;
use Carbon\Carbon;
use App\Models\Block;
use App\Traits\RegisterMethodTrait;
use App\Repositories\BlocksRepository;

class BlockManager
{
    use RegisterMethodTrait;

    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'blocks';

    const BLOCK_CACHE_PER_PAGE = 1;

    const BLOCK_CACHE_PERMANENTLY = 2;

    const BLOCK_CACHE_NONE = 3;

    /**
     * The instance of BlockRepository
     *
     * @var BlockRepository
     */
    protected $block;

    /**
     * The time a acl call should be cached
     */
    protected $cache_time;

    /**
     * DI Constructor
     *
     * @param BlocksRepository $block The instance of BlocksRepository
     */
    public function __construct(BlocksRepository $block)
    {
        $this->block      = $block;
        $this->cache_time = config('cache.block_cache');
    }

    /**
     * Get the block info
     * @param  string    $key The block id
     * @throws Exception If no block info is returned
     * @return array
     */
    public function blockInfo($key)
    {
        // Remember forever, cache can be cleared
        $value = Cache::rememberForever("blocks:info:{$key}", function () use ($key) {
            $block = config("blocks.blocks.{$key}");
            if (empty($block)) {
                throw new \Exception('Block definition not found.');
            }

            return $block;
        });

        return $value;
    }

    /**
     * Get the content from the methods
     *
     * @param  string  $field     The field to render
     * @param  string  $signature The method class signature
     * @param  array   $extra     Any extra variables
     * @return mixed
     */
    protected function variables($field, $signature, $extra = [])
    {
        $variables = [];
        $data      = array_get(static::$methods, $signature);
        $class     = \App::make($data['class']);
        $vars      = call_user_func_array([$class, $data['method']], $data['arguments']);
        // Run an after method if it exists
        if ($data['after']) {
            $vars = $vars->{$data['after']}();
        }

        // Set an empty block, so that it doesn't render
        if (empty($vars)) {
            return ['empty' => true];
        }

        // Render the relavant template
        if (!empty($data['template'])) {
            $vars              = array_merge($vars, $extra);
            $view              = View::make($data['template'], $vars);
            $variables[$field] = $view->render();
        } else {
            // If it's not a template driven one, it should return a string
            // Otherwise it will render a to string version
            $variables[$field] = $vars;
        }

        // Add aditional vars
        foreach ($vars as $key => $var) {
            if (!isset($variables[$key])) {
                $variables[$key] = $var;
            }
        }

        return $variables;
    }

    /**
     * Get the blocks for a page sorted in their regions
     *
     * @return array
     */
    public function regions()
    {
        $path   = Request::path();
        $blocks = Cache::tags(self::CACHE_PREFIX)->get('all_online_blocks');
        if (!$blocks) {
            $options = ['orderBy' => ['weight', 'ASC']];
            $blocks  = $this->block->allRegioned($options);
            $expires = \Carbon::now()->addMinutes($this->cache_time);
            Cache::tags(self::CACHE_PREFIX)->put('all_online_blocks', $blocks, $expires);
        }
        $regions = [];

        foreach ($blocks as $block) {
            $contents = $this->getBlockContent($block);

            // Check if contents isn't empty
            if (!empty($contents)) {
                $regions[$block->region][$block->name] = $contents;
            }
        }

        return $regions;
    }

    /**
     * Render the block
     *
     * @param  EloquentCollection $blocks  The collection of blocks
     * @param  string             $section The section
     * @return string
     */
    public function renderBlocks($blocks, $section = '')
    {
        $path   = Request::path();
        $output = '';

        foreach ($blocks as $block) {
            $contents = $this->getBlockContent($block, false, $path, $section);
            if (!empty($contents)) {
                $output .= $contents;
            }
        }

        return $output;
    }

    public function getBlock($block)
    {
        if (!is_object($block) && is_int($block)) {
            $block = $this->block->find($block);
        }

        if (!$block) {
            return null;
        }

        return $block;
    }

    /**
     * Get a block
     *
     * @param  mixed    $block         The block
     * @param  boolean  $check_visible If the visibility should be checked
     * @param  string   $path          The path
     * @param  string   $section       The section
     * @param  array    $preview       If it's rendering a preview
     * @return string
     */
    public function getBlockContent($block, $check_visible = true, $path = null, $section = '', $preview = false)
    {
        $block = $this->getBlock($block);

        if (!$block) {
            return '';
        }

        // If the path hasn't been used
        if (!$path) {
            $path = Request::path();
        }

        // Getting the individual block render
        $info     = $this->blockInfo($block->type);
        $contents = $this->getCached($block, $info, $path, $section);

        if (!$contents) {
            $visible = true;

            if ($check_visible) {
                $visible = $this->checkVisible($block, $path);
            }

            if ($visible) {
                $block->addBlockFields();
                $variables            = ['block' => $block];
                $variables['preview'] = $preview;

                // if they have registered methods, render the views
                foreach ($info['fields'] as $field) {
                    if (preg_match('/_method$/', $field)) {
                        $variables += $this->variables($field, $block->{$field}, $variables);
                    }
                }

                // If the method returned empty, then don't render it
                if (!empty($variables['empty'])) {
                    return '';
                }

                $view     = View::make($info['public_template'])->with($variables);
                $contents = $view->render();
                $contents = Filter::filter($contents, ['filter' => ['icons']]);
            }
            $this->setCached($contents, $block, $info, $path, $section);
        }

        return $contents;
    }

    /**
     * Get the blocks of a type
     *
     * @param  array   $types The types of blocks to get
     * @return mixed
     */
    protected function getTypes($types = [])
    {
        $blocks = [];
        if (!empty($types)) {
            // Sort the array so the key is the same regardless of how they are added
            sort($types);
            $key    = implode('_', $types);
            $blocks = Cache::tags(self::CACHE_PREFIX)->get("all_online_{$key}_blocks");
            $blocks = null;
            if (!$blocks) {
                $options = ['with' => ['categories'], 'orderBy' => ['weight', 'ASC']];
                $blocks  = $this->block->getTypes($types, $options);
                $expires = \Carbon::now()->addMinutes($this->cache_time);
                Cache::tags(self::CACHE_PREFIX)->put("all_online_{$key}_blocks", $blocks, $expires);
            }
        }
        return $blocks;
    }

    /**
     * Get the options for a the types requested
     *
     * @param  array   $types The types to get
     * @return array
     */
    public function getTypeOptions($types = [])
    {
        $options = [];
        foreach ($types as $type) {
            $options[$type] = [null => 'None'];
        }

        $blocks = $this->getTypes($types);
        foreach ($blocks as $block) {
            foreach ($block->categories as $category) {
                $options[$category->stub][$block->id] = $block->name;
            }
        }

        return $options;
    }

    /**
     * Sort the blocks into their categories
     *
     * @param  EloquentCollection $blocks The blocks collection
     * @return array
     */
    protected function sortTypes($blocks)
    {
        $sorted = [];
        foreach ($blocks as $block) {
            foreach ($block->categories as $category) {
                $sorted[$category->stub] = $block;
            }
        }

        return $sorted;
    }

    /**
     * Get a cached version of of the block
     * @param  App\Models\Block &$block  The block
     * @param  array            $info    The info
     * @param  string           $path    The current path
     * @param  string           $section The section
     * @return mixed
     */
    protected function getCached(&$block, $info, $path, $section = '')
    {
        $contents = null;
        // If the cache flag isn't set, don't cache it
        if (empty($info['cache'])) {
            return $contents;
        }

        $section = !empty($section) ? ':' . $section : '';

        switch ($info['cache']) {
            case self::BLOCK_CACHE_PER_PAGE:
                $contents = Cache::tags(self::CACHE_PREFIX)->get("page:{$path}:{$block->id}{$section}");
                break;
            case self::BLOCK_CACHE_PERMANENTLY:
                $contents = Cache::tags(self::CACHE_PREFIX)->get("block:{$block->id}{$section}");
                break;
        }

        return $contents;
    }

    /**
     * Set a cached version of the block following cache params
     *
     * @param string           $contents The content of the block
     * @param App\Models\Block &$block   The block model
     * @param array            $info     The info array
     * @param string           $path     The path
     * @param string           $section  The section
     */
    protected function setCached($contents, &$block, $info, $path, $section = '')
    {
        $expires = \Carbon::now()->addMinutes($this->cache_time);
        $section = !empty($section) ? ':' . $section : '';

        switch ($info['cache']) {
            case self::BLOCK_CACHE_PER_PAGE:
                Cache::tags(self::CACHE_PREFIX)->put("page:{$path}:{$block->id}{$section}", $contents, $expires);
                break;
            case self::BLOCK_CACHE_PERMANENTLY:
                Cache::tags(self::CACHE_PREFIX)->put("block:{$block->id}{$section}", $contents, $expires);
                break;
        }
    }

    /**
     * Check if a block should be shown
     *
     * @param  Block  $block The block object
     * @param  string $path  The path
     * @return bool
     */
    protected function checkVisible(&$block, $path)
    {
        if (empty($block->region)) {
            return false;
        }

        $match = function ($item, $path) use ($block) {
            if (empty($item)) {
                return;
            }

            if (preg_match('/^\//', $item)) {
                $item = preg_replace('/^\//', '', $item);
            }

            if (preg_match('/\*(.+)?$/', $item)) {
                $item = preg_replace('/\*(.+)?$/', '', $item);
                $item = trim($item);

                return preg_match('/^' . preg_quote($item, '/') . '/', $path);
            } else {
                $item = trim($item);
                if (empty($item)) {
                    $item = '/';
                }

                $item = '^' . preg_quote($item, '/') . '$';

                return preg_match("/{$item}/", $path);
            }
        };

        // Check if both are empty
        if (empty($block->includes) && empty($block->excludes)) {
            return true;
        }

        // Check excludes
        if (!empty($block->excludes)) {
            $paths = explode("\r\n", $block->excludes);
            foreach ($paths as $item) {
                $excluded = $match($item, $path);

                if ($excluded) {
                    return false;
                }
            }
        }

        // Check includes
        if (!empty($block->includes)) {
            $paths = explode("\n", $block->includes);

            foreach ($paths as $item) {
                $allowed = $match($item, $path);

                if ($allowed) {
                    return true;
                }
            }

            return false;
        }

        // None of those matched anything
        return true;
    }
}
