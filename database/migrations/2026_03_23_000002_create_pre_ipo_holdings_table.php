<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pre_ipo_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pre_ipo_company_id')->constrained('pre_ipo_companies')->onDelete('cascade');
            $table->unsignedInteger('shares');
            $table->decimal('purchase_price', 16, 2);
            $table->decimal('total_cost', 16, 2);
            $table->enum('status', ['active', 'converted'])->default('active');
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'pre_ipo_company_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_ipo_holdings');
    }
};
