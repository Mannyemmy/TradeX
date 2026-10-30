<?php

namespace App\Console\Commands;

use App\Services\TwelveDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncStockCatalog extends Command
{
    protected $signature = 'stocks:sync-catalog';
    protected $description = 'Import new stock listings from the TwelveData catalog';

    public function handle(TwelveDataService $twelveData)
    {
        if (!$twelveData->hasApiKey()) {
            $this->error('TwelveData API key is not configured.');
            return 1;
        }

        try {
            $imported = $twelveData->importStockCatalog();
        } catch (\RuntimeException $e) {
            Log::error('Scheduled TwelveData stock catalog sync failed: ' . $e->getMessage());
            $this->error($e->getMessage());
            return 1;
        }

        $this->info("Imported {$imported} new stock listings. All catalog listings remain inactive.");

        return 0;
    }
}
