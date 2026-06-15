<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'trade_type', 'is_demo', 'asset_type', 'asset_name',
        'trading_asset_id', 'leverage', 'duration', 'amount', 'action',
        'entry_price', 'exit_price', 'expires_at', 'status', 'profit_loss',
        'result', 'settled_by', 'settled_at', 'close_requested_at',
        'take_profit', 'stop_loss', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expires_at' => 'datetime',
        'settled_at' => 'datetime',
        'close_requested_at' => 'datetime',
        'is_demo' => 'boolean',
        'amount' => 'float',
        'entry_price' => 'float',
        'exit_price' => 'float',
        'profit_loss' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tradingAsset()
    {
        return $this->belongsTo(TradingAsset::class);
    }

    public function scopeBinary($query)
    {
        return $query->where('trade_type', 'binary');
    }

    public function scopeSpot($query)
    {
        return $query->where('trade_type', 'spot');
    }

    public function scopeLive($query)
    {
        return $query->where('is_demo', false);
    }

    public function scopeDemo($query)
    {
        return $query->where('is_demo', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
