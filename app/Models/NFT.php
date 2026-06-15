<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFT extends Model
{
    use HasFactory;

    protected $table = 'nfts';

    protected $fillable = [
        'user_id', 'original_creator_id', 'collection_id', 'category_id',
        'token_id', 'name', 'description', 'price', 'category', 'blockchain',
        'royalty_percent', 'image', 'properties', 'status',
        'views_count', 'likes_count', 'is_featured', 'is_approved', 'minted_at',
    ];

    protected $casts = [
        'price'           => 'decimal:8',
        'royalty_percent'  => 'decimal:2',
        'properties'       => 'array',
        'is_featured'     => 'boolean',
        'is_approved'     => 'boolean',
        'minted_at'       => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function originalCreator()
    {
        return $this->belongsTo(User::class, 'original_creator_id');
    }

    public function collection()
    {
        return $this->belongsTo(NftCollection::class, 'collection_id');
    }

    public function nftCategory()
    {
        return $this->belongsTo(NftCategory::class, 'category_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'nft_id');
    }

    public function transfers()
    {
        return $this->hasMany(NftTransfer::class, 'nft_id');
    }

    public function likes()
    {
        return $this->hasMany(NftLike::class, 'nft_id');
    }

    // ── Helpers ───────────────────────────────────────

    public function highestBid()
    {
        return $this->bids()->where('status', 'pending')->orderBy('amount', 'desc')->first();
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function generateTokenId()
    {
        do {
            $token = 'TXP-' . strtoupper(bin2hex(random_bytes(6)));
        } while (static::where('token_id', $token)->exists());

        return $token;
    }
}
