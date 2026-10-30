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
        $lock = Cache::lock('stocks:activate-popular:execution', 1800);
        if (!$lock->get()) {
            $this->info('Another popular-stock activation run is already in progress.');
            return 0;
        }

        try {
            return $this->activatePopularStocks($twelveData);
        } finally {
            $lock->release();
        }
    }

    protected function activatePopularStocks(TwelveDataService $twelveData)
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

        $dailyKey = 'stocks:activate-popular:quotes:' . now()->toDateString();
        Cache::add($dailyKey, 0, now()->addDay());
        $dailyAttempts = (int) Cache::get($dailyKey, 0);
        $this->info(
            "Activation status: {$activeCount}/{$this->targetActiveStocks} active; "
            . "{$dailyAttempts}/{$this->dailyQuoteLimit} quote attempts used today."
        );

        $assets = TradingAsset::ofClass('stock')
            ->whereIn('symbol', $this->popularSymbols)
            ->orderByRaw("CASE WHEN data_source = 'twelvedata' THEN 0 ELSE 1 END")
            ->get()
            ->groupBy(function ($asset) {
                return strtoupper(trim($asset->symbol));
            });

        $activated = 0;

        foreach ($this->popularSymbols as $symbol) {
            if ($activeCount >= $this->targetActiveStocks || $dailyAttempts >= $this->dailyQuoteLimit) {
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

            $retryCount = 0;
            while (true) {
                Cache::increment($dailyKey);
                $dailyAttempts++;

                try {
                    $twelveData->importStock($symbol, $asset->id);
                    break;
                } catch (\RuntimeException $e) {
                    $errorMessage = $e->getMessage();

                    if ($this->isDailyQuotaError($errorMessage)) {
                        $this->error("Stopped while requesting {$symbol}: TwelveData's daily credit limit has been reached. No more activation requests will be made today.");
                        $this->line("Activated {$activated} stocks in this run. Try again after the provider's daily quota resets.");
                        return 1;
                    }

                    if ($this->isMinuteQuotaError($errorMessage)
                        && $retryCount < 2
                        && $dailyAttempts < $this->dailyQuoteLimit) {
                        $retryCount++;
                        $this->warn("TwelveData minute quota reached for {$symbol}; waiting 61 seconds before retry {$retryCount}/2.");
                        sleep(61);
                        continue;
                    }

                    $this->error("Stopped while requesting {$symbol}: {$e->getMessage()}");
                    $this->line("Activated {$activated} stocks in this run. You can rerun this command to resume after the quota resets.");
                    return 1;
                }
            }

            $activeSymbols[$symbol] = true;
            $activeCount++;
            $activated++;
            $this->info("Activated {$symbol} ({$activeCount}/{$this->targetActiveStocks}).");

            if ($dailyAttempts < $this->dailyQuoteLimit && $activeCount < $this->targetActiveStocks) {
                sleep(13);
            }
        }

        if ($activated === 0 && $dailyAttempts < $this->dailyQuoteLimit) {
            $this->info('No eligible popular stocks were available to activate from the imported catalog.');
        } else {
            $this->info("Activated {$activated} stocks today. {$activeCount} of {$this->targetActiveStocks} active stock symbols.");
        }

        if ($dailyAttempts >= $this->dailyQuoteLimit && $activeCount < $this->targetActiveStocks) {
            $this->warn("Reached the daily safety cap of {$this->dailyQuoteLimit} quote attempts. The command can continue tomorrow.");
        }

        return 0;
    }

    protected function isDailyQuotaError(string $message): bool
    {
        return stripos($message, 'for the day') !== false
            || stripos($message, 'daily limit') !== false
            || stripos($message, 'per day') !== false;
    }

    protected function isMinuteQuotaError(string $message): bool
    {
        return !$this->isDailyQuotaError($message)
            && (
                stripos($message, 'current minute') !== false
                || stripos($message, 'per minute') !== false
                || stripos($message, 'minute quota') !== false
            );
    }
}
