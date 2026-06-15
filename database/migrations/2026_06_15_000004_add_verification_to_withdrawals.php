<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The withdrawals flow filters on and writes a `verification` column
 * (WithdrawalController / ViewsController), but no migration created it.
 */
return new class extends Migration {
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (! Schema::hasColumn('withdrawals', 'verification')) {
                $table->string('verification')->nullable();
            }
        });
    }

    public function down()
    {
        // Non-destructive.
    }
};
