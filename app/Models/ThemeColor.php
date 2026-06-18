<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    protected $table = 'theme_colors';

    /**
     * Default color values — used as fallbacks and for reset functionality.
     */
    public const DEFAULTS = [
        'primary_color'      => '#2E5C8A',
        'primary_light'      => '#5DADE2',
        'primary_dark'       => '#0F3A6E',
        'gain_color'         => '#1A3A7F',
        'loss_color'         => '#EF4444',
        'warning_color'      => '#F59E0B',
        'info_color'         => '#3B82F6',
        'surface_base'       => '#0F1115',
        'surface_raised'     => '#161A1E',
        'surface_overlay'    => '#1C2127',
        'surface_border'     => '#2A2F36',
        'surface_border_light' => '#363C44',
        'content_primary'    => '#E8EAED',
        'content_secondary'  => '#9AA0AB',
        'content_tertiary'   => '#6B7280',
        'body_bg'            => '#F5F7F9',
        'body_text'          => '#1F2937',
        'body_muted'         => '#6B7280',
        'body_border'        => '#E5E7EB',
    ];

    /**
     * Convert a hex color (#RRGGBB) to an RGB triplet string "R G B".
     * Used by admin-dash.blade.php CSS custom properties.
     */
    public static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "$r $g $b";
    }

    /**
     * Convert a hex color to an rgba() CSS string.
     * Used for primary_subtle derivation.
     */
    public static function hexToRgba(string $hex, float $alpha = 0.12): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r,$g,$b,$alpha)";
    }
}
