<?php

namespace Database\Seeders;

use App\Models\PreIpoCompany;
use App\Models\PreIpoPriceHistory;
use Illuminate\Database\Seeder;

class PreIpoCompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name'               => 'SpaceX',
                'symbol'             => 'SPACEX',
                'description'        => 'Space Exploration Technologies Corp. designs, manufactures, and launches advanced rockets and spacecraft. Founded by Elon Musk, SpaceX is revolutionizing space technology with reusable launch vehicles and the Starlink satellite internet constellation.',
                'sector'             => 'Aerospace',
                'share_price'        => 185.00,
                'initial_price'      => 185.00,
                'total_shares'       => 50000,
                'shares_sold'        => 0,
                'min_shares'         => 1,
                'max_shares_per_user'=> 500,
                'status'             => 'open',
                'expected_ipo_date'  => '2026-09-15',
                'is_featured'        => true,
                'opened_at'          => now(),
            ],
            [
                'name'               => 'Stripe',
                'symbol'             => 'STRIPE',
                'description'        => 'Stripe is a financial infrastructure platform for businesses. Millions of companies use Stripe to accept payments, grow revenue, and accelerate new business opportunities.',
                'sector'             => 'Fintech',
                'share_price'        => 72.50,
                'initial_price'      => 72.50,
                'total_shares'       => 100000,
                'shares_sold'        => 0,
                'min_shares'         => 5,
                'max_shares_per_user'=> 1000,
                'status'             => 'open',
                'expected_ipo_date'  => '2026-07-01',
                'is_featured'        => true,
                'opened_at'          => now(),
            ],
            [
                'name'               => 'Databricks',
                'symbol'             => 'DATABR',
                'description'        => 'Databricks provides a unified analytics platform for data engineering, data science, and machine learning. Built on Apache Spark, Databricks simplifies big data and AI workloads across cloud environments.',
                'sector'             => 'Technology',
                'share_price'        => 54.00,
                'initial_price'      => 54.00,
                'total_shares'       => 75000,
                'shares_sold'        => 0,
                'min_shares'         => 10,
                'max_shares_per_user'=> 750,
                'status'             => 'upcoming',
                'expected_ipo_date'  => '2026-12-01',
                'is_featured'        => false,
            ],
            [
                'name'               => 'Canva',
                'symbol'             => 'CANVA',
                'description'        => 'Canva is a global online visual communications platform with a mission to empower everyone to design. Used by over 170 million monthly users, Canva offers graphic design tools for social media, presentations, posters, and more.',
                'sector'             => 'Technology',
                'share_price'        => 38.25,
                'initial_price'      => 38.25,
                'total_shares'       => 120000,
                'shares_sold'        => 0,
                'min_shares'         => 5,
                'max_shares_per_user'=> 2000,
                'status'             => 'open',
                'expected_ipo_date'  => '2026-08-15',
                'is_featured'        => true,
                'opened_at'          => now(),
            ],
        ];

        foreach ($companies as $data) {
            $company = PreIpoCompany::create($data);

            PreIpoPriceHistory::create([
                'pre_ipo_company_id' => $company->id,
                'price'              => $company->share_price,
                'changed_by'         => null,
                'note'               => 'Initial listing price',
                'created_at'         => now(),
            ]);
        }
    }
}
