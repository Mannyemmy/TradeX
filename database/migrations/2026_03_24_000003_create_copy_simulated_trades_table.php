<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopySimulatedTradesTable extends Migration
{
    public function up()
    {
        Schema::create('copy_simulated_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copy_position_id')->constrained('copy_positions')->cascadeOnDelete();
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

            $table->index(['copy_position_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('copy_simulated_trades');
    }
}
