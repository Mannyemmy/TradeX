<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\NFT;
use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function placeBid(Request $request, NFT $nft)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.0001',
        ]);

        if ($nft->status !== 'available') {
            return back()->with('message', 'This NFT is no longer available for bidding.');
        }

        if ($nft->user_id === Auth::id()) {
            return back()->with('message', 'You cannot bid on your own NFT.');
        }

        $coinGecko = new CoinGeckoService();
        $ethPrice = $coinGecko->getCryptoPrice('ethereum');

        if (!$ethPrice) {
            return back()->with('message', 'Failed to fetch Ethereum price. Please try again.');
        }

        $bidCostUsd = $request->amount * $ethPrice;

        if (Auth::user()->account_bal < $bidCostUsd) {
            return back()->with('message', 'Insufficient balance to cover this bid.');
        }

        // Check if user already has a pending bid — update it instead of creating duplicate
        $existingBid = Bid::where('nft_id', $nft->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingBid) {
            $existingBid->update([
                'amount'     => $request->amount,
                'expires_at' => now()->addDays(2),
            ]);
            return back()->with('success', 'Bid updated successfully.');
        }

        Bid::create([
            'nft_id'     => $nft->id,
            'user_id'    => Auth::id(),
            'amount'     => $request->amount,
            'status'     => 'pending',
            'expires_at' => now()->addDays(2),
        ]);

        return back()->with('success', 'Bid placed successfully.');
    }
}
