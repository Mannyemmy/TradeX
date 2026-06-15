<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExchangeRate extends Model
{
    protected $fillable = [
        'currency_code',
        'currency_symbol',
        'currency_name',
        'rate_to_usd',
        'is_active',
        'is_manual',
    ];

    protected $casts = [
        'rate_to_usd' => 'float',
        'is_active' => 'boolean',
        'is_manual' => 'boolean',
    ];

    /**
     * Get the exchange rate for a currency code.
     * Returns how many units of that currency equal 1 USD.
     * Cached for 1 hour.
     */
    public static function getRate(string $code): float
    {
        if (strtoupper($code) === 'USD') {
            return 1.0;
        }

        return Cache::remember("exchange_rate_{$code}", 3600, function () use ($code) {
            $rate = static::where('currency_code', strtoupper($code))
                ->where('is_active', true)
                ->value('rate_to_usd');

            return $rate ?: 1.0;
        });
    }

    /**
     * Get the currency symbol for a code.
     */
    public static function getSymbol(string $code): string
    {
        return Cache::remember("currency_symbol_{$code}", 3600, function () use ($code) {
            $symbol = static::where('currency_code', strtoupper($code))->value('currency_symbol');
            return $symbol ? html_entity_decode($symbol) : '$';
        });
    }

    /**
     * Get all active currencies as [code => symbol] for dropdowns.
     */
    public static function activeCurrencies(): array
    {
        return Cache::remember('active_currencies', 3600, function () {
            return static::where('is_active', true)
                ->orderBy('currency_code')
                ->pluck('currency_symbol', 'currency_code')
                ->toArray();
        });
    }

    /**
     * Clear cached rates (call after update).
     */
    public static function clearCache(?string $code = null): void
    {
        if ($code) {
            Cache::forget("exchange_rate_{$code}");
            Cache::forget("currency_symbol_{$code}");
        }
        Cache::forget('active_currencies');
    }
}
