<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'loan_plan_id', 'amount', 'credit_facility', 'duration',
        'monthly_income', 'purpose', 'status',
        'interest_rate', 'interest_type', 'processing_fee',
        'total_repayable', 'total_repaid', 'num_installments',
        'collateral_amount', 'disbursed_at', 'first_payment_date',
        'maturity_date', 'next_payment_date', 'rejection_reason',
        'approved_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'total_repayable' => 'decimal:2',
        'total_repaid' => 'decimal:2',
        'collateral_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'disbursed_at' => 'datetime',
        'first_payment_date' => 'date',
        'maturity_date' => 'date',
        'next_payment_date' => 'date',
    ];

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanPlan()
    {
        return $this->belongsTo(LoanPlan::class);
    }

    public function repaymentSchedules()
    {
        return $this->hasMany(LoanRepaymentSchedule::class)->orderBy('installment_number');
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'repaying']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('repaymentSchedules', function ($q) {
            $q->where('status', 'overdue');
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ── Accessors ──

    public function getProgressPercentageAttribute(): float
    {
        if ($this->total_repayable <= 0) {
            return 0;
        }
        return round(($this->total_repaid / $this->total_repayable) * 100, 1);
    }

    public function getRemainingBalanceAttribute(): float
    {
        return round($this->total_repayable - $this->total_repaid, 2);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->repaymentSchedules()->where('status', 'overdue')->exists();
    }

    public function getOverdueCountAttribute(): int
    {
        return $this->repaymentSchedules()->where('status', 'overdue')->count();
    }

    public function getNextInstallmentAttribute()
    {
        return $this->repaymentSchedules()->unpaid()->first();
    }

    // ── Collateral Management ──

    /**
     * Release frozen collateral back to user's available balance.
     * Called when loan is completed.
     */
    public function releaseCollateral(): void
    {
        if ($this->collateral_amount > 0) {
            $user = $this->user;
            $user->frozen_bal = max(0, $user->frozen_bal - $this->collateral_amount);
            $user->save();
        }
    }

    /**
     * Liquidate collateral: deduct from frozen_bal and account_bal.
     * Called when loan is defaulted.
     */
    public function liquidateCollateral(): void
    {
        if ($this->collateral_amount > 0) {
            $user = $this->user;
            $user->frozen_bal = max(0, $user->frozen_bal - $this->collateral_amount);
            $user->account_bal = max(0, $user->account_bal - $this->collateral_amount);
            $user->save();
        }
    }

    // ── Schedule Generation ──

    /**
     * Generate the full repayment schedule after loan approval.
     * Must be called within a DB transaction.
     */
    public function generateRepaymentSchedule(): void
    {
        $plan = $this->loanPlan;
        $principal = $this->approved_amount ?? $this->amount;
        $duration = $this->num_installments ?? $this->duration;
        $startDate = Carbon::parse($this->first_payment_date);

        $preview = $plan->generateAmortizationPreview($principal, $duration);

        foreach ($preview as $item) {
            $this->repaymentSchedules()->create([
                'installment_number' => $item['installment_number'],
                'due_date' => $startDate->copy()->addMonths($item['installment_number'] - 1),
                'principal_amount' => $item['principal_amount'],
                'interest_amount' => $item['interest_amount'] + ($item['fee_portion'] ?? 0),
                'total_amount' => $item['total_amount'],
                'status' => 'upcoming',
            ]);
        }

        // Update loan metadata
        $totalRepayable = collect($preview)->sum('total_amount');
        $this->update([
            'total_repayable' => $totalRepayable,
            'maturity_date' => $startDate->copy()->addMonths($duration - 1),
            'next_payment_date' => $startDate,
        ]);
    }
}
