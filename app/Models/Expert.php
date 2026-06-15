<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profile_picture',
        'bio',
        'area_of_expertise',
        'daily_roi',
        'duration_days',
        'win_rate',
        'min_startup_capital',
        'max_capital',
        'profit_share_percentage',
        'total_profit',
        'followers_count',
        'total_roi',
        'is_active',
    ];

    protected $casts = [
        'daily_roi' => 'float',
        'is_active' => 'boolean',
        'followers_count' => 'integer',
        'duration_days' => 'integer',
        'min_startup_capital' => 'float',
        'max_capital' => 'float',
        'total_profit' => 'float',
        'total_roi' => 'float',
        'win_rate' => 'float',
        'profit_share_percentage' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function positions()
    {
        return $this->hasMany(CopyPosition::class);
    }

    public function activePositions()
    {
        return $this->hasMany(CopyPosition::class)->where('status', 'active');
    }

    public function getActiveFollowersCountAttribute()
    {
        return $this->activePositions()->count();
    }

    /** @deprecated Use positions() instead */
    public function copyTrades()
    {
        return $this->hasMany(CopyTrade::class);
    }

    /** @deprecated Use positions() instead */
    public function copiedUsers()
    {
        return $this->hasMany(Copy::class);
    }
}
