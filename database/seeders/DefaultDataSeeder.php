<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the singleton configuration rows the application requires in order to
 * boot and render (settings #1, settings_conts #1, paystacks #1, terms #1).
 *
 * The upstream script ships these inside a full SQL dump; this seeder
 * reconstructs sane defaults so a fresh database is immediately usable. It uses
 * updateOrInsert so it is safe to re-run and will not clobber an existing
 * configured row's primary key.
 */
class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $appUrl = config('app.url', 'http://localhost');

        $modules = [
            'trading' => true,
            'investment' => true,
            'copy_trading' => true,
            'bot_trading' => true,
            'signal' => true,
            'nft' => true,
            'loan' => true,
            'membership' => true,
            'pre_ipo' => true,
            'stocktrading' => true,
            'cryptoswap' => true,
        ];

        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => env('APP_NAME', 'TradeXpromax'),
                'site_title' => env('APP_NAME', 'TradeXpromax'),
                'description' => 'Online trading & investment platform',
                'keywords' => 'trading, investment, crypto, forex',
                'currency' => 'USD',
                's_currency' => '$',
                'scurrency' => '$',
                'site_address' => $appUrl,
                'install_type' => 'Domain',
                'timezone' => 'UTC',
                'location' => 'UTC',
                'contact_email' => env('MAIL_FROM_ADDRESS', 'support@example.com'),
                'payment_mode' => 'manual',
                'trade_mode' => 'auto',
                'weekend_trade' => 'yes',
                'trade_notification' => 'yes',
                'withdrawal_option' => 'auto',
                'deposit_option' => 'manual',
                'deduction_option' => 'auto',
                'auto_merchant_option' => 'off',
                'captcha' => 'no',
                // Stored value is used as a truthy flag in views (@if($settings->captcha_status)),
                // so "disabled" must be a falsy value, not the string 'off'.
                'captcha_status' => 0,
                'google_translate' => 'no',
                'enable_2fa' => 'no',
                'enable_kyc' => 'no',
                'enable_kyc_registration' => 'no',
                'enable_verification' => 'true',
                'enable_social_login' => 'no',
                'enable_with' => 'yes',
                'enable_annoc' => 'no',
                'wallet_status' => 'off',
                'nft_auto_approve' => 'no',
                'admin_nft_user_id' => 1,
                'mail_server' => 'smtp',
                'emailfrom' => env('MAIL_FROM_ADDRESS', 'support@example.com'),
                'emailfromname' => env('APP_NAME', 'TradeXpromax'),
                'smtp_host' => env('MAIL_HOST'),
                'smtp_port' => env('MAIL_PORT'),
                'smtp_user' => env('MAIL_USERNAME'),
                'smtp_password' => env('MAIL_PASSWORD'),
                'smtp_encrypt' => env('MAIL_ENCRYPTION', 'tls'),
                'signup_bonus' => '0',
                'deposit_bonus' => '0',
                'referral_commission' => '0',
                'referral_commission1' => '0',
                'referral_commission2' => '0',
                'referral_commission3' => '0',
                'referral_commission4' => '0',
                'referral_commission5' => '0',
                'min_balance' => '0',
                'min_return' => '0',
                'fee' => '0',
                'gasfee' => '0',
                'monthlyfee' => '0',
                'quarterlyfee' => '0',
                'yearlyfee' => '0',
                'commission_type' => 'percentage',
                'commission_fee' => '0',
                'website_theme' => 'purposeTheme',
                'theme' => 'purposeTheme',
                'site_preference' => 'dark',
                'dashboard_option' => 'default',
                'credit_card_provider' => 'stripe',
                'return_capital' => true,
                'should_cancel_plan' => false,
                'modules' => json_encode($modules),
                'newupdate' => '0',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('settings_conts')->updateOrInsert(
            ['id' => 1],
            [
                'use_crypto_feature' => true,
                'use_transfer' => '0',
                'transfer_charges' => '0',
                'min_transfer' => '0',
                'minamt' => '0',
                'fee' => '0',
                'currency_rate' => '1',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('paystacks')->updateOrInsert(
            ['id' => 1],
            [
                'paystack_url' => 'https://api.paystack.co',
                'paystack_email' => env('MAIL_FROM_ADDRESS', 'support@example.com'),
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('terms_privacies')->updateOrInsert(
            ['id' => 1],
            [
                'description' => 'Terms and privacy policy. Please update this content from the admin panel.',
                'useterms' => 'yes',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
