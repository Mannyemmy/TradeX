<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Reconciles the `settings`, `settings_conts` and `paystacks` schema with the
 * columns the application code actually reads.
 *
 * The original CodeCanyon distribution is installed from a full SQL dump, so the
 * bundled migrations never defined many columns the code depends on (SMTP /
 * Google / captcha / crypto / Flutterwave config, the `paystacks` table, etc.).
 * This migration backfills every missing piece. It is fully guarded with
 * hasColumn()/hasTable() so it is safe to run against an already-populated
 * production database as well as a fresh local one.
 */
return new class extends Migration {
    public function up()
    {
        // The settings tables hold dozens of varchar(255) columns. In the older
        // COMPACT row format MySQL counts each toward the hard 65,535-byte row
        // limit, which we'd exceed by adding more. DYNAMIC stores long varchars
        // off-page, so switch the format before adding columns.
        foreach (['settings', 'settings_conts'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                try {
                    DB::statement("ALTER TABLE `{$tbl}` ROW_FORMAT=DYNAMIC");
                } catch (\Throwable $e) {
                    // Row format may already be DYNAMIC / unsupported — ignore.
                }
            }
        }

        // ---- settings --------------------------------------------------------
        // Plain string/config columns referenced as $settings->{col}.
        $settingsStrings = [
            'admin_nft_user_id', 'auto_merchant_option', 'bnc_api_key', 'bnc_secret_key',
            'capt_secret', 'capt_sitekey', 'captcha_status', 'coingecko_api_key',
            'credit_card_provider', 'deduction_option', 'deposit_bonus', 'deposit_option',
            'emailfrom', 'emailfromname', 'enable_kyc_registration', 'enable_social_login',
            'fee', 'gasfee', 'google_id', 'google_redirect', 'google_secret',
            'install_type', 'mail_server', 'min_balance', 'min_return', 'nft_auto_approve',
            'office', 'redirect_url', 's_currency', 'smtp_encrypt', 'smtp_host',
            'smtp_password', 'smtp_port', 'smtp_user', 'telegram_bot_api', 'theme',
            'timezone', 'trade_notification', 'twelvedata_api_key', 'use_crypto_feature',
            'use_transfer', 'wallet_status', 'website_theme', 'whatsapp',
        ];

        // NOTE: the settings table already carries ~45 varchar(255) columns,
        // which nearly fill MySQL's 65,535-byte row limit. Adding the columns
        // below as TEXT keeps us well under that limit (BLOB/TEXT barely count)
        // and is functionally identical for these string config values.
        Schema::table('settings', function (Blueprint $table) use ($settingsStrings) {
            foreach ($settingsStrings as $col) {
                if (! Schema::hasColumn('settings', $col)) {
                    $table->text($col)->nullable();
                }
            }
            // Longer free-text fields.
            foreach (['trade_message', 'welcome_message'] as $col) {
                if (! Schema::hasColumn('settings', $col)) {
                    $table->text($col)->nullable();
                }
            }
            // Cast columns (see App\Models\Settings).
            if (! Schema::hasColumn('settings', 'modules')) {
                $table->json('modules')->nullable();
            }
            if (! Schema::hasColumn('settings', 'return_capital')) {
                $table->boolean('return_capital')->default(true);
            }
            if (! Schema::hasColumn('settings', 'should_cancel_plan')) {
                $table->boolean('should_cancel_plan')->default(false);
            }
        });

        // ---- settings_conts --------------------------------------------------
        $contStrings = [
            'aave', 'ada', 'bch', 'bnb', 'bnc_api_key', 'bnc_secret_key', 'btc',
            'currency_rate', 'eth', 'fee', 'flw_public_key', 'flw_secret_hash',
            'flw_secret_key', 'link', 'ltc', 'min_transfer', 'minamt',
            'telegram_bot_api', 'transfer_charges', 'usdt', 'use_transfer',
            'xlm', 'xrp',
        ];

        Schema::table('settings_conts', function (Blueprint $table) use ($contStrings) {
            foreach ($contStrings as $col) {
                if (! Schema::hasColumn('settings_conts', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // ---- paystacks -------------------------------------------------------
        if (! Schema::hasTable('paystacks')) {
            Schema::create('paystacks', function (Blueprint $table) {
                $table->id();
                $table->string('paystack_public_key')->nullable();
                $table->string('paystack_secret_key')->nullable();
                $table->string('paystack_url')->nullable();
                $table->string('paystack_email')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Non-destructive: these columns/tables back core configuration and may
        // hold live data on production, so the down path intentionally leaves
        // them in place.
    }
};
