<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSimulatedTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_subscription_id',
        'trading_asset_id',
        'asset_name',
        'asset_class',
        'action',
        'entry_price',
        'exit_price',
        'amount',
        'profit_loss',
        'result',
        'executed_at',
    ];

    protected $casts = [
        'entry_price' => 'float',
        'exit_price' => 'float',
        'amount' => 'float',
        'profit_loss' => 'float',
        'executed_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(BotSubscription::class, 'bot_subscription_id');
    }

    public function tradingAsset()
    {
        return $this->belongsTo(TradingAsset::class);
    }
}
