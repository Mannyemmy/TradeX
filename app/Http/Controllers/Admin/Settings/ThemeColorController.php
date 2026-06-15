<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\ThemeColor;
use Illuminate\Http\Request;

class ThemeColorController extends Controller
{
    public function index()
    {
        $themeColors = ThemeColor::find(1);
        $defaults = ThemeColor::DEFAULTS;

        return view('admin.Settings.ColorSettings.show')->with([
            'title' => 'Color Settings',
            'themeColors' => $themeColors,
            'defaults' => $defaults,
        ]);
    }

    public function update(Request $request)
    {
        $colorFields = array_keys(ThemeColor::DEFAULTS);
        $rules = [];
        foreach ($colorFields as $field) {
            $rules[$field] = ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        }
        $request->validate($rules);

        $data = [];
        foreach ($colorFields as $field) {
            if ($request->has($field) && $request->$field !== null) {
                $data[$field] = $request->$field;
            }
        }

        if (!empty($data)) {
            ThemeColor::where('id', 1)->update($data);
        }

        return response()->json([
            'status' => 200,
            'success' => 'Colors updated successfully.',
        ]);
    }

    public function reset(Request $request)
    {
        $group = $request->input('group', 'all');
        $defaults = ThemeColor::DEFAULTS;

        $groups = [
            'primary' => ['primary_color', 'primary_light', 'primary_dark'],
            'signal'  => ['gain_color', 'loss_color', 'warning_color', 'info_color'],
            'surface' => ['surface_base', 'surface_raised', 'surface_overlay', 'surface_border', 'surface_border_light'],
            'content' => ['content_primary', 'content_secondary', 'content_tertiary'],
            'body'    => ['body_bg', 'body_text', 'body_muted', 'body_border'],
        ];

        if ($group === 'all') {
            $data = $defaults;
        } elseif (isset($groups[$group])) {
            $data = array_intersect_key($defaults, array_flip($groups[$group]));
        } else {
            return response()->json(['status' => 422, 'error' => 'Invalid group.'], 422);
        }

        ThemeColor::where('id', 1)->update($data);

        return response()->json([
            'status' => 200,
            'success' => ucfirst($group) . ' colors reset to defaults.',
            'colors' => $data,
        ]);
    }
}
