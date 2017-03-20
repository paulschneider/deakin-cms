<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;

class CacheSingle extends Command
{
    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $manager;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:single {tag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear a single cache tag';

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
        $tag = $this->argument('tag');

        Cache::tags([$tag])->flush();

        $this->comment('Cache for ' . $tag . ' cleared');
    }
}
