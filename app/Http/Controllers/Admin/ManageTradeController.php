<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\TradingAsset;
use App\Models\User;
use App\Models\Tp_Transaction;
use App\Mail\TradeUpdateMail;
use App\Mail\AdminPlacedTradeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\TradeClosedNotification;
use Illuminate\Http\Request;

class ManageTradeController extends Controller
{
    /** Allowed leverage tiers */
    private const LEVERAGE_OPTIONS = [2, 5, 10, 25, 50, 100];

    /** Allowed duration presets (minutes) for binary trades */
    private const DURATION_OPTIONS = [1, 5, 15, 30, 60, 240, 1440];

    /**
     * List all trades with filter support.
     */
    public function index(Request $request)
    {
        $query = Trade::with('user')->orderBy('created_at', 'desc');

        // Filter tabs
        $filter = $request->get('filter', 'all');
        switch ($filter) {
            case 'binary':
                $query->where('trade_type', 'binary');
                break;
            case 'spot':
                $query->where('trade_type', 'spot');
                break;
            case 'open':
                $query->where('status', 'open');
                break;
            case 'closed':
                $query->where('status', 'closed');
                break;
            case 'demo':
                $query->where('is_demo', true);
                break;
        }

        // Prioritize close-requested spot trades
        if ($filter === 'spot' || $filter === 'open') {
            $query->orderByRaw('close_requested_at IS NOT NULL DESC');
        }

        $trades = $query->paginate(20)->appends(['filter' => $filter]);
        $title = 'Manage Users Trades';

        return view('admin.trades.index', compact('trades', 'title', 'filter'));
    }

    /**
     * Show details for a single trade.
     */
    public function show($id)
    {
        $trade = Trade::with('user', 'tradingAsset')->findOrFail($id);
        $title = 'Trade Details';
        return view('admin.trades.show', compact('trade', 'title'));
    }

