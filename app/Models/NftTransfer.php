<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NftTransfer extends Model
{
    use HasFactory;

    protected $fillable = ['nft_id', 'from_user_id', 'to_user_id', 'price', 'type'];

    protected $casts = [
        'price' => 'decimal:8',
    ];

    public function nft()
    {
        return $this->belongsTo(NFT::class, 'nft_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
