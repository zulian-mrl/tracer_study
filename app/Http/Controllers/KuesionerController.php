<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;
use App\Exports\KuesionerAlumniExport;
use Maatwebsite\Excel\Facades\Excel;

class KuesionerController extends Controller
{
        public function index()
        {
            return view('kuesioner');
        }
        
        public function store(Request $request)
        {
            // 1. Validasi
            $request->validate([
                'no_mahasiswa' => 'required',
                'nama' => 'required',
                'f8_status_saat_ini' => 'required',
                'f301' => 'required',
            ]);

            // 2. String checklist f16
            $alasan_terpilih = [];
            for ($i = 1; $i <= 12; $i++) {
                $key = 'f16' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if ($request->has($key)) {
                    $alasan_terpilih[] = $key;
                }
            }
            $f16_alasan_string = !empty($alasan_terpilih) ? implode(',', $alasan_terpilih) : null;

            // 3. Direct Insert ke phpMyAdmin
            DB::table('kuesioner_alumnis')->insert([
                'user_id' => 1, 
                'no_mahasiswa' => $request->no_mahasiswa,
                'kode_PT' => $request->kode_PT ?? '072004',
                'tahun_lulus' => $request->tahun_lulus,
                'kode_prodi' => $request->kode_prodi,
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'f8_status_saat_ini' => match ($request->f8_status_saat_ini) {
                        '1' => 'Bekerja (Full-time/Part-time)',
                        '2' => 'Wiraswasta',
                        '3' => 'Melanjutkan Pendidikan',
                        '4' => 'Mencari Kerja / Tidak Bekerja',
                        '5' => 'Belum memungkinkan bekerja',
                        default => $request->f8_status_saat_ini,
                    },
                'f504_mendapat_pekerjaan_6_bulan' => match ($request->f504_mendapat_pekerjaan_6_bulan) {
                        '1' => 'Ya',
                        '2' => 'Tidak',
                        default => $request->f504_mendapat_pekerjaan_6_bulan,
                    },
                'f502_bulan_dapat_kerja' => $request->f502_bulan_dapat_kerja_ya,
                'f505_pendapatan_per_bulan' => $request->f505_pendapatan_per_bulan,
                'f506_bulan_dapat_kerja_setelahnya' => $request->f502_bulan_dapat_kerja_tidak,
                'f510_provinsi' => $request->f510_provinsi,
                'f510_kab_kota' => $request->f510_kab_kota,
                'f11_jenis_instansi' => match ($request->f11_jenis_instansi) {
                        '1' => 'Instansi pemerintah',
                        '2'=> 'BUMN/BUMD',
                        '3'=> 'Institusi/Organisasi Multilateral',
                        '4'=> 'Organisasi non-profit/Lembaga Swadaya Masyarakat',
                        '5'=> 'Perusahaan swasta',
                        '6'=> 'Wiraswasta/Perusahaan sendiri',
                        default => $request->f11_jenis_instansi,
                    },
                'f11_jenis_instansi_lainnya' => $request->f11_02,
                'f5b_nama_perusahaan' => $request->f5b_nama_perusahaan,
                'f5c_posisi_wiraswasta' => $request->f5c_posisi,
                'f5d_tingkat_tempat_kerja' => $request->f5d_tingkat,
                'f12_sumber_biaya_kuliah' => match ($request->f12_01) {
                        '1'=> 'Biaya sendiri',
                        '2'=> 'Beasiswa ADIK',
                        '3'=> 'Beasiswa BIDIKSMISI',
                        '4'=> 'Beasiswa PPA',
                        '5'=> 'Beasiswa AFIRMASI',
                        '6'=> 'Beasiswa perusahaan/Swasta',
                        default => $request->f12_01,
                    },
                'f18a_sumber_biaya_studi'      => $request->f18a_sumber_biaya_studi,
                'f18b_perguruan_tinggi_studi'  => $request->f18b_perguruan_tinggi_studi,
                'f18c_program_studi'           => $request->f18c_program_studi,
                'f18d_tanggal_masuk'           => $request->f18d_tanggal_masuk,
                'f12_sumber_biaya_kuliah_lainnya' => $request->f12_02,
                'f14_erat_hubungan_studi' => match ($request->f14) {
                        '1'=> 'Sangat Erat',
                        '2'=> 'Erat',
                        '3'=> 'Cukup Erat',
                        '4'=> 'Kurang Erat',
                        '5'=> 'Tidak Sama Sekali',
                        default => $request->f14,
                },
                'f15_tingkat_paling_tepat' => match ($request->f15) {
                        '1'=> 'Setingkat Lebih Tinggi',
                        '2'=> 'Tngkat yang Sama',
                        '3'=> 'Setingkat Lebih Rendah',
                        '4'=> 'Tidak perlu Pendidikan Tinggi',
                        default => $request->f15,
                },
                'f1701_A' => match($request->f1761_f1762_A ?? $request->comp_a_f1761_f1762) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1702_A' => match($request->f1763_f1764_A ?? $request->comp_a_f1763_f1764) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1703_A' => match($request->f1765_f1766_A ?? $request->comp_a_f1765_f1766) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1704_A' => match($request->f1767_f1768_A ?? $request->comp_a_f1767_f1768) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', '5' => 
                    'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1705_A' => match($request->f1769_f1770_A ?? $request->comp_a_f1769_f1770) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1706_A' => match($request->f1771_f1772_A ?? $request->comp_a_f1771_f1772) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1707_A' => match($request->f1773_f1774_A ?? $request->comp_a_f1773_f1774) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },

                'f1701_B' => match($request->f1761_f1762_B ?? $request->comp_b_f1761_f1762) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1702_B' => match($request->f1763_f1764_B ?? $request->comp_b_f1763_f1764) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah' 
                },
                'f1703_B' => match($request->f1765_f1766_B ?? $request->comp_b_f1765_f1766) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1704_B' => match($request->f1767_f1768_B ?? $request->comp_b_f1767_f1768) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1705_B' => match($request->f1769_f1770_B ?? $request->comp_b_f1769_f1770) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1706_B' => match($request->f1771_f1772_B ?? $request->comp_b_f1771_f1772) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },
                'f1707_B' => match($request->f1773_f1774_B ?? $request->comp_b_f1773_f1774) {
                    '1' => 'Sangat Tinggi', 
                    '2' => 'Tinggi', 
                    '3' => 'Cukup Tinggi', 
                    '4' => 'Rendah', 
                    '5' => 'Sangat Rendah', 
                    default => 'Sangat Rendah'
                },

                'created_at' => now(),
                'updated_at' => now(),

                'f21_perkuliahan' => match ($request->f21 ?? $request->f21_perkuliahan) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f22_demonstrasi' => match ($request->f22 ?? $request->f22_demonstrasi) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f23_riset' => match ($request->f22 ?? $request->f23_riset) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f24_magang' => match ($request->f24 ?? $request->f24_magang) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f25_praktikum' => match ($request->f25 ?? $request->f25_praktikum) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f26_kerja_lapangan' => match ($request->f26 ?? $request->f26_kerja_lapangan) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f27_diskusi' => match ($request->f27 ?? $request->f27_diskusi) {
                    '1'=> 'Sangat Besar',
                    '2'=> 'Besar',
                    '3'=> 'Cukup besar',
                    '4'=> 'Kurang',
                    '5'=> 'Tidak Sama Sekali',
                    default => 'cukup'
                },
                'f301_kapan_mencari_pekerjaan' => match ($request->f301) {
                    '1'=> 'sebelum',
                    '2'=> 'sesudah',
                    '3'=> 'tidak mencari kerja',
                    default => 'tidak mencari kerja'
                },
                'f302_bulan_sebelum_lulus' => $request->f302,
                'f303_bulan_setelah_lulus' => $request->f303 ?? 0,
                'f401_iklan_koran_brosur'           => $request->has('f401') ? 'Ya' : 'Tidak',
                'f402_melamar_tanpa_lowongan'       => $request->has('f402') ? 'Ya' : 'Tidak',
                'f403_bursa_pameran_online'         => $request->has('f403') ? 'Ya' : 'Tidak',
                'f404_internet_iklan_online'        => $request->has('f404') ? 'Ya' : 'Tidak',
                'f405_dihubungi_perusahaan'         => $request->has('f405') ? 'Ya' : 'Tidak',
                'f406_menghubungi_kemenakertrans'   => $request->has('f406') ? 'Ya' : 'Tidak',
                'f407_agen_tenaga_kerja'            => $request->has('f407') ? 'Ya' : 'Tidak',
                'f408_karir_fakultas_universitas'   => $request->has('f408') ? 'Ya' : 'Tidak',
                'f409_kantor_kemanusiaan_alumni'    => $request->has('f409') ? 'Ya' : 'Tidak',
                'f410_membangun_jejaring_kuliah'    => $request->has('f410') ? 'Ya' : 'Tidak',
                'f411_melalui_relasi'               => $request->has('f411') ? 'Ya' : 'Tidak',
                'f412_membangun_bisnis_sendiri'     => $request->has('f412') ? 'Ya' : 'Tidak',
                'f413_penempatan_kerja_magang'      => $request->has('f413') ? 'Ya' : 'Tidak',
                'f414_tempat_kerja_sama_kuliah'     => $request->has('f414') ? 'Ya' : 'Tidak',
                'f415_lainnya'                      => $request->f415_lainnya ?? null,

                'f6_perusahaan_dilamar' => $request->f6_jumlah_lamaran,
                'f7_perusahaan_merespon' => $request->f7_jumlah_respons,
                'f7a_mengundang_wawancara' => $request->f17a_jumlah_wawancara,
                'f10_aktif_mencari_kerja' => match ($request->f10_aktif) {
                    '1' => 'Tidak',
                    '2' => 'Tidak, tapi saya sedang menunggu hasil lamaran kerja',
                    '3' => 'Ya, saya akan mulai bekerja dalam 2 minggu ke depan',
                    '4' => 'Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan',
                    default => $request->f10_aktif ?? null,
                },
                'f10_lainnya' => $request->f10_lainnya ?? null,

                'f1601_pertanyaan_tidak_sesuai'     => $request->has('f1601') ? 'Ya' : 'Tidak',
                'f1602_belum_dapat_kerja_sesuai'    => $request->has('f1602') ? 'Ya' : 'Tidak',
                'f1603_prospek_karir_baik'          => $request->has('f1603') ? 'Ya' : 'Tidak',
                'f1604_suka_area_kerja_tersebut'    => $request->has('f1604') ? 'Ya' : 'Tidak',
                'f1605_dipromosikan_posisi_lain'    => $request->has('f1605') ? 'Ya' : 'Tidak',
                'f1606_pendapatan_lebih_tinggi'     => $request->has('f1606') ? 'Ya' : 'Tidak',
                'f1607_pekerjaan_lebih_aman'        => $request->has('f1607') ? 'Ya' : 'Tidak',
                'f1608_pekerjaan_lebih_menarik'     => $request->has('f1608') ? 'Ya' : 'Tidak',
                'f1609_mungkinkan_kerja_tambahan'   => $request->has('f1609') ? 'Ya' : 'Tidak',
                'f1610_lokasi_dekat_rumah'          => $request->has('f1610') ? 'Ya' : 'Tidak',
                'f1611_menjamin_kebutuhan_keluarga' => $request->has('f1611') ? 'Ya' : 'Tidak',
                'f1612_awal_menitip_karir'          => $request->has('f1612') ? 'Ya' : 'Tidak',
                'f1613_lainnya'                     => $request->input('f1613'),
            ]);

        return redirect()->back()->with('success', 'Kuesioner Anda berhasil dikirim! Terima kasih atas partisipasinya.');
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
            $tahunTerpilih = $request->get('tahun_lulus');
            $prodiTerpilih = $request->get('kode_prodi');

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
            $statusKerja = ['Bekerja' => 0, 'Wiraswasta' => 0, 'Melanjutkan Studi' => 0, 'Mencari Kerja' => 0];
            foreach ($dataAlumni as $d) {
                if (isset($d->f8_status_saat_ini)) {
                    if (str_contains(strtolower($d->f8_status_saat_ini), 'kerja')) $statusKerja['Bekerja']++;
                    elseif (str_contains(strtolower($d->f8_status_saat_ini), 'wira')) $statusKerja['Wiraswasta']++;
                    elseif (str_contains(strtolower($d->f8_status_saat_ini), 'studi') || str_contains(strtolower($d->f8_status_saat_ini), 'kuliah')) $statusKerja['Melanjutkan Studi']++;
                    else $statusKerja['Mencari Kerja']++;
                }
            }

            // B. Pendapatan Per Bulan (f505_pendapatan_per_bulan)
            $pendapatan = ['< 2 Juta' => 0, '2 - 5 Juta' => 0, '> 5 Juta' => 0];
            foreach ($dataAlumni as $d) {
                $val = (int)($d->f505_pendapatan_per_bulan ?? 0);
                if ($val > 0 && $val < 2000000) $pendapatan['< 2 Juta']++;
                elseif ($val >= 2000000 && $val <= 5000000) $pendapatan['2 - 5 Juta']++;
                elseif ($val > 5000000) $pendapatan['> 5 Juta']++;
            }

            // C. Lokasi Kerja (f510_provinsi)
            $lokasiKerja = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f510_provinsi) && $d->f510_provinsi != '?') {
                    $lokasiKerja[$d->f510_provinsi] = ($lokasiKerja[$d->f510_provinsi] ?? 0) + 1;
                }
            }

            // D. Tempat Kuliah Lanjut (f18b_perguruan_tinggi_studi)
            $tempatKuliah = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                    $tempatKuliah[$d->f18b_perguruan_tinggi_studi] = ($tempatKuliah[$d->f18b_perguruan_tinggi_studi] ?? 0) + 1;
                }
            }

            // E. Kompetensi Dikuasai vs Diperlukan (f1701_A sampai f1707_B)
            // Kita hitung rata-rata skor (Sangat Tinggi = 5, Tinggi = 4, Cukup = 3, Rendah = 2, Sangat Rendah = 1)
            $skorMapping = ['sangat tinggi' => 5, 'tinggi' => 4, 'cukup tinggi' => 3, 'cukup' => 3, 'rendah' => 2, 'sangat rendah' => 1];
            $kompetensiDikuasai = [0, 0, 0, 0, 0, 0, 0]; // f1701_A sd f1707_A
            $kompetensiDiperlukan = [0, 0, 0, 0, 0, 0, 0]; // f1701_B sd f1707_B
            
            if ($totalAlumni > 0) {
                foreach ($dataAlumni as $d) {
                    for ($i = 1; $i <= 7; $i++) {
                        $colA = "f170{$i}_A"; $colB = "f170{$i}_B";
                        $kompetensiDikuasai[$i-1] += $skorMapping[strtolower($d->$colA ?? '')] ?? 3;
                        $kompetensiDiperlukan[$i-1] += $skorMapping[strtolower($d->$colB ?? '')] ?? 3;
                    }
                }
                // Jadikan rata-rata
                for ($i = 0; $i < 7; $i++) {
                    $kompetensiDikuasai[$i] = round($kompetensiDikuasai[$i] / $totalAlumni, 2);
                    $kompetensiDiperlukan[$i] = round($kompetensiDiperlukan[$i] / $totalAlumni, 2);
                }
            }

            // F. Penekanan Metode Pembelajaran
            $metodeBelajar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            foreach ($dataAlumni as $d) {
                if (isset($d->f21_perkuliahan) && str_contains(strtolower($d->f21_perkuliahan), 'besar')) $metodeBelajar['Perkuliahan']++;
                if (isset($d->f22_demonstrasi) && str_contains(strtolower($d->f22_demonstrasi), 'besar')) $metodeBelajar['Demonstrasi']++;
                if (isset($d->f23_riset) && str_contains(strtolower($d->f23_riset), 'besar')) $metodeBelajar['Riset']++;
                if (isset($d->f24_magang) && str_contains(strtolower($d->f24_magang), 'besar')) $metodeBelajar['Magang']++;
                if (isset($d->f25_praktikum) && str_contains(strtolower($d->f25_praktikum), 'besar')) $metodeBelajar['Praktikum']++;
                if (isset($d->f26_kerja_lapangan) && str_contains(strtolower($d->f26_kerja_lapangan), 'besar')) $metodeBelajar['Kerja Lapangan']++;
                if (isset($d->f27_diskusi) && str_contains(strtolower($d->f27_diskusi), 'besar')) $metodeBelajar['Diskusi']++;
            }

            // G. Waktu Mencari Kerja (f302 / f303 dlm hitungan 1-12 bulan)
            $waktuCariKerja = ['1-3 Bulan' => 0, '4-6 Bulan' => 0, '7-12 Bulan' => 0, '> 12 Bulan' => 0];
            foreach ($dataAlumni as $d) {
                $bulan = (int)($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
                if ($bulan >= 1 && $bulan <= 3) $waktuCariKerja['1-3 Bulan']++;
                elseif ($bulan >= 4 && $bulan <= 6) $waktuCariKerja['4-6 Bulan']++;
                elseif ($bulan >= 7 && $bulan <= 12) $waktuCariKerja['7-12 Bulan']++;
                elseif ($bulan > 12) $waktuCariKerja['> 12 Bulan']++;
            }

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
            foreach ($dataAlumni as $d) {
                if (($d->f401_iklan_koran_brosur ?? '') == 'Ya') $caraCariKerja['Iklan Koran']++;
                if (($d->f402_melamar_tanpa_lowongan ?? '') == 'Ya') $caraCariKerja['Melamar Langsung']++;
                if (($d->f403_bursa_pameran_online ?? '') == 'Ya') $caraCariKerja['Bursa Kerja']++;
                if (($d->f404_internet_iklan_online ?? '') == 'Ya') $caraCariKerja['Internet']++;
                if (($d->f405_dihubungi_perusahaan ?? '') == 'Ya') $caraCariKerja['Dihubungi Perusahaan']++;
                if (($d->f406_menghubungi_kemenakertrans ?? '') == 'Ya') $caraCariKerja['Kemenakertrans']++;
                if (($d->f407_agen_tenaga_kerja ?? '') == 'Ya') $caraCariKerja['Agen']++;
                if (($d->f408_karir_fakultas_universitas ?? '') == 'Ya') $caraCariKerja['CDC Kampus']++;
                if (($d->f409_kantor_kemanusiaan_alumni ?? '') == 'Ya') $caraCariKerja['Kantor Kemanusiaan']++;
                if (($d->f410_membangun_jejaring_kuliah ?? '') == 'Ya') $caraCariKerja['Kuliah']++;
                if (($d->f411_melalui_relasi ?? '') == 'Ya') $caraCariKerja['Relasi']++;
                if (($d->f412_membangun_bisnis_sendiri ?? '') == 'Ya') $caraCariKerja['Bisnis Sendiri']++;
                if (($d->f413_penempatan_kerja_magang ?? '') == 'Ya') $caraCariKerja['Tempat Magang']++;
                if (($d->f414_tempat_kerja_sama_kuliah ?? '') == 'Ya') $caraCariKerja['Kerja Saat Kuliah']++;
                if (($d->f415_lainnya ?? '') == 'Ya') $caraCariKerja['Lainnya']++;
            }

            // I. Aktivitas Lamaran (Dilamar, Merespon, Wawancara)
            $avgLamaran = [
                'Dilamar' => round($dataAlumni->avg('f6_perusahaan_dilamar') ?? 0, 1),
                'Merespon' => round($dataAlumni->avg('f7_perusahaan_merespon') ?? 0, 1),
                'Wawancara' => round($dataAlumni->avg('f7a_mengundang_wawancara') ?? 0, 1)
            ];

            // J. Keaktifan Mencari Kerja (f10_aktif_mencari_kerja)
            $keaktifan = ['Aktif' => 0, 'Tidak Aktif' => 0];
            foreach ($dataAlumni as $d) {
                $jawaban = trim(strtolower($d->f10_aktif_mencari_kerja ?? ''));
                if ($jawaban !== '' && str_contains($jawaban, 'ya')) { 
                    $keaktifan['Aktif']++;
                } else {
                    $keaktifan['Tidak Aktif']++;
                }
            }

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
                'Bisa tambah Kerja'=> 0,
                'Lokasi Dekat' => 0,
                'Menjamin kebutuan Keluarga' => 0,
                'Awal Karir' => 0,
                'lainnya'=> 0];
            foreach ($dataAlumni as $d) {
                if (($d->f1601_pertanyaan_tidak_sesuai ?? '') == 'Ya') $alasanTidakSesuai['Pekerjaan Sesuai Pendidikan']++;
                if (($d->f1602_belum_dapat_kerja_sesuai ?? '') == 'Ya') $alasanTidakSesuai['Belum Dapat Yang Sesuai']++;
                if (($d->f1603_prospek_karir_baik ?? '') == 'Ya') $alasanTidakSesuai['Prospek Karir']++;
                if (($d->f1604_suka_area_kerja_tersebut ?? '') == 'Ya') $alasanTidakSesuai['Suka Bidang Ini']++;
                if (($d->f1605_dipromosikan_posisi_lain ?? '') == 'Ya') $alasanTidakSesuai['Promosi Kurang Tepat']++;
                if (($d->f1606_pendapatan_lebih_tinggi ?? '') == 'Ya') $alasanTidakSesuai['Gaji Lebih Tinggi']++;
                if (($d->f1607_pekerjaan_lebih_aman ?? '') == 'Ya') $alasanTidakSesuai['Pekerjaan Lebih Aman']++;
                if (($d->f1608_pekerjaan_lebih_menarik ?? '') == 'Ya') $alasanTidakSesuai['Pekerjaan Lebih Menarik']++;
                if (($d->f1609_mungkinkan_kerja_tambahan ?? '') == 'Ya') $alasanTidakSesuai['Bisa tambah Kerja']++;
                if (($d->f1610_lokasi_dekat_rumah ?? '') == 'Ya') $alasanTidakSesuai['Lokasi Dekat']++;
                if (($d->f1611_menjamin_kebutuhan_keluarga ?? '') == 'Ya') $alasanTidakSesuai['Menjamin kebutuan Keluarga']++;
                if (($d->f1612_awal_menitip_karir ?? '') == 'Ya') $alasanTidakSesuai['Awal Karir']++;
                if (($d->f1613_lainnya ?? '') == 'Ya') $alasanTidakSesuai['Lainnya']++;
            }

            return view('dashboard_kurva', compact(
                'listTahun', 'prodiLabels', 'tahunTerpilih', 'prodiTerpilih', 'totalAlumni',
                'statusKerja', 'pendapatan', 'lokasiKerja', 'tempatKuliah', 'kompetensiDikuasai', 
                'kompetensiDiperlukan', 'metodeBelajar', 'waktuCariKerja', 'caraCariKerja', 'avgLamaran', 'keaktifan', 'alasanTidakSesuai'
        ));
    }
    
    public function exportExcel(Request $request) {
        
        $tahunLulus = $request->get('tahun_lulus');
        $kodeProdi  = $request->get('kode_prodi');
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
            ->orderBy('Nama', 'asc')           // <-- DIUBAH KE 'Nama' (Sesuai Gambar Tabel Anda)
            ->get();

        // 2. Logika Hitung Keaktifan Mencari Kerja
        $keaktifan = ['Aktif' => 0, 'Tidak Aktif' => 0];
        foreach ($dataAlumni as $d) {
            $jawaban = trim(strtolower($d->f10_aktif_mencari_kerja ?? ''));
            if ($jawaban !== '' && str_contains($jawaban, 'ya')) { 
                $keaktifan['Aktif']++;
            } else {
                $keaktifan['Tidak Aktif']++;
            }
        }

        // 3. Logika Hitung Saluran / Cara Cari Kerja (Sesuai grafik donut di gambar 1)
        $caraCariKerja = [
            'Iklan Koran' => 0, 'Melamar Langsung' => 0, 'Bursa Kerja' => 0, 'Internet' => 0, 
            'Dihubungi Perusahaan' => 0, 'Kemenakertrans' => 0, 'Agen' => 0, 'CDC Kampus' => 0, 
            'Kantor Kemanusiaan' => 0, 'Kuliah' => 0, 'Relasi' => 0, 'Bisnis Sendiri' => 0, 
            'Tempat Magang' => 0, 'Kerja Saat Kuliah' => 0, 'Lainnya' => 0
        ];
        foreach ($dataAlumni as $d) {
            if (($d->f401_iklan_koran_brosur ?? '') == 'Ya' || ($d->f401 ?? '') == 1) $caraCariKerja['Iklan Koran']++;
            if (($d->f402_melamar_tanpa_lowongan ?? '') == 'Ya' || ($d->f402 ?? '') == 1) $caraCariKerja['Melamar Langsung']++;
            if (($d->f403_bursa_pameran_online ?? '') == 'Ya' || ($d->f403 ?? '') == 1) $caraCariKerja['Bursa Kerja']++;
            if (($d->f404_internet_iklan_online ?? '') == 'Ya' || ($d->f404 ?? '') == 1) $caraCariKerja['Internet']++;
            if (($d->f405_dihubungi_perusahaan ?? '') == 'Ya' || ($d->f405 ?? '') == 1) $caraCariKerja['Dihubungi Perusahaan']++;
            if (($d->f406_menghubungi_kemenakertrans ?? '') == 'Ya' || ($d->f406 ?? '') == 1) $caraCariKerja['Kemenakertrans']++;
            if (($d->f407_agen_tenaga_kerja ?? '') == 'Ya' || ($d->f407 ?? '') == 1) $caraCariKerja['Agen']++;
            if (($d->f408_karir_fakultas_universitas ?? '') == 'Ya' || ($d->f408 ?? '') == 1) $caraCariKerja['CDC Kampus']++;
            if (($d->f409_kantor_kemanusiaan_alumni ?? '') == 'Ya' || ($d->f409 ?? '') == 1) $caraCariKerja['Kantor Kemanusiaan']++;
            if (($d->f410_membangun_jejaring_kuliah ?? '') == 'Ya' || ($d->f410 ?? '') == 1) $caraCariKerja['Kuliah']++;
            if (($d->f411_melalui_relasi ?? '') == 'Ya' || ($d->f411 ?? '') == 1) $caraCariKerja['Relasi']++;
            if (($d->f412_membangun_bisnis_sendiri ?? '') == 'Ya' || ($d->f412 ?? '') == 1) $caraCariKerja['Bisnis Sendiri']++;
            if (($d->f413_penempatan_kerja_magang ?? '') == 'Ya' || ($d->f413 ?? '') == 1) $caraCariKerja['Tempat Magang']++;
            if (($d->f414_tempat_kerja_sama_kuliah ?? '') == 'Ya' || ($d->f414 ?? '') == 1) $caraCariKerja['Kerja Saat Kuliah']++;
            if (($d->f415_lainnya ?? '') == 'Ya' || ($d->f415 ?? '') == 1) $caraCariKerja['Lainnya']++;
        }

        // 4. Logika Hitung Penekanan Metode Belajar
        $metodeBelajar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        foreach ($dataAlumni as $d) {
            if (isset($d->f21) && str_contains(strtolower($d->f21), 'besar')) $metodeBelajar['Perkuliahan']++;
            if (isset($d->f22) && str_contains(strtolower($d->f22), 'besar')) $metodeBelajar['Demonstrasi']++;
            if (isset($d->f23) && str_contains(strtolower($d->f23), 'besar')) $metodeBelajar['Riset']++;
            if (isset($d->f24) && str_contains(strtolower($d->f24), 'besar')) $metodeBelajar['Magang']++;
            if (isset($d->f25) && str_contains(strtolower($d->f25), 'besar')) $metodeBelajar['Praktikum']++;
            if (isset($d->f26) && str_contains(strtolower($d->f26), 'besar')) $metodeBelajar['Kerja Lapangan']++;
            if (isset($d->f27) && str_contains(strtolower($d->f27), 'besar')) $metodeBelajar['Diskusi']++;
        }

        // 5. Satukan data rekap + data mentah berurutan
        $dataDashboard = [
            'keaktifan'     => $keaktifan,
            'caraCariKerja' => $caraCariKerja,
            'metodeBelajar' => $metodeBelajar,
            'alumniRaw'     => $dataAlumni 
        ];

        $namaFile = 'laporan_tracer';
        if($tahunLulus) $namaFile .= '_tahun_' . $tahunLulus;
        if($kodeProdi)  $namaFile .= '_prodi_' . $kodeProdi;
        $namaFile .= '.xlsx';

        return Excel::download(new KuesionerAlumniExport($dataDashboard), 'laporan_tracer_study.xlsx');
    }
}