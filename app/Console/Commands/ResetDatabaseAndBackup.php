<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetDatabaseAndBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the database and create a backup before resetting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database reset...');

        // Drop all tables
        $this->call('migrate:reset');

        // Create a backup
        $backupFileName = 'backup_' . date('YmdHis') . '.sql';
        $backupPath = storage_path('app/backup/' . $backupFileName);

        $this->info('Creating backup: ' . $backupPath);

        $connection = config('database.default');
        $database = config("database.connections.$connection.database");
        $username = config("database.connections.$connection.username");
        $password = config("database.connections.$connection.password");

        $command = sprintf(
            'mysqldump -u %s -p%s %s > %s',
            $username,
            $password,
            $database,
            $backupPath
        );

        exec($command);

        $this->info('Backup created successfully.');

        $this->info('Database reset and backup completed.');
    }

}
