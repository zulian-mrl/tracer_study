<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        DB::table('kuesioner_alumnis')->truncate(); // Bersihkan data lama sebelum simulasi

        DB::table('kuesioner_alumnis')->insert([
            // ==========================================
            // ALUMNI 1: BUDI (STATUS BEKERJA)
            // ==========================================
            [
                'user_id' => 1,
                'no_mahasiswa' => '2210015111001',
                'kode_PT' => '072004',
                'tahun_lulus' => '2026',
                'kode_prodi' => '55201',
                'nama' => 'Budi Setiawan',
                'no_hp' => '081234567890',
                'email' => 'alumni1@mahasiswa.com',
                'nik' => '1234567890123456',
                'npwp' => '12.345.678.9-012.000',
                'f8_status_saat_ini' => 'Bekerja (full time/part time)',
                'f504_mendapat_pekerjaan_6_bulan' => 'Ya',
                'f502_bulan_dapat_kerja' => 3,
                'f505_pendapatan_per_bulan' => 4500000,
                'f506_bulan_dapat_kerja_setelahnya' => null,
                'f510_provinsi' => 'Sumatera Barat',
                'f510_kab_kota' => 'Kota Solok',
                'f11_jenis_instansi' => 'Perusahaan swasta',
                'f11_jenis_instansi_lainnya' => null,
                'f5b_nama_perusahaan' => 'PT. Teknologi Nusantara',
                'f5c_posisi_wiraswasta' => null,
                'f5d_tingkat_tempat_kerja' => 'Nasional',
                'f18a_sumber_biaya_studi' => null,
                'f18b_perguruan_tinggi_studi' => null,
                'f18c_program_studi' => null,
                'f18d_tanggal_masuk' => null,
                'f12_sumber_biaya_kuliah' => 'Beasiswa BIDIKMISI',
                'f12_sumber_biaya_kuliah_lainnya' => null,
                'f14_erat_hubungan_studi' => 'Sangat Erat',
                'f15_tingkat_paling_tepat' => 'Tingkat yang Sama',
                // Matriks Kompetensi Budi
                'f1761_etika_dikuasai' => 4, 'f1762_etika_diperlukan' => 5,
                'f1763_keahlian_ilmu_dikuasai' => 4, 'f1764_keahlian_ilmu_diperlukan' => 5,
                'f1765_bahasa_inggris_dikuasai' => 3, 'f1766_bahasa_inggris_diperlukan' => 4,
                'f1767_it_dikuasai' => 5, 'f1768_it_diperlukan' => 5,
                'f1769_komunikasi_dikuasai' => 4, 'f1770_komunikasi_diperlukan' => 4,
                'f1771_tim_dikuasai' => 4, 'f1772_tim_diperlukan' => 5,
                'f1773_diri_dikuasai' => 4, 'f1774_diri_diperlukan' => 4,
                // Metode Pembelajaran
                'f21_perkuliahan' => 'Sangat Besar', 'f22_demonstrasi' => 'Besar', 'f23_riset' => 'Cukup Besar',
                'f24_magang' => 'Sangat Besar', 'f25_praktikum' => 'Sangat Besar', 'f26_kerja_lapangan' => 'Besar', 'f27_diskusi' => 'Sangat Besar',
                'f301_kapan_mencari_pekerjaan' => 'Kira-kira beberapa bulan sebelum lulus',
                'f302_bulan_sebelum_lulus' => 2,
                'f303_bulan_setelah_lulus' => null,
                // Cara mencari kerja (Budi lewat internet & bursa kerja)
                'f401_iklan_koran_brosur' => 0, 'f402_melamar_tanpa_lowongan' => 0, 'f403_bursa_pameran-online' => 1,
                'f404_internet_iklan_online' => 1, 'f405_dihubungi_perusahaan' => 0, 'f406_menghubungi_kemenakertrans' => 0,
                'f407_agen_tenaga_kerja' => 0, 'f408_karir_fakultas_universitas' => 1, 'f409_kantor_kemanusiaan_alumni' => 0,
                'f410_membangun_jejaring_kuliah' => 1, 'f411_melalui_relasi' => 0, 'f412_membangun_bisnis_sendiri' => 0,
                'f413_penempatan_kerja_magang' => 0, 'f414_tempat_kerja_sama_kuliah' => 0, 'f415_lainnya' => 0, 'f416_lainnya_teks' => null,
                'f6_perusahaan_dilamar' => 5, 'f7_perusahaan_merespon' => 3, 'f7a_mengundang_wawancara' => 2,
                'f10_aktif_mencari_kerja' => 'Ya, saya akan mulai bekerja dalam 2 minggu ke depan', 'f10_aktif_mencari_kerja_lainnya' => null,
                'f1601_pertanyaan_tidak_sesuai' => 0, 'f1602_belum_dapat_kerja_sesuai' => 0, 'f1603_prospek_karir_baik' => 0, 'f1604_suka_area_kerja_tersebut' => 0, 'f1605_dipromosikan_posisi_lain' => 0, 'f1606_pendapatan_lebih_tinggi' => 0, 'f1607_pekerjaan_lebih_aman' => 0, 'f1608_pekerjaan_lebih_menarik' => 0, 'f1609_mungkinkan_kerja_tambahan' => 0, 'f1610_lokasi_dekat_rumah' => 0, 'f1611_menjamin_kebutuhan_keluarga' => 0, 'f1612_awal_menitip_karir' => 0, 'f1613_lainnya' => 0, 'f1614_lainnya_teks' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // ==========================================
            // ALUMNI 2: SITI (STATUS WIRASWASTA)
            // ==========================================
            [
                'user_id' => 2,
                'no_mahasiswa' => '2210015111002',
                'kode_PT' => '072004',
                'tahun_lulus' => '2026',
                'kode_prodi' => '55201',
                'nama' => 'Siti Aminah',
                'no_hp' => '085277665544',
                'email' => 'alumni2@mahasiswa.com',
                'nik' => '1234567890123457',
                'npwp' => null,
                'f8_status_saat_ini' => 'Wiraswasta',
                'f504_mendapat_pekerjaan_6_bulan' => 'Ya',
                'f502_bulan_dapat_kerja' => 1,
                'f505_pendapatan_per_bulan' => 6000000,
                'f506_bulan_dapat_kerja_setelahnya' => null,
                'f510_provinsi' => 'Sumatera Barat',
                'f510_kab_kota' => 'Kabupaten Solok',
                'f11_jenis_instansi' => 'Wiraswasta/perusahaan sendiri',
                'f11_jenis_instansi_lainnya' => null,
                'f5b_nama_perusahaan' => 'Siti Web Solution',
                'f5c_posisi_wiraswasta' => 'Owner / Founder',
                'f5d_tingkat_tempat_kerja' => 'Lokal/Wilayah/Sektor Tidak Berbadan Hukum',
                'f18a_sumber_biaya_studi' => null,
                'f18b_perguruan_tinggi_studi' => null,
                'f18c_program_studi' => null,
                'f18d_tanggal_masuk' => null,
                'f12_sumber_biaya_kuliah' => 'Biaya Sendiri / Keluarga',
                'f12_sumber_biaya_kuliah_lainnya' => null,
                'f14_erat_hubungan_studi' => 'Erat',
                'f15_tingkat_paling_tepat' => 'Tingkat yang Sama',
                // Matriks Kompetensi Siti
                'f1761_etika_dikuasai' => 5, 'f1762_etika_diperlukan' => 5,
                'f1763_keahlian_ilmu_dikuasai' => 5, 'f1764_keahlian_ilmu_diperlukan' => 5,
                'f1765_bahasa_inggris_dikuasai' => 4, 'f1766_bahasa_inggris_diperlukan' => 4,
                'f1767_it_dikuasai' => 5, 'f1768_it_diperlukan' => 5,
                'f1769_komunikasi_dikuasai' => 5, 'f1770_komunikasi_diperlukan' => 5,
                'f1771_tim_dikuasai' => 4, 'f1772_tim_diperlukan' => 4,
                'f1773_diri_dikuasai' => 5, 'f1774_diri_diperlukan' => 5,
                // Metode Pembelajaran
                'f21_perkuliahan' => 'Besar', 'f22_demonstrasi' => 'Sangat Besar', 'f23_riset' => 'Cukup Besar',
                'f24_magang' => 'Sangat Besar', 'f25_praktikum' => 'Sangat Besar', 'f26_kerja_lapangan' => 'Besar', 'f27_diskusi' => 'Sangat Besar',
                'f301_kapan_mencari_pekerjaan' => 'Saya tidak mencari kerja',
                'f302_bulan_sebelum_lulus' => null,
                'f303_bulan_setelah_lulus' => null,
                // Cara mencari kerja (Siti membangun bisnis sendiri)
                'f401_iklan_koran_brosur' => 0, 'f402_melamar_tanpa_lowongan' => 0, 'f403_bursa_pameran-online' => 0,
                'f404_internet_iklan_online' => 0, 'f405_dihubungi_perusahaan' => 0, 'f406_menghubungi_kemenakertrans' => 0,
                'f407_agen_tenaga_kerja' => 0, 'f408_karir_fakultas_universitas' => 0, 'f409_kantor_kemanusiaan_alumni' => 0,
                'f410_membangun_jejaring_kuliah' => 1, 'f411_melalui_relasi' => 0, 'f412_membangun_bisnis_sendiri' => 1,
                'f413_penempatan_kerja_magang' => 0, 'f414_tempat_kerja_sama_kuliah' => 0, 'f415_lainnya' => 0, 'f416_lainnya_teks' => null,
                'f6_perusahaan_dilamar' => 0, 'f7_perusahaan_merespon' => 0, 'f7a_mengundang_wawancara' => 0,
                'f10_aktif_mencari_kerja' => 'Tidak', 'f10_aktif_mencari_kerja_lainnya' => null,
                'f1601_pertanyaan_tidak_sesuai' => 0, 'f1602_belum_dapat_kerja_sesuai' => 0, 'f1603_prospek_karir_baik' => 0, 'f1604_suka_area_kerja_tersebut' => 0, 'f1605_dipromosikan_posisi_lain' => 0, 'f1606_pendapatan_lebih_tinggi' => 0, 'f1607_pekerjaan_lebih_aman' => 0, 'f1608_pekerjaan_lebih_menarik' => 0, 'f1609_mungkinkan_kerja_tambahan' => 0, 'f1610_lokasi_dekat_rumah' => 0, 'f1611_menjamin_kebutuhan_keluarga' => 0, 'f1612_awal_menitip_karir' => 0, 'f1613_lainnya' => 0, 'f1614_lainnya_teks' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // ==========================================
            // ALUMNI 3: RIAN (STATUS KULIAH S2)
            // ==========================================
            [
                'user_id' => 3,
                'no_mahasiswa' => '2210015111003',
                'kode_PT' => '072004',
                'tahun_lulus' => '2026',
                'kode_prodi' => '55201',
                'nama' => 'Rian Hidayat',
                'no_hp' => '089988776655',
                'email' => 'alumni3@mahasiswa.com',
                'nik' => '1234567890123458',
                'npwp' => null,
                'f8_status_saat_ini' => 'Melanjutkan Pendidikan',
                'f504_mendapat_pekerjaan_6_bulan' => 'Tidak',
                'f502_bulan_dapat_kerja' => null,
                'f505_pendapatan_per_bulan' => null,
                'f506_bulan_dapat_kerja_setelahnya' => null,
                'f510_provinsi' => null,
                'f510_kab_kota' => null,
                'f11_jenis_instansi' => null,
                'f11_jenis_instansi_lainnya' => null,
                'f5b_nama_perusahaan' => null,
                'f5c_posisi_wiraswasta' => null,
                'f5d_tingkat_tempat_kerja' => null,
                // Detail Studi Lanjut Rian
                'f18a_sumber_biaya_studi' => 'Beasiswa LPDP',
                'f18b_perguruan_tinggi_studi' => 'Universitas Indonesia',
                'f18c_program_studi' => 'Magister Ilmu Komputer',
                'f18d_tanggal_masuk' => '2026-09-01',
                'f12_sumber_biaya_kuliah' => 'Beasiswa PPA',
                'f12_sumber_biaya_kuliah_lainnya' => null,
                'f14_erat_hubungan_studi' => 'Sangat Erat',
                'f15_tingkat_paling_tepat' => 'Setingkat Lebih Tinggi',
                // Matriks Kompetensi Rian
                'f1761_etika_dikuasai' => 4, 'f1762_etika_diperlukan' => 4,
                'f1763_keahlian_ilmu_dikuasai' => 4, 'f1764_keahlian_ilmu_diperlukan' => 4,
                'f1765_bahasa_inggris_dikuasai' => 5, 'f1766_bahasa_inggris_diperlukan' => 5,
                'f1767_it_dikuasai' => 4, 'f1768_it_diperlukan' => 5,
                'f1769_komunikasi_dikuasai' => 3, 'f1770_komunikasi_diperlukan' => 4,
                'f1771_tim_dikuasai' => 4, 'f1772_tim_diperlukan' => 4,
                'f1773_diri_dikuasai' => 5, 'f1774_diri_diperlukan' => 5,
                // Metode Pembelajaran
                'f21_perkuliahan' => 'Sangat Besar', 'f22_demonstrasi' => 'Cukup Besar', 'f23_riset' => 'Sangat Besar',
                'f24_magang' => 'Besar', 'f25_praktikum' => 'Besar', 'f26_kerja_lapangan' => 'Cukup Besar', 'f27_diskusi' => 'Sangat Besar',
                'f301_kapan_mencari_pekerjaan' => 'Saya tidak mencari kerja',
                'f302_bulan_sebelum_lulus' => null,
                'f303_bulan_setelah_lulus' => null,
                'f401_iklan_koran_brosur' => 0, 'f402_melamar_tanpa_lowongan' => 0, 'f403_bursa_pameran-online' => 0,
                'f404_internet_iklan_online' => 0, 'f405_dihubungi_perusahaan' => 0, 'f406_menghubungi_kemenakertrans' => 0,
                'f407_agen_tenaga_kerja' => 0, 'f408_karir_fakultas_universitas' => 0, 'f409_kantor_kemanusiaan_alumni' => 0,
                'f410_membangun_jejaring_kuliah' => 0, 'f411_melalui_relasi' => 0, 'f412_membangun_bisnis_sendiri' => 0,
                'f413_penempatan_kerja_magang' => 0, 'f414_tempat_kerja_sama_kuliah' => 0, 'f415_lainnya' => 0, 'f416_lainnya_teks' => null,
                'f6_perusahaan_dilamar' => 0, 'f7_perusahaan_merespon' => 0, 'f7a_mengundang_wawancara' => 0,
                'f10_aktif_mencari_kerja' => 'Tidak', 'f10_aktif_mencari_kerja_lainnya' => null,
                'f1601_pertanyaan_tidak_sesuai' => 0, 'f1602_belum_dapat_kerja_sesuai' => 0, 'f1603_prospek_karir_baik' => 0, 'f1604_suka_area_kerja_tersebut' => 0, 'f1605_dipromosikan_posisi_lain' => 0, 'f1606_pendapatan_lebih_tinggi' => 0, 'f1607_pekerjaan_lebih_aman' => 0, 'f1608_pekerjaan_lebih_menarik' => 0, 'f1609_mungkinkan_kerja_tambahan' => 0, 'f1610_lokasi_dekat_rumah' => 0, 'f1611_menjamin_kebutuhan_keluarga' => 0, 'f1612_awal_menitip_karir' => 0, 'f1613_lainnya' => 0, 'f1614_lainnya_teks' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
        */
    }
}