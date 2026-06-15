<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiKeysToSettingsContsTable extends Migration
{
    public function up()
    {
        Schema::table('settings_conts', function (Blueprint $table) {
            $table->string('coingecko_api_key')->nullable();
            $table->string('twelvedata_api_key')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings_conts', function (Blueprint $table) {
            $table->dropColumn(['coingecko_api_key', 'twelvedata_api_key']);
        });
    }
}
