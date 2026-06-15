<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pre_ipo_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol', 20)->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('sector')->nullable();
            $table->decimal('share_price', 16, 2);
            $table->decimal('initial_price', 16, 2);
            $table->unsignedInteger('total_shares');
            $table->unsignedInteger('shares_sold')->default(0);
            $table->unsignedInteger('min_shares')->default(1);
            $table->unsignedInteger('max_shares_per_user')->nullable();
            $table->enum('status', ['upcoming', 'open', 'closed', 'ipo', 'public'])->default('upcoming');
            $table->foreignId('trading_asset_id')->nullable()->constrained('trading_assets')->nullOnDelete();
            $table->date('expected_ipo_date')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('went_public_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('status');
            $table->index('is_featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_ipo_companies');
    }
};
