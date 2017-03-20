<?php

namespace App\Repositories;

class RelatedRepository extends BasicRepository
{
    /**
     * Any nullable fields that need to be set null
     *
     * @var array
     */
    protected $nullable = ['icon_id'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Related';
    }
}
