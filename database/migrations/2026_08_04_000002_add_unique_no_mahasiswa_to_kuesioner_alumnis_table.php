<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $table->unique('no_mahasiswa');
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $table) {
            $table->dropUnique(['no_mahasiswa']);
        });
    }
};
