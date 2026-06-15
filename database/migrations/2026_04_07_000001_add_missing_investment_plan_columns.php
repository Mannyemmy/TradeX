<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingInvestmentPlanColumns extends Migration
{
    public function up()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('user_plans', 'profit_earned')) {
                $table->decimal('profit_earned', 15, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('user_plans', 'active')) {
                $table->string('active')->default('yes')->after('amount');
            }
        });

        Schema::table('tp__transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('tp__transactions', 'user_plan_id')) {
                $table->unsignedBigInteger('user_plan_id')->nullable()->after('amount');
            }
        });
    }

    public function down()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            if (Schema::hasColumn('user_plans', 'profit_earned')) {
                $table->dropColumn('profit_earned');
            }
        });

        Schema::table('tp__transactions', function (Blueprint $table) {
            if (Schema::hasColumn('tp__transactions', 'user_plan_id')) {
                $table->dropColumn('user_plan_id');
            }
        });
    }
}
