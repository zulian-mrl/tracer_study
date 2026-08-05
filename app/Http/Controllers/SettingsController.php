<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Wilayah;
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
        $provinsiRows = Wilayah::whereNull('kode_kab_kota')->orderBy('nama_provinsi')->get();
        foreach ($provinsiRows as $prov) {
            $prov->kabRows = Wilayah::where('nama_provinsi', $prov->nama_provinsi)
                ->whereNotNull('kode_kab_kota')
                ->orderBy('nama_kab_kota')
                ->get();
        }

        return view('settings', compact('settings', 'defaults', 'provinsiRows'));
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

    public function wilayahProvinsiStore(Request $request)
    {
        $this->cekUtama();

        $request->validate([
            'nama_provinsi' => 'required|string|max:255',
            'kode_provinsi' => 'required|string|max:20',
        ]);

        $nama = trim($request->nama_provinsi);
        $kode = trim($request->kode_provinsi);

        if (Wilayah::where('kode_kab_kota', null)->where('nama_provinsi', $nama)->exists()) {
            return back()->withErrors(['nama_provinsi' => 'Provinsi dengan nama tersebut sudah ada.'])->withInput();
        }

        Wilayah::create([
            'kode_provinsi' => $kode,
            'nama_provinsi' => $nama,
            'kode_kab_kota' => null,
            'nama_kab_kota' => null,
        ]);

        return back()->with('success', '🏗️ Provinsi "' . $nama . '" berhasil ditambahkan.');
    }

    public function wilayahProvinsiUpdate(Request $request, Wilayah $wilayah)
    {
        $this->cekUtama();

        $request->validate([
            'nama_provinsi' => 'required|string|max:255',
            'kode_provinsi' => 'required|string|max:20',
        ]);

        $nama = trim($request->nama_provinsi);
        $kode = trim($request->kode_provinsi);

        $duplikat = Wilayah::where('kode_kab_kota', null)
            ->where('nama_provinsi', $nama)
            ->where('id', '!=', $wilayah->id)
            ->exists();
        if ($duplikat) {
            return back()->withErrors(['nama_provinsi' => 'Provinsi dengan nama tersebut sudah ada.'])->withInput();
        }

        Wilayah::where('nama_provinsi', $wilayah->nama_provinsi)
            ->where('kode_kab_kota', null)
            ->update(['nama_provinsi' => $nama, 'kode_provinsi' => $kode]);
        Wilayah::where('nama_provinsi', $wilayah->nama_provinsi)
            ->whereNotNull('kode_kab_kota')
            ->update(['nama_provinsi' => $nama, 'kode_provinsi' => $kode]);

        return back()->with('success', '🏗️ Provinsi "' . $nama . '" berhasil diperbarui.');
    }

    public function wilayahProvinsiDestroy(Wilayah $wilayah)
    {
        $this->cekUtama();

        Wilayah::where('nama_provinsi', $wilayah->nama_provinsi)->delete();

        return back()->with('success', '🗑️ Provinsi "' . $wilayah->nama_provinsi . '" beserta kab/kota-nya dihapus.');
    }

    public function wilayahKabKotaStore(Request $request)
    {
        $this->cekUtama();

        $request->validate([
            'nama_provinsi' => 'required|string|max:255',
            'kode_provinsi' => 'required|string|max:20',
            'daftar_kab_kota' => 'required|string',
        ]);

        $namaProvinsi = trim($request->nama_provinsi);
        $kodeProvinsi = trim($request->kode_provinsi);

        if (!Wilayah::where('kode_kab_kota', null)->where('nama_provinsi', $namaProvinsi)->exists()) {
            return back()->withErrors(['nama_provinsi' => 'Provinsi tidak ditemukan.'])->withInput();
        }

        $baris = preg_split('/\r\n|\r|\n/', trim($request->daftar_kab_kota));
        $ditambah = 0;
        foreach ($baris as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = explode('|', $line, 2);
            $namaKab = trim($parts[1] ?? $parts[0]);
            $kodeKab = trim($parts[0]);
            if ($namaKab === '' || $kodeKab === '' || $namaKab === $kodeKab) continue;
            if (Wilayah::whereNotNull('kode_kab_kota')->where('nama_kab_kota', $namaKab)->exists()) continue;
            Wilayah::create([
                'kode_provinsi' => $kodeProvinsi,
                'nama_provinsi' => $namaProvinsi,
                'kode_kab_kota' => $kodeKab,
                'nama_kab_kota' => $namaKab,
            ]);
            $ditambah++;
        }

        return back()->with('success', '✅ ' . $ditambah . ' kabupaten/kota ditambahkan ke "' . $namaProvinsi . '".');
    }

    public function wilayahKabKotaUpdate(Request $request, Wilayah $wilayah)
    {
        $this->cekUtama();

        $request->validate([
            'nama_kab_kota' => 'required|string|max:255',
            'kode_kab_kota' => 'required|string|max:20',
        ]);

        $wilayah->update([
            'nama_kab_kota' => trim($request->nama_kab_kota),
            'kode_kab_kota' => trim($request->kode_kab_kota),
        ]);

        return back()->with('success', '🏙️ Kabupaten/Kota "' . trim($request->nama_kab_kota) . '" berhasil diperbarui.');
    }

    public function wilayahKabKotaDestroy(Wilayah $wilayah)
    {
        $this->cekUtama();

        $nama = $wilayah->nama_kab_kota;
        $wilayah->delete();

        return back()->with('success', '🗑️ Kabupaten/Kota "' . $nama . '" dihapus.');
    }
}
