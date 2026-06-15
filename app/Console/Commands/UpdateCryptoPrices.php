<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinGeckoService;

class UpdateCryptoPrices extends Command
{
    protected $signature = 'prices:crypto';
    protected $description = 'Fetch and update crypto asset prices from CoinGecko';

    public function handle()
    {
        $this->info('Fetching crypto prices from CoinGecko...');

        $service = new CoinGeckoService();
        $count = $service->updatePrices();

        $this->info("Updated {$count} crypto assets.");

        return 0;
    }
}
