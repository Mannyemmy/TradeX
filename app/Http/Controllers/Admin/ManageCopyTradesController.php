<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CopyPosition;
use App\Models\User;
use Illuminate\Http\Request;

class ManageCopyTradesController extends Controller
{
    public function index(Request $request)
    {
        $query = CopyPosition::with(['user', 'expert']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('expert', function ($e) use ($search) {
                    $e->where('name', 'like', "%{$search}%");
                });
            });
        }

        $positions = $query->orderByDesc('created_at')->paginate(15);

        $totalActive = CopyPosition::where('status', 'active')->count();
        $totalInvested = CopyPosition::where('status', 'active')->sum('invested_amount');
        $totalProfit = CopyPosition::sum('accumulated_profit');
        $totalSettled = CopyPosition::whereIn('status', ['settled', 'completed', 'stopped'])->count();

        $title = 'Manage Copy Trades';
        return view('admin.copy-trades.index')->with([
            'title' => $title,
            'positions' => $positions,
            'totalActive' => $totalActive,
            'totalInvested' => $totalInvested,
            'totalProfit' => $totalProfit,
            'totalSettled' => $totalSettled,
            'currentStatus' => $request->status ?? 'all',
            'currentSearch' => $request->search ?? '',
        ]);
    }

    public function show($id)
    {
        $position = CopyPosition::with(['user', 'expert'])->findOrFail($id);
        $trades = $position->simulatedTrades()->orderByDesc('executed_at')->paginate(20);

        $title = 'Copy Position #' . $position->id;
        return view('admin.copy-trades.show')->with([
            'title' => $title,
            'position' => $position,
            'trades' => $trades,
        ]);
    }

    public function settle(Request $request, $id)
    {
        $position = CopyPosition::findOrFail($id);

        if (!$position->isSettleable()) {
            return redirect()->back()->with('message', 'This position cannot be settled.');
        }

        $payout = $position->totalPayout();
        $user = User::find($position->user_id);

        if ($user) {
            $user->account_bal += $payout;
            $user->save();
        }

        $position->status = 'settled';
        $position->settled_by = 'admin';
        $position->settled_at = now();
        $position->save();

        return redirect()->back()->with('success', 'Position settled. $' . number_format($payout, 2) . ' credited to user.');
    }

    public function stop($id)
    {
        $position = CopyPosition::findOrFail($id);

        if ($position->status !== 'active') {
            return redirect()->back()->with('message', 'Only active positions can be stopped.');
        }

        $position->status = 'stopped';
        $position->stopped_at = now();
        $position->save();

        return redirect()->back()->with('success', 'Position force-stopped.');
    }

    public function adjustProfit(Request $request, $id)
    {
        $request->validate([
            'admin_profit_adjustment' => 'required|numeric',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $position = CopyPosition::findOrFail($id);
        $position->admin_profit_adjustment = $request->admin_profit_adjustment;

        if ($request->filled('admin_notes')) {
            $position->admin_notes = $request->admin_notes;
        }

        $position->save();

        return redirect()->back()->with('success', 'Profit adjustment saved.');
    }

    public function bulkSettle(Request $request)
    {
        $request->validate([
            'position_ids' => 'required|array',
            'position_ids.*' => 'integer|exists:copy_positions,id',
        ]);

        $settled = 0;
        $positions = CopyPosition::whereIn('id', $request->position_ids)->get();

        foreach ($positions as $position) {
            if (!$position->isSettleable()) {
                continue;
            }

            $payout = $position->totalPayout();
            $user = User::find($position->user_id);

            if ($user) {
                $user->account_bal += $payout;
                $user->save();
            }

            $position->status = 'settled';
            $position->settled_by = 'admin';
            $position->settled_at = now();
            $position->save();
            $settled++;
        }

        return redirect()->back()->with('success', $settled . ' position(s) settled successfully.');
    }
}
