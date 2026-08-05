<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_provinsi');
            $table->string('nama_provinsi');
            $table->string('kode_kab_kota')->nullable();
            $table->string('nama_kab_kota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
