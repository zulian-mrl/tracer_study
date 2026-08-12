<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $table->index('tahun_lulus');
            $table->index('kode_prodi');
            $table->index(['tahun_lulus', 'kode_prodi']);
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $table->dropIndex(['tahun_lulus']);
            $table->dropIndex(['kode_prodi']);
            $table->dropIndex(['tahun_lulus', 'kode_prodi']);
        });
    }
};
