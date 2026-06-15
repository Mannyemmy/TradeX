<?php

namespace App\Jobs;

use App\Models\CopyPosition;
use App\Models\CopySimulatedTrade;
use App\Models\TradingAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSimulatedTrades
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $positions = CopyPosition::active()->with('expert')->get();

        foreach ($positions as $position) {
            $asset = TradingAsset::active()->inRandomOrder()->first();

            if (!$asset || !$asset->price || $asset->price <= 0) {
                continue;
            }

            $action = random_int(0, 1) === 0 ? 'buy' : 'sell';

            // Target per-trade profit calibrated to daily ROI
            // ~180 trades per day (every 8 min) => target_per_trade = daily_profit / 180
            $dailyProfit = $position->invested_amount * ($position->daily_roi_snapshot / 100);
            $targetPerTrade = $dailyProfit / 180;

            // 60% WIN, 40% LOSS
            $isWin = random_int(1, 100) <= 60;

            if ($isWin) {
                // WIN: 0.5x to 2x target
                $profitLoss = $targetPerTrade * (random_int(50, 200) / 100);
                $result = 'WIN';
            } else {
                // LOSS: 0.2x to 0.8x target (smaller losses => net positive)
                $profitLoss = -1 * $targetPerTrade * (random_int(20, 80) / 100);
                $result = 'LOSS';
            }

            // Simulated trade size = fraction of invested amount
            $amount = round($position->invested_amount * (random_int(5, 25) / 100), 2);

            $entryPrice = $asset->price;
            // Calculate exit price based on P/L percentage
            $pctChange = ($amount > 0) ? ($profitLoss / $amount) : 0;
            if ($action === 'buy') {
                $exitPrice = $entryPrice * (1 + $pctChange);
            } else {
                $exitPrice = $entryPrice * (1 - $pctChange);
            }

            CopySimulatedTrade::create([
                'copy_position_id' => $position->id,
                'trading_asset_id' => $asset->id,
                'asset_name' => $asset->symbol ?? $asset->name,
                'asset_class' => $asset->asset_class,
                'action' => $action,
                'entry_price' => $entryPrice,
                'exit_price' => max(0, $exitPrice),
                'amount' => $amount,
                'profit_loss' => round($profitLoss, 2),
                'result' => $result,
                'executed_at' => now(),
            ]);
        }
    }
}
