<?php

namespace App\Imports;

use App\Models\MasterAlumni;
use Maatwebsite\Excel\Concerns\ToModel;

class AlumniImport implements ToModel
{
    /**
     * Kode ini memetakan BARIS DI EXCEL (berdasarkan nama header-nya)
     * ke dalam KOLOM DATABASE MySQL Anda secara otomatis.
     */
    public function model(array $row)
    {
        if (empty($row[0]) || in_array(strtolower($row[0]), ['no_mahasiswa', 'nim', 'no', 'no mahasiswa', 'tahun_lulus'])) {
            return null;
        }
        return new MasterAlumni([
            'no_mahasiswa' => $row['0'], // Membaca header 'no_mahasiswa' di Excel
            'kode_prodi'   => $row['1'],   // Membaca header 'kode_prodi' di Excel
            'nama'         => $row['2'],         // Membaca header 'nama' di Excel
            'nik'          => $row['3'],          // Membaca header 'nik' di Excel
            'tahun_lulus'  => $row['4'],
        ]);
    }
}