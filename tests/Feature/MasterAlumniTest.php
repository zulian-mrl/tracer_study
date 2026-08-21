<?php

namespace Tests\Feature;

use App\Models\MasterAlumni;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterAlumniTest extends TestCase
{
    use RefreshDatabase;

    private function buatSuperAdmin(): User
    {
        return User::factory()->create(['is_super' => true]);
    }

    private function buatAlumni(array $data): MasterAlumni
    {
        return MasterAlumni::create(array_merge([
            'kode_prodi' => '57401',
            'tahun_lulus' => '2026',
        ], $data));
    }

    public function test_index_menampilkan_data_dan_pencarian(): void
    {
        $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101']);
        $this->buatAlumni(['no_mahasiswa' => '231000000000002', 'nama' => 'Budi Santoso', 'nik' => '1372010101010102']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->get('/admin/master-alumni')
            ->assertOk()
            ->assertSee('Rian Saputra')
            ->assertSee('Budi Santoso');

        $this->actingAs($admin)
            ->get('/admin/master-alumni?q=Rian')
            ->assertOk()
            ->assertSee('Rian Saputra')
            ->assertDontSee('Budi Santoso');
    }

    public function test_index_bisa_difilter_sesuai_tahun_lulus(): void
    {
        $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101', 'tahun_lulus' => '2026']);
        $this->buatAlumni(['no_mahasiswa' => '201000474201012', 'nama' => 'Zulprianto', 'nik' => '1303080107000034', 'tahun_lulus' => '2024']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->get('/admin/master-alumni?tahun_lulus=2026')
            ->assertOk()
            ->assertSee('Rian Saputra')
            ->assertDontSee('Zulprianto');

        $this->actingAs($admin)
            ->get('/admin/master-alumni?tahun_lulus=2024')
            ->assertOk()
            ->assertSee('Zulprianto')
            ->assertDontSee('Rian Saputra');
    }

    public function test_index_bisa_difilter_sesuai_kode_prodi(): void
    {
        $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101', 'kode_prodi' => '57401']);
        $this->buatAlumni(['no_mahasiswa' => '211000488203001', 'nama' => 'Ulzi Jevrianto', 'nik' => '1302060909990005', 'kode_prodi' => '88203']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->get('/admin/master-alumni?kode_prodi=57401')
            ->assertOk()
            ->assertSee('Rian Saputra')
            ->assertDontSee('Ulzi Jevrianto');

        $this->actingAs($admin)
            ->get('/admin/master-alumni?kode_prodi=88203')
            ->assertOk()
            ->assertSee('Ulzi Jevrianto')
            ->assertDontSee('Rian Saputra');
    }

    public function test_index_bisa_menggabungkan_filter_prodi_dan_tahun(): void
    {
        $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101', 'kode_prodi' => '57401', 'tahun_lulus' => '2026']);
        $this->buatAlumni(['no_mahasiswa' => '231000000000003', 'nama' => 'Rafly Rahmad', 'nik' => '1372010101010103', 'kode_prodi' => '57401', 'tahun_lulus' => '2025']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->get('/admin/master-alumni?kode_prodi=57401&tahun_lulus=2026')
            ->assertOk()
            ->assertSee('Rian Saputra')
            ->assertDontSee('Rafly Rahmad');
    }

    public function test_update_berhasil_mengubah_data(): void
    {
        $alumni = $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->post('/admin/master-alumni/231000000000001/update', [
                'nama' => 'Rian Saputra Wijaya',
                'nik' => '1372010101010109',
                'kode_prodi' => '57401',
                'tahun_lulus' => '2026',
            ])
            ->assertRedirect(route('master.index'));

        $alumni->refresh();

        $this->assertSame('Rian Saputra Wijaya', $alumni->nama);
        $this->assertSame('1372010101010109', $alumni->nik);
    }

    public function test_update_menolak_nik_yang_sudah_dipakai_alumni_lain(): void
    {
        $this->buatAlumni(['no_mahasiswa' => '231000000000002', 'nama' => 'Budi Santoso', 'nik' => '1372010101010102']);
        $alumni = $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->post('/admin/master-alumni/231000000000001/update', [
                'nama' => 'Rian Saputra',
                'nik' => '1372010101010102',
                'kode_prodi' => '57401',
                'tahun_lulus' => '2026',
            ])
            ->assertSessionHasErrors('nik');

        $alumni->refresh();

        $this->assertSame('1372010101010101', $alumni->nik);
    }

    public function test_hapus_menghapus_data_master(): void
    {
        $alumni = $this->buatAlumni(['no_mahasiswa' => '231000000000001', 'nama' => 'Rian Saputra', 'nik' => '1372010101010101']);

        $admin = $this->buatSuperAdmin();

        $this->actingAs($admin)
            ->post('/admin/master-alumni/231000000000001/hapus')
            ->assertRedirect(route('master.index'));

        $this->assertNull(MasterAlumni::find($alumni->no_mahasiswa));
    }

    public function test_admin_biasa_tidak_bisa_membuka_halaman(): void
    {
        $admin = User::factory()->create(['is_super' => false]);

        $this->actingAs($admin)
            ->get('/admin/master-alumni')
            ->assertForbidden();
    }
}
