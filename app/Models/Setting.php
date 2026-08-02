<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    protected static $cache = null;

    public static function defaults(): array
    {
        return [
            // --- Kuesioner ---
            'kuesioner_judul' => 'KUESIONER TRACER STUDY UNIVERSITAS MAHAPUTRA MUHAMMAD YAMIN',
            'kuesioner_univ' => 'Universitas Mahaputra Muhammad Yamin',
            'kuesioner_subjudul' => 'Lembaga Pengembangan Karir dan Mahasiswa (LPKM)',
            'kuesioner_instruksi' => 'Bidang bertanda * wajib diisi. Silakan isi dengan jujur dan teliti.',
            'kuesioner_sukses' => 'Kuesioner Anda berhasil dikirim! Terima kasih atas partisipasinya.',

            'judul_identitas' => 'Identitas Alumni',
            'judul_status' => 'Jelaskan status Anda saat ini?',
            'judul_kerja6bulan' => 'Apakah anda telah mendapatkan pekerjaan <= 6 bulan / termasuk bekerja sebelum lulus ?',
            'judul_tempat_bekerja' => 'Detail Tempat Bekerja',
            'judul_studi_lanjut' => 'Riwayat Studi Lanjut & Pembiayaan Kuliah',
            'judul_keselarasan' => 'Keselarasan Bidang Studi dengan Pekerjaan',
            'judul_kompetensi' => 'Kompetensi dikuasai dan diperlukan saat bekerja',
            'judul_metode' => 'Penekanan Metode Pembelajaran',
            'judul_mulai_cari' => 'Kapan Anda Mulai Mencari Pekerjaan?',
            'judul_cara_cari' => 'Bagaimana cara anda mencari pekerjaan tersebut?',
            'judul_lamaran' => 'Proses Lamaran Pekerjaan',
            'judul_keaktifan' => 'Keaktifan Mencari Pekerjaan & Alasan Pekerjaan',

            // --- Dashboard ---
            'dashboard_judul' => 'Analitik Tracer Study UMMY Solok',
            'chart_kurva_fill' => '1',
            'chart_kurva_tension' => '0.35',
            'dashboard_aksen' => '#fbbf24',

            // --- Keamanan ---
            'kode_pemulihan' => '',

            // --- Bentuk & warna tiap grafik ---
            'chart_status_tipe' => 'pie',
            'chart_status_warna' => '#3b82f6',
            'chart_pendapatan_tipe' => 'doughnut',
            'chart_pendapatan_warna' => '#8b5cf6',
            'chart_perusahaan_tipe' => 'pie',
            'chart_perusahaan_warna' => '#3b82f6',
            'chart_dana_tipe' => 'pie',
            'chart_dana_warna' => '#3b82f6',
            'chart_lokasi_tipe' => 'bar',
            'chart_lokasi_warna' => '#14b8a6',
            'chart_lokasi_kota_tipe' => 'bar',
            'chart_lokasi_kota_warna' => '#5eead4',
            'chart_jabatan_tipe' => 'bar',
            'chart_jabatan_warna' => '#f87171',
            'chart_tingkat_tipe' => 'bar',
            'chart_tingkat_warna' => '#a3e635',
            'chart_kuliah_tipe' => 'bar',
            'chart_kuliah_warna' => '#c084fc',
            'chart_kompetensi_tipe' => 'radar',
            'chart_kompetensi_warna' => '#3b82f6',
            'chart_metode_tipe' => 'bar',
            'chart_metode_warna' => '#f43f5e',
            'chart_kurva_tipe' => 'line',
            'chart_kurva_warna' => '#10b981',
            'chart_cara_tipe' => 'doughnut',
            'chart_cara_warna' => '#ff0505',
            'chart_rasio_tipe' => 'bar',
            'chart_rasio_warna' => '#facc15',
            'chart_keaktifan_tipe' => 'pie',
            'chart_keaktifan_warna' => '#22c55e',
            'chart_alasan_tipe' => 'bar',
            'chart_alasan_warna' => '#fb923c',
            'chart_perguruan_tipe' => 'bar',
            'chart_perguruan_warna' => '#a78bfa',
            'chart_prodi_tipe' => 'bar',
            'chart_prodi_warna' => '#34d399',

            'judul_chart_status' => '💼 Status Kesibukan Alumni Saat Ini',
            'judul_chart_pendapatan' => '💰 Distribusi Pendapatan Per Bulan',
            'judul_chart_perusahaan' => '💼 Jenis Perusahaan Tempat Bekerja',
            'judul_chart_dana' => '💰 Sumber Dana Lanjut Kuliah',
            'judul_chart_jabatan' => '💼 Jenis Jabatan Tempat Bekerja',
            'judul_chart_tingkat' => '💼 Jenis Tingkat Tempat Kerja',
            'judul_chart_lokasi' => '📍 Sebaran Provinsi Wilayah Kerja',
            'judul_chart_lokasi_kota' => '📍 Sebaran Kab/Kota Wilayah Kerja',
            'judul_chart_kuliah' => '🎓 Destinasi Kampus Alumni Lanjut Studi',
            'judul_chart_kompetensi' => '🧠 Perbandingan Kompetensi: Dikuasai (A) vs Diperlukan Dunia Kerja (B)',
            'judul_chart_metode' => '🏫 Penekanan Metode Pembelajaran Saat Kuliah',
            'judul_chart_waktu' => '⏱️ Masa Tunggu Mendapat Pekerjaan (1-12 Bulan)',
            'judul_chart_cara' => '📣 Saluran/Metode Utama Mencari Pekerjaan',
            'judul_chart_rasio' => '🏢 Rata-rata Rasio Proses Lamaran Kerja Per Alumni',
            'judul_chart_keaktifan' => '🔥 Keaktifan Mencari Kerja',
            'judul_chart_alasan' => '⚠️ Alasan Mengambil Pekerjaan Yang Tidak Sesuai Bidang Pendidikan',
            'judul_chart_perguruan' => '🎓 Perguruan Tinggi Tujuan Studi Lanjut (f18b)',
            'judul_chart_prodi' => '🎓 Program Studi Tujuan Studi Lanjut (f18c)',

            // --- Nama & warna tiap irisan/seri (grafik multi-warna) ---
        ] + static::itemDefaults();
    }

    public static function itemDefaults(): array
    {
        $items = [
            'status' => [
                ['Bekerja', '#3b82f6'],
                ['Wiraswasta', '#10b981'],
                ['Lanjut Kuliah', '#f59e0b'],
                ['Cari Kerja', '#ef4444'],
                ['Belum Bekerja', '#94a3b8'],
            ],
            'pendapatan' => [
                ['< 2 Juta', '#475569'],
                ['2 - 5 Juta', '#8b5cf6'],
                ['> 5 Juta', '#ec4899'],
            ],
            'perusahaan' => [
                ['Instansi Pemerintah', '#3b82f6'],
                ['BUMN/BUMD', '#10b981'],
                ['Institusi', '#f59e0b'],
                ['Lembaga Swadaya', '#ef4444'],
                ['Swasta', '#94a3b8'],
                ['Wiraswasta', '#22d3ee'],
                ['Lainnya', '#e879f9'],
            ],
            'dana' => [
                ['Biaya Sendiri', '#3b82f6'],
                ['Beasiswa ADIK', '#10b981'],
                ['Beasiswa BIDIKMISI', '#f59e0b'],
                ['Beasiswa PPA', '#ef4444'],
                ['Beasiswa AFIRMASI', '#94a3b8'],
                ['Beasiswa Swasta', '#22d3ee'],
                ['Lainnya', '#e879f9'],
            ],
            'kurva' => [
                ['1-3 Bulan', '#10b981'],
                ['4-6 Bulan', '#3b82f6'],
                ['7-12 Bulan', '#f59e0b'],
                ['> 12 Bulan', '#ef4444'],
            ],
            'cara' => [
                ['Iklan Koran', '#ff0505'],
                ['Melamar Langsung', '#ff6004'],
                ['Bursa Kerja', '#f59e0b'],
                ['Internet', '#48ff00'],
                ['Dihubungi Perusahaan', '#07a70f'],
                ['Kemenakertrans', '#00ff95'],
                ['Agen', '#0093f5'],
                ['CDC Kampus', '#0206f3'],
                ['Kantor Kemanusiaan', '#4c00ff'],
                ['Kuliah', '#ff02ff'],
                ['Relasi', '#ff058f'],
                ['Bisnis Sendiri', '#fc003f'],
                ['Tempat Magang', '#353b42'],
                ['Kerja Saat Kuliah', '#f7f7f7'],
                ['Lainnya', '#94a3b8'],
            ],
            'keaktifan' => [
                ['Aktif', '#22c55e'],
                ['Tidak Aktif', '#64748b'],
            ],
            'kompetensi' => [
                ['Kompetensi Dikuasai (A)', '#3b82f6'],
                ['Diperlukan Dunia Kerja (B)', '#f59e0b'],
            ],
            'metode' => [
                ['Jumlah Responden Memilih "Sangat Besar"', '#f43f5e'],
                ['Jumlah Responden Memilih "Besar"', '#ffa601'],
                ['Jumlah Responden Memilih "Cukup Besar"', '#d1ff02'],
                ['Jumlah Responden Memilih "Kurang"', '#09ff00'],
                ['Jumlah Responden Memilih "Tidak Sama Sekali"', '#04ffde'],
            ],
        ];

        $out = [];
        foreach ($items as $slug => $list) {
            foreach ($list as $i => [$nama, $warna]) {
                $out["chart_{$slug}_item_{$i}_nama"] = $nama;
                $out["chart_{$slug}_item_{$i}_warna"] = $warna;
            }
        }
        return $out;
    }

    public static function items(string $slug): array
    {
        $result = [];
        $prefix = "chart_{$slug}_item_";
        foreach (static::itemDefaults() as $key => $default) {
            if (str_starts_with($key, $prefix) && str_ends_with($key, '_nama')) {
                $i = substr($key, strlen($prefix), -strlen('_nama'));
                $result[(int) $i] = [
                    'nama' => static::get("chart_{$slug}_item_{$i}_nama", $default),
                    'warna' => static::get("chart_{$slug}_item_{$i}_warna"),
                ];
            }
        }
        ksort($result);
        return array_values($result);
    }

    public static function allCached(): array
    {
        if (static::$cache === null) {
            static::$cache = static::pluck('value', 'key')->toArray();
        }
        return static::$cache;
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allCached();
        return $all[$key] ?? (static::defaults()[$key] ?? $default);
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null;
    }

    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();
        static::$cache = null;
    }

    public static function syncDefaults(): void
    {
        foreach (static::defaults() as $key => $value) {
            static::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        static::$cache = null;
    }
}
