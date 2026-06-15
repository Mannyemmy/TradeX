<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopyPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('copy_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('expert_id')->constrained('experts')->cascadeOnDelete();
            $table->decimal('invested_amount', 15, 2);
            $table->decimal('accumulated_profit', 15, 2)->default(0);
            $table->decimal('daily_roi_snapshot', 8, 4);
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->enum('status', ['active', 'stopped', 'completed', 'settled'])->default('active');
            $table->string('settled_by')->nullable();
            $table->decimal('admin_profit_adjustment', 15, 2)->default(0);
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['user_id', 'status']);
            $table->index(['expert_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('copy_positions');
    }
}
