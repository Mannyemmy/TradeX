<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NFT;
use App\Models\NftCategory;
use App\Models\NftCollection;
use App\Models\NftTransfer;
use App\Models\Bid;
use App\Models\User;

class NftSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Create a user first.');
            return;
        }

        // ── 5 Categories ─────────────────────────────────
        $categories = [
            ['name' => 'Digital Art',    'slug' => 'digital-art',    'icon' => 'art',          'sort_order' => 1, 'is_active' => true],
            ['name' => 'Photography',    'slug' => 'photography',    'icon' => 'photography',  'sort_order' => 2, 'is_active' => true],
            ['name' => 'Music',          'slug' => 'music',          'icon' => 'music',        'sort_order' => 3, 'is_active' => true],
            ['name' => 'Collectibles',   'slug' => 'collectibles',   'icon' => 'collectibles', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Virtual Worlds', 'slug' => 'virtual-worlds', 'icon' => 'worlds',       'sort_order' => 5, 'is_active' => true],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = NftCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $this->command->info('5 categories created.');

        // ── 5 Collections ────────────────────────────────
        $collections = [
            [
                'name'            => 'Cosmic Explorers',
                'slug'            => 'cosmic-explorers',
                'description'     => 'A collection of interstellar digital artworks exploring the cosmos.',
                'creator_id'      => $user->id,
                'category_id'     => $catModels[0]->id,
                'floor_price'     => 0.15,
                'total_volume'    => 0,
                'royalty_percent'  => 5.0,
                'is_featured'     => true,
                'is_active'       => true,
            ],
            [
                'name'            => 'Urban Lens',
                'slug'            => 'urban-lens',
                'description'     => 'Street photography captured across major cities worldwide.',
                'creator_id'      => $user->id,
                'category_id'     => $catModels[1]->id,
                'floor_price'     => 0.08,
                'total_volume'    => 0,
                'royalty_percent'  => 3.0,
                'is_featured'     => false,
                'is_active'       => true,
            ],
            [
                'name'            => 'Beat Drops',
                'slug'            => 'beat-drops',
                'description'     => 'Exclusive audio NFTs from independent musicians.',
                'creator_id'      => $user->id,
                'category_id'     => $catModels[2]->id,
                'floor_price'     => 0.05,
                'total_volume'    => 0,
                'royalty_percent'  => 7.5,
                'is_featured'     => true,
                'is_active'       => true,
            ],
            [
                'name'            => 'Pixel Legends',
                'slug'            => 'pixel-legends',
                'description'     => 'Retro pixel art collectible characters — 1 of 1 editions.',
                'creator_id'      => $user->id,
                'category_id'     => $catModels[3]->id,
                'floor_price'     => 0.25,
                'total_volume'    => 0,
                'royalty_percent'  => 5.0,
                'is_featured'     => false,
                'is_active'       => true,
            ],
            [
                'name'            => 'Meta Estates',
                'slug'            => 'meta-estates',
                'description'     => 'Virtual real estate parcels in the TradexPro metaverse.',
                'creator_id'      => $user->id,
                'category_id'     => $catModels[4]->id,
                'floor_price'     => 1.00,
                'total_volume'    => 0,
                'royalty_percent'  => 2.5,
                'is_featured'     => true,
                'is_active'       => true,
            ],
        ];

        $colModels = [];
        foreach ($collections as $col) {
            $colModels[] = NftCollection::firstOrCreate(['slug' => $col['slug']], $col);
        }

        $this->command->info('5 collections created.');

        // ── 5 NFTs ───────────────────────────────────────
        $nfts = [
            [
                'name'                => 'Nebula Drift #01',
                'description'         => 'A swirling nebula rendered in vivid purples and blues, capturing the birth of new stars.',
                'price'               => 0.25,
                'category'            => 'Digital Art',
                'category_id'         => $catModels[0]->id,
                'collection_id'       => $colModels[0]->id,
                'blockchain'          => 'Ethereum',
                'royalty_percent'      => 5.0,
                'properties'          => ['Background' => 'Deep Space', 'Rarity' => 'Legendary', 'Colors' => '3'],
                'status'              => 'available',
                'is_featured'         => true,
                'is_approved'         => true,
                'views_count'         => 142,
                'likes_count'         => 28,
            ],
            [
                'name'                => 'Tokyo After Rain',
                'description'         => 'Neon reflections on wet Tokyo streets after midnight — a moody urban photograph.',
                'price'               => 0.12,
                'category'            => 'Photography',
                'category_id'         => $catModels[1]->id,
                'collection_id'       => $colModels[1]->id,
                'blockchain'          => 'Ethereum',
                'royalty_percent'      => 3.0,
                'properties'          => ['Location' => 'Shibuya', 'Camera' => 'Sony A7IV', 'Edition' => '1/1'],
                'status'              => 'available',
                'is_featured'         => false,
                'is_approved'         => true,
                'views_count'         => 67,
                'likes_count'         => 11,
            ],
            [
                'name'                => 'Synthwave Sunrise',
                'description'         => 'An original 90-second synthwave track with full commercial rights.',
                'price'               => 0.08,
                'category'            => 'Music',
                'category_id'         => $catModels[2]->id,
                'collection_id'       => $colModels[2]->id,
                'blockchain'          => 'Ethereum',
                'royalty_percent'      => 7.5,
                'properties'          => ['Genre' => 'Synthwave', 'Duration' => '90s', 'BPM' => '120'],
                'status'              => 'sold',
                'is_featured'         => false,
                'is_approved'         => true,
                'views_count'         => 203,
                'likes_count'         => 45,
            ],
            [
                'name'                => 'Pixel Knight #07',
                'description'         => 'A rare pixel art knight with golden armor — part of the Pixel Legends series.',
                'price'               => 0.35,
                'category'            => 'Collectibles',
                'category_id'         => $catModels[3]->id,
                'collection_id'       => $colModels[3]->id,
                'blockchain'          => 'Ethereum',
                'royalty_percent'      => 5.0,
                'properties'          => ['Armor' => 'Gold', 'Weapon' => 'Sword', 'Rarity' => 'Epic', 'Level' => '7'],
                'status'              => 'sold',
                'is_featured'         => true,
                'is_approved'         => true,
                'views_count'         => 318,
                'likes_count'         => 72,
            ],
            [
                'name'                => 'Skyline Penthouse',
                'description'         => 'Premium virtual penthouse with panoramic metaverse skyline views.',
                'price'               => 1.50,
                'category'            => 'Virtual Worlds',
                'category_id'         => $catModels[4]->id,
                'collection_id'       => $colModels[4]->id,
                'blockchain'          => 'Ethereum',
                'royalty_percent'      => 2.5,
                'properties'          => ['Zone' => 'Downtown', 'Floors' => '2', 'View' => 'Panoramic'],
                'status'              => 'available',
                'is_featured'         => true,
                'is_approved'         => true,
                'views_count'         => 95,
                'likes_count'         => 19,
            ],
        ];

        $nftModels = [];
        foreach ($nfts as $nftData) {
            $nftData['user_id']             = $user->id;
            $nftData['original_creator_id'] = $user->id;
            $nftData['token_id']            = NFT::generateTokenId();
            $nftData['image']               = 'nfts/placeholder.png';
            $nftData['minted_at']           = now()->subDays(random_int(1, 30));

            $nftModels[] = NFT::create($nftData);
        }

        $this->command->info('5 NFTs created.');

        // ── Mint transfers for all NFTs ──────────────────
        foreach ($nftModels as $nft) {
            NftTransfer::create([
                'nft_id'       => $nft->id,
                'from_user_id' => null,
                'to_user_id'   => $user->id,
                'price'        => 0,
                'type'         => 'mint',
            ]);
        }

        // ── Sale transfers for the 2 sold NFTs ──────────
        foreach ([$nftModels[2], $nftModels[3]] as $soldNft) {
            NftTransfer::create([
                'nft_id'       => $soldNft->id,
                'from_user_id' => $user->id,
                'to_user_id'   => $user->id,
                'price'        => $soldNft->price,
                'type'         => 'sale',
            ]);
        }

        $this->command->info('Transfer history created.');

        // ── 5 Bids (spread across available NFTs) ────────
        $bids = [
            ['nft_id' => $nftModels[0]->id, 'amount' => 0.20, 'status' => 'pending'],
            ['nft_id' => $nftModels[0]->id, 'amount' => 0.22, 'status' => 'pending'],
            ['nft_id' => $nftModels[1]->id, 'amount' => 0.10, 'status' => 'pending'],
            ['nft_id' => $nftModels[4]->id, 'amount' => 1.20, 'status' => 'pending'],
            ['nft_id' => $nftModels[4]->id, 'amount' => 1.35, 'status' => 'pending'],
        ];

        foreach ($bids as $bidData) {
            Bid::create([
                'user_id'    => $user->id,
                'nft_id'     => $bidData['nft_id'],
                'amount'     => $bidData['amount'],
                'status'     => $bidData['status'],
                'expires_at' => now()->addDays(7),
            ]);
        }

        $this->command->info('5 bids created.');

        // ── Recalculate collection stats ─────────────────
        foreach ($colModels as $col) {
            $col->recalcStats();
        }

        $this->command->info('Collection stats recalculated.');
        $this->command->info('NFT seed data complete!');
    }
}
