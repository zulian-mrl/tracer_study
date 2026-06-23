<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $value) {
            // Menghapus kekangan foreign key ke tabel users
            $value->dropForeign(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_alumnis', function (Blueprint $value) {
            $value->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};