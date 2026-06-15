<?php

namespace App\Services;

use App\Models\TradingAsset;
use App\Models\SettingsCont;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinGeckoService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.coingecko.com/api/v3';

    public function __construct()
    {
        $settings = SettingsCont::find(1);
        $this->apiKey = $settings->coingecko_api_key ?? null;
    }

    /**
     * Fetch and update crypto asset prices from CoinGecko.
     * Returns count of assets updated.
     */
    public function updatePrices(): int
    {
        try {
            $headers = ['Accept' => 'application/json'];
            if ($this->apiKey) {
                $headers['x-cg-demo-key'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get($this->baseUrl . '/coins/markets', [
                    'vs_currency' => 'usd',
                    'order' => 'market_cap_desc',
                    'per_page' => 35,
                    'page' => 1,
                    'sparkline' => 'false',
                ]);

            if (!$response->successful()) {
                Log::error('CoinGecko API error: ' . $response->status() . ' - ' . $response->body());
                return 0;
            }

            $coins = $response->json();
            $count = 0;

            foreach ($coins as $coin) {
                TradingAsset::updateOrCreate(
                    [
                        'external_id' => $coin['id'],
                        'data_source' => 'coingecko',
                    ],
                    [
                        'name' => $coin['name'],
                        'symbol' => strtoupper($coin['symbol']),
                        'asset_class' => 'crypto',
                        'price' => $coin['current_price'] ?? 0,
                        'price_change_24h' => $coin['price_change_24h'] ?? null,
                        'price_change_pct_24h' => $coin['price_change_percentage_24h'] ?? null,
                        'high_24h' => $coin['high_24h'] ?? null,
                        'low_24h' => $coin['low_24h'] ?? null,
                        'volume_24h' => $coin['total_volume'] ?? null,
                        'market_cap' => $coin['market_cap'] ?? null,
                        'logo_url' => $coin['image'] ?? null,
                    ]
                );
                $count++;
            }

            return $count;
        } catch (\Exception $e) {
            Log::error('CoinGecko API exception: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Test the API connection. Returns true on success, error message on failure.
     */
    public function testConnection()
    {
        try {
            $headers = ['Accept' => 'application/json'];
            if ($this->apiKey) {
                $headers['x-cg-demo-key'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get($this->baseUrl . '/ping');

            if ($response->successful()) {
                return true;
            }

            return 'HTTP ' . $response->status() . ': ' . $response->body();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Fetch the current USD price of a cryptocurrency.
     * Defaults to Ethereum. Returns null on failure.
     */
    public function getCryptoPrice(string $coinId = 'ethereum'): ?float
    {
        try {
            $headers = ['Accept' => 'application/json'];
            if ($this->apiKey) {
                $headers['x-cg-demo-key'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(15)
                ->get($this->baseUrl . '/simple/price', [
                    'ids'           => $coinId,
                    'vs_currencies' => 'usd',
                ]);

            if ($response->successful()) {
                return $response->json()[$coinId]['usd'] ?? null;
            }

            Log::warning("CoinGecko price fetch failed for {$coinId}: " . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error("CoinGecko price exception for {$coinId}: " . $e->getMessage());
            return null;
        }
    }
}
