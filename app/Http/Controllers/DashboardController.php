<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\KuesionerAlumniExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlumniImport;
use App\Models\Setting;

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
            'file_excel.max' => 'Ukuran berkas maksimal adalah 10 MB.'
        ]);

        try {
            // 2. Proses pembacaan atau import berkas Excel di sini
            $import = new AlumniImport;
            Excel::import($import, $request->file('file_excel'));

            $counts = $import->getCounts();
            $message = '⚡ Data master acuan alumni berhasil diperbarui!';
            $bagian = [];
            if ($counts['insert'] > 0) {
                $bagian[] = $counts['insert'] . ' baris berhasil';
            }
            if ($counts['duplicate'] > 0) {
                $bagian[] = $counts['duplicate'] . ' dilewati (duplikat)';
            }
            if ($counts['invalid'] > 0) {
                $bagian[] = $counts['invalid'] . ' gagal (tidak lengkap)';
            }
            if ($bagian !== []) {
                $message .= ' (' . implode(', ', $bagian) . ')';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file_excel' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }
    }
        public function dashboard(Request $request)
        {
            // 1. Ambil data unik Tahun Lulus & Prodi untuk isi pilihan Dropdown Filter
            $listTahun = DB::table('kuesioner_alumnis')->distinct()->pluck('tahun_lulus')->sort()->values();
            
            $prodiLabels = [
                '54211' => 'Agroteknologi', '62201' => 'Akuntansi', '74201' => 'Ilmu Hukum',
                '61201' => 'Manajemen', '88201' => 'Pend. Bahasa Indonesia', '88203' => 'Pend. Bahasa Inggris',
                '84205' => 'Pend. Biologi', '87203' => 'Pend. Ekonomi', '54231' => 'Peternakan',
                '84202' => 'Pend. Matematika', '57401' => 'Manajemen Informatika', '54201' => 'Agribisnis'
            ];

            // 2. Tangkap filter dari Request Admin
            $tahunTerpilih = $request->input('tahun_lulus');
            $prodiTerpilih = $request->input('kode_prodi');

            // Super admin utama (akun is_super dengan id terkecil) untuk kunci menu Pengaturan
            $utamaId = DB::table('users')->where('is_super', 1)->orderBy('id')->value('id');

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
            
            // A. Status Bekerja (f8_status_saat_ini)
            $statusKerja = ['Bekerja' => 0, 'Wiraswasta' => 0, 'Lanjut Kuliah' => 0, 'Cari Kerja' => 0, 'Belum Bekerja' => 0];

            // B. Pendapatan Per Bulan (f505_pendapatan_per_bulan)
            $pendapatan = ['< 2 Juta' => 0, '2 - 5 Juta' => 0, '> 5 Juta' => 0];

            $statusPerusahaanKerja = ['Instansi Pemerintah' => 0, 'BUMN/BUMD' => 0, 'Institusi' => 0, 'Lembaga Swadaya' => 0, 'Swasta' => 0, 'Wiraswasta' => 0, 'Lainnya' => 0];

            $SumberDana = ['Biaya Sendiri' => 0, 'Beasiswa ADIK' => 0, 'Beasiswa BIDIKMISI' => 0, 'Beasiswa PPA' => 0, 'Beasiswa AFIRMASI' => 0, 'Beasiswa Swasta' => 0, 'Lainnya' => 0];

            $PosisiJabatan = [];
            $PilihTingkat = [];
            $lokasiKerja = [];
            $lokasiKota = [];
            $tempatKuliah = [];
            $programStudiLanjut = [];

            // E. Kompetensi Dikuasai vs Diperlukan (f1701_A sampai f1707_B)
            $kompetensiDikuasai = [0, 0, 0, 0, 0, 0, 0]; // f1701_A sd f1707_A
            $kompetensiDiperlukan = [0, 0, 0, 0, 0, 0, 0]; // f1701_B sd f1707_B

            // F. Penekanan Metode Pembelajaran
            $metodeSangatBesar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeBesar       = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeCukupBesar  = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeKurang      = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeTidakSama   = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];

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
                'Kantor Kemanusiaan'=> 0,
                'Kuliah'=> 0,
                'Relasi' => 0,
                'Bisnis Sendiri'=> 0,
                'Tempat Magang'=> 0,
                'Kerja Saat Kuliah'=> 0,
                'Lainnya'=> 0
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
                'Pekerjaan Lebih Menarik'=> 0,
                'Bisa Tambah Kerja'=> 0,
                'Lokasi Dekat' => 0,
                'Menjamin kebutuhan Keluarga' => 0,
                'Awal Karir' => 0,
                'Lainnya'=> 0];

            // L. Nama Alumni per Kategori (untuk kartu ringkasan yang bisa diklik)
            $daftarNama = [
                'total'   => [],
                'bekerja' => [],
                'aktif'   => [],
                'lanjut'  => [],
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
            $mapStatus = $labelMap('status', ['Bekerja', 'Wiraswasta', 'Lanjut Kuliah', 'Cari Kerja', 'Belum Bekerja']);
            $mapPendapatan = $labelMap('pendapatan', ['< 2 Juta', '2 - 5 Juta', '> 5 Juta']);
            $mapPerusahaan = $labelMap('perusahaan', ['Instansi Pemerintah', 'BUMN/BUMD', 'Institusi', 'Lembaga Swadaya', 'Swasta', 'Wiraswasta', 'Lainnya']);
            $mapDana = $labelMap('dana', ['Biaya Sendiri', 'Beasiswa ADIK', 'Beasiswa BIDIKMISI', 'Beasiswa PPA', 'Beasiswa AFIRMASI', 'Beasiswa Swasta', 'Lainnya']);
            $mapKurva = $labelMap('kurva', ['1-3 Bulan', '4-6 Bulan', '7-12 Bulan', '> 12 Bulan']);
            $mapKeaktifan = $labelMap('keaktifan', ['Aktif', 'Tidak Aktif']);
            $mapCaraItem = $labelMap('cara', ['Iklan Koran', 'Melamar Langsung', 'Bursa Kerja', 'Internet', 'Dihubungi Perusahaan', 'Kemenakertrans', 'Agen', 'CDC Kampus', 'Kantor Kemanusiaan', 'Kuliah', 'Relasi', 'Bisnis Sendiri', 'Tempat Magang', 'Kerja Saat Kuliah', 'Lainnya']);

            $namaPerGrafik = [];

            // Seluruh agregasi grafik dihitung dalam SATU pass (dulu 15x iterasi penuh).
            foreach ($dataAlumni as $d) {
                // A. Status Bekerja (baris tanpa f8 tidak dihitung sama sekali)
                if (isset($d->f8_status_saat_ini)) {
                    $status_val = strtolower($d->f8_status_saat_ini);
                    if ($status_val === '1')     $statusKerja['Bekerja']++;
                    elseif ($status_val === '3') $statusKerja['Wiraswasta']++;
                    elseif ($status_val === '4') $statusKerja['Lanjut Kuliah']++;
                    elseif ($status_val === '5') $statusKerja['Cari Kerja']++;
                    elseif ($status_val === '2') $statusKerja['Belum Bekerja']++;
                    else                         $statusKerja['Cari Kerja']++;
                }

                // B. Pendapatan Per Bulan
                $val = (int)($d->f505_pendapatan_per_bulan ?? 0);
                if ($val > 0 && $val < 2000000) $pendapatan['< 2 Juta']++;
                elseif ($val >= 2000000 && $val <= 5000000) $pendapatan['2 - 5 Juta']++;
                elseif ($val > 5000000) $pendapatan['> 5 Juta']++;

                // C. Jenis Perusahaan / Instansi
                if (isset($d->f11_jenis_instansi)) {
                    $status_val = strtolower($d->f11_jenis_instansi);
                    if ($status_val === '1')     $statusPerusahaanKerja['Instansi Pemerintah']++;
                    elseif ($status_val === '6') $statusPerusahaanKerja['BUMN/BUMD']++;
                    elseif ($status_val === '7') $statusPerusahaanKerja['Institusi']++;
                    elseif ($status_val === '2') $statusPerusahaanKerja['Lembaga Swadaya']++;
                    elseif ($status_val === '3') $statusPerusahaanKerja['Swasta']++;
                    elseif ($status_val === '4') $statusPerusahaanKerja['Wiraswasta']++;
                    elseif ($status_val === '5') $statusPerusahaanKerja['Lainnya']++;
                }

                // D. Sumber Dana Kuliah (nilai tidak dikenal masuk 'Lainnya')
                if (isset($d->f12_sumber_biaya_kuliah)) {
                    $status_val = strtolower($d->f12_sumber_biaya_kuliah);
                    if ($status_val === '1')      $SumberDana['Biaya Sendiri']++;
                    elseif ($status_val === '2')  $SumberDana['Beasiswa ADIK']++;
                    elseif ($status_val === '3')  $SumberDana['Beasiswa BIDIKMISI']++;
                    elseif ($status_val === '4')  $SumberDana['Beasiswa PPA']++;
                    elseif ($status_val === '5')  $SumberDana['Beasiswa AFIRMASI']++;
                    elseif ($status_val === '6')  $SumberDana['Beasiswa Swasta']++;
                    else                          $SumberDana['Lainnya']++;
                }

                // Posisi / Tingkat / Lokasi / Tempat Kuliah (teks bebas)
                if (!empty($d->f5c_posisi_wiraswasta) && $d->f5c_posisi_wiraswasta != '?') {
                    $PosisiJabatan[$d->f5c_posisi_wiraswasta] = ($PosisiJabatan[$d->f5c_posisi_wiraswasta] ?? 0) + 1;
                }
                if (!empty($d->f5d_tingkat_tempat_kerja) && $d->f5d_tingkat_tempat_kerja != '?') {
                    $PilihTingkat[$d->f5d_tingkat_tempat_kerja] = ($PilihTingkat[$d->f5d_tingkat_tempat_kerja] ?? 0) + 1;
                }
                if (!empty($d->f510_provinsi) && $d->f510_provinsi != '?') {
                    $lokasiKerja[$d->f510_provinsi] = ($lokasiKerja[$d->f510_provinsi] ?? 0) + 1;
                }
                if (!empty($d->f510_kab_kota) && $d->f510_kab_kota != '?') {
                    $lokasiKota[$d->f510_kab_kota] = ($lokasiKota[$d->f510_kab_kota] ?? 0) + 1;
                }
                if (!empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                    $tempatKuliah[$d->f18b_perguruan_tinggi_studi] = ($tempatKuliah[$d->f18b_perguruan_tinggi_studi] ?? 0) + 1;
                }
                if (!empty($d->f18c_program_studi) && $d->f18c_program_studi != '?') {
                    $programStudiLanjut[$d->f18c_program_studi] = ($programStudiLanjut[$d->f18c_program_studi] ?? 0) + 1;
                }

                // E. Kompetensi (kosong dianggap 3)
                for ($i = 1; $i <= 7; $i++) {
                    $kompetensiDikuasai[$i-1] += isset($d->{"f170{$i}_A"}) ? (int)$d->{"f170{$i}_A"} : 3;
                    $kompetensiDiperlukan[$i-1] += isset($d->{"f170{$i}_B"}) ? (int)$d->{"f170{$i}_B"} : 3;
                }

                // F. Metode Pembelajaran (f21-f27, nilai 1-5)
                foreach (['f21_perkuliahan' => 'Perkuliahan', 'f22_demonstrasi' => 'Demonstrasi', 'f23_riset' => 'Riset', 'f24_magang' => 'Magang', 'f25_praktikum' => 'Praktikum', 'f26_kerja_lapangan' => 'Kerja Lapangan', 'f27_diskusi' => 'Diskusi'] as $fieldMetode => $labelMetode) {
                    $mvMetode = $d->$fieldMetode ?? '';
                    if ($mvMetode == '1')      $metodeSangatBesar[$labelMetode]++;
                    elseif ($mvMetode == '2')  $metodeBesar[$labelMetode]++;
                    elseif ($mvMetode == '3')  $metodeCukupBesar[$labelMetode]++;
                    elseif ($mvMetode == '4')  $metodeKurang[$labelMetode]++;
                    elseif ($mvMetode == '5')  $metodeTidakSama[$labelMetode]++;
                }

                // G. Waktu Mencari Kerja (f302 / f303)
                $bulan = (int)($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
                if ($bulan >= 1 && $bulan <= 3) $waktuCariKerja['1-3 Bulan']++;
                elseif ($bulan >= 4 && $bulan <= 6) $waktuCariKerja['4-6 Bulan']++;
                elseif ($bulan >= 7 && $bulan <= 12) $waktuCariKerja['7-12 Bulan']++;
                elseif ($bulan > 12) $waktuCariKerja['> 12 Bulan']++;

                // H. Cara Mencari Kerja (f401-f415)
                foreach (['f401_iklan_koran_brosur' => 'Iklan Koran', 'f402_melamar_tanpa_lowongan' => 'Melamar Langsung', 'f403_bursa_pameran_online' => 'Bursa Kerja', 'f404_internet_iklan_online' => 'Internet', 'f405_dihubungi_perusahaan' => 'Dihubungi Perusahaan', 'f406_menghubungi_kemenakertrans' => 'Kemenakertrans', 'f407_agen_tenaga_kerja' => 'Agen', 'f408_karir_fakultas_universitas' => 'CDC Kampus', 'f409_kantor_kemanusiaan_alumni' => 'Kantor Kemanusiaan', 'f410_membangun_jejaring_kuliah' => 'Kuliah', 'f411_melalui_relasi' => 'Relasi', 'f412_membangun_bisnis_sendiri' => 'Bisnis Sendiri', 'f413_penempatan_kerja_magang' => 'Tempat Magang', 'f414_tempat_kerja_sama_kuliah' => 'Kerja Saat Kuliah', 'f415_lainnya' => 'Lainnya'] as $fieldCara => $labelCara) {
                    if (($d->$fieldCara ?? '') == '1') $caraCariKerja[$labelCara]++;
                }

                // I. Sum untuk rata-rata lamaran (f6/f7/f7a)
                foreach (['f6' => 'f6_perusahaan_dilamar', 'f7' => 'f7_perusahaan_merespon', 'f7a' => 'f7a_mengundang_wawancara'] as $keyLam => $fieldLam) {
                    if ($d->$fieldLam !== null) {
                        $sumLamaran[$keyLam] += (float) $d->$fieldLam;
                        $cntLamaran[$keyLam]++;
                    }
                }

                // J. Keaktifan Mencari Kerja (semua baris dihitung)
                $aktifVal2 = (string) trim($d->f10_aktif_mencari_kerja ?? '');
                if ($aktifVal2 === '3' || $aktifVal2 === '4') $keaktifan['Aktif']++;
                else $keaktifan['Tidak Aktif']++;

                // K. Alasan Pekerjaan Tidak Sesuai (f1601-f1613)
                foreach (['f1601_pertanyaan_tidak_sesuai' => 'Pekerjaan Sesuai Pendidikan', 'f1602_belum_dapat_kerja_sesuai' => 'Belum Dapat Yang Sesuai', 'f1603_prospek_karir_baik' => 'Prospek Karir', 'f1604_suka_area_kerja_tersebut' => 'Suka Bidang Ini', 'f1605_dipromosikan_posisi_lain' => 'Promosi Kurang Tepat', 'f1606_pendapatan_lebih_tinggi' => 'Gaji Lebih Tinggi', 'f1607_pekerjaan_lebih_aman' => 'Pekerjaan Lebih Aman', 'f1608_pekerjaan_lebih_menarik' => 'Pekerjaan Lebih Menarik', 'f1609_mungkinkan_kerja_tambahan' => 'Bisa Tambah Kerja', 'f1610_lokasi_dekat_rumah' => 'Lokasi Dekat', 'f1611_menjamin_kebutuhan_keluarga' => 'Menjamin kebutuhan Keluarga', 'f1612_awal_menitip_karir' => 'Awal Karir', 'f1613_lainnya' => 'Lainnya'] as $fieldAlasan => $labelAlasan) {
                    if (($d->$fieldAlasan ?? '') == '1') $alasanTidakSesuai[$labelAlasan]++;
                }

                $nama = trim((string)($d->nama ?? ''));
                if ($nama === '') continue;

                // L. Nama Alumni per Kategori (kartu ringkasan yang bisa diklik)
                $daftarNama['total'][] = $nama;
                $statusNama = strtolower((string)($d->f8_status_saat_ini ?? ''));
                if ($statusNama === '1')     $daftarNama['bekerja'][] = $nama;
                elseif ($statusNama === '4') $daftarNama['lanjut'][]  = $nama;
                if ($aktifVal2 === '3' || $aktifVal2 === '4') $daftarNama['aktif'][] = $nama;

                // chartStatusKerja
                $st = strtolower((string)($d->f8_status_saat_ini ?? ''));
                if ($st === '1')      $namaPerGrafik['chartStatusKerja'][$mapStatus['Bekerja']][] = $nama;
                elseif ($st === '3')  $namaPerGrafik['chartStatusKerja'][$mapStatus['Wiraswasta']][] = $nama;
                elseif ($st === '4')  $namaPerGrafik['chartStatusKerja'][$mapStatus['Lanjut Kuliah']][] = $nama;
                elseif ($st === '5')  $namaPerGrafik['chartStatusKerja'][$mapStatus['Cari Kerja']][] = $nama;
                elseif ($st === '2')  $namaPerGrafik['chartStatusKerja'][$mapStatus['Belum Bekerja']][] = $nama;
                else                  $namaPerGrafik['chartStatusKerja'][$mapStatus['Cari Kerja']][] = $nama;

                // chartPendapatan
                $gaji = (int)($d->f505_pendapatan_per_bulan ?? 0);
                if ($gaji > 0 && $gaji < 2000000)      $namaPerGrafik['chartPendapatan'][$mapPendapatan['< 2 Juta']][] = $nama;
                elseif ($gaji >= 2000000 && $gaji <= 5000000) $namaPerGrafik['chartPendapatan'][$mapPendapatan['2 - 5 Juta']][] = $nama;
                elseif ($gaji > 5000000)               $namaPerGrafik['chartPendapatan'][$mapPendapatan['> 5 Juta']][] = $nama;

                // chartPerusahaanKerja
                $f11 = strtolower((string)($d->f11_jenis_instansi ?? ''));
                $map11 = ['1'=>'Instansi Pemerintah', '6'=>'BUMN/BUMD', '7'=>'Institusi', '2'=>'Lembaga Swadaya', '3'=>'Swasta', '4'=>'Wiraswasta', '5'=>'Lainnya'];
                if (isset($map11[$f11])) $namaPerGrafik['chartPerusahaanKerja'][$mapPerusahaan[$map11[$f11]]][] = $nama;

                // chartSumberDana
                $f12 = strtolower((string)($d->f12_sumber_biaya_kuliah ?? ''));
                $map12 = ['1'=>'Biaya Sendiri', '2'=>'Beasiswa ADIK', '3'=>'Beasiswa BIDIKMISI', '4'=>'Beasiswa PPA', '5'=>'Beasiswa AFIRMASI', '6'=>'Beasiswa Swasta', '7'=>'Lainnya'];
                if ($f12 !== '') $namaPerGrafik['chartSumberDana'][$mapDana[$map12[$f12] ?? 'Lainnya']][] = $nama;

                // chartPosisiJabatan / chartPilihTingkat / chartLokasi / chartLokasiKota
                if (!empty($d->f5c_posisi_wiraswasta) && $d->f5c_posisi_wiraswasta != '?') $namaPerGrafik['chartPosisiJabatan'][$d->f5c_posisi_wiraswasta][] = $nama;
                if (!empty($d->f5d_tingkat_tempat_kerja) && $d->f5d_tingkat_tempat_kerja != '?') $namaPerGrafik['chartPilihTingkat'][$d->f5d_tingkat_tempat_kerja][] = $nama;
                if (!empty($d->f510_provinsi) && $d->f510_provinsi != '?') $namaPerGrafik['chartLokasi'][$d->f510_provinsi][] = $nama;
                if (!empty($d->f510_kab_kota) && $d->f510_kab_kota != '?') $namaPerGrafik['chartLokasiKota'][$d->f510_kab_kota][] = $nama;

                // chartTempatKuliah & chartPerguruanTinggiStudi (f18b) / chartProgramStudiStudi (f18c)
                if (!empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                    $namaPerGrafik['chartTempatKuliah'][$d->f18b_perguruan_tinggi_studi][] = $nama;
                    $namaPerGrafik['chartPerguruanTinggiStudi'][$d->f18b_perguruan_tinggi_studi][] = $nama;
                }
                if (!empty($d->f18c_program_studi) && $d->f18c_program_studi != '?') $namaPerGrafik['chartProgramStudiStudi'][$d->f18c_program_studi][] = $nama;

                // chartWaktuCariKerja
                $bulan = (int)($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
                if ($bulan >= 1 && $bulan <= 3)      $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['1-3 Bulan']][] = $nama;
                elseif ($bulan >= 4 && $bulan <= 6)  $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['4-6 Bulan']][] = $nama;
                elseif ($bulan >= 7 && $bulan <= 12) $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['7-12 Bulan']][] = $nama;
                elseif ($bulan > 12)                 $namaPerGrafik['chartWaktuCariKerja'][$mapKurva['> 12 Bulan']][] = $nama;

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
                    if ((string)($d->$field ?? '') === '1') $namaPerGrafik['chartCaraCariKerja'][$mapCaraItem[$label] ?? $label][] = $nama;
                }

                // chartKeaktifan
                $aktifVal2 = (string) trim($d->f10_aktif_mencari_kerja ?? '');
                $namaPerGrafik['chartKeaktifan'][($aktifVal2 === '3' || $aktifVal2 === '4') ? $mapKeaktifan['Aktif'] : $mapKeaktifan['Tidak Aktif']][] = $nama;

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
                    if ((string)($d->$field ?? '') === '1') $namaPerGrafik['chartAlasanTidakSesuai'][$label][] = $nama;
                }

                // chartMetodeBelajar
                $mapMetode = [
                    'Perkuliahan' => 'f21_perkuliahan', 'Demonstrasi' => 'f22_demonstrasi', 'Riset' => 'f23_riset',
                    'Magang' => 'f24_magang', 'Praktikum' => 'f25_praktikum', 'Kerja Lapangan' => 'f26_kerja_lapangan',
                    'Diskusi' => 'f27_diskusi',
                ];
                foreach ($mapMetode as $label => $field) {
                    $mv = (string)($d->$field ?? '');
                    if ($mv !== '' && $mv !== '0') $namaPerGrafik['chartMetodeBelajar'][$label][] = $nama;
                }

                // chartKompetensi
                $mapKompetensi = [
                    'Etika' => ['f1701_A','f1701_B'], 'Keahlian Inti' => ['f1702_A','f1702_B'],
                    'Bahasa Inggris' => ['f1703_A','f1703_B'], 'TIK' => ['f1704_A','f1704_B'],
                    'Komunikasi' => ['f1705_A','f1705_B'], 'Kerjasama Tim' => ['f1706_A','f1706_B'],
                    'Pengembangan Diri' => ['f1707_A','f1707_B'],
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
                    if ((int)($d->$field ?? 0) > 0) $namaPerGrafik['chartRasioLamaran'][$label][] = $nama;
                }
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

            return view('dashboard_kurva', compact(
                'listTahun', 'prodiLabels', 'tahunTerpilih', 'prodiTerpilih', 'totalAlumni',
                'statusKerja', 'statusPerusahaanKerja', 'SumberDana', 'pendapatan', 'PilihTingkat', 'PosisiJabatan', 'lokasiKerja', 'lokasiKota', 'tempatKuliah', 'programStudiLanjut', 'kompetensiDikuasai', 
                'kompetensiDiperlukan', 'waktuCariKerja', 'caraCariKerja', 'avgLamaran', 'keaktifan', 'alasanTidakSesuai', 'daftarNama', 'namaPerGrafik',
                'metodeSangatBesar', 'metodeBesar', 'metodeCukupBesar', 'metodeKurang', 'metodeTidakSama',
                'utamaId'
            ));
    }

    public function exportExcel(Request $request) {
        
        $tahunLulus = $request->input('tahun_lulus');
        $kodeProdi  = $request->input('kode_prodi');
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
        if($tahunLulus) $namaFile .= '_tahun_' . $tahunLulus;
        if($kodeProdi)  $namaFile .= '_prodi_' . $kodeProdi;
        $namaFile .= '.xlsx';

        return Excel::download(new KuesionerAlumniExport($dataDashboard), $namaFile);
    }
}
