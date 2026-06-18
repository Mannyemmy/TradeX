<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('theme_colors')->where('id', 1)->update([
            'primary_color' => '#2E5C8A',
            'primary_light' => '#5DADE2',
            'primary_dark'  => '#0F3A6E',
            'gain_color'    => '#1A3A7F',
            'updated_at'    => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('theme_colors')->where('id', 1)->update([
            'primary_color' => '#059669',
            'primary_light' => '#34D399',
            'primary_dark'  => '#047857',
            'gain_color'    => '#22C55E',
            'updated_at'    => now(),
        ]);
    }
};
