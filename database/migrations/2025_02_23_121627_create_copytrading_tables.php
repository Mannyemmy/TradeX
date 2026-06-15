<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('user_traders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trader_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // NOTE: the canonical `trades` table is created by
        // 2025_03_06_110346_create_trades_table (and extended by later
        // migrations). The obsolete copy-trading `trades` definition that used
        // to live here conflicted with it, so it was removed.
    }

    public function down()
    {
        Schema::dropIfExists('user_traders');
        Schema::dropIfExists('traders');
    }
};
