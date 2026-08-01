<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAlumni extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara spesifik
    protected $table = 'master_alumnis';

    // Daftarkan kolom yang boleh diisi massal saat proses import Excel
    protected $fillable = [
        'no_mahasiswa',
        'kode_prodi',
        'nama',
        'nik',
        'tahun_lulus'
    ];
}