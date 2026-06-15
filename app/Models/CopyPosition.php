<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopyPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expert_id',
        'invested_amount',
        'accumulated_profit',
        'daily_roi_snapshot',
        'started_at',
        'expires_at',
        'stopped_at',
        'settled_at',
        'status',
        'settled_by',
        'admin_profit_adjustment',
        'admin_notes',
    ];

    protected $casts = [
        'invested_amount' => 'float',
        'accumulated_profit' => 'float',
        'daily_roi_snapshot' => 'float',
        'admin_profit_adjustment' => 'float',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'stopped_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function simulatedTrades()
    {
        return $this->hasMany(CopySimulatedTrade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')->where('expires_at', '<=', now());
    }

    public function isSettleable()
    {
        return in_array($this->status, ['active', 'stopped', 'completed']);
    }

    public function totalPayout()
    {
        return $this->invested_amount + $this->accumulated_profit + $this->admin_profit_adjustment;
    }
}
