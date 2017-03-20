<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheOpcache extends Command
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
    protected $signature = 'cache:opcache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear PHPs opcache';

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
        $reset = opcache_reset();

        if ($reset) {
            $this->comment('Cache for PHPs opcache cleared');
        } else {
            $this->error('Cache for PHPs opcache was not cleared');
        }
    }
}
