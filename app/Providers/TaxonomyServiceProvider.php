<?php namespace App\Providers;

use App\Taxonomy\Taxonomy;
use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Tax', function ($app) {
            $term = $app->make('App\Repositories\TermsRepository');
            $vocabulary = $app->make('App\Repositories\VocabulariesRepository');
            return new Taxonomy($term, $vocabulary);
        });
    }
}
