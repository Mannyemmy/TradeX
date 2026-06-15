<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'strategy_type',
        'win_rate',
        'expected_roi',
        'min_investment',
        'max_investment',
        'profit_min_pct',
        'profit_max_pct',
        'loss_min_pct',
        'loss_max_pct',
        'trade_interval_minutes',
        'max_duration_days',
        'is_active',
        'subscribers_count',
        'total_profit',
    ];

    protected $casts = [
        'win_rate' => 'float',
        'expected_roi' => 'float',
        'min_investment' => 'float',
        'max_investment' => 'float',
        'profit_min_pct' => 'float',
        'profit_max_pct' => 'float',
        'loss_min_pct' => 'float',
        'loss_max_pct' => 'float',
        'trade_interval_minutes' => 'integer',
        'max_duration_days' => 'integer',
        'is_active' => 'boolean',
        'subscribers_count' => 'integer',
        'total_profit' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function subscriptions()
    {
        return $this->hasMany(BotSubscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(BotSubscription::class)->where('status', 'active');
    }

    public function getActiveSubscribersCountAttribute()
    {
        return $this->activeSubscriptions()->count();
    }

    public function getStrategyLabelAttribute()
    {
        return [
            'scalping' => 'Scalping',
            'day_trading' => 'Day Trading',
            'swing' => 'Swing Trading',
        ][$this->strategy_type] ?? $this->strategy_type;
    }
}
