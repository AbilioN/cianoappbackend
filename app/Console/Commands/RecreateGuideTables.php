<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecreateGuideTables extends Command
{
    protected $signature = 'guides:recreate-tables';
    protected $description = 'Recreate guide tables with new structure';

    public function handle()
    {
        $this->info('Dropping existing tables...');
        
        Schema::dropIfExists('guide_components');
        Schema::dropIfExists('guide_pages');
        Schema::dropIfExists('guides');

        $this->info('Running migrations...');
        $this->call('migrate', [
            '--path' => 'database/migrations/2025_06_07_181757_create_guides_table.php',
            '--force' => true
        ]);
        $this->call('migrate', [
            '--path' => 'database/migrations/2025_06_07_181805_create_guide_pages_table.php',
            '--force' => true
        ]);
        $this->call('migrate', [
            '--path' => 'database/migrations/2025_06_07_181835_create_guide_components_table.php',
            '--force' => true
        ]);

        $this->info('Tables recreated successfully!');
    }
} 