<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToExpertsTable extends Migration
{
    public function up()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('profile_picture');
            $table->string('area_of_expertise')->default('Mixed')->after('bio');
            $table->decimal('daily_roi', 8, 4)->default(0)->after('area_of_expertise');
            $table->integer('duration_days')->default(30)->after('daily_roi');
            $table->decimal('max_capital', 15, 2)->nullable()->after('min_startup_capital');
            $table->integer('followers_count')->default(0)->after('max_capital');
            $table->decimal('total_roi', 10, 2)->default(0)->after('followers_count');
            $table->boolean('is_active')->default(true)->after('total_roi');
        });
    }

    public function down()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->dropColumn([
                'bio', 'area_of_expertise', 'daily_roi', 'duration_days',
                'max_capital', 'followers_count', 'total_roi', 'is_active',
            ]);
        });
    }
}
