<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTradesTable extends Migration
{
    public function up()
    {
        Schema::create('stock_trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('trading_asset_id')->index();
            $table->string('type', 10);              // 'buy' or 'sell'
            $table->decimal('shares', 16, 8);
            $table->decimal('price_per_share', 20, 8);
            $table->decimal('total_amount', 16, 2);
            $table->decimal('fee_amount', 16, 2)->default(0);
            $table->string('status', 20)->default('completed'); // completed, pending, cancelled
            $table->timestamps();

            $table->index('type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trading_asset_id')->references('id')->on('trading_assets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_trades');
    }
}
