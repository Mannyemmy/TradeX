<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'min_amount', 'max_amount',
        'interest_rate', 'interest_type', 'min_duration', 'max_duration',
        'max_active_loans', 'min_account_balance', 'requires_collateral',
        'collateral_percentage', 'processing_fee', 'grace_period_days',
        'late_fee_percentage', 'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'min_account_balance' => 'decimal:2',
        'collateral_percentage' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'late_fee_percentage' => 'decimal:2',
        'requires_collateral' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── Relationships ──

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Interest Calculations ──

    /**
     * Calculate total interest for a given principal and duration.
     */
    public function calculateInterest(float $principal, int $durationMonths): float
    {
        if ($this->interest_type === 'compound') {
            $monthlyRate = $this->interest_rate / 100 / 12;
            $monthlyPayment = $this->calculateMonthlyPayment($principal, $durationMonths);
            return round(($monthlyPayment * $durationMonths) - $principal, 2);
        }

        // Simple interest
        return round($principal * ($this->interest_rate / 100) * ($durationMonths / 12), 2);
    }

    /**
     * Calculate the monthly payment amount.
     */
    public function calculateMonthlyPayment(float $principal, int $durationMonths): float
    {
        if ($this->interest_rate <= 0) {
            return round($principal / $durationMonths, 2);
        }

        if ($this->interest_type === 'compound') {
            $monthlyRate = $this->interest_rate / 100 / 12;
            $factor = pow(1 + $monthlyRate, $durationMonths);
            return round($principal * ($monthlyRate * $factor) / ($factor - 1), 2);
        }

        // Simple interest: total / months
        $totalInterest = $this->calculateInterest($principal, $durationMonths);
        return round(($principal + $totalInterest) / $durationMonths, 2);
    }

    /**
     * Calculate the one-time processing fee amount.
     */
    public function calculateProcessingFee(float $principal): float
    {
        return round($principal * ($this->processing_fee / 100), 2);
    }

    /**
     * Generate a full amortization preview (array of installments).
     */
    public function generateAmortizationPreview(float $principal, int $durationMonths): array
    {
        $schedule = [];
        $processingFee = $this->calculateProcessingFee($principal);
        $feePerInstallment = round($processingFee / $durationMonths, 2);

        if ($this->interest_type === 'compound') {
            $monthlyRate = $this->interest_rate / 100 / 12;
            $monthlyPayment = $this->calculateMonthlyPayment($principal, $durationMonths);
            $balance = $principal;

            for ($i = 1; $i <= $durationMonths; $i++) {
                $interestPortion = round($balance * $monthlyRate, 2);
                $principalPortion = round($monthlyPayment - $interestPortion, 2);

                // Last installment: adjust for rounding
                if ($i === $durationMonths) {
                    $principalPortion = round($balance, 2);
                    $interestPortion = round($monthlyPayment - $principalPortion, 2);
                    if ($interestPortion < 0) $interestPortion = 0;
                }

                $total = round($principalPortion + $interestPortion + $feePerInstallment, 2);
                $balance -= $principalPortion;

                $schedule[] = [
                    'installment_number' => $i,
                    'principal_amount' => $principalPortion,
                    'interest_amount' => $interestPortion,
                    'fee_portion' => $feePerInstallment,
                    'total_amount' => $total,
                    'remaining_balance' => round(max($balance, 0), 2),
                ];
            }
        } else {
            // Simple interest: equal principal, decreasing interest
            $principalPortion = round($principal / $durationMonths, 2);
            $balance = $principal;

            for ($i = 1; $i <= $durationMonths; $i++) {
                // Last installment: absorb rounding difference
                if ($i === $durationMonths) {
                    $principalPortion = round($balance, 2);
                }

                $interestPortion = round($balance * ($this->interest_rate / 100 / 12), 2);
                $total = round($principalPortion + $interestPortion + $feePerInstallment, 2);
                $balance -= $principalPortion;

                $schedule[] = [
                    'installment_number' => $i,
                    'principal_amount' => $principalPortion,
                    'interest_amount' => $interestPortion,
                    'fee_portion' => $feePerInstallment,
                    'total_amount' => $total,
                    'remaining_balance' => round(max($balance, 0), 2),
                ];
            }
        }

        return $schedule;
    }
}
