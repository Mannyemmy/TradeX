<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('trading_assets', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable();
            $table->string('name');
            $table->string('symbol', 20);
            $table->enum('asset_class', ['crypto', 'forex', 'stock', 'etf', 'index']);
            $table->decimal('price', 20, 8)->default(0);
            $table->decimal('price_change_24h', 20, 8)->nullable();
            $table->decimal('price_change_pct_24h', 10, 4)->nullable();
            $table->decimal('high_24h', 20, 8)->nullable();
            $table->decimal('low_24h', 20, 8)->nullable();
            $table->decimal('volume_24h', 20, 2)->nullable();
            $table->decimal('market_cap', 24, 2)->nullable();
            $table->string('logo_url')->nullable();
            $table->enum('data_source', ['coingecko', 'twelvedata', 'manual'])->default('manual');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['external_id', 'data_source']);
            $table->index('asset_class');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trading_assets');
    }
}
