<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateThemeColorsTable extends Migration
{
    public function up()
    {
        Schema::create('theme_colors', function (Blueprint $table) {
            $table->id();

            // Primary colors
            $table->string('primary_color', 9)->default('#2E5C8A');
            $table->string('primary_light', 9)->default('#5DADE2');
            $table->string('primary_dark', 9)->default('#0F3A6E');

            // Signal colors
            $table->string('gain_color', 9)->default('#1A3A7F');
            $table->string('loss_color', 9)->default('#EF4444');
            $table->string('warning_color', 9)->default('#F59E0B');
            $table->string('info_color', 9)->default('#3B82F6');

            // Surface colors (light theme backgrounds — white/blue)
            $table->string('surface_base', 9)->default('#F4F7FA');
            $table->string('surface_raised', 9)->default('#FFFFFF');
            $table->string('surface_overlay', 9)->default('#EDF2F7');
            $table->string('surface_border', 9)->default('#DCE3EC');
            $table->string('surface_border_light', 9)->default('#C8D3E0');

            // Content / text colors
            $table->string('content_primary', 9)->default('#0F1B2D');
            $table->string('content_secondary', 9)->default('#475569');
            $table->string('content_tertiary', 9)->default('#8593A3');

            // Body colors (public pages - light theme)
            $table->string('body_bg', 9)->default('#F5F7F9');
            $table->string('body_text', 9)->default('#1F2937');
            $table->string('body_muted', 9)->default('#6B7280');
            $table->string('body_border', 9)->default('#E5E7EB');

            $table->timestamps();
        });

        // Insert default row (singleton pattern — always id=1)
        DB::table('theme_colors')->insert([
            'id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('theme_colors');
    }
}
