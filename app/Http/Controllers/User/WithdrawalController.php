<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\User_plans;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalStatus;
use App\Traits\Coinpayment;
use App\Traits\TemplateTrait;
use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    use Coinpayment, TemplateTrait;
    //
    public function withdrawamount(Request $request)
    {
        $request->session()->put('paymentmethod', $request->method);
        return redirect()->route('withdrawfunds');
    }

    //Return withdrawals route
    public function withdrawfunds()
    {
        $user = User::where('id', Auth::user()->id)->first();

        // Build array of enabled steps with their labels
        $enabledSteps = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($user->{"code{$i}_enabled"}) {
                $enabledSteps[] = [
                    'step' => $i,
                    'label' => $user->{"code{$i}_label"} ?: "Code {$i}",
                ];
            }
        }

        // If no codes enabled, redirect to withdrawals list
        if (empty($enabledSteps)) {
            return redirect()->route('withdrawalsdeposits');
        }

        // Ensure current step is valid (points to an enabled code)
        $currentStep = (int) $user->step;
        $enabledStepNumbers = array_column($enabledSteps, 'step');
        if (!in_array($currentStep, $enabledStepNumbers)) {
            $currentStep = $enabledStepNumbers[0];
            User::where('id', $user->id)->update(['step' => $currentStep]);
        }

        // Get the withdrawal being verified
        $withdrawal = Withdrawal::where('id', $user->withdrawal_id)->first();

        return view("user.withdraw", [
            'title' => 'Complete Withdrawal Request',
            'enabledSteps' => $enabledSteps,
            'currentStep' => $currentStep,
            'withdrawal' => $withdrawal,
        ]);
    }

    public function getotp()
    {
        $code = $this->RandomStringGenerator(5);

        $user = Auth::user();
        User::where('id', $user->id)->update([
            'withdrawotp' => $code,
        ]);

        $message = "You have initiated a withdrawal request, use the OTP: $code to complete your request.";
        $subject = "OTP Request";
        Mail::bcc($user->email)->send(new NewNotification($message, $subject, $user->name));

        return redirect()->back()
            ->with('success', 'Action Sucessful! OTP have been sent to your email');
    }

    public function completewithdrawal(Request $request)
    {

        if (Auth::user()->sendotpemail == "Yes") {
            if ($request->otpcode != Auth::user()->withdrawotp) {
                return redirect()->back()->with('message', 'OTP is incorrect, please recheck the code');
            }
        }

        $settings = Settings::where('id', '1')->first();
        if ($settings->enable_kyc == "yes") {
            if (Auth::user()->account_verify != "Verified") {
                return redirect()->back()->with('message', 'Your account must be verified before you can make withdrawal.');
            }
        }

        $method = Wdmethod::where('name', $request->method)->first();

        // Convert user-entered amount to USD
        $amountUsd = CurrencyHelper::toUsd($request['amount']);

        if ($method->charges_type == 'percentage') {
            $charges = $amountUsd * $method->charges_amount / 100;
        } else {
            $charges = $method->charges_amount;
        }

        $to_withdraw = $amountUsd + $charges;
        //return if amount is lesser than method minimum withdrawal amount

        if (Auth::user()->available_bal < $to_withdraw) {
            return redirect()->back()
                ->with('message', 'Sorry, your available balance is insufficient for this request.');
        }

        if ($amountUsd < $method->minimum) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can withdraw is " . CurrencyHelper::formatForUser($method->minimum) . ", please try another payment method.");
        }

        //get user last investment package
        User_plans::where('user', Auth::user()->id)
            ->where('active', 'yes')
            ->orderBy('activated_at', 'asc')->first();

        //get user
        $user = User::where('id', Auth::user()->id)->first();

        if ($request->method == 'Bitcoin') {
            User::where('id', $user->id)->update([
                'btc_address' => $request->wallet_address,
               
            ]);
            $coin = "BTC";
            $wallet = $user->btc_address;
        } elseif ($request->method  == 'Ethereum') {
            User::where('id', $user->id)->update([
                'eth_address' => $request->wallet_address,
               
            ]);
            $coin = "ETH";
            $wallet = $user->eth_address;
        } elseif ($request->method  == 'Litecoin') {
            User::where('id', $user->id)->update([
                'ltc_address' => $request->wallet_address,
               
            ]);
            $coin = "LTC";
            $wallet = $user->ltc_address;
        } elseif ($request->method  == 'USDT') {
            User::where('id', $user->id)->update([
                'usdt_address' => $request->wallet_address,
               
            ]);
            $coin = "USDT.TRC20";
            $wallet = $user->usdt_address;
        } elseif ($request->method  == 'Bank Transfer') {
           
            User::where('id', $user->id)->update([
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'swift_code' => $request->swift_code,
            ]);
        }

        $amount = $amountUsd;
        $ui = $user->id;

        if ($settings->deduction_option == "userRequest") {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $to_withdraw,
                'withdrawotp' => NULL,
            ]);
        }

        if ($settings->withdrawal_option == "auto" and ($request->method == 'Bitcoin' or $request->method  == 'Litecoin' or $request->method  == 'Ethereum' or $request->method == 'USDT')) {
            return $this->cpwithdraw($amount, $coin, $wallet, $ui, $to_withdraw);
        }
        
 $details = "$request->wallet_address $request->bankname $request->account_name  $request->account_number $request->swift_code";
        //save withdrawal info
        $dp = new Withdrawal();
        $dp->amount = $amount;
        $dp->to_deduct = $to_withdraw;
        $dp->payment_mode = $request->method;
        $dp->status = 'Pending';
        $dp->paydetails =  $details;
        $dp->user = $user->id;
        $dp->wallet_address = $request->wallet_address;
        $dp->bankname= $request->bankname;
        $dp->account_name = $request->	account_name;
        $dp->account_number	 = $request->account_number	;
        $dp->verification  = "Uncompleted";
        $dp->save();


        User::where('id', Auth::user()->id)->update([
            'withdrawal_id' =>  $dp->id,
        ]);

        // Check if user has any enabled withdrawal codes
        $user = User::where('id', Auth::user()->id)->first();
        $hasEnabledCodes = false;
        for ($i = 1; $i <= 5; $i++) {
            if ($user->{"code{$i}_enabled"}) {
                $hasEnabledCodes = true;
                break;
            }
        }

        if (!$hasEnabledCodes) {
            // No codes enabled — skip verification entirely
            Withdrawal::where('id', $dp->id)->update([
                'verification' => "Completed",
            ]);

            $settings = Settings::where('id', '1')->first();
            try {
                Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Withdrawal Request', true));
                Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Withdrawal Request'));
            } catch (\Exception $e) {
                Log::error('Withdrawal email failed: ' . $e->getMessage());
            }

            \App\Services\NotificationService::notifyUser($user, 'withdrawal', 'Withdrawal Submitted', 'Your withdrawal request for $' . number_format($dp->amount, 2) . ' has been submitted and is being processed.', url('dashboard/withdrawals'));

            \App\Services\NotificationService::notifyAdmin('withdrawal', 'New Withdrawal Request', $user->name . ' requested a withdrawal of $' . number_format($dp->amount, 2) . '.', url('admin/dashboard/mwithdrawals'));

            return redirect()->route('withdrawalsdeposits')->with('success', 'Your withdrawal request has been successfully submitted! Please wait while we process your request.');
        }

        // Set user step to the first enabled code
        for ($i = 1; $i <= 5; $i++) {
            if ($user->{"code{$i}_enabled"}) {
                User::where('id', $user->id)->update(['step' => $i]);
                break;
            }
        }

        return redirect()->route('withdrawfunds');

    }

    public function brokercode(Request $request)
    {
        $pin = $request->input('pin');
        $step = (int) $request->input('step');
        $user = User::where('id', Auth::user()->id)->first();

        // Validate step is 1-5 and enabled for this user
        if ($step < 1 || $step > 5 || !$user->{"code{$step}_enabled"}) {
            return redirect()->back()->with('message', 'Invalid verification step.');
        }

        $label = $user->{"code{$step}_label"} ?: "Code {$step}";

        // Compare submitted PIN against the code for this step
        if ($user->{"code{$step}"} != $pin) {
            return redirect()->back()
                ->with('message', "Sorry, the {$label} you entered is invalid. Kindly contact support to provide you with a valid code.");
        }

        // Code matched — find the next enabled step
        $nextStep = null;
        for ($i = $step + 1; $i <= 5; $i++) {
            if ($user->{"code{$i}_enabled"}) {
                $nextStep = $i;
                break;
            }
        }

        if ($nextStep) {
            // More steps remain — advance to next enabled step
            User::where('id', $user->id)->update(['step' => $nextStep]);
            return redirect()->back()->with('success', "{$label} verified successfully.");
        }

        // All codes completed — finalize withdrawal
        // Reset step to first enabled code for next withdrawal
        $firstEnabled = 1;
        for ($i = 1; $i <= 5; $i++) {
            if ($user->{"code{$i}_enabled"}) {
                $firstEnabled = $i;
                break;
            }
        }

        User::where('id', $user->id)->update(['step' => $firstEnabled]);

        Withdrawal::where('id', $user->withdrawal_id)->update([
            'verification' => "Completed",
        ]);

        $dp = Withdrawal::where('id', $user->withdrawal_id)->first();
        $settings = Settings::where('id', '1')->first();

        try {
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Withdrawal Request', true));
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Withdrawal Request'));
        } catch (\Exception $e) {
            Log::error('Withdrawal broker code email failed: ' . $e->getMessage());
        }

        \App\Services\NotificationService::notifyUser($user, 'withdrawal', 'Withdrawal Submitted', 'Your withdrawal request for $' . number_format($dp->amount, 2) . ' has been submitted and is being processed.', url('dashboard/withdrawals'));

        \App\Services\NotificationService::notifyAdmin('withdrawal', 'New Withdrawal Request', $user->name . ' requested a withdrawal of $' . number_format($dp->amount, 2) . '.', url('admin/dashboard/mwithdrawals'));

        return redirect()->route('withdrawalsdeposits')->with('success', 'Your withdrawal request has been successfully submitted! Please wait while we process your request.');
    }

    // for front end content management
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }
}

