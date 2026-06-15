<?php

namespace App\Http\Livewire\User;

use App\Mail\NewNotification;
use App\Services\NotificationService;
use App\Models\Plans;
use App\Models\Settings;
use App\Models\Tp_Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Helpers\CurrencyHelper;

class InvestmentPlan extends Component
{
    public Plans $planSelected;
    public $amountToInvest = 0;
    public $disabled = 'disabled';
    public $paymentMethod;
    public $feedback = '';

    protected $listeners = ['selectPlanById'];

    public function selectPlanById($id)
    {
        $plan = Plans::find($id);
        if ($plan) {
            $this->planSelected = $plan;
            $this->amountToInvest = $plan->min_price;
            $this->paymentMethod = 'Account Balance';
            $this->disabled = '';
        }
    }

    public function mount()
    {
        $this->paymentMethod = 'Account Balance';
        $lastPlan = Plans::orderByDesc('id')->first();
        if ($lastPlan) {
            $this->planSelected = $lastPlan;
        }
    }

    public function render()
    {
        return view('livewire.user.investment-plan', [
            'plans' => Plans::orderByDesc('id')->get(),
        ]);
    }

    public function selectPlan($id)
    {
        $this->planSelected = Plans::find($id);
        if ($this->paymentMethod and $this->amountToInvest and $this->planSelected) {
            $this->disabled = '';
        } else {
            $this->disabled = 'disabled';
        }
    }

    public function chanegePaymentMethod($method)
    {

        $this->paymentMethod = $method;

        if ($this->amountToInvest and $this->planSelected and $this->paymentMethod) {
            $this->disabled = '';
        } else {
            $this->disabled = 'disabled';
        }
    }

    public function selectAmount($value)
    {
        $this->amountToInvest = intval($value);

        if ($this->paymentMethod and $this->planSelected and ($this->amountToInvest or empty($this->amountToInvest))) {
            $this->disabled = '';
        } else {
            $this->disabled = 'disabled';
        }
    }

    public function checkIfAmountIsEmpty()
    {
        if ($this->paymentMethod and $this->planSelected and ($this->amountToInvest or empty($this->amountToInvest))) {
            $this->disabled = '';
        } else {
            $this->disabled = 'disabled';
        }
    }


    public function joinPlan()
    {
        $plan = Plans::where('id', $this->planSelected->id)->where('type', 'Main')->first();
        if (!$plan) {
            session()->flash('message', 'Invalid plan selected.');
            return;
        }

        if (empty($this->amountToInvest)) {
            session()->flash('message', 'Enter Amount to invest');
            return;
        }
        if (!$this->paymentMethod) {
            session()->flash('message', 'Choose a Payment Method');
            return;
        }

        $plan_price = CurrencyHelper::toUsd(floatval($this->amountToInvest));

        if ($plan_price < $plan->min_price || $plan_price > $plan->max_price) {
            session()->flash('message', 'Amount must be between ' . CurrencyHelper::formatForUser($plan->min_price) . ' and ' . CurrencyHelper::formatForUser($plan->max_price));
            $this->amountToInvest = 0;
            return;
        }

        $expiration = explode(" ", $plan->expiration);
        $digit = $expiration[0];
        $frame = $expiration[1];
        $toexpire = "add" . $frame;
        $end_at = Carbon::now()->$toexpire($digit)->toDateTimeString();

        try {
            DB::transaction(function () use ($plan, $plan_price, $end_at) {
                $user = User::where('id', Auth::id())->lockForUpdate()->first();

                if ($user->account_bal < $plan_price) {
                    throw new \Exception('Your account is insufficient to purchase this plan. Please make a deposit.');
                }

                // Debit user
                $newBal = $user->account_bal - $plan_price;

                // Credit plan bonus
                if ($plan->gift > 0) {
                    $newBal += $plan->gift;
                    User::where('id', $user->id)->update([
                        'bonus' => $user->bonus + $plan->gift,
                        'account_bal' => $newBal,
                    ]);
                    $user->refresh();

                    Tp_Transaction::create([
                        'user' => $user->id,
                        'plan' => $plan->name,
                        'amount' => $plan->gift,
                        'type' => 'Gift Bonus',
                    ]);
                } else {
                    User::where('id', $user->id)->update([
                        'account_bal' => $newBal,
                    ]);
                }

                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => $plan->name,
                    'amount' => $plan_price,
                    'type' => 'Plan purchase',
                ]);

                $userplanid = DB::table('user_plans')->insertGetId([
                    'plan' => $plan->id,
                    'user' => $user->id,
                    'amount' => $plan_price,
                    'active' => 'yes',
                    'inv_duration' => $plan->expiration,
                    'expire_date' => $end_at,
                    'activated_at' => Carbon::now(),
                    'last_growth' => Carbon::now(),
                    'profit_earned' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                User::where('id', $user->id)->update([
                    'plan' => $plan->id,
                    'user_plan' => $userplanid,
                    'entered_at' => Carbon::now(),
                ]);
            });

            // Send notification outside transaction
            $user = Auth::user();
            $settings = Settings::find(1);
            $message = "This is to inform you that $user->name just purchased an investment plan: $plan->name";
            $subject = "$user->name just purchased an investment plan";
            try {
                Mail::to($settings->contact_email)->send(new NewNotification($message, $subject, 'Admin'));
            } catch (\Exception $e) {
                Log::error('Investment plan admin email failed: ' . $e->getMessage());
            }

            NotificationService::notifyUser($user, 'investment', 'Plan Activated', 'You have successfully subscribed to the ' . $plan->name . ' investment plan.', url('dashboard/mplans'));

            session()->flash('success', 'You have successfully purchased a plan and your plan is now active.');
            $this->amountToInvest = 0;
            $this->disabled = 'disabled';
            $this->planSelected = Plans::orderByDesc('id')->first();
            $this->paymentMethod = 'Account Balance';
        } catch (\Exception $e) {
            session()->flash('message', $e->getMessage());
        }
    }
}