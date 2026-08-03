<?php

namespace App\Imports;

use App\Models\MasterAlumni;
use Maatwebsite\Excel\Concerns\ToModel;

class AlumniImport implements ToModel
{
    /**
     * Memetakan kolom EXCEL (berurutan) ke kolom DATABASE:
     *   0 = no_mahasiswa, 1 = kode_prodi, 2 = nama, 3 = nik, 4 = tahun_lulus
     */
    public function model(array $row)
    {
        // Lewati baris header (jika ikut terbaca sebagai data)
        if (empty($row[0]) || in_array(strtolower(trim((string) $row[0])), ['no_mahasiswa', 'nim', 'no', 'no mahasiswa', 'tahun_lulus'])) {
            return null;
        }

        $nim = trim((string) ($row[0] ?? ''));
        $nik = trim((string) ($row[3] ?? ''));

        // Baris tanpa NIM atau NIK dianggap tidak valid
        if ($nim === '' || $nik === '') {
            return null;
        }

        // Lewati NIM/NIK yang sudah terdaftar agar tidak menggagalkan seluruh import
        if (MasterAlumni::where('no_mahasiswa', $nim)->exists()
            || MasterAlumni::where('nik', $nik)->exists()) {
            return null;
        }

        return new MasterAlumni([
            'no_mahasiswa' => $nim,
            'kode_prodi'   => $row[1] ?? null,
            'nama'         => $row[2] ?? null,
            'nik'          => $nik,
            'tahun_lulus'  => $row[4] ?? null,
        ]);
    }
}
