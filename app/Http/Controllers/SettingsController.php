<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    private function cekUtama(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_super, 403);

        $utamaId = User::where('is_super', true)->orderBy('id')->value('id');
        abort_unless(Auth::id() === $utamaId, 403, 'Hanya super admin utama yang dapat mengakses pengaturan.');
    }

    public function index()
    {
        $this->cekUtama();

        Setting::syncDefaults();
        $settings = Setting::allCached();
        $defaults = Setting::defaults();

        return view('settings', compact('settings', 'defaults'));
    }

    public function update(Request $request)
    {
        $this->cekUtama();

        $fields = Setting::defaults();
        unset($fields['kode_pemulihan']);
        $data = $request->only(array_keys($fields));

        $rules = [];
        foreach ($fields as $key => $default) {
            if (str_ends_with($key, '_warna') || $key === 'dashboard_aksen') {
                $rules[$key] = ['nullable', 'regex:/^#([0-9a-fA-F]{3,4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'];
            } elseif (str_ends_with($key, '_tipe')) {
                $rules[$key] = ['nullable', Rule::in(['pie', 'bar', 'doughnut', 'line', 'radar'])];
            } elseif (str_ends_with($key, '_tampil')) {
                $rules[$key] = ['nullable', Rule::in(['0', '1'])];
            }
        }
        $rules['chart_kurva_fill'] = ['nullable', Rule::in(['0', '1'])];
        $rules['chart_kurva_tension'] = ['nullable', 'numeric', 'between:0,1'];

        $request->validate($rules);

        if (isset($data['kuesioner_email_domain'])) {
            $data['kuesioner_email_domain'] = ltrim(trim((string) $data['kuesioner_email_domain']), '@');
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('pengaturan.index')->with('success', '⚙️ Pengaturan berhasil disimpan.');
    }
}
