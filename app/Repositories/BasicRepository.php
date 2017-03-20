<?php namespace App\Repositories;

use Cache;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class BasicRepository implements RepositoryInterface
{
    /**
     * The app instance
     *
     * @var App
     */
    private $app;

    /**
     * Any booleans that need to be checked
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Any optional fields that need to be removed
     *
     * @var array
     */
    protected $optional = [];

    /**
     * Any nullable fields that need to be set null
     *
     * @var array
     */
    protected $nullable = [];

    /**
     * The instance of the model
     *
     * @var mixed
     */
    protected $model;

    /**
     * Those that need to be removed from the input
     *
     * @var array
     */
    protected $except = ['_method', '_token'];

    /**
     * Any cache tags that need to be cleared
     *
     * @var array
     */
    protected $cache_tags = [];

    /**
     * If the cache should be cleard on descrtuct
     *
     * @var boolean
     */
    protected $destruct_cache = false;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Cleanup the cache on destruct if required.
     */
    public function __destruct()
    {
        if ($this->destruct_cache) {
            $this->clearCache();
        }
    }

    /**
     * Public callback for cache clearing for odd circumstances.
     */
    public function clearCache($entity_id = null)
    {
        if ($entity_id) {
            Cache::tags($this->model->getTable() . ':' . $entity_id)->flush();
        } elseif (!empty($this->cache_tags)) {
            Cache::tags($this->cache_tags)->flush();
        }
    }

    /*
    |---------------------------------------------------------------------
    | All model retrival mechanisms
    |---------------------------------------------------------------------
    | Gets an instance of the concrete model
    |
     */

    /**
     * The name of the model
     *
     * @return String
     */
    abstract public function model();

    /**
     * Get the instance of the model
     *
     * @return mixed
     */
    protected function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /*
    |---------------------------------------------------------------------
    | Repository Interface method
    |---------------------------------------------------------------------
    | All the methods to conform to the repository interface
    |
     */

    /**
     * Fetch the data if it should be
     *
     * @param  Eloquent\Model &$query  The query
     * @param  string         $method  The method
     * @param  array          $options The options
     * @param  array          $columns The columns to fetch
     * @return mixed
     */
    protected function fetch(&$query, $method, $options, $params = [])
    {
        // Add the order by
        $this->orderBy($query, $options);

        // If it shouldn't be executed, return it
        if (isset($options['execute']) && $options['execute'] === false) {
            return $query;
        }

        // Alter the query
        $this->alter($query);

        // Execute the with the needed method
        switch ($method) {
            case 'get':
                return $query->get($params['columns']);
                break;
            case 'find':
            case 'findOrFail':
                return $query->{$method}($params['id'], $params['columns']);
            case 'first':
                return $query->first($params['columns']);
                break;
            case 'paginate':
                return $query->paginate($params['perPage'], $params['columns']);
                break;
            default:
                return $query->{$method}($options['columns']);
        }
    }

    /**
     * Alter the query
     *
     * @param Eloquent\Model $query The model
     */
    protected function alter(&$query)
    {
        // Concrete class can alter the query
    }

    /**
     * Filter the query
     *
     * @param Eloquent\Model $query The query
     */
    protected function filter(&$query)
    {
        // Concrete class can implement a filter on all queries
        // This can be skipped in the options argument 'filter' => false
    }

    /**
     * Add any order by
     *
     * @param Elequent\Query &$query  The query
     * @param array          $options The options
     */
    protected function orderBy(&$query, $options)
    {
        if (!empty($options['orderBy']) && is_array($options['orderBy'])) {
            $orderBy = $options['orderBy'];
            if (!is_array(reset($orderBy))) {
                $orderBy = [$orderBy];
            }

            foreach ($orderBy as $value) {
                list($field, $direction) = $value;
                $query->orderBy($field, $direction);
            }
        }
    }

    /**
     * Add any scopes to the query
     * If the individual scope is an array, the value is used as options.
     *
     * @param Eloquent\Model $query  The eloquent model
     * @param array          $scopes The array of scopes
     */
    protected function scopes(&$query, $scopes = [])
    {
        foreach ($scopes as $key => $scope) {
            if (is_array($scope)) {
                $query = $query->{$key}($scope);
            } else {
                $query = $query->{$scope}();
            }
        }
    }

    /**
     * Get the query with all the withs and scopes
     *
     * @param  array            $options The options array
     * @return Eloquent\Model
     */
    public function query($options = [])
    {
        $query = $this->model->newQuery();

        if (!empty($options['with']) && is_array($options['with'])) {
            $query->with($options['with']);
        }

        if (!empty($options['scopes']) && is_array($options['scopes'])) {
            $this->scopes($query, $options['scopes']);
        }

        // Filter the query
        if (!isset($options['filter']) || $options['filter'] === true) {
            $this->filter($query);
        }

        return $query;
    }

    /**
     * Get all the entites, for semantic reasons
     *
     * @param  array   $options The options array
     * @param  array   $columns The columns to fetch
     * @return mixed
     */
    public function get($options = [], $columns = ['*'])
    {
        return $this->all($options, $columns);
    }

    /**
     * Get all the entities
     *
     * @param  array   $options The options array
     * @param  array   $columns The columns to fetch
     * @return mixed
     */
    public function all($options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'get', $options, ['columns' => $columns]);
    }

    /**
     * Get the paginated results
     *
     * @param  integer $perPage The number of results
     * @param  array   $options The options array
     * @param  array   $columns The columns to fetch
     * @return mixed
     */
    public function paginate($perPage = 15, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'paginate', $options, ['perPage' => 15, 'columns' => $columns]);
    }

    /**
     * Find by id
     * @param  integer $id      The id of the entity
     * @param  array   $options The options array
     * @param  array   $columns The columns
     * @return mixed
     */
    public function find($id, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'find', $options, ['id' => $id, 'columns' => $columns]);
    }

    /**
     * Find or fail
     * @param  integer $id      The id of the entity
     * @param  array   $options The options array
     * @param  array   $columns The columns
     * @return mixed
     */
    public function findOrFail($id, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'findOrFail', $options, ['id' => $id, 'columns' => $columns]);
    }

    /**
     * Find by an attribute
     *
     * @param  string  $attribute The field
     * @param  mixed   $value     The value
     * @param  array   $options   The options array
     * @param  array   $columns   The columns to fetch
     * @return mixed
     */
    public function findBy($attribute, $value, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        $query->where($attribute, '=', $value);

        return $this->fetch($query, 'first', $options, ['columns' => $columns]);
    }

    /**
     * Create an entity
     *
     * @param  array   $data The data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update an entity
     *
     * @param  array   $data      The data to update
     * @param  mixed   $id        The value of the attribute
     * @param  string  $attribute The attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Delete an entity
     *
     * @param  mixed     $ids The id or ids of the entity
     * @return boolean
     */
    public function delete($ids)
    {
        $this->destruct_cache = true;

        return $this->model->destroy($ids);
    }

    /*
    |---------------------------------------------------------------------
    | All saving related functionality
    |---------------------------------------------------------------------
    | All the methods relating to saving and updating a model
    |
     */

    /**
     * Get the data from the request
     *
     * @param  Illuminate\Request &$request The request object
     * @param  array              $options  The options array
     * @return array
     */
    protected function data(&$request, $options = [])
    {
        // If only is specified, then only get those
        if (!empty($options['only'])) {
            return $request->only($options['only']);
        } else {
            // Remove the defaut that we don't need
            $except = $this->except;
            // Remove any that the user has specified
            if (!empty($options['except'])) {
                $except = array_merge($except, $options['except']);
            }

            return $request->except($except);
        }
    }

    /**
     * Save the entity, will get the data form the request
     *
     * @param  Illuminate\Request &$request  The request
     * @param  mixed              $entity_id The entity id
     * @param  array              $options   The options array
     * @return mixed
     */
    public function save(&$request, $entity_id = null, $options = [])
    {
        // Get the data
        $data = $this->data($request, $options);

        // Save with the filtered data
        return $this->saveWithData($data, $entity_id, $options);
    }

    /**
     * Save the entity with prepared data
     * This can be called without a request object, useful for
     * models that get attached from other models
     *
     * @param  array   $data      The data
     * @param  mixed   $entity_id The entity id or entity
     * @param  array   $options   The options array
     * @return mixed
     */
    public function saveWithData($data, $entity_id = null, $options = [])
    {
        // Find the entity if it has been passed an id to update
        $entity = null;
        if (!empty($entity_id)) {
            // Sometimes we already haave the eneity, we might as well pass it through
            if (is_object($entity_id)) {
                $entity = &$entity_id;
            } else {
                $entity = $this->find($entity_id, $options);
            }
        }

        // Set a flag for is_new.
        $data['is_new'] = empty($entity);

        // Keep a clone of the old one
        $old = (empty($entity)) ? null : clone $entity;

        // Add booleans as they usually don't set 0
        if (!empty($this->booleans)) {
            $data = $this->addBooleans($data, $options);
        }

        // Strip out optional empty values.
        if (!empty($this->optional)) {
            $data = $this->removeOptional($data, $options);
        }

        // Set any nullable (eg foreign ids) to null
        if (!empty($this->nullable)) {
            $data = $this->setNullable($data, $entity);
        }

        // Any interaction that needs to be done before the save
        if (method_exists($this, 'beforeSave')) {
            $data = $this->beforeSave($data, $entity);
        }

        // Save the entity
        if (!$entity) {
            $entity = $this->create($data);
        } else {
            $entity->update($data);
        }

        $this->destruct_cache = true;

        // Compare if needed
        if (method_exists($this, 'compare')) {
            $this->compare($entity, $old);
        }

        // After save
        if (method_exists($this, 'afterSave')) {
            if (!empty($options['old_revision'])) {
                $old = $options['old_revision'];
            }
            $this->afterSave($data, $entity, $old);
        }
        return $entity;
    }

    /**
     * This has been left as a placeholder
     * Perform operations before saving the entity
     *
     * @param  array   $data    The input array
     * @param  mixed   &$entity The entity
     * @return array
     */
    // protected function beforeSave($data, &$entity)
    // {
    // Concrete class can add it's own functionality here

    //     return $data;
    // }

    /**
     * This has been left as a placeholder
     * Perform operations after save
     *
     * @param array $data    The input array
     * @param mixed &$entity The entity
     */
    // protected function afterSave($data, &$entity)
    // {
    // Concrete class can add it's own functionality here
    // }

    /**
     * This has been left as a placeholder
     * Compare the changed entity with the old one
     *
     * @param mixed &$entity The saved entity
     * @param mixed &$old    The old entity
     */
    // protected function compare(&$entity, &$old)
    // {
    // Concrete class can add it's own functionality here
    // }

    /**
     * Add booleans to the input array
     *
     * @param  array   $data    The data array
     * @param  array   $options The options array
     * @return array
     */
    protected function addBooleans($data, $options = [])
    {
        $skipBooleans = [];
        if (!empty($options['skipBooleans'])) {
            $skipBooleans = $options['skipBooleans'];
        }

        foreach ($this->booleans as $boolean) {
            if (!isset($data[$boolean]) && !in_array($boolean, $skipBooleans)) {
                $data[$boolean] = false;
            }
        }

        return $data;
    }

    /**
     * Remove any optional field
     *
     * @param  array   $data The data array
     * @return array
     */
    protected function removeOptional($data)
    {
        foreach ($this->optional as $option) {
            if (empty($data[$option])) {
                unset($data[$option]);
            }
        }

        return $data;
    }

    /**
     * Set null to any nullable fields
     *
     * @param  array   $data The data array
     * @return array
     */
    protected function setNullable($data)
    {
        foreach ($this->nullable as $option) {
            if (empty($data[$option])) {
                $data[$option] = null;
            }
        }

        return $data;
    }

    /**
     * Save the article terms
     *
     *   See ArticlesRevisionRepository for example
     *
     *   Inside afterSave()
     *
     *   foreach ($data['terms'] as $vocab => $terms) {
     *       $this->saveTerms($vocab, $terms, $entity);
     *   }
     *
     * @param string  $vocab   The vocab to set
     * @param array   $terms   The terms to attach
     * @param Article &$entity The article
     * @param array   $extra   Extra data to append to the pivot, key is term id.
     */
    protected function saveTerms($vocab, $terms, &$entity, $extra = [])
    {
        if (!is_array($terms)) {
            $terms = [$terms];
        }

        if (empty($terms)) {
            return;
        }

        $relation = str_plural($vocab);
        $olds     = $entity->{$relation};
        $removing = $olds->modelKeys();

        foreach ($terms as $tid) {
            if (empty($tid)) {
                continue;
            }

            $tid = (int) $tid;
            $old = $olds->find($tid);

            // Build up extra data
            $data = isset($extra[$tid]) ? $extra[$tid] : [];
            //Overwrite type
            $data['type'] = $vocab;

            if ($old) {
                $key = array_search($tid, $removing);
                unset($removing[$key]);
                $entity->{$relation}()->updateExistingPivot($tid, $data);
            } else {
                $entity->{$relation}()->attach($tid, $data);
            }
        }

        if (!empty($removing)) {
            $entity->{$relation}()->detach($removing);
        }
    }
}
