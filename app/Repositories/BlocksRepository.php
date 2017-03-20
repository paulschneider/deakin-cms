<?php namespace App\Repositories;

use BlockManager;

class BlocksRepository extends BasicRepository
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
    protected $cache_tags = ['blocks'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Block';
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
     * Perform operations before saving the entity
     *
     * @param  array   $data    The input array
     * @param  mixed   &$entity The entity
     * @return array
     */
    protected function beforeSave($data, &$entity)
    {
        // Get the block info
        $info = BlockManager::blockInfo($data['block_type']);
        // Serialize the block content
        $data         = $this->serializeFields($info, $data);
        $data['type'] = $data['block_type'];

        return $data;
    }

    /**
     * Run this after save
     *
     * @param array $data    The data array
     * @param mixed &$entity The entity to save
     * @param mixed $old     The old entity if any
     */
    protected function afterSave($data, &$entity, $old)
    {
        $categories = $data['categories'];
        // Make the categories into an array, so we can handle multiple types
        if (!is_array($categories)) {
            $categories = [$categories];
        }

        $entity->categories()->sync($categories);
    }

    /**
     * Serialize the fields
     *
     * @param  array   $info The block info
     * @param  array   $data The data
     * @return array
     */
    protected function serializeFields($info, $data)
    {
        $content = [];
        foreach ($info['fields'] as $field) {
            if (isset($data[$field])) {
                $content[$field] = $data[$field];
            } else {
                $content[$field] = null;
            }
            unset($data[$field]);
        }

        $data['content'] = serialize($content);

        return $data;
    }

    /**
     * Get all the ones that have a region attached
     *
     * @param  array                 $options The operations array
     * @param  array                 $columns The columns
     * @return Eloquent\Collection
     */
    public function allRegioned($options, $columns = ['*'])
    {
        return $this->query($options)->whereNotNull('region')->get($columns);
    }

    /**
     * Get all the ones that are not attached to a region
     *
     * @param  array                 $options The operations array
     * @param  array                 $columns The columns
     * @return Eloquent\Collection
     */
    public function allNonRegioned($options, $columns = ['*'])
    {
        return $this->query($options)->whereNull('region')->get($columns);
    }

    /**
     * Get all the blocks of a type
     *
     * @param  array   $types   The types array
     * @param  array   $options The options array
     * @param  array   $columns The columns
     * @return mixed
     */
    public function getTypes($types, $options, $columns = ['*'])
    {
        return $this->query($options)->whereHas('categories', function ($q) use ($types) {
            $q->whereIn('stub', $types);
        })->get();
    }
}
