<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreIpoHolding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'pre_ipo_company_id', 'shares',
        'purchase_price', 'total_cost', 'status', 'converted_at',
    ];

    protected $casts = [
        'purchase_price' => 'float',
        'total_cost' => 'float',
        'converted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(PreIpoCompany::class, 'pre_ipo_company_id');
    }

    public function getCurrentValueAttribute()
    {
        return round($this->shares * $this->company->current_price, 2);
    }

    public function getUnrealizedPnlAttribute()
    {
        return round($this->current_value - $this->total_cost, 2);
    }

    public function getUnrealizedPnlPercentAttribute()
    {
        if ($this->total_cost == 0) return 0;
        return round(($this->unrealized_pnl / $this->total_cost) * 100, 2);
    }
}
