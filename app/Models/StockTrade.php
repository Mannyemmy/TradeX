<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'trading_asset_id', 'type', 'shares',
        'price_per_share', 'total_amount', 'fee_amount', 'status',
    ];

    protected $casts = [
        'shares' => 'float',
        'price_per_share' => 'float',
        'total_amount' => 'float',
        'fee_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(TradingAsset::class, 'trading_asset_id');
    }
}
