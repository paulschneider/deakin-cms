<?php
namespace App\Console\Commands;

use URL;
use Cache;
use Menus;
use Route;
use Session;
use Storage;
use Illuminate\Console\Command;

class Sitemap extends Command
{
    protected $xml  = 'sitemap.xml';
    protected $disk = 'local';

    protected $captured = [];

    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $manager;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap XML nightly. Usage: make:sitemap https://www.deakindigital.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Storage::disk($this->disk)->exists($this->xml)) {
            Storage::disk($this->disk)->delete($this->xml);
        }

        $domain = $this->argument('domain');

        URL::forceSchema(parse_url($domain, PHP_URL_SCHEME));
        URL::forceRootUrl($domain);

        $links    = $this->links();
        $articles = $this->articles();
        $pages    = $this->pages();

        $this->comment('Finalizing XML');

        Storage::disk($this->disk)->prepend($this->xml, '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        Storage::disk($this->disk)->append($this->xml, '</urlset>');
    }

    protected function links()
    {
        $links = [];

        $interfaces = Cache::tags('registry')->get('registered-inferfaces');

        $menus = Menus::getMenuDb();

        $this->comment('Building Menus');

        $this->output->progressStart(count($menus));

        foreach ($menus as $menu) {
            $tree = Menus::getTree(clone $menu->links);

            if (!is_null($tree)) {
                $flat = Menus::getFlat($tree);

                foreach ($flat as $branch) {
                    $link                = $branch['link'];
                    $links[$link->route] = $link;
                }

                $this->output->progressAdvance();
            }
        }

        $this->output->progressFinish();

        $this->comment('Processing Menu Entities');

        $this->output->progressStart(count($links));

        foreach ($links as $link) {
            if (!str_is('admin*', $link->route) && !str_is('home', $link->route)) {
                $updated_at = [];

                // Cycle through anything that may link to this link.
                if (!empty($interfaces['EntityLinkInterface'])) {
                    foreach ($interfaces['EntityLinkInterface'] as $model) {
                        $orm = app()->make($model);

                        $items = $orm->where('link_id', '=', $link->id)->get();

                        foreach ($items as $item) {
                            if (isset($item->online) && $item->online) {
                                $updated_at[$item->updated_at->format('U')] = clone $item->updated_at;
                            }
                        }
                    }
                }

                // Links may have multiple items. Just get the latest updated.
                if (!empty($updated_at)) {
                    krsort($updated_at);
                    $link->lastmod = reset($updated_at);
                } else {
                    $link->lastmod = clone $link->updated_at;
                }

                $row = (object) [
                    'lastmod'    => $link->lastmod->format('c'),
                    'loc'        => url($link->route),
                    'changefreq' => 'weekly',
                    'priority'   => '0.6',
                ];

                // store the route in a master list of paths the site map will generate
                $this->captured[] = $link->route;

                $content = view('frontend.sitemap', compact('row'))->render();
                Storage::disk($this->disk)->append($this->xml, $content);
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    protected function articles()
    {
        $self = &$this;

        $this->comment('Gathering all articles');

        $repo = app()->make('App\Repositories\ArticlesRepository');

        $this->output->progressStart($repo->query()->count());

        $repo->query()->chunk(10, function ($articles) use ($self) {
            foreach ($articles as $article) {
                if (!$article->online) {
                    continue;
                }

                $row = (object) [
                    'lastmod'    => $article->updated_at->format('c'),
                    'loc'        => route('frontend.articles.slug', [$article->slug]),
                    'changefreq' => 'monthly',
                    'priority'   => '0.5',
                ];

                $content = view('frontend.sitemap', compact('row'))->render();
                Storage::disk($this->disk)->append($this->xml, $content);

                $self->output->progressAdvance();

                // store the route in a master list of paths the site map will generate
                $this->captured[] = $article->slug;
            }
        });

        $this->output->progressFinish();
    }

    /**
     * retrieve a list of active content pages
     *
     * @return null
     */
    private function pages()
    {
        $self = &$this;

        $this->comment('Gathering pages');

        $repo = app()->make('App\Repositories\PagesRepository');

        $this->output->progressStart($repo->query()->count());

        $repo->query()->chunk(10, function ($pages) use ($self) {
            foreach ($pages as $page) {
                // if the article has already been included in the site-map then ignore
                // results already filtered for visibility by model filter()
                if (in_array($page->slug, $this->captured)) {
                    continue; // Skip execution and move to next $page.
                }

                // if the domain is /home then we want to reference it as the slash equivalent
                // so just empty the string and the URL method will do the rest
                if ($page->slug == "home") {
                    $page->slug = "";
                }

                $row = (object) [
                    'lastmod'    => $page->updated_at->format('c'),
                    'loc'        => url($page->slug),
                    'changefreq' => 'monthly',
                    'priority'   => '0.5',
                ];

                $content = view('frontend.sitemap', compact('row'))->render();
                Storage::disk($this->disk)->append($this->xml, $content);

                $self->output->progressAdvance();

                // store the route in a master list of paths the site map will generate
                $this->captured[] = $page->slug;
            }
        });

        $this->output->progressFinish();
    }
}
