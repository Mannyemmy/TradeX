<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TradingAsset;
use App\Models\StockPosition;
use App\Models\StockTrade;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class StockController extends Controller
{
    public function index()
    {
        $settings = Settings::find(1);
        $user = Auth::user();

        $stocks = TradingAsset::active()->ofClass('stock')->orderBy('name')->get();

        // Get user's existing positions keyed by trading_asset_id
        $userPositions = StockPosition::where('user_id', $user->id)
            ->pluck('shares', 'trading_asset_id');

        return view('user.stocks.index')->with([
            'title' => 'Stock Shares',
            'settings' => $settings,
            'stocks' => $stocks,
            'userPositions' => $userPositions,
        ]);
    }

    public function show($id)
    {
        $settings = Settings::find(1);
        $user = Auth::user();

        $asset = TradingAsset::where('id', $id)
            ->where('asset_class', 'stock')
            ->where('is_active', true)
            ->firstOrFail();

        $position = StockPosition::where('user_id', $user->id)
            ->where('trading_asset_id', $id)
            ->first();

        $recentTrades = StockTrade::where('user_id', $user->id)
            ->where('trading_asset_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('user.stocks.show')->with([
            'title' => $asset->symbol . ' — ' . $asset->name,
            'settings' => $settings,
            'asset' => $asset,
            'position' => $position,
            'recentTrades' => $recentTrades,
        ]);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'trading_asset_id' => 'required|exists:trading_assets,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::find(Auth::id());
        $asset = TradingAsset::where('id', $request->trading_asset_id)
            ->where('asset_class', 'stock')
            ->where('is_active', true)
            ->first();

        if (!$asset) {
            return redirect()->back()->with('message', 'Stock not found or not available.');
        }

        if ($asset->price <= 0) {
            return redirect()->back()->with('message', 'Stock price is unavailable. Please try again later.');
        }

        $amount = round(CurrencyHelper::toUsd($request->amount), 2);

        // Balance check
        $availableBal = $user->account_bal - ($user->frozen_bal ?? 0);
        if ($availableBal < $amount) {
            return redirect()->back()->with('message', 'Insufficient balance. You need ' . CurrencyHelper::formatForUser($amount) . '.');
        }

        $shares = $amount / $asset->price;

        DB::transaction(function () use ($user, $asset, $amount, $shares) {
            // Debit balance
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $amount,
            ]);

            // Upsert position with weighted average cost
            $existing = StockPosition::where('user_id', $user->id)
                ->where('trading_asset_id', $asset->id)
                ->first();

            if ($existing) {
                $newTotalShares = $existing->shares + $shares;
                $newTotalInvested = $existing->total_invested + $amount;
                $newAvgPrice = $newTotalInvested / $newTotalShares;

                $existing->shares = $newTotalShares;
                $existing->avg_buy_price = round($newAvgPrice, 8);
                $existing->total_invested = round($newTotalInvested, 2);
                $existing->save();
            } else {
                StockPosition::create([
                    'user_id' => $user->id,
                    'trading_asset_id' => $asset->id,
                    'shares' => $shares,
                    'avg_buy_price' => $asset->price,
                    'total_invested' => $amount,
                ]);
            }

            // Create trade record
            StockTrade::create([
                'user_id' => $user->id,
                'trading_asset_id' => $asset->id,
                'type' => 'buy',
                'shares' => $shares,
                'price_per_share' => $asset->price,
                'total_amount' => $amount,
                'status' => 'completed',
            ]);

            // Log to unified transaction ledger
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => 'STOCK: ' . $asset->symbol,
                'amount' => $amount,
                'type' => 'STOCK_BUY',
            ]);
        });

        NotificationService::notifyUser(
            $user,
            'stock_trade',
            'Stock Purchase Completed',
            'You purchased ' . number_format($shares, 4) . ' shares of ' . $asset->symbol . ' for $' . number_format($amount, 2) . '.',
            url('dashboard/stocks/' . $asset->id)
        );
        \App\Services\NotificationService::notifyAdmin('stock_trade', 'New Stock Purchase', $user->name . ' bought ' . number_format($shares, 4) . ' shares of ' . $asset->symbol . ' for $' . number_format($amount, 2) . '.', url('admin/dashboard/stocks'));

        return redirect()->route('user.stocks.show', $asset->id)
            ->with('success', 'Successfully purchased ' . number_format($shares, 4) . ' shares of ' . $asset->symbol . '.');
    }

    public function sell(Request $request)
    {
        $request->validate([
            'trading_asset_id' => 'required|exists:trading_assets,id',
            'shares' => 'required|numeric|min:0.00000001',
        ]);

        $user = User::find(Auth::id());
        $asset = TradingAsset::where('id', $request->trading_asset_id)
            ->where('asset_class', 'stock')
            ->where('is_active', true)
            ->first();

        if (!$asset) {
            return redirect()->back()->with('message', 'Stock not found or not available.');
        }

        if ($asset->price <= 0) {
            return redirect()->back()->with('message', 'Stock price is unavailable. Please try again later.');
        }

        $position = StockPosition::where('user_id', $user->id)
            ->where('trading_asset_id', $asset->id)
            ->first();

        if (!$position || $position->shares < $request->shares) {
            $available = $position ? number_format($position->shares, 8) : '0';
            return redirect()->back()->with('message', 'Insufficient shares. You hold ' . $available . ' shares.');
        }

        $sharesToSell = $request->shares;
        $proceeds = round($sharesToSell * $asset->price, 2);

        DB::transaction(function () use ($user, $asset, $position, $sharesToSell, $proceeds) {
            // Credit balance
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal + $proceeds,
            ]);

            // Update position
            $position->shares -= $sharesToSell;
            // Proportionally reduce total_invested
            $costPerShare = $position->total_invested / ($position->shares + $sharesToSell);
            $position->total_invested -= round($costPerShare * $sharesToSell, 2);

            if ($position->shares <= 0.00000001) {
                $position->delete();
            } else {
                $position->save();
            }

            // Create trade record
            StockTrade::create([
                'user_id' => $user->id,
                'trading_asset_id' => $asset->id,
                'type' => 'sell',
                'shares' => $sharesToSell,
                'price_per_share' => $asset->price,
                'total_amount' => $proceeds,
                'status' => 'completed',
            ]);

            // Log to unified transaction ledger
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => 'STOCK SELL: ' . $asset->symbol,
                'amount' => $proceeds,
                'type' => 'STOCK_SELL',
            ]);
        });

        NotificationService::notifyUser(
            $user,
            'stock_trade',
            'Stock Sale Completed',
            'You sold ' . number_format($sharesToSell, 4) . ' shares of ' . $asset->symbol . ' for $' . number_format($proceeds, 2) . '.',
            url('dashboard/stocks/portfolio')
        );
        \App\Services\NotificationService::notifyAdmin('stock_trade', 'New Stock Sale', $user->name . ' sold ' . number_format($sharesToSell, 4) . ' shares of ' . $asset->symbol . ' for $' . number_format($proceeds, 2) . '.', url('admin/dashboard/stocks'));

        return redirect()->route('user.stocks.portfolio')
            ->with('success', 'Sold ' . number_format($sharesToSell, 4) . ' shares of ' . $asset->symbol . ' for $' . number_format($proceeds, 2) . '.');
    }

    public function portfolio()
    {
        $settings = Settings::find(1);
        $user = Auth::user();

        $positions = StockPosition::where('user_id', $user->id)
            ->with('asset')
            ->get();

        $totalInvested = $positions->sum('total_invested');
        $totalCurrentValue = $positions->sum(function ($p) {
            return $p->current_value;
        });

        return view('user.stocks.portfolio')->with([
            'title' => 'Stock Portfolio',
            'settings' => $settings,
            'positions' => $positions,
            'totalInvested' => round($totalInvested, 2),
            'totalCurrentValue' => round($totalCurrentValue, 2),
            'totalPnl' => round($totalCurrentValue - $totalInvested, 2),
        ]);
    }

    public function history(Request $request)
    {
        $settings = Settings::find(1);
        $user = Auth::user();

        $query = StockTrade::where('user_id', $user->id)
            ->with('asset')
            ->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->type, ['buy', 'sell'])) {
            $query->where('type', $request->type);
        }

        $trades = $query->paginate(20);

        return view('user.stocks.history')->with([
            'title' => 'Stock Trade History',
            'settings' => $settings,
            'trades' => $trades,
            'filter' => $request->type ?? 'all',
        ]);
    }
}
