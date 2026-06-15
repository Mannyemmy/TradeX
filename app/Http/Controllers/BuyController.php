<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NFT;
use App\Models\User;
use App\Models\NftTransfer;
use App\Models\Tp_Transaction;
use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function buyNFT(NFT $nft)
    {
        $coinGecko = new CoinGeckoService();
        $ethPrice = $coinGecko->getCryptoPrice('ethereum');

        if (!$ethPrice) {
            return back()->with('message', 'Failed to fetch Ethereum price. Please try again.');
        }

        $totalCost = $nft->price * $ethPrice;
        $buyer = Auth::user();

        if ($buyer->id === $nft->user_id) {
            return back()->with('message', 'You cannot buy your own NFT.');
        }

        if ($nft->status !== 'available') {
            return back()->with('message', 'This NFT is no longer available.');
        }

        if ($buyer->account_bal < $totalCost) {
            return back()->with('message', 'Insufficient balance. You need $' . number_format($totalCost, 2));
        }

        $seller = User::find($nft->user_id);
        $originalCreator = $nft->original_creator_id ? User::find($nft->original_creator_id) : null;

        // Royalty on secondary sales
        $royaltyAmount = 0;
        $sellerProceeds = $totalCost;

        if ($originalCreator && $originalCreator->id !== $seller->id && $nft->royalty_percent > 0) {
            $royaltyAmount = $totalCost * ($nft->royalty_percent / 100);
            $sellerProceeds = $totalCost - $royaltyAmount;
            $originalCreator->account_bal += $royaltyAmount;
            $originalCreator->save();
        }

        if ($seller) {
            $seller->account_bal += $sellerProceeds;
            $seller->save();
        }

        $buyer->account_bal -= $totalCost;
        $buyer->save();

        $previousOwnerId = $nft->user_id;
        $nft->user_id = $buyer->id;
        $nft->status = 'sold';
        $nft->save();

        NftTransfer::create([
            'nft_id'       => $nft->id,
            'from_user_id' => $previousOwnerId,
            'to_user_id'   => $buyer->id,
            'price'        => $nft->price,
            'type'         => 'sale',
        ]);

        Tp_Transaction::create([
            'user'   => $buyer->id,
            'plan'   => $nft->name . ' NFT Purchase',
            'amount' => $totalCost,
            'type'   => 'Buy NFT',
        ]);

        if ($seller) {
            Tp_Transaction::create([
                'user'   => $seller->id,
                'plan'   => $nft->name . ' NFT Sale',
                'amount' => $sellerProceeds,
                'type'   => 'Sell NFT',
            ]);
        }

        if ($royaltyAmount > 0 && $originalCreator) {
            Tp_Transaction::create([
                'user'   => $originalCreator->id,
                'plan'   => $nft->name . ' NFT Royalty',
                'amount' => $royaltyAmount,
                'type'   => 'NFT Royalty',
            ]);
        }

        if ($nft->collection_id) {
            $nft->collection->recalcStats();
        }

        $nft->bids()->where('status', 'pending')->update(['status' => 'rejected']);

        return redirect()->route('user.nfts.my')->with('success', 'NFT purchased successfully!');
    }
}
