<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Expert;
use App\Models\CopyPosition;
use App\Models\CopySimulatedTrade;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class CopyTradingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $experts = Expert::active()->paginate(12);
        $positions = CopyPosition::where('user_id', $user->id)
            ->with('expert')
            ->orderByDesc('created_at')
            ->get();

        $title = 'Copy Trading';
        return view('user.copy_trading.index')->with([
            'title' => $title,
            'experts' => $experts,
            'positions' => $positions,
        ]);
    }

    public function showExpert($id)
    {
        $user = Auth::user();
        $expert = Expert::findOrFail($id);

        $activePosition = CopyPosition::where('user_id', $user->id)
            ->where('expert_id', $id)
            ->where('status', 'active')
            ->first();

        $recentTrades = CopySimulatedTrade::whereHas('position', function ($q) use ($id) {
            $q->where('expert_id', $id);
        })->orderByDesc('executed_at')->limit(20)->get();

        $title = $expert->name;
        return view('user.copy_trading.show')->with([
            'title' => $title,
            'expert' => $expert,
            'activePosition' => $activePosition,
            'recentTrades' => $recentTrades,
        ]);
    }

    public function startCopying(Request $request, $expertId)
    {
        $request->validate([
            'invested_amount' => 'required|numeric|min:1',
        ]);

        $expert = Expert::findOrFail($expertId);
        $user = User::find(Auth::id());

        if (!$expert->is_active) {
            return redirect()->back()->with('message', 'This expert is not available for copying.');
        }

        $amount = CurrencyHelper::toUsd($request->invested_amount);

        if ($amount < $expert->min_startup_capital) {
            return redirect()->back()->with('message', 'Minimum investment is ' . CurrencyHelper::formatForUser($expert->min_startup_capital));
        }

        if ($expert->max_capital && $amount > $expert->max_capital) {
            return redirect()->back()->with('message', 'Maximum investment is ' . CurrencyHelper::formatForUser($expert->max_capital));
        }

        if ($user->account_bal < $amount) {
            return redirect()->back()->with('message', 'Insufficient balance. You have ' . CurrencyHelper::formatForUser($user->account_bal));
        }

        // Check if already copying this expert
        $existing = CopyPosition::where('user_id', $user->id)
            ->where('expert_id', $expertId)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', 'You are already copying this expert.');
        }

        $user->account_bal -= $amount;
        $user->save();

        CopyPosition::create([
            'user_id' => $user->id,
            'expert_id' => $expert->id,
            'invested_amount' => $amount,
            'daily_roi_snapshot' => $expert->daily_roi,
            'started_at' => now(),
            'expires_at' => now()->addDays($expert->duration_days),
            'status' => 'active',
        ]);

        NotificationService::notifyUser($user, 'copy_trade', 'Started Copying Expert', 'You are now copying ' . $expert->name . ' with ' . CurrencyHelper::formatForUser($amount) . ' for ' . $expert->duration_days . ' days.', url('dashboard/copy-trading'));
        \App\Services\NotificationService::notifyAdmin('copy_trade', 'New Copy Trade Started', $user->name . ' started copying ' . $expert->name . ' with $' . number_format($amount, 2) . ' for ' . $expert->duration_days . ' days.', url('admin/dashboard/copy-trading'));

        return redirect()->route('copyTrading')->with('success', 'You have started copying ' . $expert->name . '!');
    }

    public function stopCopying($positionId)
    {
        $user = User::find(Auth::id());

        $position = CopyPosition::where('id', $positionId)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $payout = $position->totalPayout();

        $user->account_bal += $payout;
        $user->save();

        $position->status = 'stopped';
        $position->stopped_at = now();
        $position->settled_by = 'user';
        $position->settled_at = now();
        $position->save();

        NotificationService::notifyUser($user, 'copy_trade', 'Copy Trading Stopped', 'You stopped copying and $' . number_format($payout, 2) . ' has been credited to your balance.', url('dashboard/copy-trading'));
        \App\Services\NotificationService::notifyAdmin('copy_trade', 'Copy Trade Stopped', $user->name . ' stopped a copy trade. Payout: $' . number_format($payout, 2) . '.', url('admin/dashboard/copy-trading'));

        return redirect()->route('copyTrading')->with('success', 'Copying stopped. $' . number_format($payout, 2) . ' credited to your balance.');
    }

    public function showPosition($id)
    {
        $user = Auth::user();

        $position = CopyPosition::where('id', $id)
            ->where('user_id', $user->id)
            ->with('expert')
            ->firstOrFail();

        $trades = $position->simulatedTrades()->with('tradingAsset')->orderByDesc('executed_at')->paginate(20);

        $title = 'Copy Position #' . $position->id;
        return view('user.copy_trading.position')->with([
            'title' => $title,
            'position' => $position,
            'trades' => $trades,
        ]);
    }
}

