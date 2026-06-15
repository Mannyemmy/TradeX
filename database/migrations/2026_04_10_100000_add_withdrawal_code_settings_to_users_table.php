<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddWithdrawalCodeSettingsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // code1/code2 originally came from the upstream SQL dump; create them
            // first so the ->after('code2') anchors below are valid on a fresh DB.
            if (! Schema::hasColumn('users', 'code1')) {
                $table->string('code1')->nullable();
            }
            if (! Schema::hasColumn('users', 'code2')) {
                $table->string('code2')->nullable();
            }

            $table->string('code3')->nullable()->after('code2');
            $table->string('code4')->nullable()->after('code3');
            $table->string('code5')->nullable()->after('code4');

            $table->boolean('code1_enabled')->default(false)->after('code5');
            $table->boolean('code2_enabled')->default(false)->after('code1_enabled');
            $table->boolean('code3_enabled')->default(false)->after('code2_enabled');
            $table->boolean('code4_enabled')->default(false)->after('code3_enabled');
            $table->boolean('code5_enabled')->default(false)->after('code4_enabled');

            $table->string('code1_label')->default('Broker Commission Fee Code')->after('code5_enabled');
            $table->string('code2_label')->default('Anti-Theft Security Code')->after('code1_label');
            $table->string('code3_label')->default('IMF Code')->after('code2_label');
            $table->string('code4_label')->default('Cost of Transfer Code')->after('code3_label');
            $table->string('code5_label')->default('Taxation Code')->after('code4_label');
        });

        // Backfill: enable codes for existing users who already have code values set
        DB::table('users')
            ->whereNotNull('code1')
            ->where('code1', '!=', '')
            ->update(['code1_enabled' => true]);

        DB::table('users')
            ->whereNotNull('code2')
            ->where('code2', '!=', '')
            ->update(['code2_enabled' => true]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'code3', 'code4', 'code5',
                'code1_enabled', 'code2_enabled', 'code3_enabled', 'code4_enabled', 'code5_enabled',
                'code1_label', 'code2_label', 'code3_label', 'code4_label', 'code5_label',
            ]);
        });
    }
}
