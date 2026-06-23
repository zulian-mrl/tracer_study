<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- F4 CARA MENCARI PEKERJAAN ---
        if (!Schema::hasColumn('kuesioner_alumnis', 'f401_iklan_koran_brosur')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f401_iklan_koran_brosur')->default(0);
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f402_melamar_tanpa_lowongan')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f402_melamar_tanpa_lowongan')->default(0);
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f403_bursa_pameran-online')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f403_bursa_pameran-online')->default(0);
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f404_internet_iklan_online')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f404_internet_iklan_online')->default(0);
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f411_melalui_relasi')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f411_melalui_relasi')->default(0);
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f412_membangun_bisnis_sendiri')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f412_membangun_bisnis_sendiri')->default(0);
            });
        }

        // --- F10 & F16 KEAKTIFAN & ALASAN ---
        if (!Schema::hasColumn('kuesioner_alumnis', 'f10_aktif_mencari_kerja')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->string('f10_aktif_mencari_kerja')->nullable();
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f10_lainnya')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->string('f10_lainnya')->nullable();
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f16_alasan')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->text('f16_alasan')->nullable();
            });
        }
        if (!Schema::hasColumn('kuesioner_alumnis', 'f1613_lainnya')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->string('f1613_lainnya')->nullable();
            });
        }

        // --- F17 MATRIKS ASPEK KOMPETENSI ---
        // Kolom A (Kompetensi Saat Lulus)
        $kompetensiA = ['f1701_A', 'f1702_A', 'f1703_A', 'f1704_A', 'f1705_A', 'f1706_A', 'f1707_A'];
        foreach ($kompetensiA as $kolomA) {
            if (!Schema::hasColumn('kuesioner_alumnis', $kolomA)) {
                Schema::table('kuesioner_alumnis', function (Blueprint $table) use ($kolomA) {
                    $table->tinyInteger($kolomA)->nullable();
                });
            }
        }

        // Kolom B (Kebutuhan di Pekerjaan)
        $kompetensiB = ['f1701_B', 'f1702_B', 'f1703_B', 'f1704_B', 'f1705_B', 'f1706_B', 'f1707_B'];
        foreach ($kompetensiB as $kolomB) {
            if (!Schema::hasColumn('kuesioner_alumnis', $kolomB)) {
                Schema::table('kuesioner_alumnis', function (Blueprint $table) use ($kolomB) {
                    $table->tinyInteger($kolomB)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $columns = [
                'f401_iklan_koran_brosur', 'f402_melamar_tanpa_lowongan', 'f403_bursa_pameran-online', 'f404_internet_iklan_online', 'f411_melalui_relasi', 'f412_membangun_bisnis_sendiri',
                'f10_aktif_mencari_kerja', 'f10_lainnya', 'f16_alasan', 'f1613_lainnya',
                'f1701_A', 'f1702_A', 'f1703_A', 'f1704_A', 'f1705_A', 'f1706_A', 'f1707_A',
                'f1701_B', 'f1702_B', 'f1703_B', 'f1704_B', 'f1705_B', 'f1706_B', 'f1707_B'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('kuesioner_alumnis', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};