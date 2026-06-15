<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Plans;
use App\Models\Tp_Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use App\Models\User_plans;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;

class UserInvPlanController extends Controller
{

    public function joinplan(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:plans,id',
            'duration' => 'required|string',
            'iamount' => 'nullable|numeric|min:0',
        ]);

        //get plan
        $plan = Plans::where('id', $request['id'])->where('type', 'Main')->first();
        if (!$plan) {
            return redirect()->back()->with('message', 'Invalid plan selected.');
        }

        if (isset($request['iamount']) && $request['iamount'] > 0) {
            $plan_price = CurrencyHelper::toUsd($request['iamount']);
        } else {
            $plan_price = $plan->price;
        }

        // Validate amount is within plan bounds
        if ($plan_price < $plan->min_price || $plan_price > $plan->max_price) {
            return redirect()->back()
                ->with('message', 'Investment amount must be between ' . CurrencyHelper::formatForUser($plan->min_price) . ' and ' . CurrencyHelper::formatForUser($plan->max_price) . '.');
        }

        $expiration = explode(" ", $plan->expiration);
        $digit = $expiration[0];
        $frame = $expiration[1];
        $toexpire = "add" . $frame;
        $end_at = Carbon::now()->$toexpire($digit)->toDateTimeString();

        return DB::transaction(function () use ($request, $plan, $plan_price, $end_at) {
            // Re-fetch user inside transaction for fresh balance
            $user = User::where('id', Auth::id())->lockForUpdate()->first();

            //check if the user account balance can buy this plan
            if ($user->account_bal < $plan_price) {
                return redirect()->route('deposits')
                    ->with('message', 'Your account is insufficient to purchase this plan. Please make a deposit.');
            }

            // Credit user the plan bonus
            if ($plan->gift > 0) {
                User::where('id', $user->id)
                    ->update([
                        'bonus' => $user->bonus + $plan->gift,
                        'account_bal' => $user->account_bal + $plan->gift,
                    ]);

                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => $plan->name,
                    'amount' => $plan->gift,
                    'type' => "Gift Bonus",
                ]);

                // Re-fetch after bonus credit
                $user->refresh();
            }

            //debit user
            User::where('id', $user->id)
                ->update([
                    'account_bal' => $user->account_bal - $plan_price,
                ]);

            //create history
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => $plan->name,
                'amount' => $plan_price,
                'type' => "Plan purchase",
            ]);

            //save user plan
            $userplanid = DB::table('user_plans')->insertGetId([
                'plan' => $plan->id,
                'user' => Auth::user()->id,
                'amount' => $plan_price,
                'active' => 'yes',
                'inv_duration' => $request['duration'],
                'expire_date' => $end_at,
                'activated_at' => Carbon::now(),
                'last_growth' => Carbon::now(),
                'profit_earned' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            User::where('id', Auth::user()->id)
                ->update([
                    'plan' => $plan->id,
                    'user_plan' => $userplanid,
                    'entered_at' => Carbon::now(),
                ]);

            //send notification
            $settings = Settings::find(1);
            $message = "This is to inform you that $user->name just purchased an investment plan: $plan->name";
            $subject = "$user->name just purchased an investment plan";
            try {
                Mail::to($settings->contact_email)->send(new NewNotification($message, $subject, 'Admin'));
            } catch (\Exception $e) {
                Log::error('Investment plan admin email failed: ' . $e->getMessage());
            }

            \App\Services\NotificationService::notifyAdmin('investment', 'New Plan Purchase: ' . $plan->name, $user->name . ' purchased the ' . $plan->name . ' plan for $' . number_format($plan_price, 2) . '.', url('admin/dashboard/active-investments'));

            return redirect()->back()
                ->with('success', "You have successfully purchased the $plan->name plan. Your investment is now active.");
        });
    }
 


    public function cancelPlan($plan)
    {
        $plan = User_plans::find($plan);

        if (!$plan || $plan->user != Auth::id()) {
            abort(404);
        }

        if ($plan->active != 'yes') {
            return back()->with('message', 'This plan is not active and cannot be cancelled.');
        }

        $plan->active = 'cancelled';
        $plan->save();

        // Re-fetch fresh user balance to avoid stale data
        $user = User::find($plan->user);

        // credit the user his capital
        User::where('id', $plan->user)
            ->update([
                'account_bal' => $user->account_bal + $plan->amount,
            ]);

        //save to transactions history
        $th = new Tp_Transaction();
        $th->plan = $plan->dplan->name;
        $th->user = $plan->user;
        $th->amount = $plan->amount;
        $th->type = "Investment capital for cancelled plan";
        $th->save();

        // Send a mail to the user informing them of their plan cancellation
        $planName = $plan->dplan->name;
        $message = "You have succesfully cancelled your $planName plan and your investment capital have been credited to your account,  If this is a mistake, please contact us immediately to reactivate it for you.";
        try {
            Mail::to(Auth::user()->email)->send(new NewNotification($message, 'Investment Plan Cancelled', Auth::user()->name));
        } catch (\Exception $e) {
            Log::error('Plan cancelled email failed: ' . $e->getMessage());
        }

        \App\Services\NotificationService::notifyUser(Auth::user(), 'investment', 'Plan Cancelled', 'Your ' . $planName . ' investment plan has been cancelled and your capital has been returned.', url('dashboard/mplans'));

        return back()->with('success', 'Plan cancelled successfully');
    }
}