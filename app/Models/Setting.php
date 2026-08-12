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
            'kuesioner_terbuka' => '1',
            'kuesioner_pesan_tutup' => 'Kuesioner ditutup. Anda akan diberitahu ketika dibuka kembali.',
            'kode_pt_default' => '101004',
            'kuesioner_kontak' => '',
            'kuesioner_footer' => '© '.date('Y').' Tracer Study LPKM UMMY Solok',
            'kuesioner_email_domain' => 'gmail.com',
            'kuesioner_tahun_mulai' => '2020',
            'kuesioner_teks_tombol' => 'SIMPAN DAN KIRIM DATA KUESIONER',
            'kuesioner_judul_browser' => 'Tracer Study - LPKM UMMY',
            'kuesioner_ikon' => '🎓',
            'kuesioner_pesan_tidak_terdaftar' => '❌ Maaf, No. Mahasiswa (NIM), NIK, Nama, Tahun Lulus, Kode Prodi Anda Salah Huruf/Angka atau tidak terdaftar sebagai data acuan alumni kelulusan Periksa Semua Pertanyaan Kembali. Ulangi Pengisian atau Hubungi Admin Tracer Study',
            'kuesioner_warna_aksen' => '#fbbf24',
            'kuesioner_warna_latar' => '#0f172a',
            'kuesioner_warna_latar2' => '#1e1b4b',
            'kuesioner_warna_univ' => '#7dd3fc',
            'kuesioner_warna_subjudul' => '#94a3b8',
            'kuesioner_warna_instruksi' => '#6b7280',
            'kuesioner_warna_label' => '#94a3b8',
            'kuesioner_warna_pilihan' => '#d1d5db',
            'kuesioner_warna_judulbagian' => '#fbbf24',
            'kuesioner_warna_tombol' => '#0f172a',
            'kuesioner_warna_footer' => '#4b5563',
            'kuesioner_warna_sukses' => '#6ee7b7',
            'kuesioner_warna_error' => '#fda4af',

            // --- Halaman Login ---
            'login_judul' => 'Login Admin',
            'login_subjudul' => 'Tracer Study LPKM UMMY Solok',
            'login_ikon' => '🔐',
            'login_label_email' => 'Email',
            'login_label_password' => 'Kata Sandi',
            'login_ingat_saya' => 'Ingat saya',
            'login_teks_tombol' => 'Masuk',
            'login_link_lupa' => '🔑 Lupa Password Super Admin?',
            'login_link_riwayat' => '📜 Riwayat Login & Password',
            'login_link_kembali' => '⬅️ Kembali ke Halaman Login',
            'login_link_kuesioner' => '← Kembali ke Form Kuesioner',
            'login_judul_browser' => 'Login Admin - Tracer Study',
            'login_warna_latar' => '#0f172a',
            'login_warna_latar2' => '#1e1b4b',
            'login_warna_aksen' => '#fbbf24',

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

            // --- Label bagian identitas kuesioner ---
            'label_nim' => 'Nomor Induk Mahasiswa (NIM)',
            'label_kode_pt' => 'Kode Perguruan Tinggi',
            'label_tahun_lulus' => 'Tahun Lulus',
            'label_kode_prodi' => 'Kode Program Studi',
            'label_nama' => 'Nama Lengkap',
            'label_no_hp' => 'Nomor Telepon / HP',
            'label_email' => 'Alamat Email',
            'label_nik' => 'NIK (Nomor Induk Kependudukan)',
            'label_npwp' => 'NPWP',
            'placeholder_tahun_lulus' => '-- Pilih Tahun Lulus --',
            'placeholder_prodi' => '-- Pilih Prodi --',

            // --- Daftar program studi (format: kode|nama per baris) ---
            'prodi_list' => implode("\n", [
                '54211|Agroteknologi',
                '62201|Akuntansi',
                '74201|Ilmu Hukum',
                '61201|Manajemen',
                '88201|Pendidikan Bahasa Indonesia',
                '88203|Pendidikan Bahasa Inggris',
                '84205|Pendidikan Biologi',
                '87203|Pendidikan Ekonomi',
                '54231|Peternakan',
                '84202|Pendidikan Matematika',
                '57401|Manajemen Informatika',
                '54201|Agribisnis',
            ]),

            // --- Label & pilihan pertanyaan kuesioner ---
            'label_kerja_ya' => 'Ya',
            'label_kerja_tidak' => 'Tidak',
            'label_f502_bulan_ya' => 'Dalam berapa bulan anda mendapatkan pekerjaan? (bagi yang sudah bekerja)',
            'label_f505_pendapatan' => 'Berapa rata-rata pendapatan per bulan? (take home pay)',
            'label_f502_bulan_tidak' => 'Di isi jika lebih dari 6 bulan belum mendapatkan pekerjaan',
            'placeholder_bulan' => '-- Pilih Bulan --',
            'label_f510_lokasi' => 'Dimana lokasi tempat Anda bekerja?',
            'placeholder_provinsi' => '-- Pilih Provinsi --',
            'placeholder_kab_kota' => '-- Pilih Kabupaten / Kota --',
            'label_provinsi_belum_bekerja' => 'Belum Bekerja',
            'label_f11_jenis' => 'Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?',
            'label_f5b' => 'Nama perusahaan/kantor',
            'label_f5c' => 'Bila berwiraswasta, posisi/jabatan',
            'label_f5d' => 'Tingkat tempat kerja anda',
            'placeholder_posisi' => 'Pilih Posisi',
            'placeholder_tingkat' => 'Pilih Tingkatan',
            'label_f18_header' => 'Pertanyaan Studi Lanjut',
            'placeholder_sumber_biaya' => '-- Pilih sumber biaya --',
            'label_f18b_placeholder' => 'Perguruan Tinggi',
            'label_f18c_placeholder' => 'Program Studi',
            'label_f18d' => 'Tanggal Masuk',
            'label_f12_01' => 'Sebutkan sumberdana dalam pembiayaan kuliah?',
            'label_f14' => 'Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?',
            'label_f15' => 'Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?',
            'label_kompetensi_aspek' => 'Aspek Kompetensi',
            'label_kompetensi_a' => 'A: Kompetensi Saat Lulus',
            'label_kompetensi_b' => 'B: Kebutuhan di Pekerjaan',
            'label_metode_instruksi' => 'Menurut anda seberapa besar penekanan pada metode pembelajaran di bawah ini dilaksanakan di program studi anda?',
            'label_mulai_cari_note' => '(Tidak termasuk pekerjaan sambilan)',
            'label_f301_1' => 'bulan sebelum lulus',
            'label_f301_2' => 'bulan sesudah lulus',
            'label_f301_3' => 'Saya tidak mencari kerja',
            'label_f301_3_note' => '(Langsung ke pertanyaan selanjutnya)',
            'label_cara_cari_note' => '(Jawaban bisa lebih dari satu)',
            'label_f6' => 'Berapa perusahaan/instansi yang sudah anda lamar sebelum memperoleh pekerjaan pertama?',
            'label_f7' => 'Berapa banyak perusahaan/instansi yang merespons lamaran anda selama ini?',
            'label_f17a' => 'Berapa banyak perusahaan/instansi yang mengundang anda untuk wawancara?',
            'placeholder_jumlah' => '... perusahaan',
            'label_f10' => 'Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?',
            'label_f10_note' => '(pilih 1 jawaban)',
            'label_f16' => 'Jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda, mengapa anda mengambilnya?',
            'label_f16_note' => '(Jawaban bisa lebih dari satu)',
            'label_lainnya' => 'Lainnya:',
            'label_lainnya_tuliskan' => 'Lainnya, tuliskan:',
            'label_tuliskan' => 'Tuliskan:',
            'label_kontak_ikon' => '📞',
            'label_hubungi_admin' => '📞 Hubungi admin:',

            // --- Pilihan jawaban kuesioner (format: nilai|label per baris) ---
            'opsi_f8_status' => implode("\n", [
                '1|Bekerja (full time/part time)',
                '3|Wiraswasta',
                '4|Melanjutkan Pendidikan',
                '5|Tidak Kerja tetapi sedang mencari kerja',
                '2|Belum memungkinkan bekerja',
            ]),
            'opsi_f12_dana' => implode("\n", [
                '1|Biaya Sendiri / Keluarga',
                '2|Beasiswa ADIK',
                '3|Beasiswa BIDIKMISI',
                '4|Beasiswa PPA',
                '5|Beasiswa AFIRMASI',
                '6|Beasiswa Perusahaan/Swasta',
                '7|Lainnya',
            ]),
            'opsi_f18a_biaya' => implode("\n", [
                'Biaya Sendiri|Biaya Sendiri',
                'Beasiswa|Beasiswa',
            ]),
            'opsi_f11_instansi' => implode("\n", [
                '1|Instansi pemerintah',
                '2|BUMN/BUMD',
                '3|Institusi/Organisasi Multilateral',
                '4|Organisasi non-profit/Lembaga Swadaya Masyarakat',
                '5|Perusahaan swasta',
                '6|Wiraswasta/Perusahaan sendiri',
                '7|Lainnya',
            ]),
            'opsi_f14' => implode("\n", [
                '1|Sangat Erat',
                '2|Erat',
                '3|Cukup Erat',
                '4|Kurang Erat',
                '5|Tidak Sama Sekali',
            ]),
            'opsi_f15' => implode("\n", [
                '1|Setingkat Lebih Tinggi',
                '2|Tingkat yang Sama',
                '3|Setingkat Lebih Rendah',
                '4|Tidak Perlu Pendidikan Tinggi',
            ]),
            'opsi_f10_aktif' => implode("\n", [
                '1|Tidak',
                '2|Tidak, tapi saya sedang menunggu hasil lamaran kerja',
                '3|Ya, saya akan mulai bekerja dalam 2 minggu ke depan',
                '4|Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan',
                '5|Lainnya',
            ]),
            'opsi_f17_kompetensi' => implode("\n", [
                'Etika',
                'Keahlian berdasarkan bidang ilmu',
                'Bahasa Inggris',
                'Penggunaan Teknologi Informasi',
                'Komunikasi',
                'Kerja sama tim',
                'Pengembangan Diri',
            ]),
            'opsi_f21_metode' => implode("\n", [
                'Perkuliahan',
                'Demonstrasi',
                'Partisipasi dalam proyek riset',
                'Magang',
                'Praktikum',
                'Kerja Lapangan',
                'Diskusi',
            ]),
            'opsi_skala_kompetensi' => implode("\n", [
                '1|1: Sangat Rendah',
                '2|2: Rendah',
                '3|3: Cukup Tinggi',
                '4|4: Tinggi',
                '5|5: Sangat Tinggi',
            ]),
            'opsi_metode_penekanan' => implode("\n", [
                '1|Sangat Besar',
                '2|Besar',
                '3|Cukup',
                '4|Kurang',
                '5|Tidak Sama Sekali',
            ]),
            'opsi_f5c_posisi' => implode("\n", [
                'Founder|Founder',
                'Co-Founder|Co-Founder',
                'Staff|Staff',
                'Freelance|Freelance / Kerja Lepas',
            ]),
            'opsi_f5d_tingkat' => implode("\n", [
                'Lokal|Lokal/Wilayah/wiraswasta tidak berbadan hukum',
                'Nasional|Nasional/Wiraswasta berbadan hukum',
                'Internasional|Multinasional/internasional',
            ]),
            'opsi_f401_cara' => implode("\n", [
                'f401|Melalui iklan di koran/majalah, brosur',
                'f402|Melamar ke perusahaan tanpa mengetahui lowongan yang ada',
                'f403|Pergi ke bursa/pameran kerja',
                'f404|Mencari lewat internet/iklan online/milis',
                'f405|Dihubungi oleh perusahaan',
                'f406|Menghubungi Kemenakertrans',
                'f407|Menghubungi agen tenaga kerja komersial/swasta',
                'f408|Memperoleh informasi dari pusat/kantor pengembangan karir fakultas/universitas',
                'f409|Menghubungi kantor kemahasiswaan/hubungan alumni',
                'f410|Membangun jejaring (network) sejak masih kuliah',
                'f411|Melalui relasi (misalnya dosen, orang tua, saudara, teman, dll.)',
                'f412|Membangun bisnis sendiri',
                'f413|Melalui penempatan kerja atau magang',
                'f414|Bekerja di tempat yang sama dengan tempat kerja semasa kuliah',
                'f415|Lainnya',
            ]),
            'opsi_f1601_alasan' => implode("\n", [
                'f1601|Pertanyaan tidak sesuai; pekerjaan saya sekarang sudah sesuai dengan pendidikan saya.',
                'f1602|Saya belum mendapatkan pekerjaan yang lebih sesuai.',
                'f1603|Di pekerjaan ini saya memperoleh prospek karir yang baik.',
                'f1604|Saya lebih suka bekerja di area pekerjaan yang tidak ada hubungannya dengan pendidikan saya.',
                'f1605|Saya dipromosikan ke posisi yang kurang berhubungan dengan pendidikan saya dibanding posisi sebelumnya.',
                'f1606|Saya dapat memeroleh pendapatan yang lebih tinggi di pekerjaan ini.',
                'f1607|Pekerjaan saya saat ini lebih aman/terjamin/secure',
                'f1608|Pekerjaan saya saat ini lebih menarik',
                'f1609|Pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel, dll.',
                'f1610|Pekerjaan saya saat ini lokasinya lebih dekat dari rumah saya.',
                'f1611|Pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga saya.',
                'f1612|Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya.',
                'f1613|Lainnya',
            ]),

            // --- Pesan error validasi kuesioner ---
            'pesan_email_required' => 'Alamat email wajib diisi.',
            'pesan_email_format' => 'Format alamat email tidak valid.',
            'pesan_email_domain' => 'Email harus menggunakan @',
            'pesan_nik_digits' => 'NIK harus berjumlah tepat 16 digit angka.',
            'pesan_no_hp_regex' => 'Nomor HP harus diawali 08 dan minimal 10 digit.',
            'pesan_bulan_ya' => 'Isi dalam berapa bulan Anda mendapatkan pekerjaan.',
            'pesan_pendapatan' => 'Isi rata-rata pendapatan per bulan.',
            'pesan_bulan_tidak' => 'Isi berapa bulan Anda belum mendapatkan pekerjaan.',
            'pesan_provinsi' => 'Pilih lokasi provinsi tempat Anda bekerja.',
            'pesan_kab_kota' => 'Pilih kabupaten/kota tempat Anda bekerja.',
            'pesan_instansi' => 'Pilih jenis perusahaan/instansi tempat Anda bekerja.',
            'pesan_f5c' => 'Pilih posisi/jabatan bila berwiraswasta.',
            'pesan_f5d' => 'Pilih tingkat tempat kerja Anda.',
            'pesan_sumber_biaya' => 'Pilih sumber biaya studi lanjut.',
            'pesan_perguruan_tinggi' => 'Isi perguruan tinggi tujuan studi lanjut.',
            'pesan_program_studi' => 'Isi program studi tujuan studi lanjut.',
            'pesan_tanggal_masuk' => 'Isi tanggal masuk studi lanjut.',
            'pesan_sumber_dana' => 'Pilih sumber dana pembiayaan kuliah.',
            'pesan_f14' => 'Pilih erat hubungan bidang studi dengan pekerjaan.',
            'pesan_f15' => 'Pilih tingkat pendidikan yang paling tepat.',
            'pesan_f302' => 'Isi berapa bulan sebelum lulus Anda mulai mencari kerja.',
            'pesan_f303' => 'Isi berapa bulan setelah lulus Anda mulai mencari kerja.',
            'pesan_f6' => 'Isi berapa perusahaan/instansi yang sudah Anda lamar.',
            'pesan_f7' => 'Isi berapa perusahaan/instansi yang merespons lamaran Anda.',
            'pesan_f17a' => 'Isi berapa perusahaan/instansi yang mengundang Anda wawancara.',
            'pesan_f11_lainnya' => 'Tuliskan jenis perusahaan/instansi lainnya.',
            'pesan_f416_lainnya' => 'Tuliskan cara mencari kerja lainnya.',
            'pesan_f10_lainnya' => 'Tuliskan jawaban lainnya.',
            'pesan_f1614_lainnya' => 'Tuliskan alasan lainnya.',
            'pesan_f12_lainnya' => 'Tuliskan sumber dana kuliah lainnya.',

            // --- Dashboard ---
            'dashboard_judul' => 'Analitik Tracer Study UMMY Solok',
            'dashboard_judul_browser' => 'Dashboard Analitik Admin - Tracer Study',
            'dashboard_ikon' => '📊',
            'dashboard_nav_pengaturan' => '⚙️ Pengaturan',
            'dashboard_nav_alumni' => '+ Kelola Data Alumni',
            'dashboard_nav_kuesioner' => 'Lihat Form Kuesioner',
            'dashboard_nav_akun' => '👥 Akun Admin',
            'dashboard_nav_ganti_password' => '🔑 Ganti Password',
            'dashboard_nav_keluar' => 'Keluar',
            'dashboard_filter_judul' => '🔍 Filter Analisis Responden',
            'dashboard_filter_tahun' => '1. Pilih Tahun Lulus',
            'dashboard_filter_semua_tahun' => '-- Semua Tahun Lulus --',
            'dashboard_filter_prodi' => '2. Pilihan Program Studi',
            'dashboard_filter_semua_prodi' => '-- Semua Program Studi --',
            'dashboard_tombol_kurva' => '⚡ Cek & Buka Kurva Analitik',
            'dashboard_kosong_ikon' => '📈',
            'dashboard_kosong_judul' => 'Silakan Gunakan Filter di Atas',
            'dashboard_kosong_teks' => 'Pilih Tahun Lulus beserta Program Studi terlebih dahulu, kemudian klik tombol "Cek & Buka Kurva Analitik" untuk melihat grafik penelusuran alumni.',
            'dashboard_stat_menampilkan' => 'Menampilkan Analisis:',
            'dashboard_stat_semua_tahun' => 'Semua Tahun',
            'dashboard_stat_prodi' => 'Prodi:',
            'dashboard_stat_semua_prodi' => 'Semua Prodi',
            'dashboard_stat_total' => 'Total Responden:',
            'dashboard_stat_total_kartu' => 'Total Responden',
            'dashboard_stat_alumni' => 'Alumni',
            'dashboard_stat_bekerja' => 'Bekerja',
            'dashboard_stat_mencari' => 'Aktif Mencari Kerja',
            'dashboard_stat_lanjut' => 'Lanjut Kuliah',
            'dashboard_ikon_total' => '🧑‍🎓',
            'dashboard_ikon_bekerja' => '💼',
            'dashboard_ikon_mencari' => '🔥',
            'dashboard_ikon_lanjut' => '🏫',
            'dashboard_unduh_excel' => 'Unduh Excel',
            'dashboard_unduh_tahun' => 'Tahun',
            'dashboard_unduh_semua' => '(Semua Tahun)',
            'chart_kurva_fill' => '1',
            'chart_kurva_tension' => '0.35',
            'dashboard_aksen' => '#fbbf24',
            'dashboard_footer' => '© '.date('Y').' Tracer Study LPKM UMMY Solok — Dashboard Analitik Alumni',
            'dashboard_warna_latar' => '#0f172a',
            'dashboard_warna_latar2' => '#1e1b4b',

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
            'chart_sumber_biaya_tipe' => 'bar',
            'chart_sumber_biaya_warna' => '#a78bfa',
            'chart_prodi_tipe' => 'bar',
            'chart_prodi_warna' => '#34d399',

            // --- Tampilkan/sembunyikan tiap grafik di dashboard (1=tampil, 0=sembunyi) ---
            'chart_status_tampil' => '1',
            'chart_pendapatan_tampil' => '1',
            'chart_perusahaan_tampil' => '1',
            'chart_dana_tampil' => '1',
            'chart_lokasi_tampil' => '1',
            'chart_lokasi_kota_tampil' => '1',
            'chart_jabatan_tampil' => '1',
            'chart_tingkat_tampil' => '1',
            'chart_kuliah_tampil' => '1',
            'chart_kompetensi_tampil' => '1',
            'chart_metode_tampil' => '1',
            'chart_kurva_tampil' => '1',
            'chart_cara_tampil' => '1',
            'chart_rasio_tampil' => '1',
            'chart_keaktifan_tampil' => '1',
            'chart_alasan_tampil' => '1',
            'chart_sumber_biaya_tampil' => '1',
            'chart_prodi_tampil' => '1',

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
            'judul_chart_sumber_biaya' => '💰 Sumber Biaya Lanjut Kuliah',
            'judul_chart_prodi' => '🎓 Program Studi Tujuan Studi Lanjut (f18c)',

            // --- Nama & warna tiap irisan/seri (grafik multi-warna) ---
        ] + static::itemDefaults();
    }

    public static function itemDefaults(): array
    {
        $items = [
            'pendapatan' => [
                ['< 2 Juta', '#475569'],
                ['2 - 5 Juta', '#8b5cf6'],
                ['> 5 Juta', '#ec4899'],
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

    public static function optionList(string $key, string $default): array
    {
        $raw = static::get($key, $default);
        $out = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = explode('|', $line, 2);
            $value = trim($parts[0]);
            $label = trim($parts[1] ?? $value);
            $out[$value] = $label;
        }

        return $out;
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

    public static function flushCache(): void
    {
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
