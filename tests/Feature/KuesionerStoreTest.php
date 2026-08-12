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
            'f5c_posisi' => 'Staff',
            'f5d_tingkat' => 'Nasional',
            'f12_01' => '1',
            'f14' => '1',
            'f15' => '1',
            'f301' => '1',
            'f302' => '2',
            'f10_aktif' => '1',
            'f6_jumlah_lamaran' => '5',
            'f7_jumlah_respons' => '2',
            'f17a_jumlah_wawancara' => '1',
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

    public function test_proses_lamaran_wajib_diisi(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f6_jumlah_lamaran' => '',
            'f7_jumlah_respons' => '',
            'f17a_jumlah_wawancara' => '',
        ]))->assertSessionHasErrors(['f6_jumlah_lamaran', 'f7_jumlah_respons', 'f17a_jumlah_wawancara']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_f10_kosong_ditolak(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f10_aktif' => '',
        ]))->assertSessionHasErrors(['f10_aktif']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_lainnya_instansi_wajib_tuliskan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f11_jenis_instansi' => '7',
            'f11_02' => '',
        ]))->assertSessionHasErrors(['f11_02']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_lainnya_cara_mencari_kerja_wajib_tuliskan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f415' => '1',
            'f416_tuliskan' => '',
        ]))->assertSessionHasErrors(['f416_tuliskan']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_lainnya_keaktifan_wajib_tuliskan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f10_aktif' => '5',
            'f10_lainnya' => '',
        ]))->assertSessionHasErrors(['f10_lainnya']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_lainnya_alasan_pekerjaan_wajib_tuliskan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f1613' => '1',
            'f1614' => '',
        ]))->assertSessionHasErrors(['f1614']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_lainnya_sumber_dana_wajib_tuliskan(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f12_01' => '7',
            'f12_02' => '',
        ]))->assertSessionHasErrors(['f12_02']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_bekerja_wajib_isi_posisi_dan_tingkat(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f5c_posisi' => '',
            'f5d_tingkat' => '',
        ]))->assertSessionHasErrors(['f5c_posisi', 'f5d_tingkat']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_wiraswasta_wajib_isi_posisi_dan_tingkat(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f8_status_saat_ini' => '3',
            'f5c_posisi' => '',
            'f5d_tingkat' => '',
        ]))->assertSessionHasErrors(['f5c_posisi', 'f5d_tingkat']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_lanjut_dengan_lokasi_kerja_wajib_isi_detail_tempat_kerja(): void
    {
        $this->post(route('kuesioner.store'), $this->payload([
            'f8_status_saat_ini' => '4',
            'f510_provinsi' => 'Prov. Sumatera Barat',
            'f510_kab_kota' => '',
            'f11_jenis_instansi' => '',
            'f5c_posisi' => '',
            'f5d_tingkat' => '',
            'f18a_sumber_biaya_studi' => 'Beasiswa',
            'f18b_perguruan_tinggi_studi' => 'Universitas Indonesia',
            'f18c_program_studi' => 'Magister Ilmu Komputer',
            'f18d_tanggal_masuk' => '2026-09-01',
        ]))->assertSessionHasErrors(['f510_kab_kota', 'f11_jenis_instansi', 'f5c_posisi', 'f5d_tingkat']);

        $this->assertDatabaseCount('kuesioner_alumnis', 0);
    }

    public function test_status_lanjut_belum_bekerja_tanpa_detail_kerja_tersimpan(): void
    {
        $data = $this->payload([
            'f8_status_saat_ini' => '4',
            'f510_provinsi' => 'Belum Bekerja',
            'f18a_sumber_biaya_studi' => 'Beasiswa',
            'f18b_perguruan_tinggi_studi' => 'Universitas Indonesia',
            'f18c_program_studi' => 'Magister Ilmu Komputer',
            'f18d_tanggal_masuk' => '2026-09-01',
        ]);

        foreach ([
            'f510_kab_kota', 'f11_jenis_instansi', 'f5d_tingkat', 'f14', 'f15',
            'comp_b_f1761_f1762', 'comp_b_f1763_f1764', 'comp_b_f1765_f1766',
            'comp_b_f1767_f1768', 'comp_b_f1769_f1770', 'comp_b_f1771_f1772',
            'comp_b_f1773_f1774',
        ] as $hapus) {
            unset($data[$hapus]);
        }

        $this->post(route('kuesioner.store'), $data)->assertSessionHas('success');

        $this->assertDatabaseHas('kuesioner_alumnis', [
            'no_mahasiswa' => '2210015111001',
            'f510_provinsi' => '0',
            'f14_erat_hubungan_studi' => null,
            'f15_tingkat_paling_tepat' => null,
            'f1701_B' => null,
        ]);
    }
}
