<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Backfills `users` columns that the application code reads/writes but that no
 * migration ever created (they previously existed only in the upstream SQL
 * dump). Guarded with hasColumn() so it is safe on fresh and existing databases.
 */
return new class extends Migration {
    public function up()
    {
        $strings = [
            'username', 'l_name', 'firstname', 'gender', 'account', 'currency',
            'state', 'zipcode', 'swift_code', 'taxtype', 'tradetype',
            'usdt_address', 'password_token', 'pass_2fa', 'token_2fa_expiry',
            'withdrawotp', 'withdrawal_id',
        ];

        Schema::table('users', function (Blueprint $table) use ($strings) {
            foreach ($strings as $col) {
                if (! Schema::hasColumn('users', $col)) {
                    $table->string($col)->nullable();
                }
            }
            if (! Schema::hasColumn('users', 'enable_2fa')) {
                $table->string('enable_2fa')->default('no');
            }
            foreach (['bonus', 'roi', 'taxamount'] as $col) {
                if (! Schema::hasColumn('users', $col)) {
                    $table->decimal($col, 20, 2)->default(0);
                }
            }
        });
    }

    public function down()
    {
        // Non-destructive: leave columns in place (may hold live data).
    }
};
