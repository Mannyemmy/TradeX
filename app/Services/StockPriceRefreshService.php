<?php

namespace App\Services;

use App\Models\TradingAsset;
use Illuminate\Support\Facades\Cache;

class StockPriceRefreshService
{
    public function refreshIfStale(?array $symbols = null): array
    {
        $staleStocks = TradingAsset::active()->ofClass('stock');

        if ($symbols !== null) {
            $staleStocks->whereIn('symbol', $symbols);
        }

        $staleSymbols = $staleStocks->where(function ($query) {
            $query->whereNull('updated_at')
                ->orWhere('updated_at', '<', now()->subMinutes(2));
        })->distinct()->pluck('symbol')->all();

        if (empty($staleSymbols)) {
            return ['status' => 200, 'data' => ['status' => 'fresh']];
        }

        $service = new TwelveDataService();
        if (!$service->hasApiKey()) {
            return [
                'status' => 503,
                'data' => [
                    'status' => 'error',
                    'message' => 'TwelveData API key is not configured.',
                ],
            ];
        }

        if (Cache::has('stock-prices-auto-refresh-running')) {
            return ['status' => 202, 'data' => ['status' => 'running']];
        }

        if (Cache::has('stock-prices-auto-refresh-cooldown')) {
            return [
                'status' => 429,
                'data' => [
                    'status' => 'cooldown',
                ],
            ];
        }

        if (!Cache::add('stock-prices-auto-refresh-running', true, 180)) {
            return ['status' => 202, 'data' => ['status' => 'running']];
        }

        try {
            set_time_limit(300);
            $updatedCount = $service->updateStockPrices($staleSymbols);
            Cache::put('stock-prices-auto-refresh-cooldown', true, 110);

            if ($updatedCount === 0) {
                return [
                    'status' => 502,
                    'data' => [
                        'status' => 'error',
                        'message' => 'No stock prices were updated. Check the application log and TwelveData usage limits.',
                    ],
                ];
            }

            return [
                'status' => 200,
                'data' => [
                    'status' => 'updated',
                    'updated' => $updatedCount,
                ],
            ];
        } finally {
            Cache::forget('stock-prices-auto-refresh-running');
        }
    }
}
