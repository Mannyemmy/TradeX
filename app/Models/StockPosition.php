<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'trading_asset_id', 'shares',
        'avg_buy_price', 'total_invested',
    ];

    protected $casts = [
        'shares' => 'float',
        'avg_buy_price' => 'float',
        'total_invested' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(TradingAsset::class, 'trading_asset_id');
    }

    public function getCurrentValueAttribute()
    {
        return round($this->shares * $this->asset->price, 2);
    }

    public function getUnrealizedPnlAttribute()
    {
        return round($this->current_value - $this->total_invested, 2);
    }

    public function getUnrealizedPnlPercentAttribute()
    {
        if ($this->total_invested == 0) return 0;
        return round(($this->unrealized_pnl / $this->total_invested) * 100, 2);
    }
}
