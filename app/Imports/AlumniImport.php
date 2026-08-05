<?php

namespace App\Imports;

use App\Models\MasterAlumni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AlumniImport implements ToModel, WithBatchInserts
{
    private ?array $existingNim = null;
    private ?array $existingNik = null;
    private array $seenNim = [];
    private array $seenNik = [];
    private int $countInsert = 0;
    private int $countDuplicate = 0;
    private int $countInvalid = 0;

    /**
     * Muat NIM/NIK yang sudah ada di database satu kali saja,
     * supaya tidak ada query exists() per baris (hindari N+1).
     */
    private function ensureExisting(): void
    {
        if ($this->existingNim === null) {
            $this->existingNim = MasterAlumni::query()->pluck('no_mahasiswa')->flip()->all();
            $this->existingNik = MasterAlumni::query()->pluck('nik')->flip()->all();
        }
    }

    /**
     * Memetakan kolom EXCEL (berurutan) ke kolom DATABASE:
     *   0 = no_mahasiswa, 1 = kode_prodi, 2 = nama, 3 = nik, 4 = tahun_lulus
     */
    public function model(array $row)
    {
        // Lewati baris header (kolom pertama berisi teks penanda kolom)
        if (!empty($row[0]) && in_array(strtolower(trim((string) $row[0])), ['no_mahasiswa', 'nim', 'no', 'no mahasiswa', 'tahun_lulus'])) {
            return null;
        }

        $nim = trim((string) ($row[0] ?? ''));
        $nik = trim((string) ($row[3] ?? ''));

        // Baris tanpa NIM atau NIK dianggap tidak valid
        if ($nim === '' || $nik === '') {
            $this->countInvalid++;
            return null;
        }

        // Lewati NIM/NIK yang sudah terdaftar atau duplikat di dalam berkas yang sama,
        // agar tidak menggagalkan seluruh import.
        $this->ensureExisting();

        if (isset($this->existingNim[$nim])
            || isset($this->seenNim[$nim])
            || isset($this->existingNik[$nik])
            || isset($this->seenNik[$nik])) {
            $this->countDuplicate++;
            return null;
        }

        $this->seenNim[$nim] = true;
        $this->seenNik[$nik] = true;
        $this->countInsert++;

        return new MasterAlumni([
            'no_mahasiswa' => $nim,
            'kode_prodi'   => $row[1] ?? null,
            'nama'         => $row[2] ?? null,
            'nik'          => $nik,
            'tahun_lulus'  => $row[4] ?? null,
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Rekapitulasi hasil import:
     *  - insert     = jumlah baris yang berhasil ditambahkan
     *  - duplicate  = jumlah baris dilewati karena NIM/NIK sudah ada di DB atau ganda dalam berkas
     *  - invalid    = jumlah baris dilewati karena NIM/NIK kosong
     */
    public function getCounts(): array
    {
        return [
            'insert' => $this->countInsert,
            'duplicate' => $this->countDuplicate,
            'invalid' => $this->countInvalid,
        ];
    }
}
