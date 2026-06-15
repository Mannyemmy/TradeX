<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'tag')) {
                $table->string('tag')->nullable()->after('type');
            }
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'tag')) {
                $table->dropColumn('tag');
            }
        });
    }
};
