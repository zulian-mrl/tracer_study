<?php

namespace Tests\Unit;

use App\Exports\KuesionerAlumniExport;
use PHPUnit\Framework\TestCase;

class KuesionerAlumniExportTest extends TestCase
{
    private function baris(array $data): array
    {
        $export = new KuesionerAlumniExport([
            'alumniRaw' => [
                (object) [
                    'kode_PT' => '101004',
                    'kode_prodi' => '54211',
                    'no_mahasiswa' => '2210015111001',
                    'nama' => 'Budi',
                    'no_hp' => '081234567890',
                    'email' => 'budi@gmail.com',
                    'tahun_lulus' => '2026',
                    'nik' => '1234567890123456',
                    'npwp' => '',
                    'f8_status_saat_ini' => '1',
                    'f5c_posisi_wiraswasta' => $data['f5c'] ?? null,
                    'f5d_tingkat_tempat_kerja' => $data['f5d'] ?? null,
                ],
            ],
        ]);

        return $export->collection()->first();
    }

    public function test_f5c_terkonversi_ke_kode(): void
    {
        $this->assertSame('1', $this->baris(['f5c' => 'Founder'])[18]);
        $this->assertSame('1', $this->baris(['f5c' => 'Owner / Founder'])[18]);
        $this->assertSame('2', $this->baris(['f5c' => 'Co-Founder'])[18]);
        $this->assertSame('3', $this->baris(['f5c' => 'Staff'])[18]);
        $this->assertSame('4', $this->baris(['f5c' => 'Freelance / Kerja Lepas'])[18]);
        $this->assertSame('Tak Dikenal', $this->baris(['f5c' => 'Tak Dikenal'])[18]);
        $this->assertSame('0', $this->baris(['f5c' => '0'])[18]);
    }

    public function test_f5d_terkonversi_ke_kode(): void
    {
        $this->assertSame('1', $this->baris(['f5d' => 'Lokal'])[19]);
        $this->assertSame('1', $this->baris(['f5d' => 'Lokal/Wilayah/Sektor Tidak Berbadan Hukum'])[19]);
        $this->assertSame('2', $this->baris(['f5d' => 'Nasional'])[19]);
        $this->assertSame('3', $this->baris(['f5d' => 'Multinasional/internasional'])[19]);
        $this->assertSame('Tak Dikenal', $this->baris(['f5d' => 'Tak Dikenal'])[19]);
        $this->assertSame('0', $this->baris(['f5d' => '0'])[19]);
    }

    public function test_bind_value_mencegah_formula_injection(): void
    {
        $export = new KuesionerAlumniExport([]);

        foreach (['=HYPERLINK("https://jahat.example","Lihat")', '+cmd', '-cmd', '@cmd'] as $value) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
            $cell = $spreadsheet->getActiveSheet()->getCell('A1');

            $export->bindValue($cell, $value);

            $this->assertSame(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, $cell->getDataType(), "nilai {$value} harus berupa teks");
            $this->assertSame($value, $cell->getValue());
            $spreadsheet->disconnectWorksheets();
        }
    }
}
