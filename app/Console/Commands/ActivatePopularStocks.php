<?php

namespace App\Console\Commands;

use App\Models\TradingAsset;
use App\Services\TwelveDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActivatePopularStocks extends Command
{
    protected $signature = 'stocks:activate-popular';
    protected $description = 'Gradually activate popular stocks until 50 unique stocks are active';

    protected $targetActiveStocks = 50;
    protected $dailyQuoteLimit = 36;

    protected $popularSymbols = [
        'AAPL', 'MSFT', 'NVDA', 'AMZN', 'GOOGL', 'META', 'AVGO', 'TSLA', 'WMT', 'JPM',
        'V', 'XOM', 'ORCL', 'MA', 'COST', 'LLY', 'NFLX', 'PLTR', 'AMD', 'BAC',
        'KO', 'GE', 'CAT', 'JNJ', 'ABBV', 'PG', 'HD', 'CRM', 'CVX', 'MRK',
        'CSCO', 'IBM', 'MCD', 'DIS', 'QCOM', 'INTC', 'TMO', 'UNH', 'TXN', 'NOW',
        'AMAT', 'PM', 'GS', 'C', 'WFC', 'PEP', 'LIN', 'NKE', 'ABT', 'UBER',
    ];

    public function handle(TwelveDataService $twelveData)
    {
        set_time_limit(1800);

        if (!$twelveData->hasApiKey()) {
            $this->error('TwelveData API key is not configured.');
            return 1;
        }

        $activeSymbols = DB::table('trading_assets')
            ->where('asset_class', 'stock')
            ->where('is_active', true)
            ->selectRaw('UPPER(symbol) as symbol')
            ->groupByRaw('UPPER(symbol)')
            ->pluck('symbol')
            ->all();
        $activeSymbols = array_fill_keys($activeSymbols, true);
        $activeCount = count($activeSymbols);

        if ($activeCount >= $this->targetActiveStocks) {
            $this->info("Already have {$activeCount} active stock symbols; target is {$this->targetActiveStocks}.");
            return 0;
        }

        $dailyKey = 'stocks:activate-popular:' . now()->toDateString();
        if (!Cache::add($dailyKey, true, now()->addDay())) {
            $this->info('The popular-stock activation limit has already been used today. It will continue tomorrow.');
            return 0;
        }

        $assets = TradingAsset::ofClass('stock')
            ->whereIn('symbol', $this->popularSymbols)
            ->orderByRaw("CASE WHEN data_source = 'twelvedata' THEN 0 ELSE 1 END")
            ->get()
            ->groupBy(function ($asset) {
                return strtoupper(trim($asset->symbol));
            });

        $activated = 0;
        $attempts = 0;

        foreach ($this->popularSymbols as $symbol) {
            if ($activeCount >= $this->targetActiveStocks || $attempts >= $this->dailyQuoteLimit) {
                break;
            }

            if (isset($activeSymbols[$symbol])) {
                continue;
            }

            $asset = isset($assets[$symbol])
                ? $assets[$symbol]->first(function ($candidate) {
                    return !$candidate->is_active;
                })
                : null;

            if ($asset === null) {
                continue;
            }

            $attempts++;
            try {
                $twelveData->importStock($symbol, $asset->id);
            } catch (\RuntimeException $e) {
                $this->error("Stopped while requesting {$symbol}: {$e->getMessage()}");
                $this->line("Activated {$activated} stocks today. The remaining stocks will be retried tomorrow.");
                return 1;
            }

            $activeSymbols[$symbol] = true;
            $activeCount++;
            $activated++;
            $this->info("Activated {$symbol} ({$activeCount}/{$this->targetActiveStocks}).");

            if ($attempts < $this->dailyQuoteLimit) {
                sleep(13);
            }
        }

        if ($activated === 0) {
            $this->info('No eligible popular stocks were available to activate from the imported catalog.');
        } else {
            $this->info("Activated {$activated} stocks today. {$activeCount} of {$this->targetActiveStocks} active stock symbols.");
        }

        return 0;
    }
}
