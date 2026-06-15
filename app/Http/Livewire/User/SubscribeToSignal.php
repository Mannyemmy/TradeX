<?php

namespace App\Http\Livewire\User;

use App\Models\SignalPlan;
use App\Models\SignalSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscribeToSignal extends Component
{
    public $planId;
    public $amount;
    public $hasSubscribe;
    public $plans;

    public function mount()
    {
        $this->planId = '';
        $this->hasSubscribe = false;
        $this->plans = SignalPlan::all();
    }

    public function render()
    {
        return view('livewire.user.subscribe-to-signal');
    }

    public function calculate()
    {
        if ($this->planId && $this->planId !== 'Choose') {
            $plan = SignalPlan::find($this->planId);
            $this->amount = $plan ? $plan->price : '';
        } else {
            $this->amount = '';
        }
    }

    public function subscribe()
    {
        $plan = SignalPlan::find($this->planId);

        if (!$plan) {
            session()->flash('message', 'Please select a valid plan.');
            return;
        }

        $user = User::find(Auth::id());

        if ($user->account_bal < $plan->price) {
            session()->flash('message', 'Insufficient balance to subscribe.');
            return;
        }

        $user->account_bal -= $plan->price;
        $user->save();

        SignalSubscription::create([
            'user_id' => $user->id,
            'signal_plan_id' => $plan->id,
            'expires_at' => now()->addDays($plan->duration),
        ]);

        $this->hasSubscribe = true;
        session()->flash('success', 'You have successfully subscribed to trading signals!');
    }
}
