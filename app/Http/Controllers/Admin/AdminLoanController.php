<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LoanApprovedMail;
use App\Mail\LoanRejectedMail;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;

class AdminLoanController extends Controller
{
    // ──────────────────────────────────────
    //  LOAN PLAN CRUD
    // ──────────────────────────────────────

    public function index(Request $request)
    {
        $settings = Settings::find(1);
        $title = 'Manage Loans';
        $tab = $request->get('tab', 'plans');

        $plans = LoanPlan::withCount(['loans as active_loans_count' => function ($q) {
            $q->whereIn('status', ['active', 'repaying']);
        }])->latest()->get();

        $loansQuery = Loan::with(['user', 'loanPlan']);

        if ($tab === 'pending') {
            $loansQuery->where('status', 'pending');
        } elseif ($tab === 'active') {
            $loansQuery->whereIn('status', ['active', 'repaying']);
        } elseif ($tab === 'completed') {
            $loansQuery->where('status', 'completed');
        } elseif ($tab === 'defaulted') {
            $loansQuery->where('status', 'defaulted');
        } elseif ($tab === 'all') {
            // no filter
        } else {
            // plans tab — loans not needed
            $loansQuery->where('status', 'pending');
        }

        $loans = $loansQuery->latest()->get();

        // Summary stats
        $stats = [
            'total_disbursed' => Loan::whereNotNull('disbursed_at')->sum('approved_amount'),
            'total_outstanding' => Loan::whereIn('status', ['active', 'repaying'])->selectRaw('SUM(total_repayable - total_repaid) as total')->value('total') ?? 0,
            'total_collected' => Loan::sum('total_repaid'),
            'default_count' => Loan::where('status', 'defaulted')->count(),
            'pending_count' => Loan::where('status', 'pending')->count(),
        ];

        return view('admin.loans.index', compact('settings', 'title', 'tab', 'plans', 'loans', 'stats'));
    }

    public function createPlan()
    {
        $settings = Settings::find(1);
        $title = 'Create Loan Plan';
        $plan = null;
        return view('admin.loans.plan_form', compact('settings', 'title', 'plan'));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'interest_type' => 'required|in:simple,compound',
            'min_duration' => 'required|integer|min:1',
            'max_duration' => 'required|integer|gte:min_duration',
            'max_active_loans' => 'required|integer|min:1',
            'min_account_balance' => 'required|numeric|min:0',
            'requires_collateral' => 'sometimes|boolean',
            'collateral_percentage' => 'nullable|numeric|min:0|max:100',
            'processing_fee' => 'required|numeric|min:0|max:100',
            'grace_period_days' => 'required|integer|min:0',
            'late_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $validated['requires_collateral'] = $request->has('requires_collateral');

        LoanPlan::create($validated);

        return redirect()->route('admin.loans.index', ['tab' => 'plans'])->with('success', 'Loan plan created successfully.');
    }

    public function editPlan(LoanPlan $plan)
    {
        $settings = Settings::find(1);
        $title = 'Edit Loan Plan: ' . $plan->name;
        return view('admin.loans.plan_form', compact('settings', 'title', 'plan'));
    }

    public function updatePlan(Request $request, LoanPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'interest_type' => 'required|in:simple,compound',
            'min_duration' => 'required|integer|min:1',
            'max_duration' => 'required|integer|gte:min_duration',
            'max_active_loans' => 'required|integer|min:1',
            'min_account_balance' => 'required|numeric|min:0',
            'requires_collateral' => 'sometimes|boolean',
            'collateral_percentage' => 'nullable|numeric|min:0|max:100',
            'processing_fee' => 'required|numeric|min:0|max:100',
            'grace_period_days' => 'required|integer|min:0',
            'late_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $validated['requires_collateral'] = $request->has('requires_collateral');

        $plan->update($validated);

        return redirect()->route('admin.loans.index', ['tab' => 'plans'])->with('success', 'Loan plan updated successfully.');
    }

    public function togglePlan(LoanPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        $status = $plan->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Loan plan {$status}.");
    }

    // ──────────────────────────────────────
    //  LOAN APPLICATION MANAGEMENT
    // ──────────────────────────────────────

