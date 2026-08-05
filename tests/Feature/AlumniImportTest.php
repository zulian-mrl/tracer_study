<?php

namespace Tests\Feature;

use App\Imports\AlumniImport;
use App\Models\MasterAlumni;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AlumniImportTest extends TestCase
{
    use RefreshDatabase;

    private function buatFile(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($rows as $i => $row) {
            foreach ($row as $j => $value) {
                $sheet->setCellValue([$j + 1, $i + 1], $value);
            }
        }
        $path = tempnam(sys_get_temp_dir(), 'import') . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        return $path;
    }

    public function test_import_menyimpan_baris_valid_dan_melewati_yang_duplikat(): void
    {
        MasterAlumni::create([
            'no_mahasiswa' => 'EXIST001',
            'kode_prodi' => '54211',
            'nama' => 'Sudah Ada',
            'nik' => '9999999999999999',
            'tahun_lulus' => '2025',
        ]);

        $file = $this->buatFile([
            ['no_mahasiswa', 'kode_prodi', 'nama', 'nik', 'tahun_lulus'],
            ['2210015111001', '54211', 'Budi Setiawan', '1234567890123456', '2026'],
            ['2210015111002', '54211', 'Siti Aminah', '1234567890123457', '2026'],
            ['', '54211', 'Tanpa NIM', '', '2026'],
            ['2210015111003', '54211', 'Bambang Pamungkas', '1234567890123458', '2026'],
            ['EXIST001', '54211', 'Duplikat NIM DB', '1234567890123459', '2025'],
            ['2210015111001', '54211', 'Duplikat Dalam File', '1234567890123460', '2026'],
            ['9999999999999', '54211', 'NIK Duplikat DB', '9999999999999999', '2025'],
        ]);

        $import = new AlumniImport;
        Excel::import($import, $file);

        $this->assertSame(3, $import->getCounts()['insert']);
        $this->assertSame(3, $import->getCounts()['duplicate']);
        $this->assertSame(1, $import->getCounts()['invalid']);

        $this->assertDatabaseCount('master_alumnis', 4);
        $this->assertDatabaseHas('master_alumnis', ['no_mahasiswa' => '2210015111001', 'nama' => 'Budi Setiawan']);
        $this->assertDatabaseHas('master_alumnis', ['no_mahasiswa' => '2210015111002', 'nama' => 'Siti Aminah']);
        $this->assertDatabaseHas('master_alumnis', ['no_mahasiswa' => '2210015111003', 'nama' => 'Bambang Pamungkas']);
        $this->assertDatabaseMissing('master_alumnis', ['no_mahasiswa' => 'EXIST001', 'nama' => 'Duplikat NIM DB']);
        $this->assertDatabaseMissing('master_alumnis', ['no_mahasiswa' => '2210015111001', 'nama' => 'Duplikat Dalam File']);

        $created = MasterAlumni::where('no_mahasiswa', '2210015111001')->firstOrFail();
        $this->assertNotNull($created->created_at);
        $this->assertNotNull($created->updated_at);

        @unlink($file);
    }

    public function test_endpoint_import_menampilkan_laporan_hasil(): void
    {
        $admin = User::factory()->create(['is_super' => true]);

        $file = $this->buatFile([
            ['no_mahasiswa', 'kode_prodi', 'nama', 'nik', 'tahun_lulus'],
            ['2210015112001', '54211', 'Andi Pratama', '1234567890123401', '2026'],
            ['2210015112001', '54211', 'Duplikat Dalam File', '1234567890123402', '2026'],
            ['', '54211', 'Tanpa NIM', '', '2026'],
        ]);

        $upload = new UploadedFile(
            $file,
            'alumni.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $this->actingAs($admin)
            ->post('/admin/alumni/import', ['file_excel' => $upload])
            ->assertSessionHas('success', function (string $message) {
                return str_contains($message, '1 baris berhasil')
                    && str_contains($message, '1 dilewati (duplikat)')
                    && str_contains($message, '1 gagal (tidak lengkap)');
            });

        $this->assertDatabaseCount('master_alumnis', 1);
        $this->assertDatabaseHas('master_alumnis', ['no_mahasiswa' => '2210015112001', 'nama' => 'Andi Pratama']);

        @unlink($file);
    }
}
