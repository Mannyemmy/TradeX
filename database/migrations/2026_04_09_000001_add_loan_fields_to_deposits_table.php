<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoanFieldsToDepositsTable extends Migration
{
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_repayment_schedule_id')->nullable()->after('proof');
            $table->foreign('loan_repayment_schedule_id')
                  ->references('id')
                  ->on('loan_repayment_schedules')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign(['loan_repayment_schedule_id']);
            $table->dropColumn('loan_repayment_schedule_id');
        });
    }
}
