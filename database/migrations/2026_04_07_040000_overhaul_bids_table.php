<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OverhaulBidsTable extends Migration
{
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            // Add status column if it doesn't exist (some versions may already have it)
            if (!Schema::hasColumn('bids', 'status')) {
                $table->string('status')->default('pending')->after('amount');
            }
            if (!Schema::hasColumn('bids', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        // No-op — we don't want to remove columns that may have pre-existed
    }
}
