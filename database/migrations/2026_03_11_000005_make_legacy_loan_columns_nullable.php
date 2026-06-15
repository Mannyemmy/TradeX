<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeLegacyLoanColumnsNullable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `loans` MODIFY `credit_facility` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `loans` MODIFY `monthly_income` VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `loans` MODIFY `credit_facility` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `loans` MODIFY `monthly_income` VARCHAR(255) NOT NULL');
    }
}
