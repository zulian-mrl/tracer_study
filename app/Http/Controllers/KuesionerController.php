<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\MasterAlumni;
use App\Models\Setting;

class KuesionerController extends Controller
{
    // Meniru logika JS detailTempatKerjaTerisi(): apakah ada data detail tempat kerja diisi
    private function tempatKerjaTerisi(Request $request): bool
    {
        foreach (['f510_provinsi', 'f510_kab_kota', 'f11_jenis_instansi', 'f11_02', 'f5b_nama_perusahaan', 'f5c_posisi', 'f5d_tingkat'] as $field) {
            $value = trim((string) $request->input($field));
            if ($field === 'f510_provinsi' && $value === 'Belum Bekerja') {
                continue;
            }
            if ($value !== '') {
                return true;
            }
        }
        return false;
    }

        public function index()
        {
            if (Setting::get('kuesioner_terbuka', '1') !== '1') {
                return view('kuesioner', [
                    'kuesionerDitutup' => true,
                    'kuesionerPesan' => Setting::get('kuesioner_pesan_tutup'),
                ]);
            }

            return view('kuesioner');
        }
        
        public function store(Request $request)
        {
            if (Setting::get('kuesioner_terbuka', '1') !== '1') {
                return redirect()->back()->withErrors(['autentikasi' => Setting::get('kuesioner_pesan_tutup')]);
            }

            // 1. Validasi lengkap di sisi server (tidak bergantung pada JavaScript)
            $domainEmail = preg_quote(ltrim(trim(Setting::get('kuesioner_email_domain', 'gmail.com')), '@'), '/');
            $status = (string) $request->input('f8_status_saat_ini');
            $statusBekerja = in_array($status, ['1', '3'], true);    // Bekerja / Wiraswasta
            $statusTidakKerja = in_array($status, ['2', '5'], true); // Belum bekerja / Cari kerja
            $statusLanjut = $status === '4';
            $dapatKerja = $request->input('f504_mendapat_pekerjaan_6_bulan') === '1';
            $tempatKerjaTerisi = $this->tempatKerjaTerisi($request);
            $keselarasanWajib = $statusBekerja || ($statusLanjut && $tempatKerjaTerisi);
            $kompAWajib = $statusBekerja || $statusTidakKerja || ($statusLanjut && $tempatKerjaTerisi);
            $kompBWajib = $statusBekerja || ($statusLanjut && $tempatKerjaTerisi);

            $kompetensi = [
                'f1761_f1762', 'f1763_f1764', 'f1765_f1766', 'f1767_f1768',
                'f1769_f1770', 'f1771_f1772', 'f1773_f1774',
            ];

            $rules = [
                'no_mahasiswa' => ['required', 'string', 'max:50'],
                'tahun_lulus' => ['required', 'string', 'max:10'],
                'kode_prodi' => ['required', 'string', 'max:10'],
                'nama' => ['required', 'string', 'max:255'],
                'no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/'],
                'nik' => ['required', 'digits:16'],
                'npwp' => ['nullable', 'string', 'max:30'],
                'email' => ['required', 'email', 'regex:/@' . $domainEmail . '$/i'],
                'f8_status_saat_ini' => ['required', Rule::in(['1', '2', '3', '4', '5'])],
                'f504_mendapat_pekerjaan_6_bulan' => ['required', Rule::in(['1', '2'])],
                'f502_bulan_dapat_kerja_ya' => [Rule::requiredIf($dapatKerja), 'nullable', 'integer', 'min:0', 'max:99'],
                'f505_pendapatan_per_bulan' => [Rule::requiredIf($dapatKerja), 'nullable', 'integer', 'min:0'],
                'f502_bulan_dapat_kerja_tidak' => [Rule::requiredIf(!$dapatKerja), 'nullable', 'integer', 'min:0', 'max:99'],
                'f510_provinsi' => [Rule::requiredIf($statusBekerja), 'nullable', 'string', 'max:60'],
                'f510_kab_kota' => [Rule::requiredIf($statusBekerja), 'nullable', 'string', 'max:60'],
                'f11_jenis_instansi' => [Rule::requiredIf($statusBekerja), 'nullable', Rule::in(['1', '2', '3', '4', '5', '6', '7'])],
                'f11_02' => ['nullable', 'string', 'max:255'],
                'f5b_nama_perusahaan' => ['nullable', 'string', 'max:255'],
                'f5c_posisi' => ['nullable', 'string', 'max:60'],
                'f5d_tingkat' => ['nullable', 'string', 'max:60'],
                'f18a_sumber_biaya_studi' => [Rule::requiredIf($statusLanjut), 'nullable', 'string', 'max:255'],
                'f18b_perguruan_tinggi_studi' => [Rule::requiredIf($statusLanjut), 'nullable', 'string', 'max:255'],
                'f18c_program_studi' => [Rule::requiredIf($statusLanjut), 'nullable', 'string', 'max:255'],
                'f18d_tanggal_masuk' => [Rule::requiredIf($statusLanjut), 'nullable', 'date'],
                'f12_01' => [Rule::requiredIf($statusLanjut), 'nullable', Rule::in(['1', '2', '3', '4', '5', '6', '7'])],
                'f12_02' => ['nullable', 'string', 'max:255'],
                'f14' => [Rule::requiredIf($keselarasanWajib), 'nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'f15' => [Rule::requiredIf($keselarasanWajib), 'nullable', Rule::in(['1', '2', '3', '4'])],
                'f301' => ['required', Rule::in(['1', '2', '3'])],
                'f302' => [Rule::requiredIf($request->input('f301') === '1'), 'nullable', 'integer', 'min:1', 'max:99'],
                'f303' => [Rule::requiredIf($request->input('f301') === '2'), 'nullable', 'integer', 'min:1', 'max:99'],
                'f6_jumlah_lamaran' => ['nullable', 'integer', 'min:0'],
                'f7_jumlah_respons' => ['nullable', 'integer', 'min:0'],
                'f17a_jumlah_wawancara' => ['nullable', 'integer', 'min:0'],
                'f10_aktif' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'f10_lainnya' => ['nullable', 'string', 'max:255'],
                'f416_tuliskan' => ['nullable', 'string', 'max:255'],
                'f1614_tuliskan' => ['nullable', 'string', 'max:255'],
            ];

            // F17 Matrik Kompetensi (kolom A dan B), nilai 1-5.
            // Form memakai nama comp_a_*/comp_b_*, tapi store() juga menerima f*_A/f*_B,
            // jadi dua nama itu dijadikan alternatif (wajib hanya jika keduanya kosong).
            foreach ($kompetensi as $k) {
                $rules["comp_a_{$k}"] = [Rule::requiredIf($kompAWajib && !$request->filled("{$k}_A")), 'nullable', Rule::in(['1', '2', '3', '4', '5'])];
                $rules["comp_b_{$k}"] = [Rule::requiredIf($kompBWajib && !$request->filled("{$k}_B")), 'nullable', Rule::in(['1', '2', '3', '4', '5'])];
                $rules["{$k}_A"] = [Rule::requiredIf($kompAWajib && !$request->filled("comp_a_{$k}")), 'nullable', Rule::in(['1', '2', '3', '4', '5'])];
                $rules["{$k}_B"] = [Rule::requiredIf($kompBWajib && !$request->filled("comp_b_{$k}")), 'nullable', Rule::in(['1', '2', '3', '4', '5'])];
            }

            // F21-F27 penekanan metode pembelajaran, nilai 1-5
            foreach (['f21', 'f22', 'f23', 'f24', 'f25', 'f26', 'f27'] as $m) {
                $rules[$m] = ['nullable', Rule::in(['1', '2', '3', '4', '5'])];
            }

            $request->validate($rules, [
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.regex' => 'Email harus menggunakan @' . ltrim(trim(Setting::get('kuesioner_email_domain', 'gmail.com')), '@') . '.',
                'nik.digits' => 'NIK harus berjumlah tepat 16 digit angka.',
                'no_hp.regex' => 'Nomor HP harus diawali 08 dan minimal 10 digit.',
                'f502_bulan_dapat_kerja_ya.required' => 'Isi dalam berapa bulan Anda mendapatkan pekerjaan.',
                'f505_pendapatan_per_bulan.required' => 'Isi rata-rata pendapatan per bulan.',
                'f502_bulan_dapat_kerja_tidak.required' => 'Isi berapa bulan Anda belum mendapatkan pekerjaan.',
                'f510_provinsi.required' => 'Pilih lokasi provinsi tempat Anda bekerja.',
                'f510_kab_kota.required' => 'Pilih kabupaten/kota tempat Anda bekerja.',
                'f11_jenis_instansi.required' => 'Pilih jenis perusahaan/instansi tempat Anda bekerja.',
                'f18a_sumber_biaya_studi.required' => 'Pilih sumber biaya studi lanjut.',
                'f18b_perguruan_tinggi_studi.required' => 'Isi perguruan tinggi tujuan studi lanjut.',
                'f18c_program_studi.required' => 'Isi program studi tujuan studi lanjut.',
                'f18d_tanggal_masuk.required' => 'Isi tanggal masuk studi lanjut.',
                'f12_01.required' => 'Pilih sumber dana pembiayaan kuliah.',
                'f14.required' => 'Pilih erat hubungan bidang studi dengan pekerjaan.',
                'f15.required' => 'Pilih tingkat pendidikan yang paling tepat.',
                'f302.required' => 'Isi berapa bulan sebelum lulus Anda mulai mencari kerja.',
                'f303.required' => 'Isi berapa bulan setelah lulus Anda mulai mencari kerja.',
            ]);

            $nimInput  = trim($request->no_mahasiswa);
            $nikInput  = trim($request->nik);
            $namaInput = trim($request->nama);

            $emailInput = (string) $request->email;
            $atPos = strrpos($emailInput, '@');
            if ($atPos !== false) {
                $emailInput = substr($emailInput, 0, $atPos) . '@' . strtolower(substr($emailInput, $atPos + 1));
            }
            $tahunlulus = $request->tahun_lulus;
            $kodeprodi= $request->kode_prodi;

            // Cek kecocokan data ke tabel master_alumnis
            $alumniValid = MasterAlumni::where('no_mahasiswa', $nimInput)
                                        ->where('nik', $nikInput)
                                        ->where('tahun_lulus', $tahunlulus)
                                        ->where('kode_prodi', $kodeprodi)
                                        ->first();

            // Jika kombinasi NIM dan NIK tidak ditemukan di database master
            if (!$alumniValid) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['autentikasi' => Setting::get('kuesioner_pesan_tidak_terdaftar')]);
            }

            // Jika nama tidak sesuai (toleran terhadap perbedaan huruf besar/kecil)
            if (strcasecmp(trim($alumniValid->nama), trim($namaInput)) !== 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['autentikasi' => '❌ Nama yang Anda masukkan tidak sesuai dengan data pemilik NIM ini.']);
            }

            // 2. Direct Insert ke phpMyAdmin
            try {
                DB::table('kuesioner_alumnis')->updateOrInsert(
                ['no_mahasiswa' => $nimInput],
                [
                'kode_PT' => $request->filled('kode_PT') ? $request->kode_PT : Setting::get('kode_pt_default', '101004'),
                'tahun_lulus' => $request->tahun_lulus,
                'kode_prodi' => $request->kode_prodi,
                'nama' => $namaInput,
                'no_hp' => $request->no_hp,
                'email' => $emailInput,
                'nik' => $nikInput,
                'npwp' => $request->npwp,
                'f8_status_saat_ini' => match ($request->f8_status_saat_ini) {
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        default => $request->f8_status_saat_ini,
                    },
                'f504_mendapat_pekerjaan_6_bulan' => match ($request->f504_mendapat_pekerjaan_6_bulan) {
                        '1' => '1',
                        '2' => '2',
                        default => $request->f504_mendapat_pekerjaan_6_bulan,
                    },
                'f502_bulan_dapat_kerja' => $request->filled('f502_bulan_dapat_kerja_ya') ? $request->f502_bulan_dapat_kerja_ya : null,
                'f505_pendapatan_per_bulan' => $request->filled('f505_pendapatan_per_bulan') ? $request->f505_pendapatan_per_bulan : null,
                'f506_bulan_dapat_kerja_setelahnya' => $request->filled('f502_bulan_dapat_kerja_tidak') ? $request->f502_bulan_dapat_kerja_tidak : null,
                'f510_provinsi' => config('wilayah.provinsi')[$request->f510_provinsi] ?? '0',

                'f510_kab_kota' => config('wilayah.kab_kota')[$request->f510_kab_kota] ?? '0',
                'f11_jenis_instansi' => match ($request->f11_jenis_instansi) {
                        '1'=> '1',
                        '2'=> '6',
                        '3'=> '7',
                        '4'=> '2',
                        '5'=> '3',
                        '6'=> '4',
                        '7'=> '5',
                        default => $request->f11_jenis_instansi,
                    },
                'f11_jenis_instansi_lainnya' => $request->f11_02,
                'f5b_nama_perusahaan' => $request->f5b_nama_perusahaan,
                'f5c_posisi_wiraswasta' => $request->f5c_posisi,
                'f5d_tingkat_tempat_kerja' => $request->f5d_tingkat,
                'f12_sumber_biaya_kuliah' => match ($request->f12_01) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        '5'=> '5',
                        '6'=> '6',
                        '7'=> '7',
                        default => $request->f12_01,
                    },
                'f18a_sumber_biaya_studi'      => $request->f18a_sumber_biaya_studi,
                'f18b_perguruan_tinggi_studi'  => $request->f18b_perguruan_tinggi_studi,
                'f18c_program_studi'           => $request->f18c_program_studi,
                'f18d_tanggal_masuk'           => $request->filled('f18d_tanggal_masuk') ? $request->f18d_tanggal_masuk : null,
                'f12_sumber_biaya_kuliah_lainnya' => $request->f12_02,
                'f14_erat_hubungan_studi' => match ($request->f14) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        '5'=> '5',
                        default => $request->f14,
                },
                'f15_tingkat_paling_tepat' => match ($request->f15) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        default => $request->f15,
                },
                'f1701_A' => match($request->f1761_f1762_A ?? $request->comp_a_f1761_f1762) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1702_A' => match($request->f1763_f1764_A ?? $request->comp_a_f1763_f1764) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1703_A' => match($request->f1765_f1766_A ?? $request->comp_a_f1765_f1766) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1704_A' => match($request->f1767_f1768_A ?? $request->comp_a_f1767_f1768) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1705_A' => match($request->f1769_f1770_A ?? $request->comp_a_f1769_f1770) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1706_A' => match($request->f1771_f1772_A ?? $request->comp_a_f1771_f1772) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1707_A' => match($request->f1773_f1774_A ?? $request->comp_a_f1773_f1774) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },

                'f1701_B' => match($request->f1761_f1762_B ?? $request->comp_b_f1761_f1762) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1702_B' => match($request->f1763_f1764_B ?? $request->comp_b_f1763_f1764) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null 
                },
                'f1703_B' => match($request->f1765_f1766_B ?? $request->comp_b_f1765_f1766) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1704_B' => match($request->f1767_f1768_B ?? $request->comp_b_f1767_f1768) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1705_B' => match($request->f1769_f1770_B ?? $request->comp_b_f1769_f1770) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1706_B' => match($request->f1771_f1772_B ?? $request->comp_b_f1771_f1772) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },
                'f1707_B' => match($request->f1773_f1774_B ?? $request->comp_b_f1773_f1774) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => null
                },

                'created_at' => now(),
                'updated_at' => now(),

                'f21_perkuliahan' => match ($request->f21 ?? $request->f21_perkuliahan) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f22_demonstrasi' => match ($request->f22 ?? $request->f22_demonstrasi) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f23_riset' => match ($request->f23 ?? $request->f23_riset) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f24_magang' => match ($request->f24 ?? $request->f24_magang) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f25_praktikum' => match ($request->f25 ?? $request->f25_praktikum) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f26_kerja_lapangan' => match ($request->f26 ?? $request->f26_kerja_lapangan) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f27_diskusi' => match ($request->f27 ?? $request->f27_diskusi) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => null
                },
                'f301_kapan_mencari_pekerjaan' => match ($request->f301) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    default => '3'
                },
                'f302_bulan_sebelum_lulus' => $request->filled('f302') ? $request->f302 : null,
                'f303_bulan_setelah_lulus' => $request->filled('f303') ? $request->f303 : null,

                'f401_iklan_koran_brosur'           => $request->has('f401') ? '1' : '0',
                'f402_melamar_tanpa_lowongan'       => $request->has('f402') ? '1' : '0',
                'f403_bursa_pameran_online'         => $request->has('f403') ? '1' : '0',
                'f404_internet_iklan_online'        => $request->has('f404') ? '1' : '0',
                'f405_dihubungi_perusahaan'         => $request->has('f405') ? '1' : '0',
                'f406_menghubungi_kemenakertrans'   => $request->has('f406') ? '1' : '0',
                'f407_agen_tenaga_kerja'            => $request->has('f407') ? '1' : '0',
                'f408_karir_fakultas_universitas'   => $request->has('f408') ? '1' : '0',
                'f409_kantor_kemanusiaan_alumni'    => $request->has('f409') ? '1' : '0',
                'f410_membangun_jejaring_kuliah'    => $request->has('f410') ? '1' : '0',
                'f411_melalui_relasi'               => $request->has('f411') ? '1' : '0',
                'f412_membangun_bisnis_sendiri'     => $request->has('f412') ? '1' : '0',
                'f413_penempatan_kerja_magang'      => $request->has('f413') ? '1' : '0',
                'f414_tempat_kerja_sama_kuliah'     => $request->has('f414') ? '1' : '0',
                'f415_lainnya'                      => $request->has('f415') ? '1' : '0',
                'f416_tuliskan'                      => $request->f416_tuliskan ?? null,

                'f6_perusahaan_dilamar' => $request->f6_jumlah_lamaran,
                'f7_perusahaan_merespon' => $request->f7_jumlah_respons,
                'f7a_mengundang_wawancara' => $request->f17a_jumlah_wawancara,
                'f10_aktif_mencari_kerja' => match ($request->f10_aktif) {
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    default => '1',
                },
                'f10_lainnya' => $request->f10_lainnya ?? null,

                'f1601_pertanyaan_tidak_sesuai'     => $request->has('f1601') ? '1' : '0',
                'f1602_belum_dapat_kerja_sesuai'    => $request->has('f1602') ? '1' : '0',
                'f1603_prospek_karir_baik'          => $request->has('f1603') ? '1' : '0',
                'f1604_suka_area_kerja_tersebut'    => $request->has('f1604') ? '1' : '0',
                'f1605_dipromosikan_posisi_lain'    => $request->has('f1605') ? '1' : '0',
                'f1606_pendapatan_lebih_tinggi'     => $request->has('f1606') ? '1' : '0',
                'f1607_pekerjaan_lebih_aman'        => $request->has('f1607') ? '1' : '0',
                'f1608_pekerjaan_lebih_menarik'     => $request->has('f1608') ? '1' : '0',
                'f1609_mungkinkan_kerja_tambahan'   => $request->has('f1609') ? '1' : '0',
                'f1610_lokasi_dekat_rumah'          => $request->has('f1610') ? '1' : '0',
                'f1611_menjamin_kebutuhan_keluarga' => $request->has('f1611') ? '1' : '0',
                'f1612_awal_menitip_karir'          => $request->has('f1612') ? '1' : '0',
                'f1613_lainnya'                       => $request->has('f1613') ? '1' : '0',
                'f1614_tuliskan'                    => $request->input('f1614'),
            ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['autentikasi' => 'Terjadi kesalahan saat menyimpan data Anda. Silakan coba beberapa saat lagi.']);
            }

        return redirect()->back()->with('success', Setting::get('kuesioner_sukses', 'Kuesioner Anda berhasil dikirim! Terima kasih atas partisipasinya.'));
        }

}
