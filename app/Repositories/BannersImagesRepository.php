<?php namespace App\Repositories;

use Cache;

class BannersImagesRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = ['online'];

    /**
     * Set the cache tags to be flushed on updates and deleted
     *
     * @var array
     */
    protected $cache_tags = ['banners'];

    /**
     * Nullable values will default to null if an empty string.
     *
     * @var array
     */
    protected $nullable = ['parent_id', 'attachment_id', 'method'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\BannerImage';
    }

    /**
     * Filter the query
     *
     * @param Eloquent\Model $query The query
     */
    protected function filter(&$query)
    {
        $query->where('online', '=', 1);
    }

    /**
     * Get a random banner image
     *
     * @param  integer       $banner_id The banner id
     * @param  array         $options   The options array
     * @param  array         $columns   The columns to get
     * @return BannerImage
     */
    public function random($banner_id, $options = [], $columns = ['*'])
    {
        return $this->query($options)->where('banner_id', '=', $banner_id)->orderBy(\DB::raw('RAND()'))->first($columns);
    }
}
