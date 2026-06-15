<?php

namespace App\Http\Livewire\User;

use App\Mail\NewNotification;
use App\Models\BncTransaction;
use App\Models\Settings;
use App\Models\User;
use App\Models\Wdmethod;
use App\Traits\BinanceApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Helpers\CurrencyHelper;

class CryptoWithdaw extends Component
{
    use BinanceApi;
    public $payment_mode;
    public $otpCode;
    public $amount;

    public function render()
    {
        return view('livewire.user.crypto-withdaw');
    }

    public function requestOtp()
    {
        sleep(2);
        $code = $this->RandomStringGenerator(5);
        $user = Auth::user();
        User::where('id', $user->id)->update([
            'withdrawotp' => $code,
        ]);

        $message = "You have initiated a withdrawal request, use the OTP: $code to complete your request.";
        $subject = "OTP Request";
        try {
            Mail::to($user->email)->send(new NewNotification($message, $subject, $user->name));
        } catch (\Exception $e) {
            Log::error('OTP email failed: ' . $e->getMessage());
        }
        session()->flash('status', 'Action Successful!, OTP have been sent to your email');
    }

    public function withdraw()
    {
        $settings = Settings::where('id', '1')->first();
        $method = Wdmethod::where('name', $this->payment_mode)->first();
        //get user
        $user = User::where('id', Auth::user()->id)->first();

        // Convert user-entered amount to USD
        $amountUsd = CurrencyHelper::toUsd($this->amount);

        if ($method->charges_type == 'percentage') {
            $charges = $amountUsd * $method->charges_amount / 100;
        } else {
            $charges = $method->charges_amount;
        }

        $to_withdraw = $amountUsd + $charges;

        if (Auth::user()->sendotpemail == "Yes" and $this->otpCode != Auth::user()->withdrawotp) {
            session()->flash('error', 'OTP is incorrect, please recheck the code');
        } elseif ($settings->enable_kyc == "yes" and Auth::user()->account_verify != "Verified") {
            session()->flash('error', 'Your account must be verified before you can make withdrawal. please complete your KYC verification');
        } elseif (Auth::user()->account_bal < $to_withdraw) {
            session()->flash('error', 'Sorry, your account balance is insufficient for this request.');
        } elseif ($amountUsd < $method->minimum) {
            session()->flash("error", "Sorry, The minimum amount you can withdraw is " . CurrencyHelper::formatForUser($method->minimum) . ", please try another payment method.");
        } else {

            $http_response = $this->payout($amountUsd, $this->RandomStringGenerator(10), $user->email);
            $data = json_decode($http_response);

            if ($data->status == "FAIL") {
                session()->flash('error', 'Something went wrong, please contact our support team if problem persist');

                // send mail to admin
                try {
                    Mail::to($settings->contact_email)->send(new NewNotification("There was a failed USDT withdrawal from your Binance account by $user->name, possible reasons maybe insufficient fund. Please login your binance account or your website to view more details and take neccesary action", "Failed USDT Withdrawal from your Binance account.", 'Admin'));
                } catch (\Exception $e) {
                    Log::error('Failed withdrawal admin email failed: ' . $e->getMessage());
                }
            } else {
                // get values from api.
                $values = $data->data;

                $brecord = new BncTransaction();
                $brecord->user_id = Auth::user()->id;
                $brecord->prepay_id = $values->requestId;
                $brecord->type = 'Withrdawal';
                $brecord->status = 'Pending';
                $brecord->save();

                //debit user
                User::where('id', $user->id)->update([
                    'account_bal' => $user->account_bal - $to_withdraw,
                    'withdrawotp' => NULL,
                ]);

                try {
                    Mail::to($settings->contact_email)->send(new NewNotification("There was a successful USDT withdrawal from your Binance account by $user->name", "Successful USDT Withdrawal from your Binance account.", 'Admin'));
                } catch (\Exception $e) {
                    Log::error('Successful withdrawal admin email failed: ' . $e->getMessage());
                }
            }
        }
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