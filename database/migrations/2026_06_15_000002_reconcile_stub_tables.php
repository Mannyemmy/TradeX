<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Backfills columns for tables whose original migrations only created id +
 * timestamps (the real columns lived in the upstream SQL dump):
 *  - activities: login/activity audit rows
 *  - signal_subscriptions: user signal-plan subscriptions
 *
 * Guarded with hasColumn() so it is safe on fresh and existing databases.
 */
return new class extends Migration {
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            if (! Schema::hasColumn('activities', 'user')) {
                $table->unsignedBigInteger('user')->nullable()->index();
            }
            foreach (['ip_address', 'device', 'browser', 'os'] as $col) {
                if (! Schema::hasColumn('activities', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        Schema::table('signal_subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('signal_subscriptions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            }
            if (! Schema::hasColumn('signal_subscriptions', 'signal_plan_id')) {
                $table->unsignedBigInteger('signal_plan_id')->nullable()->index();
            }
            if (! Schema::hasColumn('signal_subscriptions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            if (! Schema::hasColumn('signal_subscriptions', 'status')) {
                $table->string('status')->nullable();
            }
        });
    }

    public function down()
    {
        // Non-destructive.
    }
};
