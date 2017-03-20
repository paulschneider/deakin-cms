<?php

namespace App\Repositories;

class SubmissionsRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Submission';
    }

    /**
     * Prepare the data to be saved
     *
     * @param  array   $data The data array
     * @return array
     */
    public function prepareData($data)
    {
        $values     = [];
        $unfillable = [];
        $fillable   = $this->model->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $values[$key] = $value;
            } else {
                $unfillable[$key] = $value;
            }
        }
        $values['data'] = json_encode($unfillable);

        return $values;
    }

    /**
     * Get all the items for a type
     *
     * @param  string  $type     The type
     * @param  boolean $paginate If query should be paginated
     * @param  array   $options  The array of options
     * @param  array   $columns  The array of columns to retrieve
     * @return mixed
     */
    public function getAllForType($type, $paginate = false, $options = [], $columns = ['*'])
    {
        $query = $this->query($options)->where('type', '=', $type);

        if ($paginate) {
            $per_page = !empty($options['perPage']) ? $options['perPage'] : 10;
            return $query->paginate($per_page, $columns);
        }

        return $query->get($columns);
    }

    /**
     * Find the item of type
     *
     * @param  integer $id      The id
     * @param  string  $type    The type of form to retrieve
     * @param  array   $options The options array
     * @param  array   $columns The column options
     * @return mixed
     */
    public function findOrFailType($id, $type, $options = [], $columns = ['*'])
    {
        $query = $this->query($options)->where('type', '=', $type);

        return $this->fetch($query, 'findOrFail', $options, ['id' => $id, 'columns' => $columns]);
    }
}
