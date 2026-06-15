<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * crypto_accounts balance columns were created NOT NULL with no default, but the
 * app creates a row supplying only user_id (App\Http\Controllers\User\ViewsController,
 * CreateNewUser, etc.). The upstream SQL dump defaulted these to 0; restore that.
 */
return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('crypto_accounts')) {
            return;
        }

        foreach (['btc', 'eth', 'ltc', 'xrp', 'link', 'bat', 'aave', 'usdt', 'xlm', 'bch'] as $col) {
            if (Schema::hasColumn('crypto_accounts', $col)) {
                DB::statement("ALTER TABLE `crypto_accounts` MODIFY `{$col}` DOUBLE(8,2) NOT NULL DEFAULT 0");
            }
        }
    }

    public function down()
    {
        // Leave defaults in place.
    }
};
