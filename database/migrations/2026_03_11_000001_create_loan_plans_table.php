<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('loan_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->enum('interest_type', ['simple', 'compound'])->default('simple');
            $table->integer('min_duration');
            $table->integer('max_duration');
            $table->integer('max_active_loans')->default(1);
            $table->decimal('min_account_balance', 15, 2)->default(0);
            $table->boolean('requires_collateral')->default(false);
            $table->decimal('collateral_percentage', 5, 2)->nullable();
            $table->decimal('processing_fee', 5, 2)->default(0);
            $table->integer('grace_period_days')->default(0);
            $table->decimal('late_fee_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_plans');
    }
};
