<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreIpoCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'symbol', 'logo', 'description', 'sector',
        'share_price', 'initial_price', 'total_shares', 'shares_sold',
        'min_shares', 'max_shares_per_user', 'status', 'trading_asset_id',
        'expected_ipo_date', 'opened_at', 'closed_at', 'went_public_at', 'is_featured',
    ];

    protected $casts = [
        'share_price' => 'float',
        'initial_price' => 'float',
        'is_featured' => 'boolean',
        'expected_ipo_date' => 'date',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'went_public_at' => 'datetime',
    ];

    public function holdings()
    {
        return $this->hasMany(PreIpoHolding::class);
    }

    public function priceHistory()
    {
        return $this->hasMany(PreIpoPriceHistory::class);
    }

    public function tradingAsset()
    {
        return $this->belongsTo(TradingAsset::class);
    }

    public function getSharesRemainingAttribute()
    {
        return $this->total_shares - $this->shares_sold;
    }

    public function getPriceChangePercentAttribute()
    {
        if ($this->initial_price == 0) return 0;
        return round((($this->share_price - $this->initial_price) / $this->initial_price) * 100, 2);
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->status === 'public' && $this->tradingAsset) {
            return $this->tradingAsset->price;
        }
        return $this->share_price;
    }
}
