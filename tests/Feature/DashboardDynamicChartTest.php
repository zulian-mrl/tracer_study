<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardDynamicChartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('kuesioner_alumnis')->insert([
            'no_mahasiswa' => '2210015111001',
            'kode_PT' => '101004',
            'tahun_lulus' => '2026',
            'kode_prodi' => '54211',
            'nama' => 'Budi Setiawan',
            'no_hp' => '081234567890',
            'email' => 'budi@gmail.com',
            'nik' => '1234567890123456',
            'f8_status_saat_ini' => '1',
            'f504_mendapat_pekerjaan_6_bulan' => '1',
            'f301_kapan_mencari_pekerjaan' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_opsi_status_baru_di_pengaturan_muncul_di_grafik(): void
    {
        Setting::set('opsi_f8_status', "1|Bekerja Penuh\n2|Mengurus Rumah Tangga");
        DB::table('kuesioner_alumnis')->where('no_mahasiswa', '2210015111001')->update(['f8_status_saat_ini' => '2']);

        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get(route('kuesioner.dashboard', ['tahun_lulus' => '2026']))
            ->assertOk()
            ->assertSee('Mengurus Rumah Tangga', false)
            ->assertDontSee('Bekerja (full time/part time)', false);
    }

    public function test_nilai_tak_dikenal_masuk_bucket_lainnya(): void
    {
        Setting::set('opsi_f8_status', "1|Bekerja Penuh\n2|Mengurus Rumah Tangga");
        DB::table('kuesioner_alumnis')->where('no_mahasiswa', '2210015111001')->update(['f8_status_saat_ini' => '99']);

        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get(route('kuesioner.dashboard', ['tahun_lulus' => '2026']))
            ->assertOk()
            ->assertSee('\u0022Lainnya\u0022:[\u0022Budi Setiawan\u0022]', false);
    }

    public function test_kode_lama_f11_tetap_cocok_setelah_label_diubah(): void
    {
        Setting::set('opsi_f11_instansi', "1|Instansi pemerintah\n2|Perusahaan Negara\n3|Organisasi Dunia\n4|LSM\n5|Swasta\n6|Wiraswasta Sendiri\n7|Lainnya");
        DB::table('kuesioner_alumnis')->where('no_mahasiswa', '2210015111001')->update(['f11_jenis_instansi' => '6']);

        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get(route('kuesioner.dashboard', ['tahun_lulus' => '2026']))
            ->assertOk()
            ->assertSee('Perusahaan Negara', false)
            ->assertDontSee('BUMN/BUMD', false);
    }

    public function test_nilai_opsi_baru_f11_langsung_dipakai(): void
    {
        Setting::set('opsi_f11_instansi', "1|Instansi pemerintah\n2|BUMN/BUMD\n3|Institusi\n4|LSM\n5|Swasta\n6|Wiraswasta\n7|Lainnya\n8|Pegawai Negeri");
        DB::table('kuesioner_alumnis')->where('no_mahasiswa', '2210015111001')->update(['f11_jenis_instansi' => '8']);

        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get(route('kuesioner.dashboard', ['tahun_lulus' => '2026']))
            ->assertOk()
            ->assertSee('\u0022Pegawai Negeri\u0022:[\u0022Budi Setiawan\u0022]', false);
    }

    public function test_kartu_bekerja_termasuk_wiraswasta(): void
    {
        DB::table('kuesioner_alumnis')->insert([
            'no_mahasiswa' => '2210015111002',
            'kode_PT' => '101004',
            'tahun_lulus' => '2026',
            'kode_prodi' => '54211',
            'nama' => 'Siti Aminah',
            'no_hp' => '081298765432',
            'email' => 'siti@gmail.com',
            'nik' => '6543210987654321',
            'f8_status_saat_ini' => '3',
            'f504_mendapat_pekerjaan_6_bulan' => '1',
            'f301_kapan_mencari_pekerjaan' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = User::factory()->create(['is_super' => true]);

        $response = $this->actingAs($admin)
            ->get(route('kuesioner.dashboard', ['tahun_lulus' => '2026']))
            ->assertOk();

        $this->assertSame(2, (int) $response->viewData('kartuBekerja'));
        $this->assertSame(['Budi Setiawan', 'Siti Aminah'], $response->viewData('daftarNama')['bekerja']);
    }

    public function test_daftar_prodi_dashboard_mengikuti_pengaturan(): void
    {
        Setting::set('prodi_list', "54211|Agroteknologi\n99999|Prodi Baru Test");

        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get(route('kuesioner.dashboard'))
            ->assertOk()
            ->assertSee('[99999] Prodi Baru Test', false)
            ->assertDontSee('[61201] Manajemen', false);
    }
}
