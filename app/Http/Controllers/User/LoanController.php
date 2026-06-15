<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\LoanCompletedMail;
use App\Mail\LoanRepaymentMail;
use App\Mail\LoanRequestMail;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\Settings;
use App\Models\User;
use App\Models\Wdmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Helpers\CurrencyHelper;

class LoanController extends Controller
{
    /**
     * List all loans for the authenticated user.
     */
    public function index()
    {
        $settings = Settings::find(1);
        $title = 'My Loans';
        $user = Auth::user();

        $loans = Loan::where('user_id', $user->id)
            ->with('loanPlan')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'active_count' => $loans->whereIn('status', ['active', 'repaying'])->count(),
            'total_borrowed' => $loans->whereNotNull('approved_amount')->sum('approved_amount'),
            'total_repaid' => $loans->sum('total_repaid'),
            'pending_count' => $loans->where('status', 'pending')->count(),
        ];

        return view('user.loans.my_loans', compact('loans', 'settings', 'title', 'stats'));
    }

    /**
     * Show the loan application form with available plans.
     */
    public function create()
    {
        $settings = Settings::find(1);
        $title = 'Apply for Loan';
        $plans = LoanPlan::active()->get();

        return view('user.loans.apply', compact('title', 'settings', 'plans'));
    }

    /**
     * Store a new loan application.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_plan_id' => 'required|exists:loan_plans,id',
            'amount' => 'required|numeric|min:1',
            'duration' => 'required|integer|min:1',
            'purpose' => 'required|string|max:1000',
        ]);

        $user = User::find(Auth::id());
        $plan = LoanPlan::findOrFail($request->loan_plan_id);

        // Validate plan is active
        if (!$plan->is_active) {
            return back()->with('error', 'This loan plan is currently unavailable.');
        }

        // Convert user-entered amount to USD
        $amountUsd = CurrencyHelper::toUsd($request->amount);

        // Eligibility checks
        $errors = $this->validateEligibility($user, $plan, $amountUsd, $request->duration);
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $loan = Loan::create([
            'user_id' => $user->id,
            'loan_plan_id' => $plan->id,
            'amount' => $amountUsd,
            'duration' => $request->duration,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        try {
            Mail::to($user->email)->send(new LoanRequestMail($loan, $user, 'Loan Request Submitted'));
        } catch (\Exception $e) {
            Log::error('Loan request email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($user, 'loan', 'Loan Application Submitted', 'Your loan application for ' . CurrencyHelper::formatForUser($amountUsd) . ' has been submitted and is pending review.', url('dashboard/loans'));

        \App\Services\NotificationService::notifyAdmin('loan', 'New Loan Application', $user->name . ' applied for a loan of $' . number_format($amountUsd, 2) . '.', url('admin/loans'));

        return redirect()->route('loans.my')->with('success', 'Loan application submitted successfully. You will be notified once reviewed.');
    }

    /**
     * Show a single loan with repayment schedule.
     */
    public function show(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        $loan->load(['loanPlan', 'repaymentSchedules']);
        $settings = Settings::find(1);
        $title = 'Loan #' . $loan->id;

        // Manual deposit methods for "Pay via Deposit" option
        $dmethods = Wdmethod::where(function ($q) {
                $q->where('type', 'deposit')->orWhere('type', 'both');
            })
            ->where('status', 'enabled')
            ->where(function ($q) {
                $q->where('methodtype', '!=', 'currency')
                  ->orWhere('name', 'Bank Transfer');
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('user.loans.show', compact('loan', 'settings', 'title', 'dmethods'));
    }

    /**
     * Make a repayment on a loan installment.
     */
    public function repay(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($loan->status, ['active', 'repaying'])) {
            return back()->with('error', 'This loan is not eligible for repayment.');
        }

        $request->validate([
            'schedule_id' => 'required|exists:loan_repayment_schedules,id',
        ]);

        $schedule = $loan->repaymentSchedules()->findOrFail($request->schedule_id);

        if ($schedule->status === 'paid') {
            return back()->with('error', 'This installment has already been paid.');
        }

        $amountDue = $schedule->total_due;
        $user = User::find(Auth::id());

        if ($user->available_bal < $amountDue) {
            return back()->with('error', "Insufficient available balance. You need {$amountDue} but have " . number_format($user->available_bal, 2));
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $loan, $schedule, $amountDue) {
            // Debit user account
            $user->account_bal -= $amountDue;
            $user->save();

            // Mark schedule as paid
            $schedule->update([
                'status' => 'paid',
                'paid_amount' => $schedule->total_amount + $schedule->late_fee,
                'paid_at' => now(),
            ]);

            // Update loan totals
            $loan->total_repaid = $loan->repaymentSchedules()->where('status', 'paid')->sum('paid_amount');
            $loan->save();

            // Update next payment date
            $nextUnpaid = $loan->repaymentSchedules()->unpaid()->first();
            if ($nextUnpaid) {
                $loan->update([
                    'status' => 'repaying',
                    'next_payment_date' => $nextUnpaid->due_date,
                ]);
            } else {
                // All installments paid — loan complete
                $loan->update([
                    'status' => 'completed',
                    'next_payment_date' => null,
                ]);
                // Release frozen collateral
                $loan->releaseCollateral();
            }
        });

        // Send confirmation email
        $schedule->refresh();
        $loan->refresh();
        $user = User::find(Auth::id());
        try {
            Mail::to($user->email)->send(new LoanRepaymentMail($loan, $user, $schedule, 'Loan Payment Received'));
        } catch (\Exception $e) {
            Log::error('Loan repayment email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($user, 'loan', 'Loan Payment Received', 'Your loan repayment of $' . number_format($amountDue, 2) . ' has been recorded.', url('dashboard/loans/' . $loan->id));
        \App\Services\NotificationService::notifyAdmin('loan', 'Loan Repayment Received', $user->name . ' made a loan repayment of $' . number_format($amountDue, 2) . '.', url('admin/loans'));

        // If loan is completed, send completion email
        if ($loan->status === 'completed') {
            try {
                Mail::to($user->email)->send(new LoanCompletedMail($loan, $user, 'Loan Fully Repaid'));
            } catch (\Exception $e) {
                Log::error('Loan completed email failed: ' . $e->getMessage());
            }

            NotificationService::notifyUser($user, 'loan', 'Loan Fully Repaid', 'Congratulations! Your loan has been fully repaid and your collateral has been released.', url('dashboard/loans/' . $loan->id));
            \App\Services\NotificationService::notifyAdmin('loan', 'Loan Fully Repaid', $user->name . ' has fully repaid their loan.', url('admin/loans'));
        }

        return back()->with('success', 'Payment of ' . number_format($amountDue, 2) . ' recorded successfully.');
    }

    /**
     * Initiate loan repayment via a new deposit.
     * Stores loan context in session and redirects to the payment page.
     */
    public function repayViaDeposit(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($loan->status, ['active', 'repaying'])) {
            return back()->with('error', 'This loan is not eligible for repayment.');
        }

        $request->validate([
            'schedule_id' => 'required|exists:loan_repayment_schedules,id',
            'payment_method' => 'required|exists:wdmethods,id',
        ]);

        $schedule = $loan->repaymentSchedules()->findOrFail($request->schedule_id);

        if ($schedule->status === 'paid') {
            return back()->with('error', 'This installment has already been paid.');
        }

        // Validate the payment method is a manual deposit method
        $method = Wdmethod::where('id', $request->payment_method)
            ->where('status', 'enabled')
            ->where(function ($q) {
                $q->where('type', 'deposit')->orWhere('type', 'both');
            })
            ->where(function ($q) {
                $q->where('methodtype', '!=', 'currency')
                  ->orWhere('name', 'Bank Transfer');
            })
            ->first();

        if (!$method) {
            return back()->with('error', 'Invalid payment method selected.');
        }

        // Store loan repayment context in session
        $request->session()->put('amount', $schedule->total_due);
        $request->session()->put('payment_mode', $method->name);
        $request->session()->put('intent', '');
        $request->session()->put('loan_repayment', true);
        $request->session()->put('loan_repay_schedule_id', $schedule->id);
        $request->session()->put('loan_id', $loan->id);
        $request->session()->put('loan_installment_number', $schedule->installment_number);

        return redirect()->route('payment');
    }

    /**
     * AJAX: Return loan preview calculations for a given plan, amount, duration.
     */
    public function calculatePreview(Request $request)
    {
        $request->validate([
            'loan_plan_id' => 'required|exists:loan_plans,id',
            'amount' => 'required|numeric|min:1',
            'duration' => 'required|integer|min:1',
        ]);

        $plan = LoanPlan::findOrFail($request->loan_plan_id);
        $amount = (float) $request->amount;
        $duration = (int) $request->duration;

        $interest = $plan->calculateInterest($amount, $duration);
        $processingFee = $plan->calculateProcessingFee($amount);
        $monthlyPayment = $plan->calculateMonthlyPayment($amount, $duration);
        $totalRepayable = $amount + $interest + $processingFee;

        return response()->json([
            'interest_rate' => $plan->interest_rate,
            'interest_type' => $plan->interest_type,
            'total_interest' => round($interest, 2),
            'processing_fee' => round($processingFee, 2),
            'monthly_payment' => round($monthlyPayment, 2),
            'total_repayable' => round($totalRepayable, 2),
        ]);
    }

    /**
     * Validate user eligibility against a loan plan.
     * Returns array of error messages, empty if eligible.
     */
    private function validateEligibility(User $user, LoanPlan $plan, float $amount, int $duration): array
    {
        $errors = [];

        // Max active loans
        $activeLoanCount = Loan::where('user_id', $user->id)
            ->where('loan_plan_id', $plan->id)
            ->active()
            ->count();
        if ($activeLoanCount >= $plan->max_active_loans) {
            $errors[] = "You already have {$activeLoanCount} active loan(s) of this type. Maximum allowed: {$plan->max_active_loans}.";
        }

        // Minimum balance
        if ($user->account_bal < $plan->min_account_balance) {
            $errors[] = 'Your account balance (' . CurrencyHelper::formatForUser($user->account_bal) . ') is below the minimum required (' . CurrencyHelper::formatForUser($plan->min_account_balance) . ').';
        }

        // Amount range
        if ($amount < $plan->min_amount || $amount > $plan->max_amount) {
            $errors[] = 'Loan amount must be between ' . CurrencyHelper::formatForUser($plan->min_amount) . ' and ' . CurrencyHelper::formatForUser($plan->max_amount) . '.';
        }

        // Duration range
        if ($duration < $plan->min_duration || $duration > $plan->max_duration) {
            $errors[] = "Duration must be between {$plan->min_duration} and {$plan->max_duration} months.";
        }

        // No defaulted loans
        $hasDefault = Loan::where('user_id', $user->id)->where('status', 'defaulted')->exists();
        if ($hasDefault) {
            $errors[] = 'You cannot apply for loans while you have a defaulted loan on record.';
        }

        return $errors;
    }
}
