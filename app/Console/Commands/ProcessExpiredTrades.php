<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Trade;
use App\Models\TradingAsset;
use App\Models\Tp_Transaction;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class ProcessExpiredTrades extends Command
{
    protected $signature = 'process:trades';
    protected $description = 'Process all expired binary trades (live and demo)';

    public function handle()
    {
        $this->info('Processing expired binary trades...');

        $count = 0;

        // Chunked processing — only binary trades with expiry passed
        Trade::where('status', 'open')
            ->where('trade_type', 'binary')
            ->where('expires_at', '<=', now())
            ->chunkById(200, function ($trades) use (&$count) {
                foreach ($trades as $trade) {
                    $this->settleTrade($trade);
                    $count++;
                }
            });

        $this->info("Processed {$count} expired trades.");

        return 0;
    }

    private function settleTrade(Trade $trade)
    {
        $user = User::find($trade->user_id);
        if (!$user) {
            return;
        }

        $balanceField = $trade->is_demo ? 'demo_bal' : 'account_bal';
        $asset = TradingAsset::find($trade->trading_asset_id);
        $exitPrice = $asset ? $asset->price : $trade->entry_price;

        DB::transaction(function () use ($trade, $user, $balanceField, $exitPrice) {
            // Symmetric P/L: stake × (leverage / 100)
            $profitLoss = $trade->amount * ($trade->leverage / 100);

            $winChance = random_int(1, 100);
            if ($winChance <= $user->win_rate) {
                // WIN: return original stake + profit
                $returnAmount = $trade->amount + $profitLoss;
                $transactionType = 'WIN';

                $user->$balanceField += $returnAmount;
                if (!$trade->is_demo) {
                    $user->roi += $profitLoss;
                }
            } else {
                // LOSS: return original stake - loss (clamped at 0)
                $returnAmount = max(0, $trade->amount - $profitLoss);
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

        // Send in-app notification for live trades
        if (!$trade->is_demo) {
            $plFormatted = '$' . number_format(abs($trade->profit_loss), 2);
            NotificationService::notifyUser($user, 'trade', 'Trade Settled: ' . $trade->result, 'Your trade on ' . $trade->asset_name . ' closed with ' . ($trade->result === 'WIN' ? 'a profit of ' : 'a loss of ') . $plFormatted . '.', url('dashboard/tradinghistory'));
        }
    }
}
