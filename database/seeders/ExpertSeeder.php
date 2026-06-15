<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Seeder;

class ExpertSeeder extends Seeder
{
    public function run()
    {
        $experts = [
            [
                'name' => 'Marcus Chen',
                'bio' => 'Former quantitative analyst at Goldman Sachs with 12 years of experience in algorithmic trading. Specializes in cryptocurrency and forex markets with a data-driven approach to risk management.',
                'area_of_expertise' => 'Crypto & Forex',
                'daily_roi' => 1.85,
                'duration_days' => 30,
                'win_rate' => 78.50,
                'min_startup_capital' => 500.00,
                'max_capital' => 50000.00,
                'profit_share_percentage' => 15.00,
                'total_profit' => 124500.00,
                'followers_count' => 342,
                'total_roi' => 67.20,
                'is_active' => true,
            ],
            [
                'name' => 'Elena Volkov',
                'bio' => 'Certified Financial Analyst and commodities trading specialist. 8 years of experience trading gold, oil, and agricultural futures. Known for conservative yet consistent returns.',
                'area_of_expertise' => 'Commodities',
                'daily_roi' => 1.20,
                'duration_days' => 60,
                'win_rate' => 82.00,
                'min_startup_capital' => 1000.00,
                'max_capital' => 100000.00,
                'profit_share_percentage' => 12.00,
                'total_profit' => 210800.00,
                'followers_count' => 518,
                'total_roi' => 89.50,
                'is_active' => true,
            ],
            [
                'name' => 'James Okafor',
                'bio' => 'Day trading expert focused on US and European stock indices. Former hedge fund manager with a proven track record of navigating volatile markets. Aggressive short-term strategies with tight stop-losses.',
                'area_of_expertise' => 'Stocks & Indices',
                'daily_roi' => 2.40,
                'duration_days' => 14,
                'win_rate' => 71.30,
                'min_startup_capital' => 250.00,
                'max_capital' => 25000.00,
                'profit_share_percentage' => 20.00,
                'total_profit' => 87300.00,
                'followers_count' => 195,
                'total_roi' => 52.80,
                'is_active' => true,
            ],
            [
                'name' => 'Sofia Martinez',
                'bio' => 'Blockchain researcher turned full-time DeFi trader. Early Bitcoin adopter and altcoin specialist. Combines on-chain analysis with technical indicators for high-conviction crypto trades.',
                'area_of_expertise' => 'Cryptocurrency',
                'daily_roi' => 3.10,
                'duration_days' => 21,
                'win_rate' => 68.75,
                'min_startup_capital' => 100.00,
                'max_capital' => 15000.00,
                'profit_share_percentage' => 25.00,
                'total_profit' => 63200.00,
                'followers_count' => 467,
                'total_roi' => 74.10,
                'is_active' => true,
            ],
            [
                'name' => 'Richard Tan',
                'bio' => 'Veteran forex trader with 15 years in institutional and retail markets. Specializes in major and exotic currency pairs using a blend of fundamental analysis and price action. Steady, low-risk approach.',
                'area_of_expertise' => 'Forex',
                'daily_roi' => 0.95,
                'duration_days' => 90,
                'win_rate' => 85.20,
                'min_startup_capital' => 2000.00,
                'max_capital' => 200000.00,
                'profit_share_percentage' => 10.00,
                'total_profit' => 385000.00,
                'followers_count' => 723,
                'total_roi' => 112.40,
                'is_active' => true,
            ],
        ];

        foreach ($experts as $expert) {
            Expert::create($expert);
        }
    }
}
