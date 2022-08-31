<?php

namespace App\Console\Commands;

use App\Models\ConfigDB;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class NewClientConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:config {--database=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New Client Config';

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
        $content = file_get_contents(__DIR__ . '/../../../config/database_template.php');
        $search = "// Client DB";
        $replace = "";
        foreach (ConfigDB::whereNotNull('db_name')->get() as $value) {
            Config::set('database.connections.mysql.database', $value->db_name);
            DB::purge('mysql');
            try {
                DB::connection()->getPdo();
                $replace .= "'" . $value->db_name . "' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => '" . $value->db_name . "',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'unix_socket' => env('DB_SOCKET'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ],
        ";
            } catch (\Exception $e) {}
        }
        $replace .= "'" . $this->option('database') . "' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => '" . $this->option('database') . "',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'unix_socket' => env('DB_SOCKET'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ],
        ";
        Config::set('database.connections.mysql.database', session('db_name', env('DB_DATABASE')));
        DB::purge('mysql');
        $content = str_replace($search, $replace, $content);
        file_put_contents(__DIR__ . '/../../../config/database.php', $content);
        // $this->info("DONE add new database setting with other existing database");
    }
}
