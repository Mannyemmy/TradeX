<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradingAsset;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TradingAssetController extends Controller
{
    /**
     * Display all trading assets organized by asset class.
     */
    public function index()
    {
        $assets = TradingAsset::orderBy('asset_class')->orderBy('name')->get();
        $grouped = $assets->groupBy('asset_class');

        return view('admin.trading-assets.index', [
            'title' => 'Manage Trading Assets',
            'grouped' => $grouped,
            'assetClasses' => ['crypto', 'forex', 'stock', 'etf', 'index'],
        ]);
    }

    /**
     * Toggle asset active/inactive status.
     */
    public function toggleActive($id)
    {
        $asset = TradingAsset::findOrFail($id);
        $asset->update(['is_active' => !$asset->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $asset->is_active,
        ]);
    }

    /**
     * Refresh all asset prices by running both price commands.
     */
    public function refreshPrices()
    {
        Artisan::call('prices:crypto');
        $cryptoOutput = Artisan::output();

        Artisan::call('prices:market');
        $marketOutput = Artisan::output();

        return redirect()->back()->with('success', 'Prices refreshed. ' . trim($cryptoOutput) . ' ' . trim($marketOutput));
    }

    /**
     * Store a manually-added custom asset.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'asset_class' => 'required|in:crypto,forex,stock,etf,index',
            'logo_url' => 'nullable|url|max:500',
            'price' => 'nullable|numeric|min:0',
        ]);

        TradingAsset::create([
            'name' => $request->name,
            'symbol' => strtoupper($request->symbol),
            'asset_class' => $request->asset_class,
            'logo_url' => $request->logo_url,
            'price' => $request->price ?? 0,
            'data_source' => 'manual',
            'external_id' => 'manual_' . strtolower($request->symbol) . '_' . time(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Custom asset added successfully.');
    }

    /**
     * Update asset price manually (for manual data_source assets).
     */
    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $asset = TradingAsset::findOrFail($id);
        $asset->update(['price' => $request->price]);

        return redirect()->back()->with('success', 'Price updated for ' . $asset->symbol . '.');
    }

    /**
     * Show the edit form for a trading asset.
     */
    public function edit($id)
    {
        $asset = TradingAsset::findOrFail($id);

        return view('admin.trading-assets.edit', [
            'title' => 'Edit Asset: ' . $asset->symbol,
            'asset' => $asset,
            'assetClasses' => ['crypto', 'forex', 'stock', 'etf', 'index'],
        ]);
    }

    /**
     * Update a trading asset.
     */
    public function update(Request $request, $id)
    {
        $asset = TradingAsset::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'asset_class' => 'required|in:crypto,forex,stock,etf,index',
            'price' => 'required|numeric|min:0',
            'price_change_24h' => 'nullable|numeric',
            'price_change_pct_24h' => 'nullable|numeric',
            'high_24h' => 'nullable|numeric|min:0',
            'low_24h' => 'nullable|numeric|min:0',
            'logo_url' => 'nullable|url|max:500',
            'is_active' => 'nullable',
        ]);

        $asset->update([
            'name' => $request->name,
            'symbol' => strtoupper($request->symbol),
            'asset_class' => $request->asset_class,
            'price' => $request->price,
            'price_change_24h' => $request->price_change_24h,
            'price_change_pct_24h' => $request->price_change_pct_24h,
            'high_24h' => $request->high_24h,
            'low_24h' => $request->low_24h,
            'logo_url' => $request->logo_url,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.assets.index')->with('success', $asset->symbol . ' updated successfully.');
    }

    /**
     * Delete a trading asset.
     */
    public function destroy($id)
    {
        $asset = TradingAsset::findOrFail($id);

        // Block delete if asset has unsettled trades
        $activeTrades = Trade::where('trading_asset_id', $asset->id)
            ->whereNull('result')
            ->count();

        if ($activeTrades > 0) {
            return redirect()->back()->with('message', 'Cannot delete ' . $asset->symbol . ' — it has ' . $activeTrades . ' unsettled trade(s).');
        }

        $symbol = $asset->symbol;
        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success', $symbol . ' deleted successfully.');
    }
}
