<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $theme = Setting::where('key', 'theme_mode')->value('value') ?? 'light';
        $accent = Setting::where('key', 'accent_color')->value('value') ?? '';
        return view('settings.index', compact('theme', 'accent'));
    }

    public function toggleTheme()
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'theme_mode'],
            ['value' => 'light']
        );

        $setting->update([
            'value' => $setting->value === 'light' ? 'dark' : 'light'
        ]);

        return back();
    }

    public function setAccent(Request $request)
    {
        Setting::updateOrCreate(
            ['key' => 'accent_color'],
            ['value' => $request->color ?? '']
        );

        return response()->json(['status' => 'ok']);
    }

    public function resetAccent()
    {
        Setting::where('key', 'accent_color')->delete();
        return response()->json(['status' => 'reset']);
    }
}
