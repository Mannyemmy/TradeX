<?php

namespace Database\Seeders;

use App\Models\LoanPlan;
use Illuminate\Database\Seeder;

class LoanPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Trading Margin Loan',
                'description' => 'Short-term leverage for active traders. Higher amounts, lower rates, designed for quick market opportunities.',
                'min_amount' => 5000,
                'max_amount' => 500000,
                'interest_rate' => 3.50,
                'interest_type' => 'simple',
                'min_duration' => 1,
                'max_duration' => 12,
                'max_active_loans' => 2,
                'min_account_balance' => 1000,
                'processing_fee' => 0.50,
                'grace_period_days' => 3,
                'late_fee_percentage' => 2.00,
            ],
            [
                'name' => 'Personal Loan',
                'description' => 'General-purpose loan for personal needs. Medium amounts with flexible terms.',
                'min_amount' => 1000,
                'max_amount' => 100000,
                'interest_rate' => 8.00,
                'interest_type' => 'compound',
                'min_duration' => 3,
                'max_duration' => 36,
                'max_active_loans' => 1,
                'min_account_balance' => 500,
                'processing_fee' => 1.00,
                'grace_period_days' => 5,
                'late_fee_percentage' => 1.50,
            ],
            [
                'name' => 'Business Expansion Loan',
                'description' => 'Long-term financing for business growth and investment expansion.',
                'min_amount' => 10000,
                'max_amount' => 1000000,
                'interest_rate' => 6.00,
                'interest_type' => 'compound',
                'min_duration' => 6,
                'max_duration' => 60,
                'max_active_loans' => 1,
                'min_account_balance' => 5000,
                'processing_fee' => 1.50,
                'grace_period_days' => 7,
                'late_fee_percentage' => 1.00,
                'requires_collateral' => true,
                'collateral_percentage' => 10.00,
            ],
            [
                'name' => 'Quick Cash Loan',
                'description' => 'Small, short-term loan for immediate needs. Fast approval, higher rate.',
                'min_amount' => 100,
                'max_amount' => 10000,
                'interest_rate' => 12.00,
                'interest_type' => 'simple',
                'min_duration' => 1,
                'max_duration' => 6,
                'max_active_loans' => 1,
                'min_account_balance' => 0,
                'processing_fee' => 2.00,
                'grace_period_days' => 0,
                'late_fee_percentage' => 3.00,
            ],
            [
                'name' => 'Portfolio Leverage Loan',
                'description' => 'Leverage your trading portfolio with competitive rates. Designed for experienced investors.',
                'min_amount' => 25000,
                'max_amount' => 2000000,
                'interest_rate' => 4.50,
                'interest_type' => 'compound',
                'min_duration' => 3,
                'max_duration' => 24,
                'max_active_loans' => 1,
                'min_account_balance' => 10000,
                'processing_fee' => 0.75,
                'grace_period_days' => 5,
                'late_fee_percentage' => 1.50,
                'requires_collateral' => true,
                'collateral_percentage' => 15.00,
            ],
        ];

        foreach ($plans as $plan) {
            LoanPlan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
