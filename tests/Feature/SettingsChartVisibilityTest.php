<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsChartVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengaturan_menyimpan_toggle_tampil_grafik(): void
    {
        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->post('/admin/pengaturan', ['chart_status_tampil' => '0'])
            ->assertRedirect(route('pengaturan.index'));

        $this->assertSame('0', Setting::get('chart_status_tampil', '1'));
    }

    public function test_dashboard_menampilkan_grafik_secara_default(): void
    {
        $admin = User::factory()->create(['is_super' => true]);

        $this->actingAs($admin)
            ->get('/dashboard-kurva?tahun_lulus=2026')
            ->assertOk()
            ->assertSee('<canvas id="chartStatusKerja">', false)
            ->assertSee('<canvas id="chartWaktuCariKerja">', false);
    }

    public function test_dashboard_menyembunyikan_grafik_yang_dimatikan(): void
    {
        $admin = User::factory()->create(['is_super' => true]);

        Setting::set('chart_status_tampil', '0');

        $this->actingAs($admin)
            ->get('/dashboard-kurva?tahun_lulus=2026')
            ->assertOk()
            ->assertDontSee('<canvas id="chartStatusKerja">', false)
            ->assertSee('<canvas id="chartPendapatan">', false);
    }
}
