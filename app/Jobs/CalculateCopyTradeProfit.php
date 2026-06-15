<?php

namespace App\Jobs;

use App\Models\CopyPosition;
use App\Models\Expert;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateCopyTradeProfit
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $positions = CopyPosition::active()->with('expert')->get();

        foreach ($positions as $position) {
            $expert = $position->expert;
            if (!$expert) {
                continue;
            }

            // Calculate daily profit pro-rated since last update (or started_at)
            $lastCalc = $position->updated_at > $position->started_at
                ? $position->updated_at
                : $position->started_at;

            $hoursElapsed = now()->diffInMinutes($lastCalc) / 60;
            if ($hoursElapsed <= 0) {
                continue;
            }

            $dailyProfit = $position->invested_amount * ($position->daily_roi_snapshot / 100);
            $profitIncrement = $dailyProfit * ($hoursElapsed / 24);

            // Update expert's total_profit with their share
            $expertSharePct = $expert->profit_share_percentage / 100;
            $expertShare = $profitIncrement * $expertSharePct;
            $userProfit = $profitIncrement - $expertShare;

            $position->accumulated_profit += $userProfit;
            $position->save();

            $expert->total_profit += $expertShare;
            $expert->save();
        }

        // Auto-settle expired positions
        $expired = CopyPosition::expired()->with('user')->get();

        foreach ($expired as $position) {
            $payout = $position->totalPayout();
            $user = $position->user;

            if ($user) {
                $user->account_bal += $payout;
                $user->save();

                NotificationService::notifyUser($user, 'copy_trade', 'Copy Trade Completed', 'Your copy trading position has matured. $' . number_format($payout, 2) . ' has been credited to your balance.', url('dashboard/copy-trading'));
            }

            $position->status = 'completed';
            $position->settled_by = 'system';
            $position->settled_at = now();
            $position->save();
        }
    }
}
