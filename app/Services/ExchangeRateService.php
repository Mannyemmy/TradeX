<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExchangeRateService
{
    /**
     * The free API endpoint — no key required, updates daily.
     */
    protected string $apiUrl = 'https://open.er-api.com/v6/latest/USD';

    /**
     * Fetch latest exchange rates from the API and update non-manual rates in the database.
     *
     * @return array ['updated' => int, 'errors' => string[]]
     */
    public function updateRates(): array
    {
        $result = ['updated' => 0, 'errors' => []];

        try {
            $response = Http::timeout(15)->get($this->apiUrl);

            if (!$response->successful()) {
                $result['errors'][] = 'API returned HTTP ' . $response->status();
                Log::warning('ExchangeRateService: API returned HTTP ' . $response->status());
                return $result;
            }

            $data = $response->json();

            if (empty($data['rates']) || ($data['result'] ?? '') !== 'success') {
                $result['errors'][] = 'Invalid API response format';
                Log::warning('ExchangeRateService: Invalid API response', ['data' => $data]);
                return $result;
            }

            $rates = $data['rates'];

            // Update all non-manual rates that exist in our exchange_rates table
            $dbRates = ExchangeRate::where('is_manual', false)->get();

            foreach ($dbRates as $dbRate) {
                $code = $dbRate->currency_code;

                if (isset($rates[$code])) {
                    $dbRate->rate_to_usd = $rates[$code];
                    $dbRate->save();
                    ExchangeRate::clearCache($code);
                    $result['updated']++;
                }
            }

            // Store last fetch timestamp
            Cache::put('exchange_rates_last_fetched', now()->toIso8601String(), 86400);

            Log::info("ExchangeRateService: Updated {$result['updated']} exchange rates");

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
            Log::error('ExchangeRateService: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Check if rates should be refreshed (older than 24 hours).
     */
    public function shouldRefresh(): bool
    {
        $lastFetched = Cache::get('exchange_rates_last_fetched');

        if (!$lastFetched) {
            return true;
        }

        return now()->diffInHours(\Carbon\Carbon::parse($lastFetched)) >= 24;
    }

    /**
     * Fetch rates only if they haven't been fetched in the last 24 hours.
     */
    public function updateRatesIfStale(): array
    {
        if ($this->shouldRefresh()) {
            return $this->updateRates();
        }

        return ['updated' => 0, 'errors' => [], 'skipped' => 'Rates are still fresh'];
    }

    /**
     * Convert amount between currencies.
     *
     * @param float $amount
     * @param string $fromCode
     * @param string $toCode
     * @return float
     */
    public function convert(float $amount, string $fromCode, string $toCode): float
    {
        if (strtoupper($fromCode) === strtoupper($toCode)) {
            return $amount;
        }

        $fromRate = ExchangeRate::getRate($fromCode);
        $toRate = ExchangeRate::getRate($toCode);

        if ($fromRate <= 0) {
            return $amount;
        }

        // Convert: fromCurrency → USD → toCurrency
        $usdAmount = $amount / $fromRate;
        return round($usdAmount * $toRate, 2);
    }
}
