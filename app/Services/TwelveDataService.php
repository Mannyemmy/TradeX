<?php

namespace App\Services;

use App\Models\TradingAsset;
use App\Models\SettingsCont;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwelveDataService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.twelvedata.com';

    /**
     * Mapping of asset class to default symbols.
     */
    protected $assetSymbols = [
        'forex' => [
            'EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CHF',
            'NZD/USD', 'USD/CAD', 'EUR/GBP', 'EUR/JPY', 'GBP/JPY',
            'XAU/USD', 'XAG/USD',
        ],
        'stock' => [
            'AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA', 'NVDA', 'META',
            'NFLX', 'AMD', 'INTC', 'PYPL', 'DIS', 'BA', 'JPM',
        ],
        'etf' => [
            'SPY', 'QQQ', 'IWM', 'VTI', 'EEM', 'GLD', 'TLT', 'XLF', 'ARKK', 'DIA',
        ],
        'index' => [
            'SPX', 'IXIC', 'DJI', 'FTSE', 'DAX', 'N225', 'HSI', 'STOXX50E',
        ],
    ];

    /**
     * Friendly display names for known symbols.
     */
    protected $symbolNames = [
        // Forex
        'EUR/USD' => 'Euro / US Dollar',
        'GBP/USD' => 'British Pound / US Dollar',
        'USD/JPY' => 'US Dollar / Japanese Yen',
        'AUD/USD' => 'Australian Dollar / US Dollar',
        'USD/CHF' => 'US Dollar / Swiss Franc',
        'NZD/USD' => 'New Zealand Dollar / US Dollar',
        'USD/CAD' => 'US Dollar / Canadian Dollar',
        'EUR/GBP' => 'Euro / British Pound',
        'EUR/JPY' => 'Euro / Japanese Yen',
        'GBP/JPY' => 'British Pound / Japanese Yen',
        'XAU/USD' => 'Gold / US Dollar',
        'XAG/USD' => 'Silver / US Dollar',
        // Stocks
        'AAPL' => 'Apple Inc.',
        'MSFT' => 'Microsoft Corporation',
        'GOOGL' => 'Alphabet Inc.',
        'AMZN' => 'Amazon.com Inc.',
        'TSLA' => 'Tesla Inc.',
        'NVDA' => 'NVIDIA Corporation',
        'META' => 'Meta Platforms Inc.',
        'NFLX' => 'Netflix Inc.',
        'AMD' => 'Advanced Micro Devices',
        'INTC' => 'Intel Corporation',
        'PYPL' => 'PayPal Holdings',
        'DIS' => 'Walt Disney Co.',
        'BA' => 'Boeing Company',
        'JPM' => 'JPMorgan Chase & Co.',
        // ETFs
        'SPY' => 'SPDR S&P 500 ETF',
        'QQQ' => 'Invesco QQQ Trust',
        'IWM' => 'iShares Russell 2000 ETF',
        'VTI' => 'Vanguard Total Stock Market ETF',
        'EEM' => 'iShares MSCI Emerging Markets ETF',
        'GLD' => 'SPDR Gold Shares',
        'TLT' => 'iShares 20+ Year Treasury Bond ETF',
        'XLF' => 'Financial Select Sector SPDR Fund',
        'ARKK' => 'ARK Innovation ETF',
        'DIA' => 'SPDR Dow Jones Industrial Average ETF',
        // Indices
        'SPX' => 'S&P 500',
        'IXIC' => 'NASDAQ Composite',
        'DJI' => 'Dow Jones Industrial Average',
        'FTSE' => 'FTSE 100',
        'DAX' => 'DAX Performance Index',
        'N225' => 'Nikkei 225',
        'HSI' => 'Hang Seng Index',
        'STOXX50E' => 'EURO STOXX 50',
    ];

    public function __construct()
    {
        $settings = SettingsCont::find(1);
        $this->apiKey = $settings->twelvedata_api_key ?? null;
    }

    public function hasApiKey(): bool
    {
        return !empty($this->apiKey);
    }

    public function getTotalSymbolCount(): int
    {
        $count = 0;
        foreach ($this->getAssetSymbols() as $symbols) {
            $count += count($symbols);
        }
        return $count;
    }

    public function getCreditsPerMinute(): int
    {
        return $this->creditsPerMinute;
    }

    /**
     * Max API credits per minute on free tier.
     */
    protected $creditsPerMinute = 8;

    /**
     * Fetch and update all market asset prices (forex, stock, etf, index).
     * Returns associative array of counts per asset class.
     */
    public function updatePrices(): array
    {
        if (!$this->apiKey) {
            Log::warning('TwelveData API key not configured. Skipping market price update.');
            return ['forex' => 0, 'stock' => 0, 'etf' => 0, 'index' => 0];
        }

        // Collect all symbols across all classes, then process in rate-limited chunks
        $allSymbols = [];
        foreach ($this->getAssetSymbols() as $assetClass => $symbols) {
            foreach ($symbols as $symbol) {
                $allSymbols[] = ['symbol' => $symbol, 'asset_class' => $assetClass];
            }
        }

        $counts = ['forex' => 0, 'stock' => 0, 'etf' => 0, 'index' => 0];
        $chunks = array_chunk($allSymbols, $this->creditsPerMinute);

        foreach ($chunks as $i => $chunk) {
            // Wait 61 seconds between chunks to respect rate limit
            if ($i > 0) {
                sleep(61);
            }

            $symbolList = array_column($chunk, 'symbol');
            $updated = $this->fetchAndUpdateBatch($symbolList, $chunk);

            foreach ($updated as $assetClass => $count) {
                $counts[$assetClass] = ($counts[$assetClass] ?? 0) + $count;
            }
        }

        return $counts;
    }

    /**
     * Refresh the stock symbols without fetching unrelated market assets.
     */
    public function updateStockPrices(?array $requestedSymbols = null): int
    {
        if (!$this->apiKey) {
            Log::warning('TwelveData API key not configured. Skipping stock price update.');
            return 0;
        }

        $symbols = $this->getAssetSymbols()['stock'];
        if ($requestedSymbols !== null) {
            $symbols = array_values(array_intersect($symbols, $requestedSymbols));
        }

        $chunks = array_chunk($symbols, $this->creditsPerMinute);
        $updatedCount = 0;

        foreach ($chunks as $i => $chunk) {
            if ($i > 0) {
                sleep(61);
            }

            $symbolMeta = array_map(function ($symbol) {
                return ['symbol' => $symbol, 'asset_class' => 'stock'];
            }, $chunk);

            $updated = $this->fetchAndUpdateBatch($chunk, $symbolMeta);
            $updatedCount += $updated['stock'] ?? 0;
        }

        return $updatedCount;
    }

    /**
     * Fetch a stock quote and add or update the matching trading asset.
     *
     * @throws \RuntimeException When the provider cannot return a valid quote.
     */
    public function importStock(string $symbol, ?int $assetId = null): TradingAsset
    {
        if (!$this->apiKey) {
            throw new \RuntimeException('TwelveData API key is not configured.');
        }

        $symbol = strtoupper(trim($symbol));

        try {
            $response = Http::timeout(30)->get($this->baseUrl . '/quote', [
                'symbol' => $symbol,
                'apikey' => $this->apiKey,
            ]);
        } catch (\Exception $e) {
            Log::error("TwelveData stock import request failed for {$symbol}: " . $e->getMessage());
            throw new \RuntimeException('Could not connect to TwelveData. Please try again.');
        }

        if (!$response->successful()) {
            Log::error("TwelveData stock import HTTP error for {$symbol}: " . $response->status());
            $error = $response->json();
            $providerMessage = is_array($error) ? ($error['message'] ?? null) : null;
            $message = "TwelveData returned HTTP {$response->status()} for {$symbol}.";
            if ($providerMessage) {
                $message .= ' ' . $providerMessage;
            }

            throw new \RuntimeException($message);
        }

        $quote = $response->json();
        if (!is_array($quote)
            || (isset($quote['code']) && (int) $quote['code'] !== 200)
            || empty($quote['close'])
            || (float) $quote['close'] <= 0) {
            $providerMessage = is_array($quote) ? ($quote['message'] ?? null) : null;
            throw new \RuntimeException($providerMessage
                ? "TwelveData could not find a valid quote for {$symbol}: {$providerMessage}"
                : "TwelveData could not find a valid quote for {$symbol}.");
        }

        $matchingAssets = TradingAsset::ofClass('stock')
            ->whereRaw('UPPER(symbol) = ?', [$symbol])
            ->when($assetId !== null, function ($query) use ($assetId) {
                $query->where('id', $assetId);
            })
            ->orderByRaw("CASE WHEN data_source = 'twelvedata' THEN 0 ELSE 1 END")
            ->orderByDesc('updated_at');
        $asset = $matchingAssets->first();

        $asset = $asset ?? new TradingAsset([
            'external_id' => $symbol,
            'asset_class' => 'stock',
        ]);
        $asset->fill([
            'name' => $quote['name'] ?? ($this->symbolNames[$symbol] ?? $symbol),
            'symbol' => $symbol,
            'asset_class' => 'stock',
            'price' => (float) $quote['close'],
            'price_change_24h' => isset($quote['change']) ? (float) $quote['change'] : null,
            'price_change_pct_24h' => isset($quote['percent_change']) ? (float) $quote['percent_change'] : null,
            'high_24h' => isset($quote['high']) ? (float) $quote['high'] : null,
            'low_24h' => isset($quote['low']) ? (float) $quote['low'] : null,
            'volume_24h' => isset($quote['volume']) ? (float) $quote['volume'] : null,
            'data_source' => 'twelvedata',
            'is_active' => true,
        ]);
        $asset->save();

        return $asset;
    }

    /**
     * Import the provider's stock catalog without activating unquoted listings.
     *
     * @throws \RuntimeException When the provider cannot return a valid catalog.
     */
    public function importStockCatalog(): int
    {
        if (!$this->apiKey) {
            throw new \RuntimeException('TwelveData API key is not configured.');
        }

        try {
            $response = Http::timeout(60)->get($this->baseUrl . '/stocks', [
                'apikey' => $this->apiKey,
            ]);
        } catch (\Exception $e) {
            Log::error('TwelveData stock catalog request failed: ' . $e->getMessage());
            throw new \RuntimeException('Could not connect to TwelveData. Please try again.');
        }

        if (!$response->successful()) {
            Log::error('TwelveData stock catalog HTTP error: ' . $response->status());
            throw new \RuntimeException('TwelveData returned an HTTP error. Please try again later.');
        }

        $catalog = $response->json('data');
        unset($response);
        gc_collect_cycles();

        if (!is_array($catalog)) {
            throw new \RuntimeException('TwelveData returned an invalid stock catalog.');
        }

        $imported = 0;
        $batch = [];
        foreach ($catalog as $item) {
            if (!is_array($item) || empty($item['symbol'])) {
                continue;
            }

            $symbol = strtoupper(trim($item['symbol']));
            $name = trim($item['name'] ?? '');
            if ($symbol === '' || strlen($symbol) > 20 || $name === '' || strlen($name) > 100) {
                continue;
            }

            $batch[$symbol] = [
                'external_id' => 'stock_catalog_' . strtolower($symbol),
                'name' => $name,
                'symbol' => $symbol,
                'asset_class' => 'stock',
                'price' => 0,
                'data_source' => 'twelvedata',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= 500) {
                $imported += $this->insertNewCatalogStocks($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $imported += $this->insertNewCatalogStocks($batch);
        }

        if ($imported === 0 && empty($catalog)) {
            throw new \RuntimeException('TwelveData returned no stock listings to import.');
        }

        unset($catalog);
        gc_collect_cycles();

        return $imported;
    }

    protected function insertNewCatalogStocks(array $batch): int
    {
        $symbols = array_keys($batch);
        $placeholders = implode(',', array_fill(0, count($symbols), '?'));
        $knownSymbols = TradingAsset::ofClass('stock')
            ->whereRaw('UPPER(symbol) IN (' . $placeholders . ')', $symbols)
            ->pluck('symbol')
            ->map(function ($symbol) {
                return strtoupper(trim($symbol));
            })
            ->flip();

        $newAssets = array_values(array_filter($batch, function ($asset) use ($knownSymbols) {
            return !$knownSymbols->has($asset['symbol']);
        }));

        if (!empty($newAssets)) {
            TradingAsset::insert($newAssets);
        }

        return count($newAssets);
    }

    /**
     * Include active admin-managed stocks in addition to the default symbols.
     */
    protected function getAssetSymbols(): array
    {
        $symbols = $this->assetSymbols;
        $activeStocks = TradingAsset::active()
            ->ofClass('stock')
            ->pluck('symbol')
            ->map(function ($symbol) {
                return strtoupper(trim($symbol));
            })
            ->filter()
            ->all();

        $symbols['stock'] = array_values(array_unique(array_merge(
            $symbols['stock'],
            $activeStocks
        )));

        return $symbols;
    }

    /**
     * Fetch quotes for a batch of symbols (max 8) and update the database.
     */
    protected function fetchAndUpdateBatch(array $symbols, array $symbolMeta): array
    {
        $counts = [];

        try {
            $symbolString = implode(',', $symbols);

            $response = Http::timeout(30)->get($this->baseUrl . '/quote', [
                'symbol' => $symbolString,
                'apikey' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::error("TwelveData API HTTP error: " . $response->status() . ' - ' . $response->body());
                return $counts;
            }

            $data = $response->json();

            // Check for top-level rate limit or error response
            if (isset($data['code']) && in_array($data['code'], [429, 401, 403, 400])) {
                Log::error("TwelveData API error {$data['code']}: " . ($data['message'] ?? 'Unknown'));
                return $counts;
            }

            // Single symbol returns flat object; multiple returns keyed by symbol
            if (count($symbols) === 1) {
                $data = [$symbols[0] => $data];
            }

            foreach ($symbolMeta as $meta) {
                $symbol = $meta['symbol'];
                $assetClass = $meta['asset_class'];
                $quote = $data[$symbol] ?? null;

                // Skip symbols with errors or missing data
                if (!$quote || (isset($quote['code']) && $quote['code'] !== 200)) {
                    Log::warning("TwelveData: No data for {$symbol}" . (isset($quote['message']) ? " - {$quote['message']}" : ''));
                    continue;
                }

                // Require a valid close price
                if (empty($quote['close']) || floatval($quote['close']) <= 0) {
                    Log::warning("TwelveData: Invalid close price for {$symbol}");
                    continue;
                }

                $name = $quote['name'] ?? ($this->symbolNames[$symbol] ?? $symbol);

                $asset = TradingAsset::where('symbol', $symbol)
                    ->where('asset_class', $assetClass)
                    ->where('data_source', 'twelvedata')
                    ->first();

                if (!$asset && $assetClass === 'stock') {
                    $asset = TradingAsset::where('symbol', $symbol)
                        ->where('asset_class', $assetClass)
                        ->first();
                }

                $attributes = [
                    'name' => $asset && $asset->data_source === 'manual' ? $asset->name : $name,
                    'symbol' => $symbol,
                    'asset_class' => $assetClass,
                    'price' => floatval($quote['close']),
                    'price_change_24h' => isset($quote['change']) ? floatval($quote['change']) : null,
                    'price_change_pct_24h' => isset($quote['percent_change']) ? floatval($quote['percent_change']) : null,
                    'high_24h' => isset($quote['high']) ? floatval($quote['high']) : null,
                    'low_24h' => isset($quote['low']) ? floatval($quote['low']) : null,
                    'volume_24h' => isset($quote['volume']) ? floatval($quote['volume']) : null,
                ];

                if ($asset) {
                    $asset->fill($attributes);
                    $asset->data_source = 'twelvedata';
                    $asset->save();
                } else {
                    TradingAsset::create(array_merge([
                        'external_id' => $symbol,
                        'data_source' => 'twelvedata',
                        'is_active' => true,
                    ], $attributes));
                }

                $counts[$assetClass] = ($counts[$assetClass] ?? 0) + 1;
            }

            return $counts;
        } catch (\Exception $e) {
            Log::error("TwelveData API exception: " . $e->getMessage());
            return $counts;
        }
    }

    /**
     * Test the API connection. Returns true on success, error message on failure.
     */
    public function testConnection()
    {
        if (!$this->apiKey) {
            return 'API key is not configured.';
        }

        try {
            $response = Http::timeout(10)->get($this->baseUrl . '/quote', [
                'symbol' => 'AAPL',
                'apikey' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['code']) && $data['code'] === 401) {
                    return 'Invalid API key.';
                }
                return true;
            }

            return 'HTTP ' . $response->status() . ': ' . $response->body();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
