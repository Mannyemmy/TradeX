<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NFT;
use App\Models\NftCategory;
use App\Models\NftCollection;
use App\Models\NftLike;
use App\Models\NftTransfer;
use App\Models\Tp_Transaction;
use App\Models\Settings;
use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Auth;

class UserNFTController extends Controller
{
    public function gallery(Request $request)
    {
        $query = NFT::available();

        if ($request->filled('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('collection_id')) {
            $query->where('collection_id', $request->collection_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('token_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':  $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'popular':    $query->orderBy('views_count', 'desc'); break;
            case 'liked':      $query->orderBy('likes_count', 'desc'); break;
            default:           $query->latest(); break;
        }

        $title = 'NFT Marketplace';
        $nfts = $query->paginate(12);
        $categories = NftCategory::active()->get();
        $collections = NftCollection::active()->withCount('nfts')->get();
        $featuredNfts = NFT::available()->featured()->limit(6)->get();

        return view('user.nfts.gallery', compact('nfts', 'categories', 'collections', 'featuredNfts', 'title'));
    }

    public function collection(NftCollection $collection)
    {
        $title = $collection->name;
        $nfts = NFT::available()
            ->where('collection_id', $collection->id)
            ->latest()
            ->paginate(12);

        return view('user.nfts.collection', compact('collection', 'nfts', 'title'));
    }

    public function show($id)
    {
        $nft = NFT::with('user', 'originalCreator', 'collection', 'nftCategory', 'bids.user', 'transfers.fromUser', 'transfers.toUser')
            ->findOrFail($id);

        $nft->increment('views_count');

        $title = $nft->name;
        $isLiked = Auth::check() ? $nft->isLikedBy(Auth::id()) : false;
        $relatedNfts = NFT::available()
            ->where('id', '!=', $nft->id)
            ->where(function ($q) use ($nft) {
                $q->where('category_id', $nft->category_id)
                  ->orWhere('collection_id', $nft->collection_id);
            })
            ->limit(4)
            ->get();

        return view('user.nfts.details', compact('nft', 'title', 'isLiked', 'relatedNfts'));
    }

    public function create()
    {
        $title = 'Mint NFT';
        $categories = NftCategory::active()->get();
        $collections = NftCollection::active()->get();
        return view('user.nfts.create', compact('title', 'categories', 'collections'));
    }

    public function store(Request $request)
    {
        $settings = Settings::find(1);
        $user = Auth::user();
        $gasFee = $settings->gasfee ?? 0;

        $coinGecko = new CoinGeckoService();
        $ethPrice = $coinGecko->getCryptoPrice('ethereum');

        if (!$ethPrice) {
            return back()->with('message', 'Failed to fetch Ethereum price. Please try again.');
        }

        $gasCostUsd = $gasFee * $ethPrice;

        if ($user->account_bal < $gasCostUsd) {
            return back()->with('message', 'Insufficient balance to mint NFT. Gas fee: $' . number_format($gasCostUsd, 2));
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'category_id'   => 'required|exists:nft_categories,id',
            'collection_id' => 'nullable|exists:nft_collections,id',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'properties'    => 'nullable|json',
        ]);

        $user->account_bal -= $gasCostUsd;
        $user->save();

        $imagePath = $request->file('image')->store('nfts', 'public');
        $category = NftCategory::find($request->category_id);

        $nft = NFT::create([
            'user_id'             => $user->id,
            'original_creator_id' => $user->id,
            'collection_id'       => $request->collection_id,
            'category_id'         => $request->category_id,
            'token_id'            => NFT::generateTokenId(),
            'name'                => $request->name,
            'description'         => $request->description,
            'price'               => $request->price,
            'category'            => $category->name ?? 'Uncategorized',
            'blockchain'          => 'Ethereum',
            'royalty_percent'      => $request->royalty_percent ?? 0,
            'image'               => $imagePath,
            'properties'          => $request->properties ? json_decode($request->properties, true) : null,
            'status'              => 'available',
            'is_approved'         => ($settings->nft_auto_approve ?? false) ? true : false,
            'minted_at'           => now(),
        ]);

        NftTransfer::create([
            'nft_id'       => $nft->id,
            'from_user_id' => null,
            'to_user_id'   => $user->id,
            'price'        => 0,
            'type'         => 'mint',
        ]);

        Tp_Transaction::create([
            'user'   => $user->id,
            'plan'   => 'NFT Minting - ' . $nft->name,
            'amount' => $request->price,
            'type'   => 'NFT',
        ]);

        Tp_Transaction::create([
            'user'   => $user->id,
            'plan'   => 'NFT Gas Fee',
            'amount' => $gasCostUsd,
            'type'   => 'NFT Gas fee',
        ]);

        if ($nft->collection_id) {
            $nft->collection->recalcStats();
        }

        \App\Services\NotificationService::notifyUser($user, 'nft', 'NFT Minted Successfully', 'Your NFT "' . $nft->name . '" has been minted and is now live.', url('dashboard/nfts/my'));
        \App\Services\NotificationService::notifyAdmin('nft', 'New NFT Minted', $user->name . ' minted a new NFT: "' . $nft->name . '" priced at $' . number_format($nft->price, 2) . '.', url('admin/dashboard/nfts'));

        return redirect()->route('user.nfts.my')->with('success', 'NFT minted successfully!');
    }

    public function myNFTs(Request $request)
    {
        $user = Auth::user();
        $title = 'My NFTs';

        $tab = $request->get('tab', 'owned');

        switch ($tab) {
            case 'created':
                $nfts = NFT::where('original_creator_id', $user->id)->latest()->paginate(12);
                break;
            case 'favorites':
                $nftIds = NftLike::where('user_id', $user->id)->pluck('nft_id');
                $nfts = NFT::whereIn('id', $nftIds)->latest()->paginate(12);
                break;
            default:
                $nfts = NFT::where('user_id', $user->id)->latest()->paginate(12);
                break;
        }

        $stats = [
            'owned'    => NFT::where('user_id', $user->id)->count(),
            'created'  => NFT::where('original_creator_id', $user->id)->count(),
            'favorites'=> NftLike::where('user_id', $user->id)->count(),
            'total_value' => NFT::where('user_id', $user->id)->sum('price'),
        ];

        return view('user.nfts.my', compact('nfts', 'title', 'tab', 'stats'));
    }

    public function sellNFT(NFT $nft)
    {
        $user = Auth::user();

        if ($nft->user_id !== $user->id) {
            return back()->with('message', 'You can only sell your own NFTs.');
        }

        $nft->status = 'available';
        $nft->save();

        return back()->with('success', 'Your NFT is now listed for sale.');
    }

    public function toggleLike(NFT $nft)
    {
        $userId = Auth::id();
        $like = NftLike::where('user_id', $userId)->where('nft_id', $nft->id)->first();

        if ($like) {
            $like->delete();
            $nft->decrement('likes_count');
            return back()->with('success', 'Removed from favorites.');
        }

        NftLike::create(['user_id' => $userId, 'nft_id' => $nft->id]);
        $nft->increment('likes_count');

        return back()->with('success', 'Added to favorites.');
    }
}
