<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TradingAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id', 'name', 'symbol', 'asset_class', 'price',
        'price_change_24h', 'price_change_pct_24h', 'high_24h', 'low_24h',
        'volume_24h', 'market_cap', 'logo_url', 'data_source', 'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'price_change_24h' => 'float',
        'price_change_pct_24h' => 'float',
        'high_24h' => 'float',
        'low_24h' => 'float',
        'volume_24h' => 'float',
        'market_cap' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to only active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by asset class.
     */
    public function scopeOfClass($query, $class)
    {
        return $query->where('asset_class', $class);
    }

    /**
     * Keep one preferred quote per asset class and symbol.
     */
    public static function preferProviderBySymbol(Collection $assets): Collection
    {
        return $assets
            ->groupBy(function ($asset) {
                $symbol = strtoupper(trim((string) $asset->symbol));

                return $asset->asset_class . ':' . ($symbol !== '' ? $symbol : 'id:' . $asset->id);
            })
            ->map(function (Collection $rows) {
                $preferredSource = [
                    'crypto' => 'coingecko',
                    'stock' => 'twelvedata',
                ][$rows->first()->asset_class] ?? null;

                return $rows->sort(function ($left, $right) use ($preferredSource) {
                    $leftPreferred = $preferredSource !== null && $left->data_source === $preferredSource;
                    $rightPreferred = $preferredSource !== null && $right->data_source === $preferredSource;
                    if ($leftPreferred !== $rightPreferred) {
                        return $rightPreferred <=> $leftPreferred;
                    }

                    $leftUpdatedAt = $left->updated_at ? $left->updated_at->timestamp : 0;
                    $rightUpdatedAt = $right->updated_at ? $right->updated_at->timestamp : 0;
                    if ($leftUpdatedAt !== $rightUpdatedAt) {
                        return $rightUpdatedAt <=> $leftUpdatedAt;
                    }

                    return $right->id <=> $left->id;
                })->first();
            })
            ->values();
    }

    /**
     * Price formatted to appropriate precision based on asset class.
     */
    public function getFormattedPriceAttribute()
    {
        switch ($this->asset_class) {
            case 'crypto':
                // Up to 6 decimal places; trim trailing zeros
                if ($this->price >= 1) {
                    return '$' . number_format($this->price, 2);
                }
                return '$' . rtrim(rtrim(number_format($this->price, 6), '0'), '.');
            case 'forex':
                return number_format($this->price, $this->price >= 100 ? 3 : 5);
            default: // stock, etf, index
                return '$' . number_format($this->price, 2);
        }
    }

    /**
     * 24h change percentage formatted with sign.
     */
    public function getFormattedChangeAttribute()
    {
        if ($this->price_change_pct_24h === null) {
            return '—';
        }
        $sign = $this->price_change_pct_24h >= 0 ? '+' : '';
        return $sign . number_format($this->price_change_pct_24h, 2) . '%';
    }
}
