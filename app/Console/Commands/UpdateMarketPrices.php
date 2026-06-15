<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwelveDataService;

class UpdateMarketPrices extends Command
{
    protected $signature = 'prices:market';
    protected $description = 'Fetch and update forex, stock, ETF, and index prices from TwelveData';

    public function handle()
    {
        $this->info('Fetching market prices from TwelveData...');

        $service = new TwelveDataService();

        if (!$service->hasApiKey()) {
            $this->warn('⚠ TwelveData API key is not configured!');
            $this->warn('  Set it via: Admin → App Settings → API Configuration');
            $this->warn('  Or get a free key at: https://twelvedata.com/pricing');
            $this->warn('  Forex/stock/ETF/index prices will use fallback defaults until configured.');
            return 1;
        }

        $totalSymbols = $service->getTotalSymbolCount();
        $perMinute = $service->getCreditsPerMinute();
        $chunks = ceil($totalSymbols / $perMinute);
        $this->info("Free tier: {$perMinute} credits/min. {$totalSymbols} symbols in {$chunks} chunk(s).");
        if ($chunks > 1) {
            $estimatedMins = $chunks - 1;
            $this->info("Rate-limited — will take ~{$estimatedMins} min(s) with pauses between chunks.");
        }

        $counts = $service->updatePrices();

        foreach ($counts as $class => $count) {
            $this->info("Updated {$count} {$class} assets.");
        }

        $total = array_sum($counts);
        $this->info("Total: {$total} market assets updated.");

        return 0;
    }
}
