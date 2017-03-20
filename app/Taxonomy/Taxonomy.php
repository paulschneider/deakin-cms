<?php namespace App\Taxonomy;

use App\Repositories\TermsRepository;
use App\Repositories\VocabulariesRepository;
use Cache;

class Taxonomy
{
    /**
     * The cache prefix
     */
    const CACHE_PREFIX = 'tax';

    /**
     * The term repository
     *
     * @var App\Repositories\TermsRepository
     */
    protected $term;

    /**
     * The vocabulary
     *
     * @var App\Repositories\VocabulariesRepository
     */
    protected $vocabulary;

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
    public function __construct(TermsRepository $term, VocabulariesRepository $vocabulary)
    {
        $this->term       = $term;
        $this->vocabulary = $vocabulary;
        $this->cache_time = config('cache.taxonomy_cache');
    }

    /**
     * Get a term by it's stub
     *
     * The force stub is useful if a vocabulary name is numeric
     * @param  string                $stub       The stub
     * @param  mixed                 $vocabulary The vocabulary id or the name
     * @param  bool                  $force      If the search should be forced to be a string
     * @return Eloquent\Collection
     */
    public function find($stub, $vocabulary, $force = false)
    {
        if (!$value = Cache::tags(self::CACHE_PREFIX)->get("{$stub}:{$vocabulary}")) {
            $options = [];
            $query   = $this->term->query($options);

            // If a vocabulary is passed, then check for it
            if ($vocabulary) {
                if (is_numeric($vocabulary) && !$force) {
                    // It's a number so check the id
                    $query->where('vocabulary_id', $vocabulary);
                } else {
                    // It's a string so check the name
                    $query->whereHas('vocabulary', function ($q) use ($vocabulary) {
                        $q->where('stub', '=', $vocabulary);
                    });
                }
            }

            $query->where('stub', '=', $stub);

            $value = $query->first(['terms.*']);

            Cache::tags(self::CACHE_PREFIX)->put("{$stub}:{$vocabulary}", $value, $this->cache_time);
        }

        return $value;
    }

    /**
     * Load a term by the tid
     *
     * @param  int     $tid The term id
     * @return mixed
     */
    public function term($tid)
    {
        return $this->term->find($tid);
    }

    /**
     * Get the terms of a vocabulary
     *
     * @param  mixed   $vocabulary The vocabulary to get
     * @param  bool    $force      If the value should be forecd to be a string
     * @return array
     */
    public function terms($vocabulary, $force = false)
    {
        if (!$value = Cache::tags(self::CACHE_PREFIX)->get($vocabulary)) {
            $options = ['with' => ['terms'], 'orderBy' => ['terms.name', 'desc']];
            $by      = (is_numeric($vocabulary) && !$force) ? 'id' : 'stub';

            $value = $this->vocabulary->findBy($by, $vocabulary)->terms;

            Cache::tags(self::CACHE_PREFIX)->put($vocabulary, $value, $this->cache_time);
        }

        return $value;
    }

    /**
     * Get options for a vocabulary
     *
     * @param  mixed   $vocabulary The vocabulary
     * @param  string  $select     The first option
     * @param  bool    $force      If the vocabulary should be force to a string
     * @return array
     */
    public function vocabularyOptions($vocabulary, $select = null, $force = false)
    {
        $terms = $this->terms($vocabulary, $force);
        $tree  = $this->getTree($terms);

        $options = [];
        if (null !== $select) {
            $options = ['' => $select];
        }

        if (!empty($tree)) {
            $tree = $this->getFlat($tree);

            foreach ($tree as $branch) {
                $term               = $branch['term'];
                $options[$term->id] = str_pad('', $branch['depth'] - 1, '-') . $term->name;
            }
        }

        return $options;
    }

    /**
     * Add Terms into a vocabulary
     *
     * @param  string  $stub      The vocabulary stub
     * @param  array   $items     Inputs to add terms for
     * @param  string  $seperator Optionally override the slug seperator.
     * @return array
     */
    public function addTerms($stub, $items, $seperator = '-')
    {
        $vocabulary = $this->vocabulary->findBy('stub', $stub);
        $saveTerms  = [];

        // Can't process if there is no vocabulary
        if (!$vocabulary) {
            throw new Exception("Vocabulary not found");
        }

        // Parse the passed in items
        if (!is_array($items)) {
            $items = str_replace(' ', ',', $items);
            $items = preg_replace('/,+/', ',', $items);
            $items = explode(',', $items);
        }

        // Process each term
        foreach ($items as $item) {
            if ($term = $vocabulary->terms()->where('name', '=', $item)->first()) {
                $saveTerms[] = $term->id;
            } else {
                $data = [
                    'name'          => $item,
                    'stub'          => str_slug($item, $seperator),
                    'vocabulary_id' => $vocabulary->id,
                ];

                // Save a new term
                $term        = $this->term->saveWithData($data);
                $saveTerms[] = $term->id;
            }
        }

        return $saveTerms;
    }

    /**
     * Used in various functions to gather the heirarchy.
     * This is a recursive function.
     *
     * @param  collection $terms
     * @param  mixed      $parent  The ID to build on
     * @return array
     */
    public function getTree($terms, $parent = null, $depth = 0)
    {
        $result = [];
        ++$depth;

        foreach ($terms as $key => $term) {
            if ($term->parent_id == $parent) {
                $result[] = [
                    'term'     => $term,
                    'depth'    => $depth,
                    'children' => $this->getTree($terms, $term->id, $depth),
                ];
                unset($terms[$key]);
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Flatten out a tree, keeping its depth index.
     * @param  array   $tree
     * @param  array   &$flat_array
     * @return array
     */
    public function getFlat(array $tree, array &$flat_array = [])
    {
        foreach ($tree as $term) {
            $flat_array[] = [
                'term'  => $term['term'],
                'depth' => $term['depth'],
            ];

            if (!empty($term['children'])) {
                $this->getFlat($term['children'], $flat_array);
            }
        }

        return $flat_array;
    }
}
