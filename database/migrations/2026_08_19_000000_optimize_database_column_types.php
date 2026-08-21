<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── kuesioner_alumnis: fix ALL column types ──────────────
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            // identity fields
            $table->string('nama', 150)->change();
            $table->string('email', 255)->change();

            // free-text fields — keep at 255
            $table->string('f416_tuliskan', 255)->nullable()->change();
            $table->string('f1614_tuliskan', 255)->nullable()->change();
            $table->string('f1613_lainnya', 50)->nullable()->change();
            $table->string('f10_aktif_mencari_kerja', 50)->nullable()->change();
            $table->string('f10_lainnya', 100)->nullable()->change();

            // competency ratings (14 cols) — varchar(10)
            foreach (['f1701_A','f1701_B','f1702_A','f1702_B','f1703_A','f1703_B','f1704_A','f1704_B','f1705_A','f1705_B','f1706_A','f1706_B','f1707_A','f1707_B'] as $col) {
                $table->string($col, 10)->nullable()->change();
            }

            // learning method ratings (7 cols) — varchar(10)
            foreach (['f21_perkuliahan','f22_demonstrasi','f23_riset','f24_magang','f25_praktikum','f26_kerja_lapangan','f27_diskusi'] as $col) {
                $table->string($col, 10)->nullable()->change();
            }

            // boolean (tinyInteger) — fix corrupted varchar(2) columns
            $table->boolean('f401_iklan_koran_brosur')->default(0)->change();
            $table->boolean('f402_melamar_tanpa_lowongan')->default(0)->change();
            $table->boolean('f403_bursa_pameran_online')->default(0)->change();
            $table->boolean('f404_internet_iklan_online')->default(0)->change();
            $table->boolean('f405_dihubungi_perusahaan')->default(0)->change();
            $table->boolean('f406_menghubungi_kemenakertrans')->default(0)->change();
            $table->boolean('f407_agen_tenaga_kerja')->default(0)->change();
            $table->boolean('f408_karir_fakultas_universitas')->default(0)->change();
            $table->boolean('f409_kantor_kemanusiaan_alumni')->default(0)->change();
            $table->boolean('f410_membangun_jejaring_kuliah')->default(0)->change();
            $table->boolean('f411_melalui_relasi')->default(0)->change();
            $table->boolean('f412_membangun_bisnis_sendiri')->default(0)->change();
            $table->boolean('f413_penempatan_kerja_magang')->default(0)->change();
            $table->boolean('f414_tempat_kerja_sama_kuliah')->default(0)->change();
            $table->boolean('f415_lainnya')->default(0)->change();
            $table->boolean('f1601_pertanyaan_tidak_sesuai')->default(0)->change();
            $table->boolean('f1602_belum_dapat_kerja_sesuai')->default(0)->change();
            $table->boolean('f1603_prospek_karir_baik')->default(0)->change();
            $table->boolean('f1604_suka_area_kerja_tersebut')->default(0)->change();
            $table->boolean('f1605_dipromosikan_posisi_lain')->default(0)->change();
            $table->boolean('f1606_pendapatan_lebih_tinggi')->default(0)->change();
            $table->boolean('f1607_pekerjaan_lebih_aman')->default(0)->change();
            $table->boolean('f1608_pekerjaan_lebih_menarik')->default(0)->change();
            $table->boolean('f1609_mungkinkan_kerja_tambahan')->default(0)->change();
            $table->boolean('f1610_lokasi_dekat_rumah')->default(0)->change();
            $table->boolean('f1611_menjamin_kebutuhan_keluarga')->default(0)->change();
            $table->boolean('f1612_awal_menitip_karir')->default(0)->change();

            // integer → tinyInteger (bulan)
            $table->tinyInteger('f502_bulan_dapat_kerja')->nullable()->change();
            $table->tinyInteger('f506_bulan_dapat_kerja_setelahnya')->nullable()->change();
            $table->tinyInteger('f302_bulan_sebelum_lulus')->nullable()->change();
            $table->tinyInteger('f303_bulan_setelah_lulus')->nullable()->change();

            // integer → smallInteger (count)
            $table->smallInteger('f6_perusahaan_dilamar')->nullable()->change();
            $table->smallInteger('f7_perusahaan_merespon')->nullable()->change();
            $table->smallInteger('f7a_mengundang_wawancara')->nullable()->change();
        });

        // ── wilayah ────────────────────────────────────────────────
        Schema::table('wilayah', function (Blueprint $table) {
            $table->string('kode_provinsi', 10)->change();
            $table->string('kode_kab_kota', 50)->nullable()->change();
        });

        // ── sessions ───────────────────────────────────────────────
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('last_activity')->change();
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $table->string('nama')->change();
            $table->string('email')->unique()->change();

            $table->string('f416_tuliskan')->nullable()->change();
            $table->string('f1614_tuliskan')->nullable()->change();
            $table->string('f1613_lainnya')->nullable()->change();
            $table->string('f10_aktif_mencari_kerja')->nullable()->change();
            $table->string('f10_lainnya')->nullable()->change();

            foreach (['f1701_A','f1701_B','f1702_A','f1702_B','f1703_A','f1703_B','f1704_A','f1704_B','f1705_A','f1705_B','f1706_A','f1706_B','f1707_A','f1707_B'] as $col) {
                $table->string($col)->nullable()->change();
            }
            foreach (['f21_perkuliahan','f22_demonstrasi','f23_riset','f24_magang','f25_praktikum','f26_kerja_lapangan','f27_diskusi'] as $col) {
                $table->string($col)->nullable()->change();
            }

            $table->tinyInteger('f401_iklan_koran_brosur')->default(0)->change();
            $table->tinyInteger('f402_melamar_tanpa_lowongan')->default(0)->change();
            $table->tinyInteger('f403_bursa_pameran_online')->default(0)->change();
            $table->tinyInteger('f404_internet_iklan_online')->default(0)->change();
            $table->tinyInteger('f405_dihubungi_perusahaan')->default(0)->change();
            $table->tinyInteger('f406_menghubungi_kemenakertrans')->default(0)->change();
            $table->tinyInteger('f407_agen_tenaga_kerja')->default(0)->change();
            $table->tinyInteger('f408_karir_fakultas_universitas')->default(0)->change();
            $table->tinyInteger('f409_kantor_kemanusiaan_alumni')->default(0)->change();
            $table->tinyInteger('f410_membangun_jejaring_kuliah')->default(0)->change();
            $table->tinyInteger('f411_melalui_relasi')->default(0)->change();
            $table->tinyInteger('f412_membangun_bisnis_sendiri')->default(0)->change();
            $table->tinyInteger('f413_penempatan_kerja_magang')->default(0)->change();
            $table->tinyInteger('f414_tempat_kerja_sama_kuliah')->default(0)->change();
            $table->tinyInteger('f415_lainnya')->default(0)->change();
            $table->tinyInteger('f1601_pertanyaan_tidak_sesuai')->default(0)->change();
            $table->tinyInteger('f1602_belum_dapat_kerja_sesuai')->default(0)->change();
            $table->tinyInteger('f1603_prospek_karir_baik')->default(0)->change();
            $table->tinyInteger('f1604_suka_area_kerja_tersebut')->default(0)->change();
            $table->tinyInteger('f1605_dipromosikan_posisi_lain')->default(0)->change();
            $table->tinyInteger('f1606_pendapatan_lebih_tinggi')->default(0)->change();
            $table->tinyInteger('f1607_pekerjaan_lebih_aman')->default(0)->change();
            $table->tinyInteger('f1608_pekerjaan_lebih_menarik')->default(0)->change();
            $table->tinyInteger('f1609_mungkinkan_kerja_tambahan')->default(0)->change();
            $table->tinyInteger('f1610_lokasi_dekat_rumah')->default(0)->change();
            $table->tinyInteger('f1611_menjamin_kebutuhan_keluarga')->default(0)->change();
            $table->tinyInteger('f1612_awal_menitip_karir')->default(0)->change();

            $table->integer('f502_bulan_dapat_kerja')->nullable()->change();
            $table->integer('f506_bulan_dapat_kerja_setelahnya')->nullable()->change();
            $table->integer('f302_bulan_sebelum_lulus')->nullable()->change();
            $table->integer('f303_bulan_setelah_lulus')->nullable()->change();
            $table->integer('f6_perusahaan_dilamar')->nullable()->change();
            $table->integer('f7_perusahaan_merespon')->nullable()->change();
            $table->integer('f7a_mengundang_wawancara')->nullable()->change();
        });

        Schema::table('wilayah', function (Blueprint $table) {
            $table->string('kode_provinsi')->change();
            $table->string('kode_kab_kota')->nullable()->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('last_activity')->change();
        });
    }
};
