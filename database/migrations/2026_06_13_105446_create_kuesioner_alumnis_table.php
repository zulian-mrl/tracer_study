<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kuesioner_alumnis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // --- F1: IDENTITAS ALUMNI ---
            $table->string('no_mahasiswa');
            $table->string('kode_PT');
            $table->string('tahun_lulus');
            $table->string('kode_prodi');
            $table->string('nama');
            $table->string('no_hp');
            $table->string('email');
            $table->string('nik', 16);
            $table->string('npwp')->nullable();

            // --- F8 STATUS ALUMNI ---
            $table->string('f8_status_saat_ini');

            // --- F504 PEKERJAAN ---
            $table->string('f504_mendapat_pekerjaan_6_bulan');
            $table->integer('f502_bulan_dapat_kerja')->nullable();
            $table->integer('f505_pendapatan_per_bulan')->nullable();
            $table->integer('f506_bulan_dapat_kerja_setelahnya')->nullable();

            // --- F510 LOKASI TEMPAT KERJA ---
            $table->string('f510_provinsi')->nullable();
            $table->string('f510_kab_kota')->nullable();

            // --- F11 JENIS INSTANSI ---
            $table->string('f11_jenis_instansi')->nullable();
            $table->string('f11_jenis_instansi_lainnya')->nullable();

            // --- F5B NAMA PERUSAHAAN ---
            $table->string('f5b_nama_perusahaan')->nullable();

            // --- F5C JABATAN(WIRASWASTA) ---
            $table->string('f5c_posisi_wiraswasta')->nullable();

            // --- F5D TINGKAT TEMPAT KERJA ---
            $table->string('f5d_tingkat_tempat_kerja')->nullable();

            // --- F18 PERTANYAAN STUDI LANJUT ---
            $table->string('f18a_sumber_biaya_studi')->nullable();
            $table->string('f18b_perguruan_tinggi_studi')->nullable();
            $table->string('f18c_program_studi')->nullable();
            $table->date('f18d_tanggal_masuk')->nullable();

            // --- F12 SUMBER DANA PEMBIAYAAN KULIAH ---
            $table->string('f12_sumber_biaya_kuliah')->nullable();
            $table->string('f12_sumber_biaya_kuliah_lainnya')->nullable();

            // --- F14 HUBUNGAN BIDANG STUDI DENGAN PEKERJAAN ---
            $table->string('f14_erat_hubungan_studi')->nullable();

            // --- F15 TINGKAT PENDIDIKAN PALING TEPAT ---
            $table->string('f15_tingkat_paling_tepat')->nullable();

            // --- F17 MATRIK KOMPETENSI ALUMNI ---
            $table->string('f1701_A')->nullable();
            $table->string('f1701_B')->nullable();
            $table->string('f1702_A')->nullable();
            $table->string('f1702_B')->nullable();
            $table->string('f1703_A')->nullable();
            $table->string('f1703_B')->nullable();
            $table->string('f1704_A')->nullable();
            $table->string('f1704_B')->nullable();
            $table->string('f1705_A')->nullable();
            $table->string('f1705_B')->nullable();
            $table->string('f1706_A')->nullable();
            $table->string('f1706_B')->nullable();
            $table->string('f1707_A')->nullable();
            $table->string('f1707_B')->nullable();

            // --- F2 PENEKANAN METODE PEMBELAJARAN KULIAH ---
            $table->string('f21_perkuliahan')->nullable();
            $table->string('f22_demonstrasi')->nullable();
            $table->string('f23_riset')->nullable();
            $table->string('f24_magang')->nullable();
            $table->string('f25_praktikum')->nullable();
            $table->string('f26_kerja_lapangan')->nullable();
            $table->string('f27_diskusi')->nullable();

            // --- F3 MULAI MENCARI PEKERJAAN ---
            $table->string('f301_kapan_mencari_pekerjaan');
            $table->integer('f302_bulan_sebelum_lulus')->nullable();
            $table->integer('f303_bulan_setelah_lulus')->nullable();

            // --- F4 CARA MENCARI PEKERJAAN ---
            $table->boolean('f401_iklan_koran_brosur')->default(0);
            $table->boolean('f402_melamar_tanpa_lowongan')->default(0);
            $table->boolean('f403_bursa_pameran_online')->default(0);
            $table->boolean('f404_internet_iklan_online')->default(0);
            $table->boolean('f405_dihubungi_perusahaan')->default(0);
            $table->boolean('f406_menghubungi_kemenakertrans')->default(0);
            $table->boolean('f407_agen_tenaga_kerja')->default(0);
            $table->boolean('f408_karir_fakultas_universitas')->default(0);
            $table->boolean('f409_kantor_kemanusiaan_alumni')->default(0);
            $table->boolean('f410_membangun_jejaring_kuliah')->default(0);
            $table->boolean('f411_melalui_relasi')->default(0);
            $table->boolean('f412_membangun_bisnis_sendiri')->default(0);
            $table->boolean('f413_penempatan_kerja_magang')->default(0);
            $table->boolean('f414_tempat_kerja_sama_kuliah')->default(0);
            $table->boolean('f415_lainnya')->default(0);
            $table->string('f416_tuliskan')->nullable();

            // --- F6 PERUSAHAAN DILAMAR ---
            $table->integer('f6_perusahaan_dilamar')->nullable();

            // --- F7 PERUSAHAAN MERESPON ---
            $table->integer('f7_perusahaan_merespon')->nullable();

            // --- F7a WAWANCARA PERUSAHAAN --- 
            $table->integer('f7a_mengundang_wawancara')->nullable();

            // --- F10 KEAKTIFAN MENCARI PEKERJAAN ---
            $table->string('f10_aktif_mencari_kerja')->nullable();
            $table->string('f10_lainnya')->nullable();

            // --- F16 ALASAN MENGAMBIL PEKERJAAN TIDAK SESUAI ---
            $table->boolean('f1601_pertanyaan_tidak_sesuai')->default(0);
            $table->boolean('f1602_belum_dapat_kerja_sesuai')->default(0);
            $table->boolean('f1603_prospek_karir_baik')->default(0);
            $table->boolean('f1604_suka_area_kerja_tersebut')->default(0);
            $table->boolean('f1605_dipromosikan_posisi_lain')->default(0);
            $table->boolean('f1606_pendapatan_lebih_tinggi')->default(0);
            $table->boolean('f1607_pekerjaan_lebih_aman')->default(0);
            $table->boolean('f1608_pekerjaan_lebih_menarik')->default(0);
            $table->boolean('f1609_mungkinkan_kerja_tambahan')->default(0);
            $table->boolean('f1610_lokasi_dekat_rumah')->default(0);
            $table->boolean('f1611_menjamin_kebutuhan_keluarga')->default(0);
            $table->boolean('f1612_awal_menitip_karir')->default(0);
            $table->string('f1613_lainnya', 5)->nullable();
            $table->string('f1614_tuliskan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_alumnis');
    }
};
