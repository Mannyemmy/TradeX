<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TradingBot;
use App\Models\BotSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class BotTradingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bots = TradingBot::active()->paginate(12);
        $subscriptions = BotSubscription::where('user_id', $user->id)
            ->with('tradingBot')
            ->orderByDesc('created_at')
            ->get();

        $title = 'Bot Trading';
        return view('user.bot-trading.index')->with([
            'title' => $title,
            'bots' => $bots,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function showBot($id)
    {
        $user = Auth::user();
        $bot = TradingBot::findOrFail($id);

        $activeSubscription = BotSubscription::where('user_id', $user->id)
            ->where('trading_bot_id', $id)
            ->where('status', 'active')
            ->first();

        $recentTrades = \App\Models\BotSimulatedTrade::whereHas('subscription', function ($q) use ($id) {
            $q->where('trading_bot_id', $id);
        })->orderByDesc('executed_at')->limit(20)->get();

        $title = $bot->name;
        return view('user.bot-trading.show')->with([
            'title' => $title,
            'bot' => $bot,
            'activeSubscription' => $activeSubscription,
            'recentTrades' => $recentTrades,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'trading_bot_id' => 'required|exists:trading_bots,id',
            'invested_amount' => 'required|numeric|min:1',
            'duration_days' => 'required|integer|min:1',
        ]);

        $bot = TradingBot::findOrFail($request->trading_bot_id);
        $user = User::find(Auth::id());

        if (!$bot->is_active) {
            return redirect()->back()->with('message', 'This bot is not currently active.');
        }

        $amount = CurrencyHelper::toUsd($request->invested_amount);
        $durationDays = min($request->duration_days, $bot->max_duration_days);

        if ($amount < $bot->min_investment) {
            return redirect()->back()->with('message', 'Minimum investment is ' . CurrencyHelper::formatForUser($bot->min_investment));
        }

        if ($bot->max_investment && $amount > $bot->max_investment) {
            return redirect()->back()->with('message', 'Maximum investment is ' . CurrencyHelper::formatForUser($bot->max_investment));
        }

        if ($user->account_bal < $amount) {
            return redirect()->back()->with('message', 'Insufficient balance. You have ' . CurrencyHelper::formatForUser($user->account_bal));
        }

        // Check if already subscribed to this bot
        $existing = BotSubscription::where('user_id', $user->id)
            ->where('trading_bot_id', $bot->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', 'You already have an active subscription to this bot.');
        }

        $user->account_bal -= $amount;
        $user->save();

        BotSubscription::create([
            'user_id' => $user->id,
            'trading_bot_id' => $bot->id,
            'invested_amount' => $amount,
            'daily_roi_snapshot' => $bot->expected_roi,
            'started_at' => now(),
            'expires_at' => now()->addDays($durationDays),
            'status' => 'active',
        ]);

        $bot->increment('subscribers_count');

        NotificationService::notifyUser($user, 'bot_trading', 'Bot Subscription Started', 'You subscribed to ' . $bot->name . ' with ' . CurrencyHelper::formatForUser($amount) . ' for ' . $durationDays . ' days.', url('dashboard/bot-trading'));
        \App\Services\NotificationService::notifyAdmin('bot_trading', 'New Bot Subscription', $user->name . ' subscribed to bot ' . $bot->name . ' with $' . number_format($amount, 2) . ' for ' . $durationDays . ' days.', url('admin/dashboard/bots'));

        return redirect()->route('botTrading')->with('success', 'You have subscribed to ' . $bot->name . '!');
    }

    public function stopSubscription($id)
    {
        $user = User::find(Auth::id());

        $subscription = BotSubscription::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $payout = $subscription->totalPayout();

        $user->account_bal += $payout;
        $user->save();

        $subscription->status = 'stopped';
        $subscription->stopped_at = now();
        $subscription->settled_by = 'user';
        $subscription->settled_at = now();
        $subscription->save();

        $subscription->tradingBot()->decrement('subscribers_count');

        NotificationService::notifyUser($user, 'bot_trading', 'Bot Subscription Stopped', 'You stopped your bot subscription. $' . number_format($payout, 2) . ' has been credited to your balance.', url('dashboard/bot-trading'));
        \App\Services\NotificationService::notifyAdmin('bot_trading', 'Bot Subscription Stopped', $user->name . ' stopped a bot subscription. Payout: $' . number_format($payout, 2) . '.', url('admin/dashboard/bots'));

        return redirect()->route('botTrading')->with('success', 'Subscription stopped. $' . number_format($payout, 2) . ' credited to your balance.');
    }

    public function showSubscription($id)
    {
        $user = Auth::user();

        $subscription = BotSubscription::where('id', $id)
            ->where('user_id', $user->id)
            ->with('tradingBot')
            ->firstOrFail();

        $trades = $subscription->simulatedTrades()->orderByDesc('executed_at')->paginate(20);

        $title = 'Bot Subscription #' . $subscription->id;
        return view('user.bot-trading.subscription')->with([
            'title' => $title,
            'subscription' => $subscription,
            'trades' => $trades,
        ]);
    }
}
