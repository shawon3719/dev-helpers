<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CombinedSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will migrate fresh database and seed data to both Modules and Main app.';

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
     * @return int
     */
    public function handle()
    {
        $this->info('------------------------------------------------------');
        $this->info('Refreshing database and seeding data...');
        Artisan::call('migrate:fresh --seed');
        Artisan::call('module:seed  DiagnosticCentre');
        $this->info('Database seeding completed successfully.');
        $this->info('------------------------------------------------------');
    }
}
