<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        Setting::syncDefaults();
        $settings = Setting::allCached();
        $defaults = Setting::defaults();

        return view('settings', compact('settings', 'defaults'));
    }

    public function update(Request $request)
    {
        $fields = Setting::defaults();
        $data = $request->only(array_keys($fields));

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('pengaturan.index')->with('success', '⚙️ Pengaturan berhasil disimpan.');
    }
}
