<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashboardBannerToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('dashboard_banner_message')->nullable()->after('dashboard_style');
            $table->string('dashboard_banner_type', 20)->nullable()->default('warning')->after('dashboard_banner_message');
            $table->boolean('dashboard_banner_enabled')->default(false)->after('dashboard_banner_type');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dashboard_banner_message', 'dashboard_banner_type', 'dashboard_banner_enabled']);
        });
    }
}
