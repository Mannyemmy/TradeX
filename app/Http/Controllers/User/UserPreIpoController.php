<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PreIpoCompany;
use App\Models\PreIpoHolding;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\Settings;
use App\Mail\PreIpoPurchaseMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;

class UserPreIpoController extends Controller
{
    public function index()
    {
        $settings = Settings::find(1);
        $user = Auth::user();

        // Companies that are upcoming/open, or any status where user has holdings
        $userCompanyIds = PreIpoHolding::where('user_id', $user->id)->pluck('pre_ipo_company_id')->toArray();

        $companies = PreIpoCompany::where(function ($q) use ($userCompanyIds) {
            $q->whereIn('status', ['upcoming', 'open'])
              ->orWhereIn('id', $userCompanyIds);
        })
        ->orderByDesc('is_featured')
        ->orderByDesc('created_at')
        ->paginate(12);

        return view('user.pre-ipo.index')->with([
            'title' => 'Pre-IPO Shares',
            'settings' => $settings,
            'companies' => $companies,
            'userHoldings' => PreIpoHolding::where('user_id', $user->id)->pluck('shares', 'pre_ipo_company_id'),
        ]);
    }

    public function show($id)
    {
        $settings = Settings::find(1);
        $user = Auth::user();
        $company = PreIpoCompany::findOrFail($id);
        $holding = PreIpoHolding::where('user_id', $user->id)
            ->where('pre_ipo_company_id', $id)
            ->first();
        $priceHistory = $company->priceHistory()->orderBy('created_at')->get();

        return view('user.pre-ipo.show')->with([
            'title' => $company->name,
            'settings' => $settings,
            'company' => $company,
            'holding' => $holding,
            'priceHistory' => $priceHistory,
        ]);
    }

