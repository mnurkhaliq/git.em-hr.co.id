<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class NewClientDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:database {--database=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New Client Database';

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
        DB::statement('CREATE DATABASE IF NOT EXISTS ' . $this->option('database') . ' CHARACTER SET ' . Config::get('database.connections.mysql.charset') . ' COLLATE ' . Config::get('database.connections.mysql.collation'));
        // $this->info("DONE create new database");
    }
}
