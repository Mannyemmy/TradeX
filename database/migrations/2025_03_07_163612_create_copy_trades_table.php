<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    // NOTE: this migration was originally a copy-paste of the experts table
    // (which is created by 2025_03_07_162602_create_experts_table). It is the
    // intended home of the copy_trades table that the CopyTrade model maps to,
    // so it now creates that table instead.
    if (! Schema::hasTable('copy_trades')) {
        Schema::create('copy_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('expert_id')->constrained()->onDelete('cascade');
            $table->decimal('invested_amount', 15, 2)->default(0);
            $table->decimal('current_profit', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
}


    public function down()
    {
        Schema::dropIfExists('copy_trades');
    }
};
