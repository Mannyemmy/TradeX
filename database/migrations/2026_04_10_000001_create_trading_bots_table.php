<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingBotsTable extends Migration
{
    public function up()
    {
        Schema::create('trading_bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('strategy_type', ['scalping', 'day_trading', 'swing'])->default('day_trading');
            $table->decimal('win_rate', 5, 2)->default(70);
            $table->decimal('expected_roi', 8, 4)->default(2.5);
            $table->decimal('min_investment', 15, 2)->default(100);
            $table->decimal('max_investment', 15, 2)->default(50000);
            $table->decimal('profit_min_pct', 5, 2)->default(0.5);
            $table->decimal('profit_max_pct', 5, 2)->default(3.0);
            $table->decimal('loss_min_pct', 5, 2)->default(0.2);
            $table->decimal('loss_max_pct', 5, 2)->default(1.5);
            $table->integer('trade_interval_minutes')->default(5);
            $table->integer('max_duration_days')->default(90);
            $table->boolean('is_active')->default(true);
            $table->integer('subscribers_count')->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->timestamps();

            $table->index('strategy_type');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trading_bots');
    }
}
