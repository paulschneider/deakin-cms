<?php
namespace App\Repositories;

use App\Models\Article;
use App\Search\SearchFilters;
use App\Search\SearchParams;
use App\Traits\RevisionRepositoryTrait;
use Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticlesRepository extends BasicRepository
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
    protected $cache_tags = ['articles', 'pages', 'url'];

    /**
     * holder for the feature article if there is one
     *
     * @var App\Models\Article
     */
    protected $featured;

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Article';
    }

    /**
     * Specify the Model class name for the ArticleRevision
     *
     * @return string
     */
    public function revisionRepository()
    {
        return 'App\Repositories\ArticlesRevisionsRepository';
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

    /**
     * Run this after save
     *
     * @param array $data    The data array
     * @param Page  &$entity The page
     * @param Page  $old     The old entity if any
     */
    protected function afterSave($data, &$entity, $old)
    {
        $this->checkWasFeatured($entity);
    }

    /**
     * if this was a featured article mark all other articles as not featured
     *
     * @param  $entity
     * @return void
     */
    private function checkWasFeatured($entity)
    {
        // if the article revision was featured
        if ($entity->isFeatured()) {
            // update all other revisions so they aren't featured
            Article::where("id", "!=", $entity->id)->update(["is_featured" => null]);
        }
    }

    /**
     * Get the latest news for the block
     *
     * @return array
     */
    public function latestArticles()
    {
        $options  = ['filter' => true, 'with' => ['revision', 'revision.article_types']];
        $query    = $this->query($options);
        $articles = $query->orderBy('created_at', 'DESC')->take(3)->get();

        return ['articles' => $articles];
    }

    /**
     * Get the latest news for the block
     *
     *  TODO after break: ADD FILTERS
     *
     * @return array
     */
    public function allArticles()
    {
        $results = [];
        $perPage = 6;
        $query   = request()->query('q', '');
        $page    = (int) request()->get('page', 1);
        $active  = request()->query('filter', []);

        if (!is_array($active) || !is_string($query)) {
            throw new \Exception('Incorrect URL construction. Please start search again.');
        }

        // Makes the results be offset by -1 on page 2
        // Not worth fixing right now.
        // Just shows the same last article from page 1 on page 2.
        if ($page === 1) {
            ++$perPage;
        }

        $params = (new SearchParams)->create($page, $perPage, $query);

        $filters = new SearchFilters($params);

        if (empty($active)) {
            $filters->stubAny($params);
        }

        foreach ($active as $key) {
            if (!is_string($key)) {
                throw new \Exception('Incorrect URL construction. Please start search again.');
            }

            $filters->stubFilter($key);
        }

        $params = $filters->getParams();

        $client  = (new ClientBuilder)->create()->build();
        $results = $client->search($params);
        $hits    = data_get($results, 'hits.hits', []);
        $total   = data_get($results, 'hits.total', 0);

        $ids = array_pluck($hits, '_source.id');

        $options = ['filter' => true, 'with' => ['revision', 'revision.article_types']];

        if (!empty($ids)) {
            $temp = $this->query($options)
                         ->whereIn('id', $ids)
                         ->orderByRaw(
                             \DB::raw('FIELD(id, ' . implode(',', $ids) . ')')
                         )->get();
        } else {
            $temp = collect([]);
        }

        $articles = new LengthAwarePaginator($temp, $total, $perPage, $page, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        $articles = $this->getFeaturedArticle($articles);

        return ['articles' => $articles, "featured" => $this->featured];
    }

    /**
     * get a featured article from the listing if there is one
     *
     * @param  Illuminate\Pagination\LengthAwarePaginator $articles
     * @return Illuminate\Pagination\LengthAwarePaginator $articles
     */
    private function getFeaturedArticle($articles)
    {
        $this->featured = $articles->getCollection()->filter(function ($article) {
            return $article->isFeatured();
        })->first();

        // if there's no featured article then just return back the original result set
        if (!$this->featured) {
            return $articles;
        }

        // work out where the featured article is in the collection
        $featured = $articles->getCollection()->search($this->featured);

        // and remove the featured item from it
        $articles->getCollection()->forget($featured);

        // return the paginator back
        return $articles;
    }
}
