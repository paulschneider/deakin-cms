<?php
namespace App\Repositories;

use App\Traits\RevisionRepositoryTrait;

class CredentialsRepository extends BasicRepository
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
    protected $cache_tags = ['pages', 'credentials', 'url'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Credential';
    }

    /**
     * Specify the Model class name for the ArticleRevision
     *
     * @return string
     */
    public function revisionRepository()
    {
        return 'App\Repositories\CredentialsRevisionsRepository';
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
