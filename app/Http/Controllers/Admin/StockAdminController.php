<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradingAsset;
use App\Models\StockPosition;
use App\Models\StockTrade;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockAdminController extends Controller
{
    public function index()
    {
        $settings = Settings::find(1);

        $stocks = TradingAsset::ofClass('stock')->orderBy('name')->get();

        // Aggregate stats per stock
        $positionStats = StockPosition::select('trading_asset_id')
            ->selectRaw('COUNT(*) as holders')
            ->selectRaw('SUM(shares) as total_shares')
            ->selectRaw('SUM(total_invested) as total_invested')
            ->selectRaw('CONCAT(COUNT(*), "|", IFNULL(SUM(shares),0), "|", IFNULL(SUM(total_invested),0)) as stats_str')
            ->groupBy('trading_asset_id')
            ->pluck('stats_str', 'trading_asset_id')
            ->toArray();

        return view('admin.stocks.index')->with([
            'title' => 'Stock Shares Management',
            'settings' => $settings,
            'stocks' => $stocks,
            'positionStats' => $positionStats,
        ]);
    }

    public function trades(Request $request)
    {
        $settings = Settings::find(1);

        $query = StockTrade::with(['user', 'asset'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->type, ['buy', 'sell'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $trades = $query->paginate(20);

        return view('admin.stocks.trades')->with([
            'title' => 'All Stock Trades',
            'settings' => $settings,
            'trades' => $trades,
            'filter' => $request->type ?? 'all',
        ]);
    }

    public function userPositions($userId)
    {
        $settings = Settings::find(1);
        $user = User::findOrFail($userId);

        $positions = StockPosition::where('user_id', $userId)
            ->with('asset')
            ->get();

        $totalInvested = $positions->sum('total_invested');
        $totalCurrentValue = $positions->sum(function ($p) {
            return $p->current_value;
        });

        return view('admin.stocks.positions')->with([
            'title' => 'Stock Positions — ' . $user->name,
            'settings' => $settings,
            'user' => $user,
            'positions' => $positions,
            'totalInvested' => round($totalInvested, 2),
            'totalCurrentValue' => round($totalCurrentValue, 2),
            'totalPnl' => round($totalCurrentValue - $totalInvested, 2),
        ]);
    }

    public function editPosition($id)
    {
        $settings = Settings::find(1);
        $position = StockPosition::with(['user', 'asset'])->findOrFail($id);

        return view('admin.stocks.edit-position')->with([
            'title' => 'Edit Position — ' . $position->asset->symbol,
            'settings' => $settings,
            'position' => $position,
        ]);
    }

    public function updatePosition(Request $request, $id)
    {
        $request->validate([
            'shares' => 'required|numeric|min:0',
            'avg_buy_price' => 'required|numeric|min:0',
            'total_invested' => 'required|numeric|min:0',
        ]);

        $position = StockPosition::findOrFail($id);

        $oldShares = $position->shares;
        $position->shares = $request->shares;
        $position->avg_buy_price = $request->avg_buy_price;
        $position->total_invested = $request->total_invested;
        $position->save();

        // Audit log
        Tp_Transaction::create([
            'user' => $position->user_id,
            'plan' => 'STOCK ADMIN: ' . ($position->asset->symbol ?? 'N/A'),
            'amount' => $request->total_invested,
            'type' => 'STOCK_ADMIN_ADJUST',
        ]);

        return redirect()->route('admin.stocks.user-positions', $position->user_id)
            ->with('success', 'Position updated. Shares: ' . number_format($oldShares, 4) . ' → ' . number_format($request->shares, 4));
    }

    public function createPosition(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trading_asset_id' => 'required|exists:trading_assets,id',
            'shares' => 'required|numeric|min:0.00000001',
            'avg_buy_price' => 'required|numeric|min:0.01',
        ]);

        $asset = TradingAsset::where('id', $request->trading_asset_id)
            ->where('asset_class', 'stock')
            ->firstOrFail();

        $totalInvested = round($request->shares * $request->avg_buy_price, 2);

        $existing = StockPosition::where('user_id', $request->user_id)
            ->where('trading_asset_id', $request->trading_asset_id)
            ->first();

        if ($existing) {
            $newTotalShares = $existing->shares + $request->shares;
            $newTotalInvested = $existing->total_invested + $totalInvested;
            $existing->shares = $newTotalShares;
            $existing->avg_buy_price = round($newTotalInvested / $newTotalShares, 8);
            $existing->total_invested = round($newTotalInvested, 2);
            $existing->save();
        } else {
            StockPosition::create([
                'user_id' => $request->user_id,
                'trading_asset_id' => $request->trading_asset_id,
                'shares' => $request->shares,
                'avg_buy_price' => $request->avg_buy_price,
                'total_invested' => $totalInvested,
            ]);
        }

        // Audit log
        Tp_Transaction::create([
            'user' => $request->user_id,
            'plan' => 'STOCK ADMIN CREATE: ' . $asset->symbol,
            'amount' => $totalInvested,
            'type' => 'STOCK_ADMIN_ADJUST',
        ]);

        return redirect()->route('admin.stocks.user-positions', $request->user_id)
            ->with('success', 'Position created: ' . number_format($request->shares, 4) . ' shares of ' . $asset->symbol);
    }

    public function deletePosition($id)
    {
        $position = StockPosition::findOrFail($id);
        $userId = $position->user_id;
        $symbol = $position->asset->symbol ?? 'N/A';

        // Audit log
        Tp_Transaction::create([
            'user' => $userId,
            'plan' => 'STOCK ADMIN DELETE: ' . $symbol,
            'amount' => $position->total_invested,
            'type' => 'STOCK_ADMIN_ADJUST',
        ]);

        $position->delete();

        return redirect()->route('admin.stocks.user-positions', $userId)
            ->with('success', 'Position for ' . $symbol . ' deleted.');
    }

    // Edit stock trade (backdate + edit details)
    public function editTrade(int $id)
    {
        $trade = StockTrade::with(['user', 'asset'])->findOrFail($id);
        $title = 'Edit Stock Trade #' . $id;
        return view('admin.stocks.edit', compact('trade', 'title'));
    }

    public function updateTrade(Request $request, int $id)
    {
        $request->validate([
            'shares'          => 'required|numeric|min:0.0001',
            'price_per_share' => 'required|numeric|min:0',
            'total_amount'    => 'required|numeric|min:0',
            'status'          => 'required|in:completed,pending,cancelled',
            'created_at'      => 'nullable|date',
        ]);

        $trade = StockTrade::findOrFail($id);

        $data = [
            'shares'          => $request->shares,
            'price_per_share' => $request->price_per_share,
            'total_amount'    => $request->total_amount,
            'status'          => $request->status,
            'updated_at'      => now(),
        ];
        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }
        DB::table('stock_trades')->where('id', $trade->id)->update($data);

        return redirect()->route('admin.stocks.trades')->with('success', 'Stock trade updated successfully!');
    }
}
