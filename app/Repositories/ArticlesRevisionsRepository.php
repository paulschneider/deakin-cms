<?php
namespace App\Repositories;

class ArticlesRevisionsRepository extends BasicRepository
{
    /**
     * Which fields are set to null if empty
     * @var array
     */
    public $nullable = ['thumbnail_id', 'image_id'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\ArticleRevision';
    }

    /**
     * Run this after save
     *
     * @param array $data    The data array
     * @param Page  &$entity The page
     * @param Page  $old     The old entity if any
     */
    protected function afterSave($data, &$entity, $old)
    {
        foreach ($data['terms'] as $vocab => $terms) {
            $this->saveTerms($vocab, $terms, $entity);
        }
    }
}
