<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
