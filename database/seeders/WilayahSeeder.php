<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        if (Wilayah::count() > 0) {
            return;
        }

        $provinsi = config('wilayah.provinsi', []);
        $kabKota = config('wilayah.kab_kota', []);
        $provinsiList = config('wilayah.provinsi_list', []);

        foreach ($provinsi as $nama => $kode) {
            Wilayah::create([
                'kode_provinsi' => $kode,
                'nama_provinsi' => $nama,
                'kode_kab_kota' => null,
                'nama_kab_kota' => null,
            ]);

            foreach ($provinsiList[$nama] ?? [] as $namaKab) {
                Wilayah::create([
                    'kode_provinsi' => $kode,
                    'nama_provinsi' => $nama,
                    'kode_kab_kota' => $kabKota[$namaKab] ?? $namaKab,
                    'nama_kab_kota' => $namaKab,
                ]);
            }
        }
    }
}
