<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class RefreshDatabaseWithSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:reset';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate:fresh');
        $this->info('Seeding data...');
        Artisan::call('db:seed');
        $this->info('Database refreshed and seeded successfully.');
    }
}