    public function show(Loan $loan)
    {
        $loan->load(['user', 'loanPlan', 'repaymentSchedules']);
        $settings = Settings::find(1);
        $title = 'Loan #' . $loan->id . ' Details';

        // Eligibility info for pending loans
        $eligibility = null;
        if ($loan->status === 'pending' && $loan->loanPlan) {
            $eligibility = $this->checkEligibility($loan->user, $loan->loanPlan, $loan->amount, $loan->duration);
        }

        return view('admin.loans.show', compact('loan', 'settings', 'title', 'eligibility'));
    }

    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('message', 'This loan is not pending approval.');
        }

        $approvedAmount = $request->input('approved_amount', $loan->amount);

        DB::transaction(function () use ($loan, $approvedAmount) {
            $plan = $loan->loanPlan;
            $user = $loan->user;
            $duration = $loan->duration;

            // Calculate financials
            $interestTotal = $plan->calculateInterest($approvedAmount, $duration);
            $processingFee = $plan->calculateProcessingFee($approvedAmount);
            $totalRepayable = $approvedAmount + $interestTotal + $processingFee;

            // Calculate collateral if required
            $collateralAmount = 0;
            if ($plan->requires_collateral && $plan->collateral_percentage > 0) {
                $collateralAmount = round($approvedAmount * ($plan->collateral_percentage / 100), 2);
                if ($user->available_bal < $collateralAmount) {
                    throw new \Exception('Insufficient available balance for collateral. Needs ' . $collateralAmount . ', available: ' . $user->available_bal);
                }
                $user->frozen_bal += $collateralAmount;
                $user->save();
            }

            // Update loan record
            $loan->update([
                'status' => 'active',
                'approved_amount' => $approvedAmount,
                'interest_rate' => $plan->interest_rate,
                'interest_type' => $plan->interest_type,
                'processing_fee' => $processingFee,
                'total_repayable' => $totalRepayable,
                'num_installments' => $duration,
                'collateral_amount' => $collateralAmount,
                'disbursed_at' => now(),
                'first_payment_date' => now()->addMonth()->startOfDay(),
            ]);

            // Generate repayment schedule
            $loan->generateRepaymentSchedule();

            // Credit user account
            $user->account_bal += $approvedAmount;
            $user->save();
        });

        // Send email outside transaction
        $loan->refresh();
        $user = $loan->user;
        try {
            Mail::to($user->email)->send(new LoanApprovedMail($loan, $user, 'Loan Application Approved'));
        } catch (\Exception $e) {
            Log::error('Loan approved email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($user, 'loan', 'Loan Approved', 'Your loan application for $' . number_format($loan->approved_amount, 2) . ' has been approved and funds have been credited to your account.', url('dashboard/loans/' . $loan->id));

        return back()->with('success', 'Loan approved, schedule generated, and funds credited.');
    }

    public function reject(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('message', 'This loan is not pending.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $loan->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        $user = $loan->user;
        try {
            Mail::to($user->email)->send(new LoanRejectedMail($loan, $user, 'Loan Application Rejected'));
        } catch (\Exception $e) {
            Log::error('Loan rejected email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser($user, 'loan', 'Loan Rejected', 'Your loan application has been rejected. Reason: ' . $request->rejection_reason, url('dashboard/loans'));

        return back()->with('success', 'Loan application rejected.');
    }

    public function markDefaulted(Loan $loan)
    {
        if (!in_array($loan->status, ['active', 'repaying'])) {
            return back()->with('message', 'Only active loans can be marked as defaulted.');
        }

        $loan->update(['status' => 'defaulted']);
        $loan->liquidateCollateral();

        return back()->with('success', 'Loan marked as defaulted.');
    }

    // ──────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────

    private function checkEligibility($user, LoanPlan $plan, float $amount, int $duration): array
    {
        $checks = [];

        // 1. Active loan count
        $activeLoanCount = Loan::where('user_id', $user->id)
            ->where('loan_plan_id', $plan->id)
            ->active()
            ->count();
        $checks['max_active_loans'] = [
            'label' => "Max {$plan->max_active_loans} active loans of this type",
            'passed' => $activeLoanCount < $plan->max_active_loans,
            'detail' => "Currently has {$activeLoanCount}",
        ];

        // 2. Minimum balance
        $checks['min_balance'] = [
            'label' => "Minimum account balance: " . number_format($plan->min_account_balance, 2),
            'passed' => $user->account_bal >= $plan->min_account_balance,
            'detail' => "Balance: " . number_format($user->account_bal, 2),
        ];

        // 3. Amount range
        $checks['amount_range'] = [
            'label' => "Amount between " . number_format($plan->min_amount, 2) . " – " . number_format($plan->max_amount, 2),
            'passed' => $amount >= $plan->min_amount && $amount <= $plan->max_amount,
            'detail' => "Requested: " . number_format($amount, 2),
        ];

        // 4. Duration range
        $checks['duration_range'] = [
            'label' => "Duration between {$plan->min_duration} – {$plan->max_duration} months",
            'passed' => $duration >= $plan->min_duration && $duration <= $plan->max_duration,
            'detail' => "Requested: {$duration} months",
        ];

        // 5. No defaulted loans
        $hasDefault = Loan::where('user_id', $user->id)->where('status', 'defaulted')->exists();
        $checks['no_defaults'] = [
            'label' => 'No defaulted loans',
            'passed' => !$hasDefault,
            'detail' => $hasDefault ? 'Has defaulted loans' : 'Clear',
        ];

        return $checks;
    }

    // Edit loan (backdate + edit amount/status)
    public function editLoan(Loan $loan)
    {
        $title = 'Edit Loan #' . $loan->id;
        return view('admin.loans.edit', compact('loan', 'title'));
    }

    public function updateLoan(Request $request, Loan $loan)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:0',
            'status'       => 'required|in:pending,active,repaying,completed,rejected,defaulted',
            'disbursed_at' => 'nullable|date',
            'maturity_date'=> 'nullable|date',
            'created_at'   => 'nullable|date',
        ]);

        $data = [
            'amount'        => $request->amount,
            'status'        => $request->status,
            'disbursed_at'  => $request->disbursed_at ? Carbon::parse($request->disbursed_at) : $loan->disbursed_at,
            'maturity_date' => $request->maturity_date ? Carbon::parse($request->maturity_date) : $loan->maturity_date,
            'updated_at'    => now(),
        ];
        if ($request->filled('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at);
        }
        DB::table('loans')->where('id', $loan->id)->update($data);

        return redirect()->route('admin.loans.show', $loan->id)->with('success', 'Loan updated successfully!');
    }
}
