<?php

namespace Tests\Feature;

use App\Models\MasterAlumni;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KuesionerStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        MasterAlumni::create([
            'no_mahasiswa' => '2210015111001',
            'kode_prodi' => '54211',
            'nama' => 'Budi Setiawan',
            'nik' => '1234567890123456',
            'tahun_lulus' => '2026',
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'no_mahasiswa' => '2210015111001',
            'kode_PT' => '101004',
            'tahun_lulus' => '2026',
            'kode_prodi' => '54211',
            'nama' => 'Budi Setiawan',
            'no_hp' => '081234567890',
            'email' => 'budi@gmail.com',
            'nik' => '1234567890123456',
            'npwp' => '',
            'f8_status_saat_ini' => '1',
            'f504_mendapat_pekerjaan_6_bulan' => '1',
            'f502_bulan_dapat_kerja_ya' => '3',
            'f505_pendapatan_per_bulan' => '4000000',
            'f510_provinsi' => 'Prov. Sumatera Barat',
            'f510_kab_kota' => 'Kota Solok',
            'f11_jenis_instansi' => '5',
            'f5d_tingkat' => 'Nasional',
            'f12_01' => '1',
            'f14' => '1',
            'f15' => '1',
            'f301' => '1',
            'f302' => '2',
            'comp_a_f1761_f1762' => '4',
            'comp_a_f1763_f1764' => '4',
            'comp_a_f1765_f1766' => '4',
            'comp_a_f1767_f1768' => '4',
            'comp_a_f1769_f1770' => '4',
            'comp_a_f1771_f1772' => '4',
            'comp_a_f1773_f1774' => '4',
            'comp_b_f1761_f1762' => '5',
            'comp_b_f1763_f1764' => '5',
            'comp_b_f1765_f1766' => '5',
            'comp_b_f1767_f1768' => '5',
            'comp_b_f1769_f1770' => '5',
            'comp_b_f1771_f1772' => '5',
            'comp_b_f1773_f1774' => '5',
        ], $overrides);
    }

    public function test_payload_lengkap_tersimpan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kuesioner_alumnis', [
            'no_mahasiswa' => '2210015111001',
            'f8_status_saat_ini' => '1',
            'f505_pendapatan_per_bulan' => '4000000',
        ]);
    }

    public function test_status_bekerja_menolak_payload_tanpa_lokasi(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f510_provinsi' => '',
            'f510_kab_kota' => '',
            'f11_jenis_instansi' => '',
        ]))->assertSessionHasErrors(['f510_provinsi', 'f510_kab_kota', 'f11_jenis_instansi']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_nik_tidak_16_digit_ditolak(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'nik' => '123456789012',
        ]))->assertSessionHasErrors(['nik']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_lanjut_mewajibkan_data_studi_lanjut(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f8_status_saat_ini' => '4',
            'f18a_sumber_biaya_studi' => '',
            'f18b_perguruan_tinggi_studi' => '',
            'f18c_program_studi' => '',
            'f18d_tanggal_masuk' => '',
            'f12_01' => '',
        ]))->assertSessionHasErrors(['f18a_sumber_biaya_studi', 'f18b_perguruan_tinggi_studi', 'f18c_program_studi', 'f12_01']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_bekerja_wajib_isi_sumber_dana_kuliah(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f12_01' => '',
        ]))->assertSessionHasErrors(['f12_01']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_bekerja_tidak_mewajibkan_data_studi_lanjut(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f18a_sumber_biaya_studi' => '',
            'f18b_perguruan_tinggi_studi' => '',
            'f18c_program_studi' => '',
            'f18d_tanggal_masuk' => '',
        ]))->assertSessionHas('success');

        $this->assertDatabaseHas('kuesioner_alumnis', [
            'no_mahasiswa' => '2210015111001',
            'f8_status_saat_ini' => '1',
        ]);
    }

    public function test_alumni_tidak_terdaftar_ditolak(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'no_mahasiswa' => '9999999999999',
            'nik' => '1234567890123456',
        ]))->assertSessionHasErrors(['autentikasi']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }
}
