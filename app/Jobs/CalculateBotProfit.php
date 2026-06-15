<?php

namespace App\Jobs;

use App\Models\BotSubscription;
use App\Models\TradingBot;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateBotProfit
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Calculate and accrue profit for active bot subscriptions, auto-settle expired ones.
     */
    public function handle(): void
    {
        $subscriptions = BotSubscription::active()->with('tradingBot')->get();

        foreach ($subscriptions as $subscription) {
            $bot = $subscription->tradingBot;
            if (!$bot) {
                continue;
            }

            // Pro-rate daily profit since last update
            $lastCalc = $subscription->updated_at > $subscription->started_at
                ? $subscription->updated_at
                : $subscription->started_at;

            $hoursElapsed = now()->diffInMinutes($lastCalc) / 60;
            if ($hoursElapsed <= 0) {
                continue;
            }

            $dailyProfit = $subscription->invested_amount * ($subscription->daily_roi_snapshot / 100);
            $profitIncrement = $dailyProfit * ($hoursElapsed / 24);

            // 100% goes to user (no profit sharing for bots)
            $subscription->accumulated_profit += $profitIncrement;
            $subscription->save();

            // Track on the bot's total_profit
            $bot->total_profit += $profitIncrement;
            $bot->save();
        }

        // Auto-settle expired subscriptions
        $expired = BotSubscription::expired()->with(['user', 'tradingBot'])->get();

        foreach ($expired as $subscription) {
            $payout = $subscription->totalPayout();
            $user = $subscription->user;

            if ($user) {
                $user->account_bal += $payout;
                $user->save();

                NotificationService::notifyUser($user, 'bot_trading', 'Bot Subscription Completed', 'Your bot subscription has matured. $' . number_format($payout, 2) . ' has been credited to your balance.', url('dashboard/bot-trading'));
            }

            $subscription->status = 'completed';
            $subscription->settled_by = 'system';
            $subscription->settled_at = now();
            $subscription->save();

            // Decrement subscriber count
            if ($subscription->tradingBot) {
                $subscription->tradingBot->decrement('subscribers_count');
            }
        }
    }
}
