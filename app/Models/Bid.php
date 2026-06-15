<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nft_id', 'amount', 'status', 'expires_at'];

    protected $casts = [
        'amount'     => 'decimal:8',
        'expires_at' => 'datetime',
    ];

    public function nft()
    {
        return $this->belongsTo(NFT::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'pending')
                     ->where(function ($q) {
                         $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                     });
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
