<?php
namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Search\SearchFilters;
use App\Search\SearchParams;
use Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Request;

class SearchController extends Controller
{
    /**
     * Show the search index
     * Slightly munty as we need to search outside of Scout to get across indexes.
     *
     * @return Response
     */
    public function index()
    {
        $perPage = 10;
        $query   = request()->get('q', '');
        $page    = (int) request()->get('page', 1);
        $active  = request()->get('filter', []);

        if (!is_array($active) || !is_string($query)) {
            throw new \Exception('Incorrect URL construction. Please start search again.');
        }

        $params = (new SearchParams)->create($page, $perPage, $query);
        $params = $this->addFilters($active, $params);
        $client = (new ClientBuilder)->create()->build();

        $results = $client->search($params);

        $banner = view('frontend.banners.search-banner', ['title' => 'Search results', 'query' => $query, 'active' => $active])->render();

        $hits  = data_get($results, 'hits.hits', []);
        $total = data_get($results, 'hits.total', 0);

        $results = new LengthAwarePaginator($hits, $total, $perPage, $page, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return view('frontend.search.index', compact('query', 'results', 'banner'));
    }

    private function addFilters($active, $params)
    {
        $configs = collect(config('search.globals'));
        $filters = new SearchFilters($params);

        foreach ($active as $key) {
            if (!is_string($key)) {
                throw new \Exception('Incorrect URL construction. Please start search again.');
            }
            if (method_exists($filters, $key . 'Filter')) {
                $filters->{$key . 'Filter'}();
            }
        }

        return $filters->getParams();
    }
}
