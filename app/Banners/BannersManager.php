<?php
namespace App\Banners;

use App\Models\Banner;
use App\Repositories\BannersImagesRepository;
use App\Repositories\BannersRepository;
use App\Traits\RegisterMethodTrait;
use BlockManager;
use Cache;
use Illuminate\Database\Eloquent\Collection;

class BannersManager
{
    use RegisterMethodTrait;

    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'banners';

    /**
     * The banners
     *
     * @var App\Repositories\BannersRepository
     */
    protected $banners;

    /**
     * The images
     *
     * @var App\Repositories\BannersImagesRepository
     */
    protected $images;

    /**
     * Temp storage of a banner about to be rendered.
     *
     * @var mixed
     */
    protected $banner_storage;

    /**
     * The time a taxonomy call should be cached
     */
    protected $cache_time;

    /**
     * Inject the dependencies
     *
     * @param TermsRepository        $term       The term repository
     * @param VocabulariesRepository $vocabulary The vocabulary repository
     */
    public function __construct(BannersRepository $banners, BannersImagesRepository $images)
    {
        $this->banners    = $banners;
        $this->images     = $images;
        $this->cache_time = config('cache.banner_cache');
    }

    /**
     * Helper function to get a banner.
     * @param  mixed    $banner_id The banner stub.
     * @return Mixed.
     */
    public function get($banner_id)
    {
        $self = &$this;

        return Cache::tags(self::CACHE_PREFIX)->remember('banner:' . $banner_id, $this->cache_time, function () use ($self, $banner_id) {
            if (is_numeric($banner_id)) {
                $banner = $self->banners->find($banner_id, ['with' => ['images']]);
            } else {
                $banner = $self->banners->findBy('stub', $banner_id, ['with' => ['images']]);
            }

            return $banner;
        });
    }

    /**
     * Compose out a banner.
     * @param  mixed  $banner_id ID or Stub
     * @param  string $blade     What templates to affect.
     * @param  string $region    Where in the blade to inject.
     * @param  array  $options   Optionally pass options to the render.
     * @return void
     */
    public function compose($banner_id, $blade, $region, $options = [])
    {
        $banner = $this->get($banner_id);

        if (!$banner->id) {
            return;
        }

        $region = preg_replace('/^banner_/', '', $region);
        $region = 'banner_' . $region;

        $renderer = $this->renderType($banner);
        $output   = $renderer->render($options);

        view()->composer($blade, function ($view) use ($region, $output) {
            $view->with($region, $output);
        });
    }

    /**
     * Render a block for an entity
     *
     * @param  integer  $banner_id The banner id
     * @param  integer  $image_id  The image id
     * @param  array    $options   The options array
     * @return string
     */
    public function render($banner_id, $image_id = null, $options = [])
    {
        $banner       = null;
        $queryOptions = ['with' => ['attachment']];

        if (empty($image_id)) {
            // We are picking a random image from the pool
            $banner = $this->images->random($banner_id, $queryOptions);
        } else {
            $banner = $this->images->find($image_id, $queryOptions);
        }

        if (empty($banner)) {
            return null;
        }

        $renderer = $this->renderType($banner);

        return $renderer->render($options);
    }

    /**
     * Render a type of a banner
     *
     * @param  Banner &$banner The banner object
     * @return mixed  object
     */
    public function renderType(&$banner)
    {
        // Render the different types of banners
        if (!empty($banner->method)) {
            $method    = $this->method($banner->method);
            $variables = $this->runMethod($method);
            return new MethodRender($banner, $method['template'], $variables);
        } else {
            return new StaticRender($banner);
        }
    }

    /**
     * Return all banners got options
     * @return array
     */
    public function getGroups()
    {
        $output = [];

        foreach ($this->banners->all() as $banner) {
            $output[$banner->id] = $banner->title;
        }

        return $output;
    }

    /**
     * Function to return the banner options list for a dropdown select.
     * @param  int   $stub      The banner stub.
     * @param  int   $ignore_id Ignore a branch under an id.
     * @return array A built array of options.
     */
    public function getOptions(Banner $banner, $ignore_id = null, $options = [])
    {
        $tree = $this->getTree(clone $banner->images, $ignore_id);

        if (empty($options)) {
            $options = ['' => 'Random Image'];
        }

        if (!empty($tree)) {
            $tree = $this->getFlat($tree);

            foreach ($tree as $branch) {
                $image               = $branch['image'];
                $options[$image->id] = str_pad('', $branch['depth'] - 1, '-') . $image->title;
            }
        }

        return $options;
    }

    /**
     * Build a tree of images from a collection.
     * @param  Collection $images    [description]
     * @param  int        $ignore_id Items to ignore.
     * @param  int        $root      Set recursivly.
     * @param  integer    $depth     Set recursivly.
     * @param  boolean    $acl       Use the ACL?
     * @return array
     */
    public function getTree(Collection $images, $ignore_id = null, $root = null, $depth = 0, $acl = true)
    {
        $return = [];
        ++$depth;

        foreach ($images as $key => $image) {
            if ($image->id && $ignore_id && $ignore_id == $image->id) {
                continue;
            }

            if ($image->parent_id == $root) {
                unset($images[$key]);

                $return[] = [
                    'image'    => $image,
                    'depth'    => $depth,
                    'children' => $this->getTree($images, $ignore_id, $image->id, $depth),
                ];
            }
        }

        return empty($return) ? null : $return;
    }

    /**
     * Flatten out a tree, keeping its depth index.
     * @param  array   $tree
     * @param  array   &$flat_array
     * @return array
     */
    public function getFlat(array $tree, array &$flat_array = [])
    {
        foreach ($tree as $image) {
            $flat_array[] = [
                'image' => $image['image'],
                'depth' => $image['depth'],
            ];

            if (!empty($image['children'])) {
                $this->getFlat($image['children'], $flat_array);
            }
        }

        return $flat_array;
    }

    /**
     * Get a custom banner
     *
     * @param  string   $name The name of the banner
     * @return string
     */
    public function getCustom($name, $options = [])
    {
        $variables = ['name' => $name, 'options' => $options];

        // Get any values that you need
        return $variables;
    }

    /**
     * Get the banner image
     *
     * @param  array    $data    The data array
     * @param  array    $options The options array
     * @return string
     */
    public function getBannerImage($data, $options)
    {
        return $this->render($data['id'], $data['image'], $options);
    }
}
