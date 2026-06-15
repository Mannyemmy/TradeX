<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Settings;
use App\Models\Deposit;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\Tp_Transaction;
use App\Mail\DepositStatus;
use App\Mail\LoanRepaymentMail;
use App\Mail\LoanCompletedMail;
use App\Services\NotificationService;
use App\Traits\PingServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManageDepositController extends Controller
{
    use PingServer;

    //Delete deposit
    public function deldeposit($id)
    {
        $deposit = Deposit::where('id', $id)->first();
        Storage::disk('public')->delete($deposit->proof);
        Deposit::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Deposit history has been deleted!');
    }

    //process deposits
    public function pdeposit($id)
    {
        //confirm the users plan
        $deposit = Deposit::where('id', $id)->first();
        $user = User::where('id', $deposit->user)->first();
        //get settings 
        $settings = Settings::where('id', '=', '1')->first();

        $response = $this->callServer('earnings', '/process-deposit', [
            'referral_commission' => $settings->referral_commission,
            'amount' => $deposit->amount,
            'account_bal' => $user->account_bal,
            'depositBonus' => $settings->deposit_bonus,
        ]);

    if($deposit->user==$user->id){
            //add funds to user's account
            User::where('id',$user->id)
            ->update([
                'account_bal' => $user->account_bal + $deposit->amount,
                'cstatus' => 'Customer',
            ]);
            
            //get settings 
            $settings=Settings::where('id', '=', '1')->first();
            $earnings=$settings->referral_commission*$deposit->amount/100;

          if (!empty($user->ref_by)) {
                //get agent
                $agent = User::where('id', $user->ref_by)->first();
                User::where('id', $user->ref_by)
                    ->update([
                        'account_bal' => $agent->account_bal + $earnings,
                        'ref_bonus' => $agent->ref_bonus + $earnings,
                    ]);
        
                //create history
                Tp_Transaction::create([
                    'user' => $user->ref_by,
                    'plan' => "Credit",
                    'amount'=>$earnings,
                    'type'=>"Ref_bonus",
                ]);

                NotificationService::notifyUser($agent, 'referral', 'Referral Bonus', 'You earned a referral bonus of $' . number_format($earnings, 2) . ' from ' . $user->name . "'s deposit.", url('dashboard/accounthistory'));
        
                //credit commission to ancestors
                $deposit_amount = $deposit->amount;
                $array=User::all();
                $parent=$user->id;
                $this->getAncestors($array, $deposit_amount, $parent);
            }
             //update deposits
        Deposit::where('id',$id)
            ->update([
            'status' => 'Processed',
        ]);
               $deposit = Deposit::where('id', $id)->first();
        $user = User::where('id', $deposit->user)->first();
            //Send confirmation email to user regarding his deposit and it's successful.
            try {
                Mail::to($user->email)->send(new DepositStatus($deposit, $user,'Your Deposit have been Confirmed', false));
            } catch (\Exception $e) {
                Log::error('Deposit confirmed email failed: ' . $e->getMessage());
            }

            NotificationService::notifyUser($user, 'deposit', 'Deposit Approved', 'Your deposit of $' . number_format($deposit->amount, 2) . ' has been approved and credited to your account.', url('dashboard/deposits'));
    
            // ── Auto-process linked loan repayment ──
            if (!empty($deposit->loan_repayment_schedule_id)) {
                $this->processLoanRepayment($deposit, $user);
            }
        }

       
        
        return redirect()->back()->with('success', 'Action Sucessful!');
    }


    /**
     * Process loan repayment when a linked deposit is approved.
     * Debits the deposited amount from user balance to pay the loan installment.
     */
    private function processLoanRepayment(Deposit $deposit, User $user)
    {
        $schedule = LoanRepaymentSchedule::find($deposit->loan_repayment_schedule_id);
        if (!$schedule) {
            return;
        }

        $loan = Loan::find($schedule->loan_id);
        if (!$loan || !in_array($loan->status, ['active', 'repaying'])) {
            return;
        }

        if ($schedule->status === 'paid') {
            return;
        }

        $amountDue = $schedule->total_due;
        $applyAmount = min((float) $deposit->amount, $amountDue);

        DB::transaction(function () use ($user, $loan, $schedule, $applyAmount, $amountDue) {
            // Debit user account for the loan payment
            $freshUser = User::find($user->id);
            $freshUser->account_bal -= $applyAmount;
            $freshUser->save();

            // Update schedule payment
            $newPaid = $schedule->paid_amount + $applyAmount;
            $totalRequired = $schedule->total_amount + $schedule->late_fee;

            if ($newPaid >= $totalRequired) {
                $schedule->update([
                    'status' => 'paid',
                    'paid_amount' => $totalRequired,
                    'paid_at' => now(),
                ]);
            } else {
                $schedule->update([
                    'status' => 'partial',
                    'paid_amount' => $newPaid,
                ]);
            }

            // Update loan totals
            $loan->total_repaid = $loan->repaymentSchedules()->where('status', 'paid')->sum('paid_amount')
                                + $loan->repaymentSchedules()->where('status', 'partial')->sum('paid_amount');
            $loan->save();

            // Check if all installments are paid
            $nextUnpaid = $loan->repaymentSchedules()->unpaid()->first();
            if ($nextUnpaid) {
                $loan->update([
                    'status' => 'repaying',
                    'next_payment_date' => $nextUnpaid->due_date,
                ]);
            } else {
                $loan->update([
                    'status' => 'completed',
                    'next_payment_date' => null,
                ]);
                $loan->releaseCollateral();
            }
        });

        // Refresh models for email
        $schedule->refresh();
        $loan->refresh();
        $freshUser = User::find($user->id);

        try {
            Mail::to($freshUser->email)->send(new LoanRepaymentMail($loan, $freshUser, $schedule, 'Loan Payment Received'));
        } catch (\Exception $e) {
            Log::error('Loan repayment email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($freshUser, 'loan', 'Loan Payment Received', 'Your loan repayment of $' . number_format($applyAmount, 2) . ' has been recorded.', url('dashboard/loans/' . $loan->id));

        if ($loan->status === 'completed') {
            try {
                Mail::to($freshUser->email)->send(new LoanCompletedMail($loan, $freshUser, 'Loan Fully Repaid'));
            } catch (\Exception $e) {
                Log::error('Loan completed email failed: ' . $e->getMessage());
            }
            NotificationService::notifyUser($freshUser, 'loan', 'Loan Fully Repaid', 'Congratulations! Your loan has been fully repaid and your collateral has been released.', url('dashboard/loans/' . $loan->id));
        }
    }


    public function viewdepositimage($id)
    {
        $deposit = Deposit::where('id', $id)->first();

        return view('admin.Deposits.depositimg', [
            'deposit' => $deposit,
            'title' => 'View Deposit Screenshot',
            'settings' => Settings::where('id', '=', '1')->first(),
        ]);
    }


    //Get uplines
    function getAncestors($array, $deposit_amount, $parent = 0, $level = 0)
    {
        $referedMembers = '';
        $parent = User::where('id', $parent)->first();

        foreach ($array as $entry) {
            if ($entry->id == $parent->ref_by) {
                //get settings 
                $settings = Settings::where('id', '=', '1')->first();

                if ($level == 1) {
                    $earnings = $settings->referral_commission1 * $deposit_amount / 100;
                    //add earnings to ancestor balance
                    User::where('id', $entry->id)
                        ->update([
                            'account_bal' => $entry->account_bal + $earnings,
                            'ref_bonus' => $entry->ref_bonus + $earnings,
                        ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'plan' => "Credit",
                        'amount' => $earnings,
                        'type' => "Ref_bonus",
                    ]);
                } elseif ($level == 2) {
                    $earnings = $settings->referral_commission2 * $deposit_amount / 100;
                    //add earnings to ancestor balance
                    User::where('id', $entry->id)
                        ->update([
                            'account_bal' => $entry->account_bal + $earnings,
                            'ref_bonus' => $entry->ref_bonus + $earnings,
                        ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'plan' => "Credit",
                        'amount' => $earnings,
                        'type' => "Ref_bonus",
                    ]);
                } elseif ($level == 3) {
                    $earnings = $settings->referral_commission3 * $deposit_amount / 100;
                    //add earnings to ancestor balance
                    User::where('id', $entry->id)
                        ->update([
                            'account_bal' => $entry->account_bal + $earnings,
                            'ref_bonus' => $entry->ref_bonus + $earnings,
                        ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'plan' => "Credit",
                        'amount' => $earnings,
                        'type' => "Ref_bonus",
                    ]);
                } elseif ($level == 4) {
                    $earnings = $settings->referral_commission4 * $deposit_amount / 100;
                    //add earnings to ancestor balance
                    User::where('id', $entry->id)
                        ->update([
                            'account_bal' => $entry->account_bal + $earnings,
                            'ref_bonus' => $entry->ref_bonus + $earnings,
                        ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'plan' => "Credit",
                        'amount' => $earnings,
                        'type' => "Ref_bonus",
                    ]);
                } elseif ($level == 5) {
                    $earnings = $settings->referral_commission5 * $deposit_amount / 100;
                    //add earnings to ancestor balance
                    User::where('id', $entry->id)
                        ->update([
                            'account_bal' => $entry->account_bal + $earnings,
                            'ref_bonus' => $entry->ref_bonus + $earnings,
                        ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'plan' => "Credit",
                        'amount' => $earnings,
                        'type' => "Ref_bonus",
                    ]);
                }

                if ($level == 6) {
                    break;
                }

                //$referedMembers .= '- ' . $entry->name . '- Level: '. $level. '- Commission: '.$earnings.'<br/>';
                $referedMembers .= $this->getAncestors($array, $deposit_amount, $entry->id, $level + 1);
            }
        }
        return $referedMembers;
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

    // Edit deposit (backdate + edit amount/status)
    public function edit(int $id)
    {
        $deposit = Deposit::findOrFail($id);
        $title = 'Edit Deposit #' . $id;
        return view('admin.Deposits.edit', compact('deposit', 'title'));
    }

    public function editDeposit(Request $request, int $id)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:0',
            'payment_mode' => 'nullable|string|max:255',
            'status'       => 'required|in:Pending,Processed',
            'created_at'   => 'nullable|date',
        ]);

        $deposit = Deposit::findOrFail($id);

        $data = [
            'amount'       => $request->amount,
            'payment_mode' => $request->payment_mode ?? $deposit->payment_mode,
            'status'       => $request->status,
            'updated_at'   => now(),
        ];
        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }
        DB::table('deposits')->where('id', $deposit->id)->update($data);

        return redirect()->back()->with('success', 'Deposit updated successfully!');
    }
}