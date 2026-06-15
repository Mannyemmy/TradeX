<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotSimulatedTradesTable extends Migration
{
    public function up()
    {
        Schema::create('bot_simulated_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_subscription_id')->constrained('bot_subscriptions')->cascadeOnDelete();
            $table->unsignedBigInteger('trading_asset_id')->nullable();
            $table->string('asset_name');
            $table->string('asset_class');
            $table->enum('action', ['buy', 'sell']);
            $table->decimal('entry_price', 18, 8);
            $table->decimal('exit_price', 18, 8);
            $table->decimal('amount', 15, 2);
            $table->decimal('profit_loss', 15, 2);
            $table->enum('result', ['WIN', 'LOSS']);
            $table->timestamp('executed_at');
            $table->timestamps();

            $table->index('bot_subscription_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_simulated_trades');
    }
}
