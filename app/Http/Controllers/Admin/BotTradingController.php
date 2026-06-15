<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotSubscription;
use App\Models\TradingBot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BotTradingController extends Controller
{
    public function index()
    {
        $bots = TradingBot::withCount(['subscriptions as active_subscribers_count' => function ($q) {
            $q->where('status', 'active');
        }])->paginate(15);

        $totalBots = TradingBot::count();
        $activeBots = TradingBot::where('is_active', true)->count();
        $totalActiveSubscribers = BotSubscription::where('status', 'active')->count();
        $totalInvested = BotSubscription::where('status', 'active')->sum('invested_amount');

        $title = 'Manage Trading Bots';
        return view('admin.bot-trading.index')->with([
            'title' => $title,
            'bots' => $bots,
            'totalBots' => $totalBots,
            'activeBots' => $activeBots,
            'totalActiveSubscribers' => $totalActiveSubscribers,
            'totalInvested' => $totalInvested,
        ]);
    }

    public function create()
    {
        $title = 'Create Trading Bot';
        return view('admin.bot-trading.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'strategy_type' => 'required|in:scalping,day_trading,swing',
            'win_rate' => 'required|numeric|min:1|max:100',
            'expected_roi' => 'required|numeric|min:0.01|max:100',
            'min_investment' => 'required|numeric|min:1',
            'max_investment' => 'required|numeric|min:1',
            'profit_min_pct' => 'required|numeric|min:0.01|max:100',
            'profit_max_pct' => 'required|numeric|min:0.01|max:100',
            'loss_min_pct' => 'required|numeric|min:0.01|max:100',
            'loss_max_pct' => 'required|numeric|min:0.01|max:100',
            'trade_interval_minutes' => 'required|integer|min:1|max:1440',
            'max_duration_days' => 'required|integer|min:1|max:365',
        ]);

        $data = $request->only([
            'name', 'description', 'strategy_type', 'win_rate', 'expected_roi',
            'min_investment', 'max_investment', 'profit_min_pct', 'profit_max_pct',
            'loss_min_pct', 'loss_max_pct', 'trade_interval_minutes', 'max_duration_days',
        ]);

        $data['is_active'] = $request->has('is_active');

        TradingBot::create($data);

        return redirect()->route('admin.bot-trading.index')->with('success', 'Trading bot created successfully.');
    }

    public function edit($id)
    {
        $bot = TradingBot::findOrFail($id);
        $title = 'Edit Bot: ' . $bot->name;
        return view('admin.bot-trading.edit', compact('bot', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'strategy_type' => 'required|in:scalping,day_trading,swing',
            'win_rate' => 'required|numeric|min:1|max:100',
            'expected_roi' => 'required|numeric|min:0.01|max:100',
            'min_investment' => 'required|numeric|min:1',
            'max_investment' => 'required|numeric|min:1',
            'profit_min_pct' => 'required|numeric|min:0.01|max:100',
            'profit_max_pct' => 'required|numeric|min:0.01|max:100',
            'loss_min_pct' => 'required|numeric|min:0.01|max:100',
            'loss_max_pct' => 'required|numeric|min:0.01|max:100',
            'trade_interval_minutes' => 'required|integer|min:1|max:1440',
            'max_duration_days' => 'required|integer|min:1|max:365',
        ]);

        $bot = TradingBot::findOrFail($id);

        $data = $request->only([
            'name', 'description', 'strategy_type', 'win_rate', 'expected_roi',
            'min_investment', 'max_investment', 'profit_min_pct', 'profit_max_pct',
            'loss_min_pct', 'loss_max_pct', 'trade_interval_minutes', 'max_duration_days',
        ]);

        $data['is_active'] = $request->has('is_active');

        $bot->update($data);

        return redirect()->route('admin.bot-trading.index')->with('success', 'Trading bot updated successfully.');
    }

    public function destroy($id)
    {
        $bot = TradingBot::findOrFail($id);

        $activeCount = BotSubscription::where('trading_bot_id', $id)->where('status', 'active')->count();
        if ($activeCount > 0) {
            return redirect()->back()->with('message', 'Cannot delete bot with ' . $activeCount . ' active subscription(s). Stop them first.');
        }

        $bot->delete();

        return redirect()->route('admin.bot-trading.index')->with('success', 'Trading bot deleted successfully.');
    }

    public function toggleActive($id)
    {
        $bot = TradingBot::findOrFail($id);
        $bot->is_active = !$bot->is_active;
        $bot->save();

        return response()->json([
            'success' => true,
            'is_active' => $bot->is_active,
        ]);
    }

    public function subscriptions(Request $request)
    {
        $query = BotSubscription::with(['user', 'tradingBot']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('tradingBot', function ($b) use ($search) {
                    $b->where('name', 'like', "%{$search}%");
                });
            });
        }

        $subscriptions = $query->orderByDesc('created_at')->paginate(15);

        $totalActive = BotSubscription::where('status', 'active')->count();
        $totalInvested = BotSubscription::where('status', 'active')->sum('invested_amount');
        $totalProfit = BotSubscription::sum('accumulated_profit');
        $totalSettled = BotSubscription::whereIn('status', ['settled', 'completed', 'stopped'])->count();

        $title = 'Bot Trading Subscriptions';
        return view('admin.bot-trading.subscriptions')->with([
            'title' => $title,
            'subscriptions' => $subscriptions,
            'totalActive' => $totalActive,
            'totalInvested' => $totalInvested,
            'totalProfit' => $totalProfit,
            'totalSettled' => $totalSettled,
            'currentStatus' => $request->status ?? 'all',
            'currentSearch' => $request->search ?? '',
        ]);
    }

    public function showSubscription($id)
    {
        $subscription = BotSubscription::with(['user', 'tradingBot'])->findOrFail($id);
        $trades = $subscription->simulatedTrades()->orderByDesc('executed_at')->paginate(20);

        $title = 'Bot Subscription #' . $subscription->id;
        return view('admin.bot-trading.subscription-show')->with([
            'title' => $title,
            'subscription' => $subscription,
            'trades' => $trades,
        ]);
    }

    public function settleSubscription($id)
    {
        $subscription = BotSubscription::findOrFail($id);

        if (!$subscription->isSettleable()) {
            return redirect()->back()->with('message', 'This subscription cannot be settled.');
        }

        $payout = $subscription->totalPayout();
        $user = User::find($subscription->user_id);

        if ($user) {
            $user->account_bal += $payout;
            $user->save();
        }

        $subscription->status = 'settled';
        $subscription->settled_by = 'admin';
        $subscription->settled_at = now();
        $subscription->save();

        return redirect()->back()->with('success', 'Subscription settled. $' . number_format($payout, 2) . ' credited to user.');
    }

    public function adjustProfit(Request $request, $id)
    {
        $request->validate([
            'admin_profit_adjustment' => 'required|numeric',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $subscription = BotSubscription::findOrFail($id);
        $subscription->admin_profit_adjustment = $request->admin_profit_adjustment;

        if ($request->filled('admin_notes')) {
            $subscription->admin_notes = $request->admin_notes;
        }

        $subscription->save();

        return redirect()->back()->with('success', 'Profit adjustment saved.');
    }

    public function bulkSettle(Request $request)
    {
        $request->validate([
            'subscription_ids' => 'required|array',
            'subscription_ids.*' => 'integer|exists:bot_subscriptions,id',
        ]);

        $settled = 0;
        $subscriptions = BotSubscription::whereIn('id', $request->subscription_ids)->get();

        foreach ($subscriptions as $subscription) {
            if (!$subscription->isSettleable()) {
                continue;
            }

            $payout = $subscription->totalPayout();
            $user = User::find($subscription->user_id);

            if ($user) {
                $user->account_bal += $payout;
                $user->save();
            }

            $subscription->status = 'settled';
            $subscription->settled_by = 'admin';
            $subscription->settled_at = now();
            $subscription->save();
            $settled++;
        }

        return redirect()->back()->with('success', $settled . ' subscription(s) settled.');
    }

    public function editSubscription(int $id)
    {
        $subscription = BotSubscription::with(['user', 'tradingBot'])->findOrFail($id);
        $title = 'Edit Subscription #' . $subscription->id;
        return view('admin.bot-trading.subscription-edit', compact('subscription', 'title'));
    }

    public function updateSubscription(Request $request, int $id)
    {
        $request->validate([
            'invested_amount'  => 'required|numeric|min:0',
            'started_at'       => 'nullable|date',
            'expires_at'       => 'nullable|date',
            'status'           => 'required|in:active,stopped,completed,settled',
            'created_at'       => 'nullable|date',
        ]);

        $subscription = BotSubscription::findOrFail($id);

        $data = [
            'invested_amount' => $request->invested_amount,
            'status'          => $request->status,
            'updated_at'      => now(),
        ];
        if ($request->filled('started_at')) {
            $data['started_at'] = Carbon::parse($request->started_at);
        }
        if ($request->filled('expires_at')) {
            $data['expires_at'] = Carbon::parse($request->expires_at);
        }
        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }
        DB::table('bot_subscriptions')->where('id', $subscription->id)->update($data);

        return redirect()->route('admin.bot-trading.subscription', $subscription->id)
            ->with('success', 'Subscription updated successfully.');
    }
}
