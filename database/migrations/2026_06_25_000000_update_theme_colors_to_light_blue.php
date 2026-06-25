<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Switch the user dashboard theme from the original dark surfaces to a
 * light "white & blue" palette that matches the public site (homepage,
 * login, register). Primary blues are left untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('theme_colors')->where('id', 1)->update([
            // Surfaces — dark → light/white
            'surface_base'         => '#F4F7FA',
            'surface_raised'       => '#FFFFFF',
            'surface_overlay'      => '#EDF2F7',
            'surface_border'       => '#DCE3EC',
            'surface_border_light' => '#C8D3E0',
            // Text — light → dark for readability on white
            'content_primary'      => '#0F1B2D',
            'content_secondary'    => '#475569',
            'content_tertiary'     => '#8593A3',
            'updated_at'           => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('theme_colors')->where('id', 1)->update([
            'surface_base'         => '#0F1115',
            'surface_raised'       => '#161A1E',
            'surface_overlay'      => '#1C2127',
            'surface_border'       => '#2A2F36',
            'surface_border_light' => '#363C44',
            'content_primary'      => '#E8EAED',
            'content_secondary'    => '#9AA0AB',
            'content_tertiary'     => '#6B7280',
            'updated_at'           => now(),
        ]);
    }
};
