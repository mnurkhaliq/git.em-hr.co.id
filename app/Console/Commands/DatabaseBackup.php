<?php

namespace App\Console\Commands;

use Artisan;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {--database=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Backup & Delete';

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
        $dirName = Config::get('backup.backup.name');
        $diskName = Config::set('backup.backup.destination.disks');
        $dbName = Config::get('database.connections.mysql.database');

        Config::set('backup.backup.name', $this->option('database'));
        Config::set('backup.backup.destination.disks', ['databaseBackup']);
        Config::set('database.connections.mysql.database', $this->option('database'));

        Artisan::call('backup:run', [
            '--only-db' => true,
            '--disable-notifications' => true,
        ]);
        DB::statement('DROP DATABASE ' . $this->option('database'));

        Config::set('backup.backup.name', $dirName);
        Config::set('backup.backup.destination.disks', $diskName);
        Config::set('database.connections.mysql.database', $dbName);

        $this->info('DONE backup & delete database ' . $this->option('database'));
    }
}
