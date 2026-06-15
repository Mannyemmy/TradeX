<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NftCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'banner_image', 'logo_image',
        'creator_id', 'category_id', 'floor_price', 'total_volume',
        'royalty_percent', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'floor_price'     => 'decimal:8',
        'total_volume'    => 'decimal:8',
        'royalty_percent'  => 'decimal:2',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category()
    {
        return $this->belongsTo(NftCategory::class, 'category_id');
    }

    public function nfts()
    {
        return $this->hasMany(NFT::class, 'collection_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function recalcStats()
    {
        $this->floor_price = $this->nfts()->where('status', 'available')->min('price') ?? 0;
        $this->total_volume = NftTransfer::whereIn('nft_id', $this->nfts()->pluck('id'))
            ->whereIn('type', ['sale', 'bid_accept'])
            ->sum('price');
        $this->save();
    }
}
