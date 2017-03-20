<?php

namespace App\Repositories;

class VocabulariesRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Set the cache tags to be flushed on updates and deleted
     *
     * @var array
     */
    protected $cache_tags = ['tax'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Vocabulary';
    }
}
