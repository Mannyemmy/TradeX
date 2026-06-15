<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\NewNotification;
use App\Models\Settings;
use App\Models\SettingsCont;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tp_Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class TransferController extends Controller
{

    public function transfertouser(Request $request)
    {

        $receiver = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        $sender = Auth::user();
        $settings = Settings::find(1);
        $settingss = SettingsCont::find(1);
        $amountUsd = CurrencyHelper::toUsd($request->amount);
        $charges = $amountUsd * $settingss->transfer_charges / 100;
        $todeduct = $amountUsd + $charges;

        if (!Hash::check($request->password, $sender->password)) {
            return response()->json([
                'status' => 419,
                'message' => 'Incorrect Password',
            ]);
        }

        if ($sender->email == $receiver->email or $sender->username == $receiver->username) {
            return response()->json([
                'status' => 419,
                'message' => 'You cannot send funds to yourself',
            ]);
        }
        if (!$receiver) {
            return response()->json([
                'status' => 419,
                'message' => 'No user with this email address exist',
            ]);
        }

        if ($sender->account_bal < $todeduct) {
            return response()->json([
                'status' => 419,
                'message' => 'Insufficient Funds',
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->account_bal = $sender->account_bal - $todeduct;
        $user->save();

        User::where('email', $request->email)->orWhere('username', $request->email)->update([
            'account_bal' => $receiver->account_bal + $amountUsd,
        ]);

        //create history
        Tp_Transaction::create([
            'user' => $sender->id,
            'plan' => "Transfered to $receiver->name",
            'amount' => $amountUsd,
            'type' => "Fund Transfer",
        ]);

        //create history for receiver
        Tp_Transaction::create([
            'user' => $receiver->id,
            'plan' => "Received from $sender->name",
            'amount' => $amountUsd,
            'type' => "Fund Transfer",
        ]);


        $message = "You just received " . CurrencyHelper::formatForUser($amountUsd, $receiver) . " from $sender->name and your account balance is now " . CurrencyHelper::formatForUser($receiver->account_bal + $amountUsd, $receiver);

        try {
            Mail::to($receiver->email)->send(new NewNotification($message, 'Credit Alert', $receiver->name));
        } catch (\Exception $e) {
            Log::error('Transfer credit alert email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($sender, 'transfer', 'Transfer Sent', 'You sent ' . CurrencyHelper::formatForUser($amountUsd) . ' to ' . $receiver->name . '.', url('dashboard/accounthistory'));
        NotificationService::notifyUser($receiver, 'transfer', 'Transfer Received', 'You received ' . CurrencyHelper::formatForUser($amountUsd, $receiver) . ' from ' . $sender->name . '.', url('dashboard/accounthistory'));

        return response()->json([
            'status' => 200,
            'message' => 'Transfer Completed, Refreshing page',
        ]);
    }
}
