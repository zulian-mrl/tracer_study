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
        if (!Schema::hasColumn('kuesioner_alumnis', 'f403_bursa_pameran_online')) {
            Schema::table('kuesioner_alumnis', function (Blueprint $table) {
                $table->tinyInteger('f403_bursa_pameran_online')->default(0);
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
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $columns = [
                'f401_iklan_koran_brosur', 'f402_melamar_tanpa_lowongan', 'f403_bursa_pameran_online', 'f404_internet_iklan_online', 'f411_melalui_relasi', 'f412_membangun_bisnis_sendiri',
                'f10_aktif_mencari_kerja', 'f10_lainnya'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('kuesioner_alumnis', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};