    /**
     * Admin settle a trade — sets result, P/L, credits/debits user balance.
     */
    public function updateProfitLoss(Request $request, $id)
    {
        $request->validate([
            'profit_loss' => 'required|numeric',
            'result' => 'required|in:WIN,LOSS',
        ]);

        $trade = Trade::findOrFail($id);
        $user = User::where('id', $trade->user_id)->first();
        $balanceField = $trade->is_demo ? 'demo_bal' : 'account_bal';

        $profitLossValue = abs(floatval($request->profit_loss));
        $asset = TradingAsset::find($trade->trading_asset_id);
        $exitPrice = $asset ? $asset->price : $trade->entry_price;

        DB::transaction(function () use ($trade, $user, $request, $profitLossValue, $balanceField, $exitPrice) {
            if ($request->result == 'WIN') {
                $returnAmount = $trade->amount + $profitLossValue;
                $user->$balanceField += $returnAmount;
                if (!$trade->is_demo) {
                    $user->roi += $profitLossValue;
                }
                $plStored = $profitLossValue;
            } else {
                $returnAmount = max(0, $trade->amount - $profitLossValue);
                $user->$balanceField += $returnAmount;
                $plStored = -$profitLossValue;
            }

            $trade->update([
                'profit_loss' => $plStored,
                'result' => $request->result,
                'status' => 'closed',
                'exit_price' => $exitPrice,
                'settled_by' => 'admin',
                'settled_at' => now(),
            ]);

            // Record transaction for live trades only
            if (!$trade->is_demo) {
                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => $trade->asset_name,
                    'amount' => $plStored,
                    'type' => $request->result,
                ]);
            }

            $user->save();
        });

        $trade->user->notify(new TradeClosedNotification($trade));
        $subject = 'Trade Update Notification';
        try {
            Mail::to($user->email)->send(new TradeUpdateMail($trade, $user, $subject));
        } catch (\Exception $e) {
            Log::error('Trade update email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Trade settled successfully.');
    }

    /**
     * Bulk settle multiple trades.
     */
    public function bulkSettle(Request $request)
    {
        $request->validate([
            'trade_ids' => 'required|array',
            'trade_ids.*' => 'exists:trades,id',
            'bulk_result' => 'required|in:WIN,LOSS',
            'bulk_profit_loss' => 'required|numeric|min:0',
        ]);

        $count = 0;
        foreach ($request->trade_ids as $tradeId) {
            $trade = Trade::where('id', $tradeId)->where('status', 'open')->first();
            if (!$trade) continue;

            $user = User::find($trade->user_id);
            if (!$user) continue;

            $balanceField = $trade->is_demo ? 'demo_bal' : 'account_bal';
            $profitLossValue = abs(floatval($request->bulk_profit_loss));
            $asset = TradingAsset::find($trade->trading_asset_id);
            $exitPrice = $asset ? $asset->price : $trade->entry_price;

            DB::transaction(function () use ($trade, $user, $request, $profitLossValue, $balanceField, $exitPrice) {
                if ($request->bulk_result == 'WIN') {
                    $returnAmount = $trade->amount + $profitLossValue;
                    $user->$balanceField += $returnAmount;
                    if (!$trade->is_demo) {
                        $user->roi += $profitLossValue;
                    }
                    $plStored = $profitLossValue;
                } else {
                    $returnAmount = max(0, $trade->amount - $profitLossValue);
                    $user->$balanceField += $returnAmount;
                    $plStored = -$profitLossValue;
                }

                $trade->update([
                    'profit_loss' => $plStored,
                    'result' => $request->bulk_result,
                    'status' => 'closed',
                    'exit_price' => $exitPrice,
                    'settled_by' => 'admin',
                    'settled_at' => now(),
                ]);

                if (!$trade->is_demo) {
                    Tp_Transaction::create([
                        'user' => $user->id,
                        'plan' => $trade->asset_name,
                        'amount' => $plStored,
                        'type' => $request->bulk_result,
                    ]);
                }

                $user->save();
            });

            $plFormatted = '$' . number_format(abs($trade->fresh()->profit_loss), 2);
            \App\Services\NotificationService::notifyUser($user, 'trade', 'Trade Settled: ' . $request->bulk_result, 'Your trade on ' . $trade->asset_name . ' was settled with ' . ($request->bulk_result === 'WIN' ? 'a profit of ' : 'a loss of ') . $plFormatted . '.', url('dashboard/tradinghistory'));

            $count++;
        }

        return redirect()->back()->with('success', "{$count} trades settled successfully.");
    }

    /**
     * Create trade form.
     */
    public function create()
    {
        $users = User::where('id', '!=', 192)->get();
        $recentUsers = User::latest()->limit(10)->get();
        $assets = TradingAsset::active()->orderBy('name')->get();
        $title = 'Create trades for Users';
        return view('admin.trades.create', compact('users', 'title', 'recentUsers', 'assets'));
    }

    /**
     * Store trade placed by admin for a user.
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'trading_asset_id' => 'required|exists:trading_assets,id',
            'amount' => 'required|numeric|min:1',
            'leverage' => 'required|integer|in:' . implode(',', self::LEVERAGE_OPTIONS),
            'action' => 'required|in:buy,sell',
            'trade_type' => 'required|in:binary,spot',
            'is_demo' => 'sometimes|boolean',
        ];

        if ($request->input('trade_type') === 'binary') {
            $rules['duration'] = 'required|integer|in:' . implode(',', self::DURATION_OPTIONS);
        }

        $request->validate($rules);

        $asset = TradingAsset::findOrFail($request->trading_asset_id);
        $isDemo = (bool) ($request->is_demo ?? false);
        $balanceField = $isDemo ? 'demo_bal' : 'account_bal';
        $duration = $request->trade_type === 'binary' ? (int) $request->duration : 0;
        $expiresAt = $request->trade_type === 'binary' ? now()->addMinutes($duration) : null;

        $user = User::where('id', $request->user_id)->first();

        DB::transaction(function () use ($user, $asset, $request, $isDemo, $balanceField, $duration, $expiresAt) {
            User::where('id', $user->id)->update([
                $balanceField => $user->$balanceField - $request->amount,
            ]);

            $trade = Trade::create([
                'user_id' => $request->user_id,
                'trade_type' => $request->trade_type,
                'is_demo' => $isDemo,
                'asset_type' => $asset->asset_class,
                'asset_name' => $asset->symbol . ' — ' . $asset->name,
                'trading_asset_id' => $asset->id,
                'amount' => $request->amount,
                'leverage' => $request->leverage,
                'duration' => $duration,
                'action' => $request->action,
                'entry_price' => $asset->price,
                'status' => 'open',
                'profit_loss' => 0.00,
                'expires_at' => $expiresAt,
            ]);

            $subject = 'New Trade Placed for You';
            try {
                Mail::to($user->email)->send(new AdminPlacedTradeMail($trade, $user, $subject));
            } catch (\Exception $e) {
                Log::error('Admin placed trade email failed: ' . $e->getMessage());
            }

            \App\Services\NotificationService::notifyUser($user, 'trade', 'Trade Placed', 'A ' . $request->action . ' trade has been placed on your account for ' . $trade->asset_name . '.', url('dashboard/tradinghistory'));
        });

        return redirect()->route('admin.trades.index')->with('success', 'Trade created successfully!');
    }

    /**
     * Search for users (AJAX).
     */
    public function search(Request $request)
    {
        $search = $request->get('q');

        $users = User::where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

        $results = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "{$user->name} ({$user->email})",
            ];
        });

        return response()->json($results);
    }

    /**
     * Edit trade form.
     */
    public function edit(Trade $trade)
    {
        $users = User::all();
        $assets = TradingAsset::orderBy('name')->get();
        $title = "Update User Trades";
        return view('admin.trades.edit', compact('trade', 'users', 'assets', 'title'));
    }

    /**
     * Update trade.
     */
    public function update(Request $request, Trade $trade)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'asset_type' => 'required|string',
            'asset_name' => 'required|string',
            'action' => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:0',
            'leverage' => 'required|integer|min:1',
            'duration' => 'required|integer|min:0',
            'status' => 'required|in:open,closed',
            'profit_loss' => 'nullable|numeric',
            'result' => 'required|in:PENDING,WIN,LOSS',
            'created_at' => 'nullable|date',
        ]);

        $data = [
            'user_id'     => $request->user_id,
            'asset_type'  => $request->asset_type,
            'asset_name'  => $request->asset_name,
            'action'      => $request->action,
            'amount'      => $request->amount,
            'leverage'    => $request->leverage,
            'duration'    => $request->duration,
            'expires_at'  => $request->duration > 0 ? now()->addMinutes($request->duration) : null,
            'status'      => $request->status,
            'profit_loss' => $request->profit_loss,
            'result'      => $request->result,
            'updated_at'  => now(),
        ];

        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }

        DB::table('trades')->where('id', $trade->id)->update($data);

        return redirect()->route('admin.trades.index')->with('success', 'Trade updated successfully!');
    }
}
