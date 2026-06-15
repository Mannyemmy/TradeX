<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\TradingAsset;
use App\Models\Settings;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\User_plans;
use App\Models\CopyPosition;
use App\Models\PreIpoHolding;
use App\Models\StockPosition;
use App\Models\NFT;
use App\Models\Loan;
use App\Models\BotSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\TradeExecutedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\TradeClosedNotification;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class TradeController extends Controller
{
    /** Allowed leverage tiers */
    private const LEVERAGE_OPTIONS = [2, 5, 10, 25, 50, 100];

    /** Allowed duration presets (minutes) */
    private const DURATION_OPTIONS = [1, 5, 15, 30, 60, 240, 1440];

    /**
     * Display the trading page.
     */
    public function trade()
    {
        $settings = Settings::where('id', 1)->first();
        $userId = Auth::id();

        $assets = TradingAsset::active()->orderBy('asset_class')->orderBy('name')->get();

        $tradesopen = Trade::where('user_id', $userId)
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $tradesclosed = Trade::where('user_id', $userId)
            ->where('status', 'closed')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.trades.trade')->with([
            'title' => 'Trade Center',
            'settings' => $settings,
            'assets' => $assets,
            'tradesopen' => $tradesopen,
            'tradesclosed' => $tradesclosed,
        ]);
    }

    /**
     * Place a new trade (binary or spot, live or demo).
     */
    public function store(Request $request)
    {
        $rules = [
            'trading_asset_id' => 'required|exists:trading_assets,id',
            'leverage' => 'required|integer|in:' . implode(',', self::LEVERAGE_OPTIONS),
            'amount' => 'required|numeric|min:1',
            'action' => 'required|in:buy,sell',
            'trade_type' => 'required|in:binary,spot',
            'is_demo' => 'required|boolean',
        ];

        // Duration only required for binary trades
        if ($request->input('trade_type') === 'binary') {
            $rules['duration'] = 'required|integer|in:' . implode(',', self::DURATION_OPTIONS);
        }

        $request->validate($rules);

        $user = User::where('id', Auth::id())->first();
        $asset = TradingAsset::findOrFail($request->trading_asset_id);
        $amount = CurrencyHelper::toUsd($request->amount);
        $isDemo = (bool) $request->is_demo;
        $balanceField = $isDemo ? 'demo_bal' : 'account_bal';

        // Check balance
        if ($isDemo) {
            if ($user->demo_bal < $amount) {
                return redirect()->route('trade')
                    ->with('message', 'Insufficient demo balance. Your demo balance is $' . number_format($user->demo_bal, 2) . '.');
            }
        } else {
            if ($user->available_bal < $amount) {
                return redirect()->route('deposits')
                    ->with('message', 'Your available balance is insufficient to place this trade. Please make a deposit.');
            }
        }

        $tradeType = $request->trade_type;
        $duration = $tradeType === 'binary' ? (int) $request->duration : 0;
        $expiresAt = $tradeType === 'binary' ? now()->addMinutes($duration) : null;

        DB::transaction(function () use ($user, $asset, $request, $amount, $isDemo, $balanceField, $tradeType, $duration, $expiresAt) {
            // Debit balance
            User::where('id', $user->id)->update([
                $balanceField => $user->$balanceField - $amount,
            ]);

            $trade = Trade::create([
                'user_id' => $user->id,
                'trade_type' => $tradeType,
                'is_demo' => $isDemo,
                'asset_type' => $asset->asset_class,
                'asset_name' => $asset->symbol . ' — ' . $asset->name,
                'trading_asset_id' => $asset->id,
                'leverage' => $request->leverage,
                'duration' => $duration,
                'amount' => $amount,
                'action' => $request->action,
                'entry_price' => $asset->price,
                'expires_at' => $expiresAt,
                'status' => 'open',
            ]);

            $settings = Settings::where('id', 1)->first();
            if ($settings->trade_message == 'on' && !$isDemo) {
                try {
                    Mail::to($user->email)->send(new TradeExecutedMail($trade, $user, 'Trade Executed'));
                } catch (\Exception $e) {
                    Log::error('Trade executed email failed: ' . $e->getMessage());
                }
            }

            if (!$isDemo) {
                NotificationService::notifyUser($user, 'trade', 'Trade Opened', 'You opened a ' . $request->action . ' trade on ' . $trade->asset_name . ' for $' . number_format($amount, 2) . '.', route('user.trades.history'));
                \App\Services\NotificationService::notifyAdmin('trade', 'New Trade Opened', $user->name . ' opened a ' . $request->action . ' trade on ' . $trade->asset_name . ' for $' . number_format($amount, 2) . '.', url('admin/dashboard/trades'));
            }
        });

        return redirect()->route('user.trades.history')
            ->with('success', 'Trade executed successfully. You can review the details in your trade history.');
    }

    /**
     * Show trade history with filters and summary stats.
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        $title = 'Trade History';

        // Base query
        $query = Trade::where('user_id', $userId)->with('tradingAsset');

        // Apply filters
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('trade_type', $request->type);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('demo') && $request->demo !== 'all') {
            $query->where('is_demo', $request->demo === 'demo');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_name', 'like', "%{$search}%")
                  ->orWhereHas('tradingAsset', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('symbol', 'like', "%{$search}%");
                  });
            });
        }

        $trades = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Summary stats (unfiltered totals for badge counts)
        $allTrades = Trade::where('user_id', $userId);
        $stats = [
            'total' => (clone $allTrades)->count(),
            'open_count' => (clone $allTrades)->where('status', 'open')->count(),
            'closed_count' => (clone $allTrades)->where('status', 'closed')->count(),
            'binary_count' => (clone $allTrades)->where('trade_type', 'binary')->count(),
            'spot_count' => (clone $allTrades)->where('trade_type', 'spot')->count(),
            'all_count' => (clone $allTrades)->count(),
            'wins' => Trade::where('user_id', $userId)->where('result', 'WIN')->where('is_demo', false)->count(),
            'losses' => Trade::where('user_id', $userId)->where('result', 'LOSS')->where('is_demo', false)->count(),
            'net_pl' => Trade::where('user_id', $userId)->where('status', 'closed')->where('is_demo', false)->sum('profit_loss'),
        ];

        if ($request->ajax()) {
            return view('user.trades.partials.history_table', compact('trades', 'title', 'stats'))->render();
        }

        $settings = Settings::find(1);

        return view('user.trades.history')->with([
            'title' => $title,
            'trades' => $trades,
            'stats' => $stats,
            'settings' => $settings,
        ]);
    }

    /**
     * Show individual trade details.
     */
    public function show($id)
    {
        $trade = Trade::where('user_id', Auth::id())
            ->with('tradingAsset')
            ->findOrFail($id);

        $settings = Settings::find(1);

        return view('user.trades.show')->with([
            'title' => 'Trade #' . $trade->id,
            'trade' => $trade,
            'settings' => $settings,
        ]);
    }

    /**
     * Portfolio overview — open positions grouped by asset, allocation, recent activity.
     */
    public function portfolio()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $settings = Settings::find(1);
        $mod = $settings->modules ?? [];

        // Determine which tabs to show
        $tabs = ['overview' => true];

        // ─── Trading ───────────────────────────────────────────
        $openTrades = collect();
        $positionsByAsset = collect();
        $totalTradeInvested = 0;
        $totalUnrealizedPL = 0;
        $realizedPL = 0;
        $tradeAllocation = collect();
        $recentClosed = collect();

        if (!empty($mod['trading'])) {
            $tabs['trading'] = true;

            $openTrades = Trade::where('user_id', $userId)
                ->where('status', 'open')
                ->where('is_demo', false)
                ->with('tradingAsset')
                ->orderBy('created_at', 'desc')
                ->get();

            $positionsByAsset = $openTrades->groupBy('trading_asset_id');

            foreach ($openTrades as $trade) {
                $totalTradeInvested += $trade->amount;
                if ($trade->tradingAsset && $trade->entry_price > 0) {
                    $currentPrice = $trade->tradingAsset->price;
                    $direction = $trade->action === 'buy' ? 1 : -1;
                    $priceChange = ($currentPrice - $trade->entry_price) / $trade->entry_price;
                    $trade->unrealized_pl = round($trade->amount * $priceChange * $direction, 2);
                } else {
                    $trade->unrealized_pl = 0;
                }
                $totalUnrealizedPL += $trade->unrealized_pl;
            }

            $realizedPL = Trade::where('user_id', $userId)
                ->where('status', 'closed')
                ->where('is_demo', false)
                ->sum('profit_loss');

            $tradeAllocation = $openTrades->groupBy(function ($trade) {
                return $trade->tradingAsset->asset_class ?? 'unknown';
            })->map(function ($trades) {
                return $trades->sum('amount');
            });

            $recentClosed = Trade::where('user_id', $userId)
                ->where('status', 'closed')
                ->with('tradingAsset')
                ->orderBy('settled_at', 'desc')
                ->limit(5)
                ->get();
        }

        // ─── Investments ───────────────────────────────────────
        $activePlans = collect();
        $totalPlanInvested = 0;
        $totalPlanProfit = 0;

        if (!empty($mod['investment'])) {
            $tabs['investments'] = true;

            $activePlans = User_plans::where('user', $userId)
                ->where('active', 'yes')
                ->get();

            // Eager-load the plan details
            foreach ($activePlans as $plan) {
                $plan->planDetails = $plan->dplan;
            }

            $totalPlanInvested = $activePlans->sum('amount');
            $totalPlanProfit = $activePlans->sum('profit_earned');
        }

        // ─── Copy Trading ──────────────────────────────────────
        $activeCopyPositions = collect();
        $totalCopyInvested = 0;
        $totalCopyProfit = 0;

        if (!empty($mod['copy_trading'])) {
            $tabs['copy_trading'] = true;

            $activeCopyPositions = CopyPosition::where('user_id', $userId)
                ->where('status', 'active')
                ->with('expert')
                ->orderBy('started_at', 'desc')
                ->get();

            $totalCopyInvested = $activeCopyPositions->sum('invested_amount');
            $totalCopyProfit = $activeCopyPositions->sum('accumulated_profit');
        }

        // ─── Bot Trading ───────────────────────────────────────
        $activeBotSubscriptions = collect();
        $totalBotInvested = 0;
        $totalBotProfit = 0;

        if (!empty($mod['bot_trading'])) {
            $tabs['bot_trading'] = true;

            $activeBotSubscriptions = BotSubscription::where('user_id', $userId)
                ->where('status', 'active')
                ->with('tradingBot')
                ->orderBy('started_at', 'desc')
                ->get();

            $totalBotInvested = $activeBotSubscriptions->sum('invested_amount');
            $totalBotProfit = $activeBotSubscriptions->sum('accumulated_profit');
        }

        // ─── Pre-IPO ───────────────────────────────────────────
        $preIpoHoldings = collect();
        $totalPreIpoCost = 0;
        $totalPreIpoValue = 0;

        if (!empty($mod['pre_ipo'])) {
            $tabs['pre_ipo'] = true;

            $preIpoHoldings = PreIpoHolding::where('user_id', $userId)
                ->where('status', 'held')
                ->with('company')
                ->get();

            $totalPreIpoCost = $preIpoHoldings->sum('total_cost');
            $totalPreIpoValue = $preIpoHoldings->sum('current_value');
        }

        // ─── Stock Shares ──────────────────────────────────────
        $stockPositions = collect();
        $totalStockInvested = 0;
        $totalStockValue = 0;

        if (!empty($mod['stocktrading'])) {
            $tabs['stocktrading'] = true;

            $stockPositions = StockPosition::where('user_id', $userId)
                ->with('asset')
                ->get();

            $totalStockInvested = $stockPositions->sum('total_invested');
            $totalStockValue = $stockPositions->sum(function ($p) {
                return $p->current_value;
            });
        }

        // ─── NFTs ──────────────────────────────────────────────
        $ownedNfts = collect();
        $totalNftValue = 0;

        if (!empty($mod['nft'])) {
            $tabs['nfts'] = true;

            $ownedNfts = NFT::where('user_id', $userId)
                ->whereIn('status', ['listed', 'unlisted'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalNftValue = $ownedNfts->sum('price');
        }

        // ─── Loans ─────────────────────────────────────────────
        $activeLoans = collect();
        $totalLoanOutstanding = 0;

        if (!empty($mod['loan'])) {
            $tabs['loans'] = true;

            $activeLoans = Loan::where('user_id', $userId)
                ->whereIn('status', ['active', 'repaying'])
                ->orderBy('next_payment_date', 'asc')
                ->get();

            $totalLoanOutstanding = $activeLoans->sum(function ($loan) {
                return $loan->total_repayable - $loan->total_repaid;
            });
        }

        // ─── Overview Aggregates ───────────────────────────────
        $totalInvested = $totalTradeInvested + $totalPlanInvested + $totalCopyInvested + $totalBotInvested + $totalPreIpoCost + $totalStockInvested;
        $totalPL = $totalUnrealizedPL + $realizedPL + $totalPlanProfit + $totalCopyProfit + $totalBotProfit + ($totalPreIpoValue - $totalPreIpoCost) + ($totalStockValue - $totalStockInvested);
        $netWorth = ($user->account_bal ?? 0)
            + $totalUnrealizedPL
            + $totalPlanProfit
            + $totalCopyProfit
            + $totalBotProfit
            + $totalPreIpoValue
            + $totalStockValue
            + $totalNftValue
            - $totalLoanOutstanding;

        // Category allocation for overview
        $categoryAllocation = collect();
        if ($totalTradeInvested > 0) $categoryAllocation['Trading'] = $totalTradeInvested;
        if ($totalPlanInvested > 0) $categoryAllocation['Investments'] = $totalPlanInvested;
        if ($totalCopyInvested > 0) $categoryAllocation['Copy Trading'] = $totalCopyInvested;
        if ($totalBotInvested > 0) $categoryAllocation['Bot Trading'] = $totalBotInvested;
        if ($totalPreIpoCost > 0) $categoryAllocation['Pre-IPO'] = $totalPreIpoCost;
        if ($totalStockInvested > 0) $categoryAllocation['Stocks'] = $totalStockInvested;
        if ($totalNftValue > 0) $categoryAllocation['NFTs'] = $totalNftValue;

        return view('user.trades.portfolio')->with([
            'title' => 'Portfolio',
            'settings' => $settings,
            'user' => $user,
            'tabs' => $tabs,
            // Overview
            'netWorth' => round($netWorth, 2),
            'totalInvested' => round($totalInvested, 2),
            'totalPL' => round($totalPL, 2),
            'categoryAllocation' => $categoryAllocation,
            'totalLoanOutstanding' => round($totalLoanOutstanding, 2),
            // Trading
            'openTrades' => $openTrades,
            'positionsByAsset' => $positionsByAsset,
            'totalTradeInvested' => round($totalTradeInvested, 2),
            'totalUnrealizedPL' => round($totalUnrealizedPL, 2),
            'realizedPL' => $realizedPL,
            'tradeAllocation' => $tradeAllocation,
            'recentClosed' => $recentClosed,
            // Investments
            'activePlans' => $activePlans,
            'totalPlanInvested' => round($totalPlanInvested, 2),
            'totalPlanProfit' => round($totalPlanProfit, 2),
            // Copy Trading
            'activeCopyPositions' => $activeCopyPositions,
            'totalCopyInvested' => round($totalCopyInvested, 2),
            'totalCopyProfit' => round($totalCopyProfit, 2),
            // Bot Trading
            'activeBotSubscriptions' => $activeBotSubscriptions,
            'totalBotInvested' => round($totalBotInvested, 2),
            'totalBotProfit' => round($totalBotProfit, 2),
            // Pre-IPO
            'preIpoHoldings' => $preIpoHoldings,
            'totalPreIpoCost' => round($totalPreIpoCost, 2),
            'totalPreIpoValue' => round($totalPreIpoValue, 2),
            // Stock Shares
            'stockPositions' => $stockPositions,
            'totalStockInvested' => round($totalStockInvested, 2),
            'totalStockValue' => round($totalStockValue, 2),
            // NFTs
            'ownedNfts' => $ownedNfts,
            'totalNftValue' => round($totalNftValue, 2),
            // Loans
            'activeLoans' => $activeLoans,
            'totalLoanOutstanding' => round($totalLoanOutstanding, 2),
        ]);
    }

    /**
     * Open positions manager — dedicated management of all open trades.
     */
    public function positions(Request $request)
    {
        $userId = Auth::id();
        $settings = Settings::find(1);

        $query = Trade::where('user_id', $userId)
            ->where('status', 'open')
            ->with('tradingAsset')
            ->orderBy('created_at', 'desc');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('trade_type', $request->type);
        }
        if ($request->filled('demo') && $request->demo !== 'all') {
            $query->where('is_demo', $request->demo === 'demo');
        }

        $openTrades = $query->get();

        // Calculate unrealized P/L per trade
        foreach ($openTrades as $trade) {
            if ($trade->tradingAsset && $trade->entry_price > 0) {
                $currentPrice = $trade->tradingAsset->price;
                $direction = $trade->action === 'buy' ? 1 : -1;
                $priceChange = ($currentPrice - $trade->entry_price) / $trade->entry_price;
                $trade->unrealized_pl = round($trade->amount * $priceChange * $direction, 2);
            } else {
                $trade->unrealized_pl = 0;
            }
        }

        $spotTrades = $openTrades->where('trade_type', 'spot');
        $binaryTrades = $openTrades->where('trade_type', 'binary');

        $stats = [
            'total' => Trade::where('user_id', $userId)->where('status', 'open')->count(),
            'binary' => Trade::where('user_id', $userId)->where('status', 'open')->where('trade_type', 'binary')->count(),
            'spot' => Trade::where('user_id', $userId)->where('status', 'open')->where('trade_type', 'spot')->count(),
            'capital_at_risk' => Trade::where('user_id', $userId)->where('status', 'open')->sum('amount'),
        ];

        return view('user.trades.positions')->with([
            'title' => 'Open Positions',
            'settings' => $settings,
            'openTrades' => $openTrades,
            'spotTrades' => $spotTrades,
            'binaryTrades' => $binaryTrades,
            'stats' => $stats,
        ]);
    }

    /**
     * Markets / Asset browser — browse all available trading assets.
     */
    public function markets(Request $request)
    {
        $settings = Settings::find(1);

        $query = TradingAsset::active();

        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('asset_class', $request->class);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $assets = $query->orderBy('asset_class')->orderBy('market_cap', 'desc')->orderBy('name')->get();

        // Stats
        $allActive = TradingAsset::active()->get();
        $topGainer = $allActive->sortByDesc('price_change_pct_24h')->first();
        $topLoser = $allActive->sortBy('price_change_pct_24h')->first();

        // Counts by class
        $classCounts = $allActive->groupBy('asset_class')->map->count();

        return view('user.markets')->with([
            'title' => 'Markets',
            'settings' => $settings,
            'assets' => $assets,
            'topGainer' => $topGainer,
            'topLoser' => $topLoser,
            'classCounts' => $classCounts,
            'totalAssets' => $allActive->count(),
        ]);
    }

    /**
     * Trade analytics — performance stats, breakdowns, P/L timeline.
     */
    public function analytics()
    {
        $userId = Auth::id();
        $settings = Settings::find(1);

        $allTrades = Trade::where('user_id', $userId)->where('is_demo', false);
        $closedTrades = Trade::where('user_id', $userId)->where('status', 'closed')->where('is_demo', false);

        $totalCount = $allTrades->count();
        $openCount = Trade::where('user_id', $userId)->where('status', 'open')->where('is_demo', false)->count();
        $closedCount = $closedTrades->count();
        $wins = (clone $closedTrades)->where('result', 'WIN')->count();
        $losses = (clone $closedTrades)->where('result', 'LOSS')->count();
        $winRate = $closedCount > 0 ? round(($wins / $closedCount) * 100, 1) : 0;
        $totalProfit = (clone $closedTrades)->where('profit_loss', '>', 0)->sum('profit_loss');
        $totalLoss = abs((clone $closedTrades)->where('profit_loss', '<', 0)->sum('profit_loss'));
        $netPL = $totalProfit - $totalLoss;
        $avgTradeSize = $totalCount > 0
            ? Trade::where('user_id', $userId)->where('is_demo', false)->avg('amount')
            : 0;
        $bestTrade = (clone $closedTrades)->orderByDesc('profit_loss')->first();
        $worstTrade = (clone $closedTrades)->orderBy('profit_loss')->first();

        // --- Breakdowns ---
        // By trade type
        $byType = [];
        foreach (['binary', 'spot'] as $type) {
            $typeQuery = Trade::where('user_id', $userId)->where('is_demo', false)->where('status', 'closed')->where('trade_type', $type);
            $typeCount = $typeQuery->count();
            $typeWins = (clone $typeQuery)->where('result', 'WIN')->count();
            $byType[$type] = [
                'count' => $typeCount,
                'win_rate' => $typeCount > 0 ? round(($typeWins / $typeCount) * 100, 1) : 0,
                'pl' => $typeQuery->sum('profit_loss'),
            ];
        }

        // By asset class
        $byClass = Trade::where('user_id', $userId)
            ->where('is_demo', false)
            ->where('status', 'closed')
            ->with('tradingAsset')
            ->get()
            ->groupBy(fn ($t) => $t->tradingAsset->asset_class ?? 'unknown')
            ->map(function ($trades) {
                $wins = $trades->where('result', 'WIN')->count();
                $total = $trades->count();
                return [
                    'count' => $total,
                    'win_rate' => $total > 0 ? round(($wins / $total) * 100, 1) : 0,
                    'pl' => $trades->sum('profit_loss'),
                ];
            });

        // By direction
        $byDirection = [];
        foreach (['buy', 'sell'] as $dir) {
            $dirQuery = Trade::where('user_id', $userId)->where('is_demo', false)->where('status', 'closed')->where('action', $dir);
            $dirCount = $dirQuery->count();
            $dirWins = (clone $dirQuery)->where('result', 'WIN')->count();
            $byDirection[$dir] = [
                'count' => $dirCount,
                'win_rate' => $dirCount > 0 ? round(($dirWins / $dirCount) * 100, 1) : 0,
                'pl' => $dirQuery->sum('profit_loss'),
            ];
        }

        // P/L timeline (for Chart.js)
        $timeline = Trade::where('user_id', $userId)
            ->where('is_demo', false)
            ->where('status', 'closed')
            ->whereNotNull('settled_at')
            ->orderBy('settled_at')
            ->select('settled_at', 'profit_loss')
            ->get();

        $cumulativePL = 0;
        $timelineData = [];
        foreach ($timeline as $t) {
            $cumulativePL += $t->profit_loss;
            $timelineData[] = [
                'date' => \Carbon\Carbon::parse($t->settled_at)->format('Y-m-d'),
                'cumulative_pl' => round($cumulativePL, 2),
            ];
        }

        // Recent trades
        $recentTrades = Trade::where('user_id', $userId)
            ->where('is_demo', false)
            ->where('status', 'closed')
            ->with('tradingAsset')
            ->orderByDesc('settled_at')
            ->limit(10)
            ->get();

        return view('user.trades.analytics')->with([
            'title' => 'Trade Analytics',
            'settings' => $settings,
            'totalCount' => $totalCount,
            'openCount' => $openCount,
            'closedCount' => $closedCount,
            'wins' => $wins,
            'losses' => $losses,
            'winRate' => $winRate,
            'totalProfit' => $totalProfit,
            'totalLoss' => $totalLoss,
            'netPL' => $netPL,
            'avgTradeSize' => round($avgTradeSize, 2),
            'bestTrade' => $bestTrade,
            'worstTrade' => $worstTrade,
            'byType' => $byType,
            'byClass' => $byClass,
            'byDirection' => $byDirection,
            'timelineData' => $timelineData,
            'recentTrades' => $recentTrades,
        ]);
    }

    /**
     * Process/settle an expired binary trade (AJAX).
     */
    public function processTrade(Request $request)
    {
        $trade = Trade::where('id', $request->trade_id)
            ->where('status', 'open')
            ->first();

        if (!$trade) {
            return response()->json(['success' => false, 'message' => 'Trade not found or already closed.']);
        }

        // Only auto-process binary trades
        if ($trade->trade_type !== 'binary') {
            return response()->json(['success' => false, 'message' => 'Spot trades cannot be auto-settled.']);
        }

        $user = User::find($trade->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $balanceField = $trade->is_demo ? 'demo_bal' : 'account_bal';
        $asset = TradingAsset::find($trade->trading_asset_id);
        $exitPrice = $asset ? $asset->price : $trade->entry_price;

        DB::transaction(function () use ($trade, $user, $balanceField, $exitPrice) {
            $profitLoss = $trade->amount * ($trade->leverage / 100);
            $transactionType = 'LOSS';

            // RNG vs win_rate
            $winChance = random_int(1, 100);
            if ($winChance <= $user->win_rate) {
                // WIN: return stake + profit
                $returnAmount = $trade->amount + $profitLoss;
                $transactionType = 'WIN';

                $user->$balanceField += $returnAmount;
                if (!$trade->is_demo) {
                    $user->roi += $profitLoss;
                }
            } else {
                // LOSS: return stake - loss
                $returnAmount = $trade->amount - $profitLoss;
                if ($returnAmount < 0) {
                    $returnAmount = 0;
                }
                $transactionType = 'LOSS';

                $user->$balanceField += $returnAmount;
            }

            $trade->update([
                'status' => 'closed',
                'profit_loss' => $transactionType === 'WIN' ? $profitLoss : -$profitLoss,
                'result' => $transactionType,
                'exit_price' => $exitPrice,
                'settled_by' => 'system',
                'settled_at' => now(),
            ]);

            // Record transaction for live trades only
            if (!$trade->is_demo) {
                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => $trade->asset_name,
                    'amount' => $transactionType === 'WIN' ? $profitLoss : -$profitLoss,
                    'type' => $transactionType,
                ]);
            }

            $user->save();
        });

        if (!$trade->is_demo) {
            $plFormatted = '$' . number_format(abs($trade->profit_loss), 2);
            NotificationService::notifyUser($user, 'trade', 'Trade Settled: ' . $trade->result, 'Your trade on ' . $trade->asset_name . ' closed with ' . ($trade->result === 'WIN' ? 'a profit of ' : 'a loss of ') . $plFormatted . '.', route('user.trades.history'));
        }

        return response()->json([
            'success' => true,
            'trade_id' => $trade->id,
            'result' => $trade->result,
            'profit_loss' => $trade->profit_loss,
        ]);
    }

    /**
     * Request close on an open spot trade.
     */
    public function requestClose(Request $request)
    {
        $trade = Trade::where('id', $request->trade_id)
            ->where('user_id', Auth::id())
            ->where('trade_type', 'spot')
            ->where('status', 'open')
            ->whereNull('close_requested_at')
            ->first();

        if (!$trade) {
            return response()->json(['success' => false, 'message' => 'Trade not found or already requested.']);
        }

        $trade->update(['close_requested_at' => now()]);

        $user = User::find(Auth::id());
        NotificationService::notifyUser($user, 'trade', 'Close Request Submitted', 'Your close request for the trade on ' . $trade->asset_name . ' has been submitted for review.', route('user.trades.history'));

        return response()->json(['success' => true, 'message' => 'Close request submitted.']);
    }

    /**
     * Get assets for the trading page (AJAX for asset picker).
     */
    public function getAssets(Request $request)
    {
        $query = TradingAsset::active();

        if ($request->filled('asset_class')) {
            $query->where('asset_class', $request->asset_class);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $assets = $query->orderBy('name')->get();

        return response()->json($assets);
    }
}


