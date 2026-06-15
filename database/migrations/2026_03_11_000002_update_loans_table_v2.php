<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Step 1: Drop the existing enum constraint and re-add with expanded values
        // MySQL doesn't support ALTER ENUM easily, so we change the column
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('loan_plan_id')->nullable()->after('user_id')->constrained('loan_plans')->nullOnDelete();
            $table->decimal('interest_rate', 5, 2)->default(0)->after('purpose');
            $table->string('interest_type', 10)->default('simple')->after('interest_rate');
            $table->decimal('processing_fee', 15, 2)->default(0)->after('interest_type');
            $table->decimal('total_repayable', 15, 2)->default(0)->after('processing_fee');
            $table->decimal('total_repaid', 15, 2)->default(0)->after('total_repayable');
            $table->integer('num_installments')->nullable()->after('total_repaid');
            $table->decimal('collateral_amount', 15, 2)->default(0)->after('num_installments');
            $table->timestamp('disbursed_at')->nullable()->after('collateral_amount');
            $table->date('first_payment_date')->nullable()->after('disbursed_at');
            $table->date('maturity_date')->nullable()->after('first_payment_date');
            $table->date('next_payment_date')->nullable()->after('maturity_date');
            $table->text('rejection_reason')->nullable()->after('next_payment_date');
            $table->decimal('approved_amount', 15, 2)->nullable()->after('rejection_reason');
        });

        // Step 2: Expand the status enum
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending','approved','active','repaying','completed','defaulted','cancelled','rejected') DEFAULT 'pending'");

        // Step 3: Migrate legacy 'approved' loans to 'completed' (no repayment was tracked)
        DB::table('loans')->where('status', 'approved')->update(['status' => 'completed']);
    }

    public function down()
    {
        // Revert legacy status changes
        DB::table('loans')->where('status', 'completed')->update(['status' => 'approved']);

        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending','approved','rejected') DEFAULT 'pending'");

        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['loan_plan_id']);
            $table->dropColumn([
                'loan_plan_id', 'interest_rate', 'interest_type', 'processing_fee',
                'total_repayable', 'total_repaid', 'num_installments', 'collateral_amount',
                'disbursed_at', 'first_payment_date', 'maturity_date', 'next_payment_date',
                'rejection_reason', 'approved_amount',
            ]);
        });
    }
};
