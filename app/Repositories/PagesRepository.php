<?php

namespace App\Repositories;

use App\Traits\RevisionRepositoryTrait;

class PagesRepository extends BasicRepository
{
    use RevisionRepositoryTrait;

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
    protected $cache_tags = ['pages', 'url'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Page';
    }

    /**
     * Specify the Model class name for the ArticleRevision
     *
     * @return string
     */
    public function revisionRepository()
    {
        return 'App\Repositories\PagesRevisionsRepository';
    }

    /**
     * Filter the query
     *
     * @param Eloquent\Model $query The query
     */
    protected function filter(&$query)
    {
        $query->where('online', '=', 1)->whereNotNull('revision_id');
    }
}
