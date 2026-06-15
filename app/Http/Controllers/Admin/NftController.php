<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NFT;
use App\Models\NftCategory;
use App\Models\NftCollection;
use App\Models\NftTransfer;
use App\Models\Bid;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NftController extends Controller
{
    // ── NFT Management ────────────────────────────────

    public function index(Request $request)
    {
        $query = NFT::with('user', 'nftCategory', 'collection');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('collection_id')) {
            $query->where('collection_id', $request->collection_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('token_id', 'like', "%{$search}%");
            });
        }

        $nfts = $query->latest()->paginate(20);
        $categories = NftCategory::active()->get();
        $collections = NftCollection::active()->get();
        $title = 'Manage NFTs';

        $stats = [
            'total'     => NFT::count(),
            'available' => NFT::where('status', 'available')->count(),
            'sold'      => NFT::where('status', 'sold')->count(),
            'pending'   => NFT::where('is_approved', false)->count(),
        ];

        return view('admin.nfts.index', compact('nfts', 'categories', 'collections', 'stats', 'title'));
    }

    public function create()
    {
        $title = 'Create New NFT';
        $categories = NftCategory::active()->get();
        $collections = NftCollection::active()->get();
        return view('admin.nfts.create', compact('title', 'categories', 'collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'category_id'   => 'required|exists:nft_categories,id',
            'collection_id' => 'nullable|exists:nft_collections,id',
            'blockchain'    => 'required|string|max:50',
            'royalty_percent'=> 'nullable|numeric|min:0|max:50',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'properties'    => 'nullable|json',
        ]);

        $imagePath = $request->file('image')->store('nfts', 'public');
        $settings = Settings::find(1);

        $nft = NFT::create([
            'user_id'             => $settings->admin_nft_user_id ?? Auth::guard('admin')->id(),
            'original_creator_id' => $settings->admin_nft_user_id ?? Auth::guard('admin')->id(),
            'collection_id'       => $request->collection_id,
            'category_id'         => $request->category_id,
            'token_id'            => NFT::generateTokenId(),
            'name'                => $request->name,
            'description'         => $request->description,
            'price'               => $request->price,
            'category'            => NftCategory::find($request->category_id)->name ?? 'Uncategorized',
            'blockchain'          => $request->blockchain,
            'royalty_percent'      => $request->royalty_percent ?? 0,
            'image'               => $imagePath,
            'properties'          => $request->properties ? json_decode($request->properties, true) : null,
            'status'              => 'available',
            'is_approved'         => true,
            'minted_at'           => now(),
        ]);

        NftTransfer::create([
            'nft_id'       => $nft->id,
            'from_user_id' => null,
            'to_user_id'   => $nft->user_id,
            'price'        => 0,
            'type'         => 'mint',
        ]);

        if ($nft->collection_id) {
            $nft->collection->recalcStats();
        }

        return redirect()->route('admin.nfts.index')->with('success', 'NFT created successfully!');
    }

    public function edit(NFT $nft)
    {
        $title = 'Edit NFT';
        $categories = NftCategory::active()->get();
        $collections = NftCollection::active()->get();
        return view('admin.nfts.edit', compact('nft', 'title', 'categories', 'collections'));
    }

    public function update(Request $request, NFT $nft)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'category_id'   => 'required|exists:nft_categories,id',
            'collection_id' => 'nullable|exists:nft_collections,id',
            'blockchain'    => 'required|string|max:50',
            'royalty_percent'=> 'nullable|numeric|min:0|max:50',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'properties'    => 'nullable|json',
            'created_at'    => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($nft->image);
            $nft->image = $request->file('image')->store('nfts', 'public');
        }

        $nft->update([
            'name'            => $request->name,
            'description'     => $request->description,
            'price'           => $request->price,
            'category_id'     => $request->category_id,
            'category'        => NftCategory::find($request->category_id)->name ?? $nft->category,
            'collection_id'   => $request->collection_id,
            'blockchain'      => $request->blockchain,
            'royalty_percent'  => $request->royalty_percent ?? 0,
            'properties'      => $request->properties ? json_decode($request->properties, true) : $nft->properties,
            'image'           => $nft->image,
        ]);

        if ($request->filled('created_at')) {
            DB::table('nfts')->where('id', $nft->id)
                ->update(['created_at' => Carbon::parse($request->created_at), 'updated_at' => $nft->updated_at]);
        }

        return redirect()->route('admin.nfts.index')->with('success', 'NFT updated successfully!');
    }

    public function destroy(NFT $nft)
    {
        Storage::disk('public')->delete($nft->image);
        $nft->delete();
        return redirect()->route('admin.nfts.index')->with('success', 'NFT deleted successfully!');
    }

    public function toggleFeatured(NFT $nft)
    {
        $nft->is_featured = !$nft->is_featured;
        $nft->save();
        return back()->with('success', $nft->is_featured ? 'NFT featured.' : 'NFT unfeatured.');
    }

    public function toggleApproval(NFT $nft)
    {
        $nft->is_approved = !$nft->is_approved;
        $nft->save();
        return back()->with('success', $nft->is_approved ? 'NFT approved.' : 'NFT unapproved.');
    }

    public function soldNFTs(Request $request)
    {
        $title = 'Sold NFTs';
        $soldNFTs = NFT::where('status', 'sold')->with('user', 'originalCreator')->latest()->paginate(20);
        return view('admin.nfts.sold', compact('soldNFTs', 'title'));
    }

    public function transfers(Request $request)
    {
        $title = 'Transfer History';
        $transfers = NftTransfer::with('nft', 'fromUser', 'toUser')->latest()->paginate(20);
        return view('admin.nfts.transfers', compact('transfers', 'title'));
    }

    // ── Category Management ───────────────────────────

    public function categories()
    {
        $title = 'NFT Categories';
        $categories = NftCategory::withCount('nfts')->orderBy('sort_order')->get();
        return view('admin.nfts.categories', compact('categories', 'title'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:nft_categories,name',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        NftCategory::create([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, NftCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:nft_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $category->update([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? $category->sort_order,
            'is_active'  => $request->has('is_active'),
        ]);

        return back()->with('success', 'Category updated.');
    }

    public function destroyCategory(NftCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ── Collection Management ─────────────────────────

    public function collections()
    {
        $title = 'NFT Collections';
        $collections = NftCollection::withCount('nfts')->with('category', 'creator')->latest()->get();
        $categories = NftCategory::active()->get();
        return view('admin.nfts.collections', compact('collections', 'categories', 'title'));
    }

    public function createCollection()
    {
        $title = 'Create Collection';
        $categories = NftCategory::active()->get();
        return view('admin.nfts.collection-form', compact('title', 'categories'));
    }

    public function storeCollection(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:nft_collections,name',
            'description'     => 'nullable|string',
            'category_id'     => 'nullable|exists:nft_categories,id',
            'royalty_percent'  => 'nullable|numeric|min:0|max:50',
            'banner_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'logo_image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'description'    => $request->description,
            'category_id'    => $request->category_id,
            'royalty_percent' => $request->royalty_percent ?? 0,
            'creator_id'     => null,
            'is_active'      => true,
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('nft-collections', 'public');
        }
        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $request->file('logo_image')->store('nft-collections', 'public');
        }

        NftCollection::create($data);

        return redirect()->route('admin.nft.collections.index')->with('success', 'Collection created.');
    }

    public function editCollection(NftCollection $collection)
    {
        $title = 'Edit Collection';
        $categories = NftCategory::active()->get();
        return view('admin.nfts.collection-form', compact('collection', 'title', 'categories'));
    }

    public function updateCollection(Request $request, NftCollection $collection)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:nft_collections,name,' . $collection->id,
            'description'     => 'nullable|string',
            'category_id'     => 'nullable|exists:nft_categories,id',
            'royalty_percent'  => 'nullable|numeric|min:0|max:50',
            'banner_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'logo_image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured'     => 'nullable|boolean',
            'is_active'       => 'nullable|boolean',
        ]);

        $data = [
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'description'    => $request->description,
            'category_id'    => $request->category_id,
            'royalty_percent' => $request->royalty_percent ?? $collection->royalty_percent,
            'is_featured'    => $request->has('is_featured'),
            'is_active'      => $request->has('is_active'),
        ];

        if ($request->hasFile('banner_image')) {
            Storage::disk('public')->delete($collection->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('nft-collections', 'public');
        }
        if ($request->hasFile('logo_image')) {
            Storage::disk('public')->delete($collection->logo_image);
            $data['logo_image'] = $request->file('logo_image')->store('nft-collections', 'public');
        }

        $collection->update($data);

        return redirect()->route('admin.nft.collections.index')->with('success', 'Collection updated.');
    }

    public function destroyCollection(NftCollection $collection)
    {
        Storage::disk('public')->delete($collection->banner_image);
        Storage::disk('public')->delete($collection->logo_image);
        $collection->delete();
        return redirect()->route('admin.nft.collections.index')->with('success', 'Collection deleted.');
    }

    public function toggleCollectionFeatured(NftCollection $collection)
    {
        $collection->is_featured = !$collection->is_featured;
        $collection->save();
        return back()->with('success', $collection->is_featured ? 'Collection featured.' : 'Collection unfeatured.');
    }
}

