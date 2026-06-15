<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletConnectStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // `wallet_connected` is written to by the app but was never created
            // by a migration (it lived only in the upstream SQL dump). Create it
            // here so the ->after() anchor below is valid on a fresh database.
            if (! Schema::hasColumn('users', 'wallet_connected')) {
                $table->integer('wallet_connected')->default(0);
            }
            if (! Schema::hasColumn('users', 'wallet_connect_status')) {
                $table->string('wallet_connect_status', 10)->default('on');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wallet_connect_status');
        });
    }
}
