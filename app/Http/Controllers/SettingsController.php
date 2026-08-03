<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    private function cekSuper(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_super, 403);
    }

    public function index()
    {
        $this->cekSuper();

        Setting::syncDefaults();
        $settings = Setting::allCached();
        $defaults = Setting::defaults();

        return view('settings', compact('settings', 'defaults'));
    }

    public function update(Request $request)
    {
        $this->cekSuper();

        $fields = Setting::defaults();
        unset($fields['kode_pemulihan']);
        $data = $request->only(array_keys($fields));

        $rules = [];
        foreach ($fields as $key => $default) {
            if (str_ends_with($key, '_warna') || $key === 'dashboard_aksen') {
                $rules[$key] = ['nullable', 'regex:/^#([0-9a-fA-F]{3,4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'];
            } elseif (str_ends_with($key, '_tipe')) {
                $rules[$key] = ['nullable', Rule::in(['pie', 'bar', 'doughnut', 'line', 'radar'])];
            }
        }
        $rules['chart_kurva_fill'] = ['nullable', Rule::in(['0', '1'])];
        $rules['chart_kurva_tension'] = ['nullable', 'numeric', 'between:0,1'];

        $request->validate($rules);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('pengaturan.index')->with('success', '⚙️ Pengaturan berhasil disimpan.');
    }
}
