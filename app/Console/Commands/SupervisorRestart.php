<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SupervisorRestart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supervisor:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supervisor Restart';

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
        exec("supervisorctl restart all");
        // $this->info("DONE restart queue engine");
    }
}
