<?php

namespace App\Console\Commands;

use App\Models\ConfigDB;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class RoutineMigrateSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routine:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Routine Migrate Seed';

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
        foreach (ConfigDB::whereNotNull('db_name')->get() as $value) {
            Config::set('database.connections.mysql.database', $value->db_name);
            DB::purge('mysql');
            try {
                DB::connection()->getPdo();
                exec('php artisan migrate --database=' . $value->db_name);
                $this->info('DONE migrating ' . $value->db_name);
            } catch (\Exception $e) {
                $this->info('ERROR migrating ' . $value->db_name . ' (' . $e->getMessage() . ')');
            }
        }
        Config::set('database.connections.mysql.database', session('db_name', env('DB_DATABASE')));
        DB::purge('mysql');

        exec('php artisan migrate');
        $this->info('DONE migrating ' . env('DB_DATABASE'));

        exec('php artisan db:seed --class=EventSeeder');
        $this->info('DONE inactivate event scheduler');
    }
}
