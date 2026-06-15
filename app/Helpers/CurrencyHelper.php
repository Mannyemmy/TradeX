<?php

namespace App\Helpers;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Auth;

class CurrencyHelper
{
    /**
     * Format a USD amount into the user's preferred currency with symbol.
     *
     * @param float $usdAmount Amount in USD (system currency)
     * @param \App\Models\User|null $user Specific user, or null for current auth user
     * @param int $decimals Decimal places (default 2)
     * @return string Formatted string like "₦1,500.00" or "$1,000.00"
     */
    public static function formatForUser($usdAmount, $user = null, int $decimals = 2): string
    {
        $user = $user ?? Auth::user();
        $code = $user->currency_code ?? 'USD';
        $symbol = html_entity_decode(ExchangeRate::getSymbol($code));
        $rate = ExchangeRate::getRate($code);
        $converted = round(($usdAmount ?? 0) * $rate, $decimals);

        return $symbol . number_format($converted, $decimals);
    }

    /**
     * Convert a user-entered amount to USD.
     *
     * @param float $userAmount Amount in the user's currency
     * @param \App\Models\User|null $user
     * @return float Amount in USD
     */
    public static function toUsd($userAmount, $user = null): float
    {
        $user = $user ?? Auth::user();
        $code = $user->currency_code ?? 'USD';
        $rate = ExchangeRate::getRate($code);
        $userAmount = $userAmount ?? 0;

        if ($rate <= 0) {
            return (float) $userAmount;
        }

        return round($userAmount / $rate, 2);
    }

    /**
     * Get the user's currency symbol (decoded).
     *
     * @param \App\Models\User|null $user
     * @return string
     */
    public static function getUserSymbol($user = null): string
    {
        $user = $user ?? Auth::user();
        $code = $user->currency_code ?? 'USD';
        return html_entity_decode(ExchangeRate::getSymbol($code));
    }

    /**
     * Get the user's currency code.
     *
     * @param \App\Models\User|null $user
     * @return string
     */
    public static function getUserCode($user = null): string
    {
        $user = $user ?? Auth::user();
        return $user->currency_code ?? 'USD';
    }

    /**
     * Get the exchange rate for the user's currency.
     *
     * @param \App\Models\User|null $user
     * @return float
     */
    public static function getUserRate($user = null): float
    {
        $user = $user ?? Auth::user();
        $code = $user->currency_code ?? 'USD';
        return ExchangeRate::getRate($code);
    }
}
