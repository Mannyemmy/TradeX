<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pre_ipo_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_ipo_company_id')->constrained('pre_ipo_companies')->onDelete('cascade');
            $table->decimal('price', 16, 2);
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('note')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('changed_by')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_ipo_price_history');
    }
};
