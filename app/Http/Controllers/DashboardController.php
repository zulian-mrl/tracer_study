<?php

namespace App\Http\Controllers;

use App\Exports\KuesionerAlumniExport;
use App\Imports\AlumniImport;
use App\Models\Setting;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    private function cekSuper(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_super, 403);
    }

    public function import(Request $request)
    {
        $this->cekSuper();

        // 1. Validasi pastikan berkas wajib diisi dan bertipe excel
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_excel.required' => 'Silakan pilih berkas Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format berkas harus berupa .xlsx, .xls, atau .csv',
            'file_excel.max' => 'Ukuran berkas maksimal adalah 10 MB.',
        ]);

        try {
            // 2. Proses pembacaan atau import berkas Excel di sini
            $import = new AlumniImport;
            Excel::import($import, $request->file('file_excel'));

            $counts = $import->getCounts();
            $message = '⚡ Data master acuan alumni berhasil diperbarui!';
            $bagian = [];
            if ($counts['insert'] > 0) {
                $bagian[] = $counts['insert'].' baris berhasil';
            }
            if ($counts['duplicate'] > 0) {
                $bagian[] = $counts['duplicate'].' dilewati (duplikat)';
            }
            if ($counts['invalid'] > 0) {
                $bagian[] = $counts['invalid'].' gagal (tidak lengkap)';
            }
            if ($bagian !== []) {
                $message .= ' ('.implode(', ', $bagian).')';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Import alumni gagal: '.$e->getMessage());

            return redirect()->back()->withErrors(['file_excel' => 'Terjadi kesalahan saat mengimpor data. Pastikan format berkas benar dan coba lagi.']);
        }
    }

    public function dashboard(Request $request)
    {
        // 1. Ambil data unik Tahun Lulus & Prodi untuk isi pilihan Dropdown Filter
        $listTahun = DB::table('kuesioner_alumnis')->distinct()->pluck('tahun_lulus')->sort()->values();

        $prodiLabels = Setting::optionList('prodi_list', Setting::defaults()['prodi_list']);

        // 2. Tangkap filter dari Request Admin
        $tahunTerpilih = $request->input('tahun_lulus');
        $prodiTerpilih = $request->input('kode_prodi');

        // Super admin utama (akun is_super dengan id terkecil) untuk kunci menu Pengaturan
        $utamaId = DB::table('users')->where('is_super', 1)->orderBy('id')->value('id');

        // Peringatan: pengisi kuesioner yang NIM-nya tidak ada di master_alumnis (belum diimpor lembaga)
        $alumniBelumImpor = DB::table('kuesioner_alumnis as k')
            ->leftJoin('master_alumnis as m', 'm.no_mahasiswa', '=', 'k.no_mahasiswa')
            ->whereNull('m.no_mahasiswa')
            ->orderBy('k.created_at')
            ->get(['k.no_mahasiswa', 'k.nama', 'k.tahun_lulus', 'k.kode_prodi']);

        // Buat query dasar pencarian data kuesioner
        $query = DB::table('kuesioner_alumnis');

        // Jika filter diisi oleh admin, saring datanya
        if ($tahunTerpilih) {
            $query->where('tahun_lulus', $tahunTerpilih);
        }
        if ($prodiTerpilih) {
            $query->where('kode_prodi', $prodiTerpilih);
        }

        $dataAlumni = $query->get();
        $totalAlumni = $dataAlumni->count();

        // --- PROSES DATA GRAFIK (HANYA DIHITUNG JIKA TOTAL DATA > 0) ---

        // Opsi pilihan tunggal yang bisa diubah admin; menambah/mengurangi opsi otomatis mengubah grafik.
        $defaultStatus = "1|Bekerja (full time/part time)\n3|Wiraswasta\n4|Melanjutkan Pendidikan\n5|Tidak Kerja tetapi sedang mencari kerja\n2|Belum memungkinkan bekerja";
        $defaultPerusahaan = "1|Instansi pemerintah\n2|BUMN/BUMD\n3|Institusi/Organisasi Multilateral\n4|Organisasi non-profit/Lembaga Swadaya Masyarakat\n5|Perusahaan swasta\n6|Wiraswasta/Perusahaan sendiri\n7|Lainnya";
        $defaultDana = "1|Biaya Sendiri / Keluarga\n2|Beasiswa ADIK\n3|Beasiswa BIDIKMISI\n4|Beasiswa PPA\n5|Beasiswa AFIRMASI\n6|Beasiswa Perusahaan/Swasta\n7|Lainnya";

        $opsiStatus = Setting::optionList('opsi_f8_status', $defaultStatus);
        $opsiPerusahaan = Setting::optionList('opsi_f11_instansi', $defaultPerusahaan);
        $opsiDana = Setting::optionList('opsi_f12_dana', $defaultDana);

        // Peta nilai simpanan → label. f11 disimpan memakai kode lama (1,6,7,2,3,4,5),
        // nilai baru di luar 1-7 disimpan apa adanya.
        $konversiF11 = ['1' => '1', '2' => '6', '3' => '7', '4' => '2', '5' => '3', '6' => '4', '7' => '5'];
        $labelStatus = $opsiStatus;
        $labelDana = $opsiDana;
        $labelPerusahaan = [];
        foreach ($opsiPerusahaan as $nilaiOpsi => $label) {
            $labelPerusahaan[$konversiF11[$nilaiOpsi] ?? $nilaiOpsi] = $label;
        }

        $bucketOpsi = function (array $opsi): array {
            $out = [];
            foreach ($opsi as $label) {
                $out[$label] = 0;
            }
            $out['Lainnya'] = 0;

            return $out;
        };

        // A. Status Bekerja (f8_status_saat_ini)
        $statusKerja = $bucketOpsi($opsiStatus);

        // B. Pendapatan Per Bulan (f505_pendapatan_per_bulan)
        $pendapatan = ['< 2 Juta' => 0, '2 - 5 Juta' => 0, '> 5 Juta' => 0];

        $statusPerusahaanKerja = $bucketOpsi($opsiPerusahaan);

        $SumberDana = $bucketOpsi($opsiDana);

        $PosisiJabatan = [];
        $PilihTingkat = [];
        $lokasiKerja = [];
        $lokasiKota = [];
        $tempatKuliah = [];
        $programStudiLanjut = [];
        $sumberBiayaLanjut = ['Biaya Sendiri' => 0, 'Beasiswa' => 0, 'Lainnya' => 0];

        // E. Kompetensi Dikuasai vs Diperlukan (f1701_A sampai f1707_B)
        $kompetensiDikuasai = [0, 0, 0, 0, 0, 0, 0]; // f1701_A sd f1707_A
        $kompetensiDiperlukan = [0, 0, 0, 0, 0, 0, 0]; // f1701_B sd f1707_B

        // F. Penekanan Metode Pembelajaran
        $metodeSangatBesar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        $metodeBesar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        $metodeCukupBesar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        $metodeKurang = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        $metodeTidakSama = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];

        // G. Waktu Mencari Kerja (f302 / f303 dlm hitungan 1-12 bulan)
        $waktuCariKerja = ['1-3 Bulan' => 0, '4-6 Bulan' => 0, '7-12 Bulan' => 0, '> 12 Bulan' => 0];

        // H. Bagaimana Mencari Pekerjaan (Metode Terbanyak)
        $caraCariKerja = [
            'Iklan Koran' => 0,
            'Melamar Langsung' => 0,
            'Bursa Kerja' => 0,
            'Internet' => 0,
            'Dihubungi Perusahaan' => 0,
            'Kemenakertrans' => 0,
            'Agen' => 0,
            'CDC Kampus' => 0,
            'Kantor Kemanusiaan' => 0,
            'Kuliah' => 0,
            'Relasi' => 0,
            'Bisnis Sendiri' => 0,
            'Tempat Magang' => 0,
            'Kerja Saat Kuliah' => 0,
            'Lainnya' => 0,
        ];

        // I. Aktivitas Lamaran (Dilamar, Merespon, Wawancara)
        $sumLamaran = ['f6' => 0, 'f7' => 0, 'f7a' => 0];
        $cntLamaran = ['f6' => 0, 'f7' => 0, 'f7a' => 0];

        // J. Keaktifan Mencari Kerja (f10_aktif_mencari_kerja)
        $keaktifan = ['Aktif' => 0, 'Tidak Aktif' => 0];

        // K. Alasan Pekerjaan Tidak Sesuai (f1601 - f1612)
        $alasanTidakSesuai = [
            'Pekerjaan Sesuai Pendidikan' => 0,
            'Belum Dapat Yang Sesuai' => 0,
            'Prospek Karir' => 0,
            'Suka Bidang Ini' => 0,
            'Promosi Kurang Tepat' => 0,
            'Gaji Lebih Tinggi' => 0,
            'Pekerjaan Lebih Aman' => 0,
            'Pekerjaan Lebih Menarik' => 0,
            'Bisa Tambah Kerja' => 0,
            'Lokasi Dekat' => 0,
            'Menjamin kebutuhan Keluarga' => 0,
            'Awal Karir' => 0,
            'Lainnya' => 0];

        // L. Nama Alumni per Kategori (untuk kartu ringkasan yang bisa diklik)
        $daftarNama = [
            'total' => [],
            'bekerja' => [],
            'aktif' => [],
            'lanjut' => [],
        ];

        // M. Nama Alumni per irisan/bar tiap grafik (untuk klik pada grafik)
        // Label yang ditampilkan grafik bisa diubah admin (nama item di Pengaturan),
        // jadi grupkan berdasarkan nama item aktif agar klik pada irisan tetap cocok.
        $labelMap = function (string $slug, array $fixed): array {
            $namaItem = array_column(Setting::items($slug), 'nama');
            $map = [];
            foreach ($fixed as $i => $label) {
                $map[$label] = $namaItem[$i] ?? $label;
            }

            return $map;
        };
        $mapPendapatan = $labelMap('pendapatan', ['< 2 Juta', '2 - 5 Juta', '> 5 Juta']);
        $mapKurva = $labelMap('kurva', ['1-3 Bulan', '4-6 Bulan', '7-12 Bulan', '> 12 Bulan']);
        $mapKeaktifan = $labelMap('keaktifan', ['Aktif', 'Tidak Aktif']);
        $mapCaraItem = $labelMap('cara', ['Iklan Koran', 'Melamar Langsung', 'Bursa Kerja', 'Internet', 'Dihubungi Perusahaan', 'Kemenakertrans', 'Agen', 'CDC Kampus', 'Kantor Kemanusiaan', 'Kuliah', 'Relasi', 'Bisnis Sendiri', 'Tempat Magang', 'Kerja Saat Kuliah', 'Lainnya']);

        $namaPerGrafik = [];

        // Seluruh agregasi grafik dihitung dalam SATU pass (dulu 15x iterasi penuh).
        foreach ($dataAlumni as $d) {
            // A. Status Bekerja (nilai tidak dikenal masuk 'Lainnya')
            if (isset($d->f8_status_saat_ini)) {
                $status_val = strtolower($d->f8_status_saat_ini);
                $statusKerja[$labelStatus[$status_val] ?? 'Lainnya']++;
            }

            // B. Pendapatan Per Bulan
            $val = (int) ($d->f505_pendapatan_per_bulan ?? 0);
            if ($val > 0 && $val < 2000000) {
                $pendapatan['< 2 Juta']++;
            } elseif ($val >= 2000000 && $val <= 5000000) {
                $pendapatan['2 - 5 Juta']++;
            } elseif ($val > 5000000) {
                $pendapatan['> 5 Juta']++;
            }

            // C. Jenis Perusahaan / Instansi (nilai tidak dikenal masuk 'Lainnya')
            if (isset($d->f11_jenis_instansi)) {
                $status_val = strtolower($d->f11_jenis_instansi);
                $statusPerusahaanKerja[$labelPerusahaan[$status_val] ?? 'Lainnya']++;
            }

            // D. Sumber Dana Kuliah (nilai tidak dikenal masuk 'Lainnya')
            if (isset($d->f12_sumber_biaya_kuliah)) {
                $status_val = strtolower($d->f12_sumber_biaya_kuliah);
                $SumberDana[$labelDana[$status_val] ?? 'Lainnya']++;
            }

            // Posisi / Tingkat / Lokasi / Tempat Kuliah (teks bebas)
            if (! empty($d->f5c_posisi_wiraswasta) && $d->f5c_posisi_wiraswasta != '?') {
                $PosisiJabatan[$d->f5c_posisi_wiraswasta] = ($PosisiJabatan[$d->f5c_posisi_wiraswasta] ?? 0) + 1;
            }
            if (! empty($d->f5d_tingkat_tempat_kerja) && $d->f5d_tingkat_tempat_kerja != '?') {
                $PilihTingkat[$d->f5d_tingkat_tempat_kerja] = ($PilihTingkat[$d->f5d_tingkat_tempat_kerja] ?? 0) + 1;
            }
            if (! empty($d->f510_provinsi) && $d->f510_provinsi != '?') {
                $lokasiKerja[$d->f510_provinsi] = ($lokasiKerja[$d->f510_provinsi] ?? 0) + 1;
            }
            if (! empty($d->f510_kab_kota) && $d->f510_kab_kota != '?') {
                $lokasiKota[$d->f510_kab_kota] = ($lokasiKota[$d->f510_kab_kota] ?? 0) + 1;
            }
            if (! empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                $tempatKuliah[$d->f18b_perguruan_tinggi_studi] = ($tempatKuliah[$d->f18b_perguruan_tinggi_studi] ?? 0) + 1;
            }
            if (! empty($d->f18c_program_studi) && $d->f18c_program_studi != '?') {
                $programStudiLanjut[$d->f18c_program_studi] = ($programStudiLanjut[$d->f18c_program_studi] ?? 0) + 1;
            }
            if (! empty($d->f18a_sumber_biaya_studi) && $d->f18a_sumber_biaya_studi != '?') {
                $kunciBiaya = in_array(strtolower(trim($d->f18a_sumber_biaya_studi)), ['biaya sendiri', 'beasiswa']) ? ucwords(strtolower(trim($d->f18a_sumber_biaya_studi))) : 'Lainnya';
                $sumberBiayaLanjut[$kunciBiaya]++;
            }

            // E. Kompetensi (kosong dianggap 3)
            for ($i = 1; $i <= 7; $i++) {
                $kompetensiDikuasai[$i - 1] += isset($d->{"f170{$i}_A"}) ? (int) $d->{"f170{$i}_A"} : 3;
                $kompetensiDiperlukan[$i - 1] += isset($d->{"f170{$i}_B"}) ? (int) $d->{"f170{$i}_B"} : 3;
            }

            // F. Metode Pembelajaran (f21-f27, nilai 1-5)
            foreach (['f21_perkuliahan' => 'Perkuliahan', 'f22_demonstrasi' => 'Demonstrasi', 'f23_riset' => 'Riset', 'f24_magang' => 'Magang', 'f25_praktikum' => 'Praktikum', 'f26_kerja_lapangan' => 'Kerja Lapangan', 'f27_diskusi' => 'Diskusi'] as $fieldMetode => $labelMetode) {
                $mvMetode = $d->$fieldMetode ?? '';
                if ($mvMetode == '1') {
                    $metodeSangatBesar[$labelMetode]++;
                } elseif ($mvMetode == '2') {
                    $metodeBesar[$labelMetode]++;
                } elseif ($mvMetode == '3') {
                    $metodeCukupBesar[$labelMetode]++;
                } elseif ($mvMetode == '4') {
                    $metodeKurang[$labelMetode]++;
                } elseif ($mvMetode == '5') {
                    $metodeTidakSama[$labelMetode]++;
                }
            }

            // G. Waktu Mencari Kerja (f302 / f303)
            $bulan = (int) ($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
            if ($bulan >= 1 && $bulan <= 3) {
                $waktuCariKerja['1-3 Bulan']++;
            } elseif ($bulan >= 4 && $bulan <= 6) {
                $waktuCariKerja['4-6 Bulan']++;
            } elseif ($bulan >= 7 && $bulan <= 12) {
                $waktuCariKerja['7-12 Bulan']++;
            } elseif ($bulan > 12) {
                $waktuCariKerja['> 12 Bulan']++;
            }

            // H. Cara Mencari Kerja (f401-f415)
            foreach (['f401_iklan_koran_brosur' => 'Iklan Koran', 'f402_melamar_tanpa_lowongan' => 'Melamar Langsung', 'f403_bursa_pameran_online' => 'Bursa Kerja', 'f404_internet_iklan_online' => 'Internet', 'f405_dihubungi_perusahaan' => 'Dihubungi Perusahaan', 'f406_menghubungi_kemenakertrans' => 'Kemenakertrans', 'f407_agen_tenaga_kerja' => 'Agen', 'f408_karir_fakultas_universitas' => 'CDC Kampus', 'f409_kantor_kemanusiaan_alumni' => 'Kantor Kemanusiaan', 'f410_membangun_jejaring_kuliah' => 'Kuliah', 'f411_melalui_relasi' => 'Relasi', 'f412_membangun_bisnis_sendiri' => 'Bisnis Sendiri', 'f413_penempatan_kerja_magang' => 'Tempat Magang', 'f414_tempat_kerja_sama_kuliah' => 'Kerja Saat Kuliah', 'f415_lainnya' => 'Lainnya'] as $fieldCara => $labelCara) {
                if (($d->$fieldCara ?? '') == '1') {
                    $caraCariKerja[$labelCara]++;
                }
            }

            // I. Sum untuk rata-rata lamaran (f6/f7/f7a)
            foreach (['f6' => 'f6_perusahaan_dilamar', 'f7' => 'f7_perusahaan_merespon', 'f7a' => 'f7a_mengundang_wawancara'] as $keyLam => $fieldLam) {
                if ($d->$fieldLam !== null) {
                    $sumLamaran[$keyLam] += (float) $d->$fieldLam;
                    $cntLamaran[$keyLam]++;
                }
            }

            // J. Keaktifan Mencari Kerja (semua baris dihitung; '5'/Lainnya & kosong tidak masuk hitungan)
            $aktifVal2 = (string) trim($d->f10_aktif_mencari_kerja ?? '');
            if ($aktifVal2 === '3' || $aktifVal2 === '4') {
                $keaktifan['Aktif']++;
            } elseif ($aktifVal2 === '1' || $aktifVal2 === '2') {
                $keaktifan['Tidak Aktif']++;
            }

            // K. Alasan Pekerjaan Tidak Sesuai (f1601-f1613)
            foreach (['f1601_pertanyaan_tidak_sesuai' => 'Pekerjaan Sesuai Pendidikan', 'f1602_belum_dapat_kerja_sesuai' => 'Belum Dapat Yang Sesuai', 'f1603_prospek_karir_baik' => 'Prospek Karir', 'f1604_suka_area_kerja_tersebut' => 'Suka Bidang Ini', 'f1605_dipromosikan_posisi_lain' => 'Promosi Kurang Tepat', 'f1606_pendapatan_lebih_tinggi' => 'Gaji Lebih Tinggi', 'f1607_pekerjaan_lebih_aman' => 'Pekerjaan Lebih Aman', 'f1608_pekerjaan_lebih_menarik' => 'Pekerjaan Lebih Menarik', 'f1609_mungkinkan_kerja_tambahan' => 'Bisa Tambah Kerja', 'f1610_lokasi_dekat_rumah' => 'Lokasi Dekat', 'f1611_menjamin_kebutuhan_keluarga' => 'Menjamin kebutuhan Keluarga', 'f1612_awal_menitip_karir' => 'Awal Karir', 'f1613_lainnya' => 'Lainnya'] as $fieldAlasan => $labelAlasan) {
                if (($d->$fieldAlasan ?? '') == '1') {
                    $alasanTidakSesuai[$labelAlasan]++;
                }
            }

            $nama = trim((string) ($d->nama ?? ''));
            if ($nama === '') {
                continue;
            }

            // L. Nama Alumni per Kategori (kartu ringkasan yang bisa diklik)
            $daftarNama['total'][] = $nama;
            $statusNama = strtolower((string) ($d->f8_status_saat_ini ?? ''));
            if (in_array($statusNama, ['1', '3'], true)) {
                $daftarNama['bekerja'][] = $nama;
            } elseif ($statusNama === '4') {
                $daftarNama['lanjut'][] = $nama;
            }
            if ($aktifVal2 === '3' || $aktifVal2 === '4') {
                $daftarNama['aktif'][] = $nama;
            }

            // chartStatusKerja
            $st = strtolower((string) ($d->f8_status_saat_ini ?? ''));
            if ($st !== '') {
                $namaPerGrafik['chartStatusKerja'][$labelStatus[$st] ?? 'Lainnya'][] = $nama;
            }

            // chartPendapatan
            $gaji = (int) ($d->f505_pendapatan_per_bulan ?? 0);
            if ($gaji > 0 && $gaji < 2000000) {
                $namaPerGrafik['chartPendapatan'][$mapPendapatan['< 2 Juta']][] = $nama;
            } elseif ($gaji >= 2000000 && $gaji <= 5000000) {
                $namaPerGrafik['chartPendapatan'][$mapPendapatan['2 - 5 Juta']][] = $nama;
            } elseif ($gaji > 5000000) {
                $namaPerGrafik['chartPendapatan'][$mapPendapatan['> 5 Juta']][] = $nama;
            }

            // chartPerusahaanKerja
            $f11 = strtolower((string) ($d->f11_jenis_instansi ?? ''));
            if ($f11 !== '') {
                $namaPerGrafik['chartPerusahaanKerja'][$labelPerusahaan[$f11] ?? 'Lainnya'][] = $nama;
            }

            // chartSumberDana
            $f12 = strtolower((string) ($d->f12_sumber_biaya_kuliah ?? ''));
            if ($f12 !== '') {
                $namaPerGrafik['chartSumberDana'][$labelDana[$f12] ?? 'Lainnya'][] = $nama;
            }

            // chartPosisiJabatan / chartPilihTingkat / chartLokasi / chartLokasiKota
            if (! empty($d->f5c_posisi_wiraswasta) && $d->f5c_posisi_wiraswasta != '?') {
                $namaPerGrafik['chartPosisiJabatan'][$d->f5c_posisi_wiraswasta][] = $nama;
            }
            if (! empty($d->f5d_tingkat_tempat_kerja) && $d->f5d_tingkat_tempat_kerja != '?') {
                $namaPerGrafik['chartPilihTingkat'][$d->f5d_tingkat_tempat_kerja][] = $nama;
            }
            if (! empty($d->f510_provinsi) && $d->f510_provinsi != '?') {
                $namaPerGrafik['chartLokasi'][$d->f510_provinsi][] = $nama;
            }
            if (! empty($d->f510_kab_kota) && $d->f510_kab_kota != '?') {
                $namaPerGrafik['chartLokasiKota'][$d->f510_kab_kota][] = $nama;
            }

            // chartTempatKuliah (f18b) / chartSumberBiayaLanjut (f18a) / chartProgramStudiStudi (f18c)
            if (! empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                $namaPerGrafik['chartTempatKuliah'][$d->f18b_perguruan_tinggi_studi][] = $nama;
            }
            if (! empty($d->f18a_sumber_biaya_studi) && $d->f18a_sumber_biaya_studi != '?') {
                $kunciBiaya = in_array(strtolower(trim($d->f18a_sumber_biaya_studi)), ['biaya sendiri', 'beasiswa']) ? ucwords(strtolower(trim($d->f18a_sumber_biaya_studi))) : 'Lainnya';
                $namaPerGrafik['chartSumberBiayaLanjut'][$kunciBiaya][] = $nama;
            }
            if (! empty($d->f18c_program_studi) && $d->f18c_program_studi != '?') {
                $namaPerGrafik['chartProgramStudiStudi'][$d->f18c_program_studi][] = $nama;
            }

            // chartWaktuCariKerja
            $bulan = (int) ($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
            if ($bulan >= 1 && $bulan <= 3) {
                $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['1-3 Bulan']][] = $nama;
            } elseif ($bulan >= 4 && $bulan <= 6) {
                $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['4-6 Bulan']][] = $nama;
            } elseif ($bulan >= 7 && $bulan <= 12) {
                $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['7-12 Bulan']][] = $nama;
            } elseif ($bulan > 12) {
                $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['> 12 Bulan']][] = $nama;
            }

            // chartCaraCariKerja
            $mapCara = [
                'f401_iklan_koran_brosur' => 'Iklan Koran', 'f402_melamar_tanpa_lowongan' => 'Melamar Langsung',
                'f403_bursa_pameran_online' => 'Bursa Kerja', 'f404_internet_iklan_online' => 'Internet',
                'f405_dihubungi_perusahaan' => 'Dihubungi Perusahaan', 'f406_menghubungi_kemenakertrans' => 'Kemenakertrans',
                'f407_agen_tenaga_kerja' => 'Agen', 'f408_karir_fakultas_universitas' => 'CDC Kampus',
                'f409_kantor_kemanusiaan_alumni' => 'Kantor Kemanusiaan', 'f410_membangun_jejaring_kuliah' => 'Kuliah',
                'f411_melalui_relasi' => 'Relasi', 'f412_membangun_bisnis_sendiri' => 'Bisnis Sendiri',
                'f413_penempatan_kerja_magang' => 'Tempat Magang', 'f414_tempat_kerja_sama_kuliah' => 'Kerja Saat Kuliah',
                'f415_lainnya' => 'Lainnya',
            ];
            foreach ($mapCara as $field => $label) {
                if ((string) ($d->$field ?? '') === '1') {
                    $namaPerGrafik['chartCaraCariKerja'][$mapCaraItem[$label] ?? $label][] = $nama;
                }
            }

            // chartKeaktifan
            $aktifVal2 = (string) trim($d->f10_aktif_mencari_kerja ?? '');
            if ($aktifVal2 === '3' || $aktifVal2 === '4') {
                $namaPerGrafik['chartKeaktifan'][$mapKeaktifan['Aktif']][] = $nama;
            } elseif ($aktifVal2 === '1' || $aktifVal2 === '2') {
                $namaPerGrafik['chartKeaktifan'][$mapKeaktifan['Tidak Aktif']][] = $nama;
            }

            // chartAlasanTidakSesuai
            $mapAlasan = [
                'f1601_pertanyaan_tidak_sesuai' => 'Pekerjaan Sesuai Pendidikan', 'f1602_belum_dapat_kerja_sesuai' => 'Belum Dapat Yang Sesuai',
                'f1603_prospek_karir_baik' => 'Prospek Karir', 'f1604_suka_area_kerja_tersebut' => 'Suka Bidang Ini',
                'f1605_dipromosikan_posisi_lain' => 'Promosi Kurang Tepat', 'f1606_pendapatan_lebih_tinggi' => 'Gaji Lebih Tinggi',
                'f1607_pekerjaan_lebih_aman' => 'Pekerjaan Lebih Aman', 'f1608_pekerjaan_lebih_menarik' => 'Pekerjaan Lebih Menarik',
                'f1609_mungkinkan_kerja_tambahan' => 'Bisa Tambah Kerja', 'f1610_lokasi_dekat_rumah' => 'Lokasi Dekat',
                'f1611_menjamin_kebutuhan_keluarga' => 'Menjamin kebutuhan Keluarga', 'f1612_awal_menitip_karir' => 'Awal Karir',
                'f1613_lainnya' => 'Lainnya',
            ];
            foreach ($mapAlasan as $field => $label) {
                if ((string) ($d->$field ?? '') === '1') {
                    $namaPerGrafik['chartAlasanTidakSesuai'][$label][] = $nama;
                }
            }

            // chartMetodeBelajar
            $mapMetode = [
                'Perkuliahan' => 'f21_perkuliahan', 'Demonstrasi' => 'f22_demonstrasi', 'Riset' => 'f23_riset',
                'Magang' => 'f24_magang', 'Praktikum' => 'f25_praktikum', 'Kerja Lapangan' => 'f26_kerja_lapangan',
                'Diskusi' => 'f27_diskusi',
            ];
            foreach ($mapMetode as $label => $field) {
                $mv = (string) ($d->$field ?? '');
                if ($mv !== '' && $mv !== '0') {
                    $namaPerGrafik['chartMetodeBelajar'][$label][] = $nama;
                }
            }

            // chartKompetensi
            $mapKompetensi = [
                'Etika' => ['f1701_A', 'f1701_B'], 'Keahlian Inti' => ['f1702_A', 'f1702_B'],
                'Bahasa Inggris' => ['f1703_A', 'f1703_B'], 'TIK' => ['f1704_A', 'f1704_B'],
                'Komunikasi' => ['f1705_A', 'f1705_B'], 'Kerjasama Tim' => ['f1706_A', 'f1706_B'],
                'Pengembangan Diri' => ['f1707_A', 'f1707_B'],
            ];
            foreach ($mapKompetensi as $label => $fields) {
                foreach ($fields as $f) {
                    if (isset($d->$f) && $d->$f !== null && $d->$f !== '') {
                        $namaPerGrafik['chartKompetensi'][$label][] = $nama;
                        break;
                    }
                }
            }

            // chartRasioLamaran
            $mapRasio = ['f6_perusahaan_dilamar' => 'Perusahaan Dilamar', 'f7_perusahaan_merespon' => 'Mendapat Respons', 'f7a_mengundang_wawancara' => 'Diundang Wawancara'];
            foreach ($mapRasio as $field => $label) {
                if ((int) ($d->$field ?? 0) > 0) {
                    $namaPerGrafik['chartRasioLamaran'][$label][] = $nama;
                }
            }
        }

        // Tampilkan nama (bukan kode) untuk provinsi & kab/kota pada grafik lokasi
        foreach ($lokasiKerja as $kode => $jumlah) {
            $namaLokasi = $kode === '0' ? 'Belum Bekerja' : Wilayah::provinsiName((string) $kode);
            if ($namaLokasi !== null && $namaLokasi !== $kode) {
                unset($lokasiKerja[$kode]);
                $lokasiKerja[$namaLokasi] = ($lokasiKerja[$namaLokasi] ?? 0) + $jumlah;
            }
        }
        foreach ($lokasiKota as $kode => $jumlah) {
            $namaLokasi = $kode === '0' ? 'Belum Bekerja' : Wilayah::kabKotaName((string) $kode);
            if ($namaLokasi !== null && $namaLokasi !== $kode) {
                unset($lokasiKota[$kode]);
                $lokasiKota[$namaLokasi] = ($lokasiKota[$namaLokasi] ?? 0) + $jumlah;
            }
        }
        foreach ($namaPerGrafik['chartLokasi'] ?? [] as $kode => $names) {
            $namaLokasi = $kode === '0' ? 'Belum Bekerja' : (Wilayah::provinsiName((string) $kode) ?? $kode);
            unset($namaPerGrafik['chartLokasi'][$kode]);
            $namaPerGrafik['chartLokasi'][$namaLokasi] = array_values(array_unique(array_merge($namaPerGrafik['chartLokasi'][$namaLokasi] ?? [], $names)));
        }
        foreach ($namaPerGrafik['chartLokasiKota'] ?? [] as $kode => $names) {
            $namaLokasi = $kode === '0' ? 'Belum Bekerja' : (Wilayah::kabKotaName((string) $kode) ?? $kode);
            unset($namaPerGrafik['chartLokasiKota'][$kode]);
            $namaPerGrafik['chartLokasiKota'][$namaLokasi] = array_values(array_unique(array_merge($namaPerGrafik['chartLokasiKota'][$namaLokasi] ?? [], $names)));
        }

        // Rata-rata Aktivitas Lamaran (dari akumulasi per baris)
        $avgLamaran = [
            'Dilamar' => round($cntLamaran['f6'] ? $sumLamaran['f6'] / $cntLamaran['f6'] : 0, 1),
            'Merespon' => round($cntLamaran['f7'] ? $sumLamaran['f7'] / $cntLamaran['f7'] : 0, 1),
            'Wawancara' => round($cntLamaran['f7a'] ? $sumLamaran['f7a'] / $cntLamaran['f7a'] : 0, 1),
        ];

        // Rata-rata Kompetensi (dibagi total alumni)
        if ($totalAlumni > 0) {
            for ($i = 0; $i < 7; $i++) {
                $kompetensiDikuasai[$i] = round($kompetensiDikuasai[$i] / $totalAlumni, 2);
                $kompetensiDiperlukan[$i] = round($kompetensiDiperlukan[$i] / $totalAlumni, 2);
            }
        }

        foreach ($namaPerGrafik as $cid => $labels) {
            foreach ($labels as $l => $names) {
                $namaPerGrafik[$cid][$l] = array_values(array_unique($names));
            }
        }

        // Jumlah kartu ringkasan mengikuti label opsi yang aktif (nilai LLDIKTI '1' = bekerja, '4' = lanjut kuliah)
        $kartuBekerja = 0;
        foreach (['1', '3'] as $statusKode) {
            $kartuBekerja += isset($labelStatus[$statusKode]) ? ($statusKerja[$labelStatus[$statusKode]] ?? 0) : 0;
        }
        $kartuLanjut = isset($labelStatus['4']) ? ($statusKerja[$labelStatus['4']] ?? 0) : 0;

        return view('dashboard_kurva', compact(
            'listTahun', 'prodiLabels', 'tahunTerpilih', 'prodiTerpilih', 'totalAlumni',
            'statusKerja', 'statusPerusahaanKerja', 'SumberDana', 'pendapatan', 'PilihTingkat', 'PosisiJabatan', 'lokasiKerja', 'lokasiKota', 'tempatKuliah', 'programStudiLanjut', 'sumberBiayaLanjut', 'kompetensiDikuasai',
            'kompetensiDiperlukan', 'waktuCariKerja', 'caraCariKerja', 'avgLamaran', 'keaktifan', 'alasanTidakSesuai', 'daftarNama', 'namaPerGrafik',
            'metodeSangatBesar', 'metodeBesar', 'metodeCukupBesar', 'metodeKurang', 'metodeTidakSama',
            'kartuBekerja', 'kartuLanjut',
            'alumniBelumImpor',
            'utamaId'
        ));
    }

    public function exportExcel(Request $request)
    {

        $tahunLulus = $request->input('tahun_lulus');
        $kodeProdi = $request->input('kode_prodi');
        // 1. Ambil data alumni dengan pengurutan otomatis (Tahun -> Prodi -> Nama Abjad A-Z)
        $dataAlumni = DB::table('kuesioner_alumnis')
            ->when($tahunLulus, function ($query, $tahunLulus) {
                return $query->where('tahun_lulus', $tahunLulus);
            })
            ->when($kodeProdi, function ($query, $kodeProdi) {
                return $query->where('kode_prodi', $kodeProdi);
            })
            ->orderBy('tahun_lulus', 'asc')    // Sesuai kolom tahun kelulusan
            ->orderBy('kode_prodi', 'asc')     // Sesuai kolom kode prodi
            ->orderBy('nama', 'asc')           // <-- DIUBAH KE 'nama' (Sesuai Gambar Tabel Anda)
            ->get();

        // 2. Data mentah berurutan (class export hanya membaca alumniRaw;
        //    rekap keaktifan/caraCariKerja/metodeBelajar tidak pernah dipakai di sana)
        $dataDashboard = [
            'alumniRaw' => $dataAlumni,
        ];

        $namaFile = 'laporan_tracer';
        if ($tahunLulus) {
            $namaFile .= '_tahun_'.$tahunLulus;
        }
        if ($kodeProdi) {
            $namaFile .= '_prodi_'.$kodeProdi;
        }
        $namaFile .= '.xlsx';

        return Excel::download(new KuesionerAlumniExport($dataDashboard), $namaFile);
    }
}
