<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 5)->unique();
            $table->string('currency_symbol');
            $table->string('currency_name')->nullable();
            $table->decimal('rate_to_usd', 16, 6)->default(1.000000);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_manual')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
