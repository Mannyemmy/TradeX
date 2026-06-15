<?php

namespace App\Jobs;

use App\Models\BotSubscription;
use App\Models\BotSimulatedTrade;
use App\Models\TradingAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateBotTrades
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Generate simulated trades for active bot subscriptions.
     */
    public function handle(): void
    {
        $subscriptions = BotSubscription::active()->with('tradingBot')->get();

        foreach ($subscriptions as $subscription) {
            $bot = $subscription->tradingBot;
            if (!$bot || !$bot->is_active) {
                continue;
            }

            // Check if enough time has passed since last trade
            $lastTrade = $subscription->simulatedTrades()->orderByDesc('executed_at')->first();
            if ($lastTrade && $lastTrade->executed_at->diffInMinutes(now()) < $bot->trade_interval_minutes) {
                continue;
            }

            // Pick a random active asset filtered by strategy type
            $asset = $this->pickAsset($bot->strategy_type);
            if (!$asset || !$asset->price || $asset->price <= 0) {
                // Fallback: any active asset
                $asset = TradingAsset::active()->inRandomOrder()->first();
                if (!$asset || !$asset->price || $asset->price <= 0) {
                    continue;
                }
            }

            $action = random_int(0, 1) === 0 ? 'buy' : 'sell';

            // WIN/LOSS based on bot's configured win_rate
            $isWin = random_int(1, 100) <= $bot->win_rate;

            // Trade size = 5-25% of invested amount
            $amount = round($subscription->invested_amount * (random_int(5, 25) / 100), 2);

            if ($isWin) {
                // Profit: random between profit_min_pct and profit_max_pct of trade amount
                $pctRange = random_int(
                    (int) ($bot->profit_min_pct * 100),
                    (int) ($bot->profit_max_pct * 100)
                ) / 100;
                $profitLoss = round($amount * ($pctRange / 100), 2);
                $result = 'WIN';
            } else {
                // Loss: random between loss_min_pct and loss_max_pct of trade amount
                $pctRange = random_int(
                    (int) ($bot->loss_min_pct * 100),
                    (int) ($bot->loss_max_pct * 100)
                ) / 100;
                $profitLoss = round(-1 * $amount * ($pctRange / 100), 2);
                $result = 'LOSS';
            }

            $entryPrice = $asset->price;
            $pctChange = ($amount > 0) ? ($profitLoss / $amount) : 0;
            if ($action === 'buy') {
                $exitPrice = $entryPrice * (1 + $pctChange);
            } else {
                $exitPrice = $entryPrice * (1 - $pctChange);
            }

            BotSimulatedTrade::create([
                'bot_subscription_id' => $subscription->id,
                'trading_asset_id' => $asset->id,
                'asset_name' => $asset->symbol ?? $asset->name,
                'asset_class' => $asset->asset_class,
                'action' => $action,
                'entry_price' => $entryPrice,
                'exit_price' => max(0, $exitPrice),
                'amount' => $amount,
                'profit_loss' => $profitLoss,
                'result' => $result,
                'executed_at' => now(),
            ]);
        }
    }

    /**
     * Pick asset based on bot strategy type.
     * scalping => crypto/forex (high volatility)
     * day_trading => any class
     * swing => stock/etf/index (longer trends)
     */
    private function pickAsset(string $strategyType): ?TradingAsset
    {
        $query = TradingAsset::active();

        switch ($strategyType) {
            case 'scalping':
                $query->where(function ($q) {
                    $q->where('asset_class', 'crypto')->orWhere('asset_class', 'forex');
                });
                break;
            case 'swing':
                $query->where(function ($q) {
                    $q->whereIn('asset_class', ['stock', 'etf', 'index']);
                });
                break;
            case 'day_trading':
            default:
                // All asset classes
                break;
        }

        return $query->inRandomOrder()->first();
    }
}
