<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\NFT;
use App\Models\NftTransfer;
use App\Models\Tp_Transaction;
use App\Mail\BidApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Services\CoinGeckoService;
use App\Services\NotificationService;

class AdminBidController extends Controller
{
    public function bidsForApproval(Request $request)
    {
        $title = 'Manage Bids';
        $query = Bid::with('nft', 'user')->orderBy('created_at', 'desc');

        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bids = $query->paginate(20);
        return view('admin.bids.index', compact('bids', 'title', 'status'));
    }

    public function approveBid($bidId)
    {
        $coinGecko = new CoinGeckoService();
        $ethPrice = $coinGecko->getCryptoPrice('ethereum');

        if (!$ethPrice) {
            return back()->with('message', 'Failed to fetch Ethereum price. Please try again.');
        }

        $bid = Bid::findOrFail($bidId);
        $nft = NFT::findOrFail($bid->nft_id);
        $buyer = User::findOrFail($bid->user_id);
        $seller = User::findOrFail($nft->user_id);

        $totalCost = $bid->amount * $ethPrice;

        if ($buyer->account_bal < $totalCost) {
            return back()->with('message', 'Buyer does not have enough balance ($' . number_format($totalCost, 2) . ' needed).');
        }

        // Royalty on secondary sale
        $originalCreator = $nft->original_creator_id ? User::find($nft->original_creator_id) : null;
        $royaltyAmount = 0;
        $sellerProceeds = $totalCost;

        if ($originalCreator && $originalCreator->id !== $seller->id && $nft->royalty_percent > 0) {
            $royaltyAmount = $totalCost * ($nft->royalty_percent / 100);
            $sellerProceeds = $totalCost - $royaltyAmount;
            $originalCreator->account_bal += $royaltyAmount;
            $originalCreator->save();
        }

        $buyer->account_bal -= $totalCost;
        $buyer->save();

        $seller->account_bal += $sellerProceeds;
        $seller->save();

        $previousOwnerId = $nft->user_id;
        $nft->user_id = $buyer->id;
        $nft->status = 'sold';
        $nft->save();

        // Transfer record
        NftTransfer::create([
            'nft_id'       => $nft->id,
            'from_user_id' => $previousOwnerId,
            'to_user_id'   => $buyer->id,
            'price'        => $bid->amount,
            'type'         => 'bid_accept',
        ]);

        // Transaction records
        Tp_Transaction::create([
            'user'   => $buyer->id,
            'plan'   => $nft->name . ' NFT Bid Purchase',
            'amount' => $totalCost,
            'type'   => 'Buy NFT',
        ]);

        Tp_Transaction::create([
            'user'   => $seller->id,
            'plan'   => $nft->name . ' NFT Bid Sale',
            'amount' => $sellerProceeds,
            'type'   => 'Sell NFT',
        ]);

        if ($royaltyAmount > 0 && $originalCreator) {
            Tp_Transaction::create([
                'user'   => $originalCreator->id,
                'plan'   => $nft->name . ' NFT Royalty',
                'amount' => $royaltyAmount,
                'type'   => 'NFT Royalty',
            ]);
        }

        // Reject all other bids
        Bid::where('nft_id', $nft->id)->where('id', '!=', $bid->id)->update(['status' => 'rejected']);
        $bid->status = 'approved';
        $bid->save();

        if ($nft->collection_id) {
            $nft->collection->recalcStats();
        }

        // Send mail
        try {
            $subject = 'Congratulations! Your Bid Was Approved';
            Mail::to($buyer->email)->send(new BidApprovedMail($bid, $nft, $subject));
        } catch (\Exception $e) {
            // Mail failure shouldn't block the transaction
        }

        NotificationService::notifyUser($buyer, 'nft', 'NFT Bid Approved', 'Your bid of ' . $bid->amount . ' ETH on "' . $nft->name . '" was approved! The NFT is now in your collection.', url('dashboard/nfts'));
        NotificationService::notifyUser($seller, 'nft', 'NFT Sold', 'Your NFT "' . $nft->name . '" was sold for $' . number_format($sellerProceeds, 2) . '.', url('dashboard/nfts'));

        return back()->with('success', 'Bid approved. NFT transferred to buyer.');
    }

    public function rejectBid($bidId)
    {
        $bid = Bid::findOrFail($bidId);
        $bid->status = 'rejected';
        $bid->save();

        return back()->with('success', 'Bid rejected.');
    }
}
