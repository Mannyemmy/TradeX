<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('trading_asset_id');
            $table->decimal('shares', 16, 8)->default(0);
            $table->decimal('avg_buy_price', 20, 8)->default(0);
            $table->decimal('total_invested', 16, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'trading_asset_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trading_asset_id')->references('id')->on('trading_assets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_positions');
    }
}
