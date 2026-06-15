<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use App\Mail\WithdrawalStatus;
use App\Mail\NewNotification;
use App\Services\NotificationService;
use App\Traits\PingServer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ManageWithdrawalController extends Controller
{
    use PingServer;

    //process withdrawals
    public function pwithdrawal(Request $request)
    {
        $withdrawal=Withdrawal::where('id',$request->id)->first();
        $user=User::where('id',$withdrawal->user)->first();
         $settings=Settings::where('id', '=', '1')->first();


        if ($request->action == "Paid") {
            Withdrawal::where('id',$request->id)
            ->update([
                'status' => 'Processed',
            ]);
            
            //settings



//deduting user balance   if its on admin Admin Aprove          
        if ($settings->deduction_option == "AdminApprove") {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal -  $withdrawal->to_deduct,
                'withdrawotp' => NULL,
            ]);
        }

  
         //sending mail to user  
            
      $withdrawal=Withdrawal::where('id',$request->id)->first();
            
                 //send notification to user
     try {
         Mail::to($user->email)->send(new WithdrawalStatus( $withdrawal, $user, 'Withdrawal Approved'));
     } catch (\Exception $e) {
         Log::error('Withdrawal approved email failed: ' . $e->getMessage());
     }

     NotificationService::notifyUser($user, 'withdrawal', 'Withdrawal Approved', 'Your withdrawal of $' . number_format($withdrawal->amount, 2) . ' has been processed and approved.', url('dashboard/withdrawals'));
     
        }else {

            if($withdrawal->user==$user->id){
                
                 if ($settings->deduction_option == "userRequest") {
                User::where('id',$user->id)
                ->update([
                    'account_bal' => $user->account_bal+$withdrawal->to_deduct,
                ]);
                 }
                // Withdrawal::where('id',$request->id)->delete();

                 Withdrawal::where('id',$request->id)
            ->update([
                'status' => 'Rejected',
            ]);
                if ($request->emailsend == "true") {
                    try {
                        Mail::to($user->email)->send(new NewNotification($request->reason,$request->subject, $user->name));
                    } catch (\Exception $e) {
                        Log::error('Withdrawal rejected email failed: ' . $e->getMessage());
                    }
                }

                NotificationService::notifyUser($user, 'withdrawal', 'Withdrawal Declined', 'Your withdrawal request has been declined. Please contact support for details.', url('dashboard/withdrawals'));

              }

        }

        return redirect()->route('mwithdrawals')->with('success', 'Action Sucessful!');
    }


    public function processwithdraw($id){
         $with = Withdrawal::where('id',$id)->first();
         if (!$with) {
             return redirect()->route('mwithdrawals')->with('message', 'Withdrawal request not found.');
         }
         $method = Wdmethod::where('name', $with->payment_mode)->first();
         $user = User::where('id', $with->user)->first();
        return view('admin.Withdrawals.pwithrdawal',[
            'withdrawal' => $with,
            'method' => $method,
            'user' => $user,
            'title'=>'Process withdrawal Request',
        ]);
    }

    // Edit withdrawal (backdate + edit amount/status)
    public function edit(int $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $title = 'Edit Withdrawal #' . $id;
        return view('admin.Withdrawals.edit', compact('withdrawal', 'title'));
    }

    public function editWithdrawal(Request $request, int $id)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:0',
            'payment_mode' => 'nullable|string|max:255',
            'status'       => 'required|in:Pending,Processed,Rejected',
            'created_at'   => 'nullable|date',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        $data = [
            'amount'       => $request->amount,
            'payment_mode' => $request->payment_mode ?? $withdrawal->payment_mode,
            'status'       => $request->status,
            'updated_at'   => now(),
        ];
        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }
        DB::table('withdrawals')->where('id', $withdrawal->id)->update($data);

        return redirect()->back()->with('success', 'Withdrawal updated successfully!');
    }
}