    public function buy(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $company = PreIpoCompany::findOrFail($id);
        $user = User::find(Auth::id());
        $quantity = (int) $request->quantity;

        // Status check
        if ($company->status !== 'open') {
            return redirect()->back()->with('message', 'This company is not currently accepting purchases.');
        }

        // Min shares
        if ($quantity < $company->min_shares) {
            return redirect()->back()->with('message', 'Minimum purchase is ' . $company->min_shares . ' shares.');
        }

        // Max per user
        if ($company->max_shares_per_user) {
            $existingShares = PreIpoHolding::where('user_id', $user->id)
                ->where('pre_ipo_company_id', $company->id)
                ->value('shares') ?? 0;

            if (($existingShares + $quantity) > $company->max_shares_per_user) {
                return redirect()->back()->with('message', 'Maximum ' . $company->max_shares_per_user . ' shares per user. You already hold ' . $existingShares . '.');
            }
        }

        // Availability
        if (($company->shares_sold + $quantity) > $company->total_shares) {
            return redirect()->back()->with('message', 'Only ' . $company->shares_remaining . ' shares remaining.');
        }

        $totalCost = round($quantity * $company->share_price, 2);

        // Balance check
        if ($user->available_bal < $totalCost) {
            return redirect()->back()->with('message', 'Insufficient balance. You need $' . number_format($totalCost, 2) . '.');
        }

        DB::transaction(function () use ($user, $company, $quantity, $totalCost) {
            // Debit balance
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $totalCost,
            ]);

            // Upsert holding
            $existing = PreIpoHolding::where('user_id', $user->id)
                ->where('pre_ipo_company_id', $company->id)
                ->first();

            if ($existing) {
                // Weighted average price
                $newTotalShares = $existing->shares + $quantity;
                $newTotalCost = $existing->total_cost + $totalCost;
                $newAvgPrice = round($newTotalCost / $newTotalShares, 2);

                $existing->shares = $newTotalShares;
                $existing->purchase_price = $newAvgPrice;
                $existing->total_cost = $newTotalCost;
                $existing->save();
            } else {
                PreIpoHolding::create([
                    'user_id' => $user->id,
                    'pre_ipo_company_id' => $company->id,
                    'shares' => $quantity,
                    'purchase_price' => $company->share_price,
                    'total_cost' => $totalCost,
                    'status' => 'active',
                ]);
            }

            // Increment shares sold
            PreIpoCompany::where('id', $company->id)->increment('shares_sold', $quantity);

            // Log transaction (FK is 'user' not 'user_id')
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => 'PRE-IPO: ' . $company->name,
                'amount' => $totalCost,
                'type' => 'PRE_IPO_BUY',
            ]);
        });

        // Send purchase confirmation email if trade notifications are enabled
        $settings = Settings::find(1);
        if ($settings->trade_notification ?? false) {
            try {
                Mail::to($user->email)->send(new PreIpoPurchaseMail($user, $company, $quantity, $totalCost));
            } catch (\Exception $e) {
                // Email failure should not block the transaction
            }
        }

        NotificationService::notifyUser($user, 'pre_ipo', 'Pre-IPO Shares Purchased', 'You purchased ' . $quantity . ' shares of ' . $company->name . ' for $' . number_format($totalCost, 2) . '.', url('dashboard/pre-ipo/holdings'));
        \App\Services\NotificationService::notifyAdmin('pre_ipo', 'New Pre-IPO Purchase', $user->name . ' purchased ' . $quantity . ' shares of ' . $company->name . ' for $' . number_format($totalCost, 2) . '.', url('admin/dashboard/pre-ipo'));

        return redirect()->route('user.pre-ipo.holdings')->with('success', 'Successfully purchased ' . $quantity . ' shares of ' . $company->name . '.');
    }

    public function holdings()
    {
        $settings = Settings::find(1);
        $user = Auth::user();
        $holdings = PreIpoHolding::where('user_id', $user->id)
            ->with('company')
            ->get();

        $activeHoldings = $holdings->where('status', 'active');
        $convertedHoldings = $holdings->where('status', 'converted');

        $totalInvested = $holdings->sum('total_cost');
        $totalCurrentValue = $holdings->sum(function ($h) {
            return $h->current_value;
        });

        return view('user.pre-ipo.holdings')->with([
            'title' => 'My Pre-IPO Holdings',
            'settings' => $settings,
            'activeHoldings' => $activeHoldings,
            'convertedHoldings' => $convertedHoldings,
            'totalInvested' => $totalInvested,
            'totalCurrentValue' => $totalCurrentValue,
            'totalPnl' => $totalCurrentValue - $totalInvested,
        ]);
    }

    public function sell(Request $request, $holdingId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = User::find(Auth::id());
        $holding = PreIpoHolding::where('id', $holdingId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $company = PreIpoCompany::findOrFail($holding->pre_ipo_company_id);
        $quantity = (int) $request->quantity;

        if ($company->status !== 'public') {
            return redirect()->back()->with('message', 'Shares can only be sold after the company goes public.');
        }

        if ($quantity > $holding->shares) {
            return redirect()->back()->with('message', 'You only hold ' . $holding->shares . ' shares.');
        }

        // Get live price from TradingAsset
        $currentPrice = $company->current_price;
        $proceeds = round($quantity * $currentPrice, 2);

        DB::transaction(function () use ($user, $holding, $company, $quantity, $proceeds) {
            // Credit balance
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal + $proceeds,
            ]);

            // Update holding
            $holding->shares -= $quantity;
            // Proportionally reduce total_cost
            $costPerShare = $holding->total_cost / ($holding->shares + $quantity);
            $holding->total_cost -= round($costPerShare * $quantity, 2);

            if ($holding->shares <= 0) {
                $holding->delete();
            } else {
                $holding->save();
            }

            // Log transaction
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => 'PRE-IPO SELL: ' . $company->name,
                'amount' => $proceeds,
                'type' => 'PRE_IPO_SELL',
            ]);
        });

        return redirect()->route('user.pre-ipo.holdings')->with('success', 'Sold ' . $quantity . ' shares for $' . number_format($proceeds, 2) . '.');
    }

    public function priceHistory($id)
    {
        $history = PreIpoCompany::findOrFail($id)
            ->priceHistory()
            ->orderBy('created_at')
            ->get(['price', 'created_at']);

        return response()->json($history);
    }
}
