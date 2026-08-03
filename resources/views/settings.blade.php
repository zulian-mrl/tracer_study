<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Tracer Study</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            background-attachment: fixed;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 15% 20%, rgba(251, 191, 36, 0.14), transparent 42%),
                radial-gradient(circle at 85% 8%, rgba(56, 189, 248, 0.16), transparent 42%),
                radial-gradient(circle at 50% 92%, rgba(168, 85, 247, 0.13), transparent 48%);
        }
        .glass {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.72));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .grad-text {
            background: linear-gradient(90deg, #fbbf24, #f472b6, #38bdf8, #fbbf24);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shine 6s linear infinite;
        }
        @keyframes shine { to { background-position: 300% 0; } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .5s ease both; }

        .inp {
            width: 100%;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgb(71 85 105);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            color: #fff;
            outline: none;
            color-scheme: dark;
            transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease;
        }
        .inp:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.22), 0 10px 20px -10px rgba(0, 0, 0, 0.6);
            transform: translateY(-1px);
        }
        .inp::placeholder { color: #64748b; }
        select.inp option { background: #1e293b; color: #fff; }
        input[type="color"].inp { height: 42px; padding: 4px; cursor: pointer; }

        .sec-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fbbf24;
            border-left: 4px solid #fbbf24;
            padding-left: 0.75rem;
            margin-bottom: 1rem;
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">

    <nav class="sticky top-0 z-40 border-b border-white/10 bg-slate-900/70 backdrop-blur-lg shadow-2xl">
        <div class="max-w-5xl mx-auto p-4 flex justify-between items-center">
            <h1 class="text-xl font-extrabold tracking-wider grad-text uppercase">⚙️ Pengaturan Aplikasi</h1>
            <div class="flex items-center space-x-2">
                <a href="{{ route('kuesioner.dashboard') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">← Dashboard</a>
                <button type="button"
                        onclick="bukaGantiPassword()"
                        class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">
                    🔑 Ganti Password
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-600 hover:to-rose-500 px-4 py-2 rounded-lg border border-rose-500/50 shadow-lg">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="fade-up bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 px-4 py-3 rounded-xl shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="glass rounded-2xl p-5 text-sm text-gray-400 shadow-xl fade-up">
            Ubah isi kuesioner dan tampilan dashboard di sini, lalu klik <span class="text-amber-400 font-semibold">Simpan Pengaturan</span> di bagian bawah.
            Perubahan langsung diterapkan tanpa perlu mengubah kodingan.
        </div>

        <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- ================= ISI KUESIONER ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">📝 Isi Kuesioner</h2>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Teks Header</div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Utama Kuesioner</label>
                            <input type="text" name="kuesioner_judul" value="{{ old('kuesioner_judul', $settings['kuesioner_judul'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nama Universitas</label>
                            <input type="text" name="kuesioner_univ" value="{{ old('kuesioner_univ', $settings['kuesioner_univ'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Subjudul / Nama Lembaga</label>
                            <input type="text" name="kuesioner_subjudul" value="{{ old('kuesioner_subjudul', $settings['kuesioner_subjudul'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Instruksi (di bawah header)</label>
                            <textarea name="kuesioner_instruksi" rows="2" class="inp">{{ old('kuesioner_instruksi', $settings['kuesioner_instruksi'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Pesan Sukses Setelah Mengirim</label>
                            <input type="text" name="kuesioner_sukses" value="{{ old('kuesioner_sukses', $settings['kuesioner_sukses'] ?? '') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Judul Setiap Bagian Pertanyaan</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'judul_identitas' => 'Identitas Alumni',
                            'judul_status' => 'Status Saat Ini',
                            'judul_kerja6bulan' => 'Pekerjaan &le; 6 Bulan',
                            'judul_tempat_bekerja' => 'Detail Tempat Bekerja',
                            'judul_studi_lanjut' => 'Studi Lanjut & Pembiayaan',
                            'judul_keselarasan' => 'Keselarasan Kerja',
                            'judul_kompetensi' => 'Matriks Kompetensi',
                            'judul_metode' => 'Metode Pembelajaran',
                            'judul_mulai_cari' => 'Mulai Mencari Pekerjaan',
                            'judul_cara_cari' => 'Cara Mencari Pekerjaan',
                            'judul_lamaran' => 'Proses Lamaran',
                            'judul_keaktifan' => 'Keaktifan & Alasan',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <textarea name="{{ $key }}" rows="2" class="inp text-sm">{{ old($key, $settings[$key] ?? '') }}</textarea>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>

            <!-- ================= STATUS & TAMPILAN FORM ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">🔓 Status & Tampilan Form Kuesioner</h2>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Buka / Tutup Kuesioner</div>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="hidden" name="kuesioner_terbuka" value="0">
                        <input type="checkbox" name="kuesioner_terbuka" value="1" class="rounded text-amber-400 bg-slate-800 border-slate-600" {{ (old('kuesioner_terbuka', $settings['kuesioner_terbuka'] ?? '1') == '1') ? 'checked' : '' }}>
                        <span class="text-sm text-gray-300">Kuesioner dibuka untuk umum</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Jika tidak dicentang, halaman kuesioner hanya menampilkan pesan di bawah ini dan formulir tidak bisa diisi.</p>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-400 mb-1">Pesan Saat Kuesioner Ditutup</label>
                        <textarea name="kuesioner_pesan_tutup" rows="3" class="inp">{{ old('kuesioner_pesan_tutup', $settings['kuesioner_pesan_tutup'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Tampilan & Kontak</div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Kode Perguruan Tinggi (default di form)</label>
                            <input type="text" name="kode_pt_default" value="{{ old('kode_pt_default', $settings['kode_pt_default'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Kontak Admin (ditampilkan saat ada masalah pengisian / kuesioner ditutup)</label>
                            <textarea name="kuesioner_kontak" rows="2" class="inp">{{ old('kuesioner_kontak', $settings['kuesioner_kontak'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Footer Form Kuesioner</label>
                            <input type="text" name="kuesioner_footer" value="{{ old('kuesioner_footer', $settings['kuesioner_footer'] ?? '') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Warna Tampilan Form Kuesioner</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Aksen (judul, tombol, tanda *)</label>
                            <input type="color" name="kuesioner_warna_aksen" value="{{ old('kuesioner_warna_aksen', $settings['kuesioner_warna_aksen'] ?? '#fbbf24') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar 1 (atas)</label>
                            <input type="color" name="kuesioner_warna_latar" value="{{ old('kuesioner_warna_latar', $settings['kuesioner_warna_latar'] ?? '#0f172a') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar 2 (bawah)</label>
                            <input type="color" name="kuesioner_warna_latar2" value="{{ old('kuesioner_warna_latar2', $settings['kuesioner_warna_latar2'] ?? '#1e1b4b') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Warna Teks Kuesioner</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nama Universitas</label>
                            <input type="color" name="kuesioner_warna_univ" value="{{ old('kuesioner_warna_univ', $settings['kuesioner_warna_univ'] ?? '#7dd3fc') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Subjudul / Lembaga</label>
                            <input type="color" name="kuesioner_warna_subjudul" value="{{ old('kuesioner_warna_subjudul', $settings['kuesioner_warna_subjudul'] ?? '#94a3b8') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Instruksi &amp; Teks Keterangan</label>
                            <input type="color" name="kuesioner_warna_instruksi" value="{{ old('kuesioner_warna_instruksi', $settings['kuesioner_warna_instruksi'] ?? '#6b7280') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Pertanyaan</label>
                            <input type="color" name="kuesioner_warna_label" value="{{ old('kuesioner_warna_label', $settings['kuesioner_warna_label'] ?? '#94a3b8') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Pilihan Jawaban</label>
                            <input type="color" name="kuesioner_warna_pilihan" value="{{ old('kuesioner_warna_pilihan', $settings['kuesioner_warna_pilihan'] ?? '#d1d5db') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Bagian Pertanyaan</label>
                            <input type="color" name="kuesioner_warna_judulbagian" value="{{ old('kuesioner_warna_judulbagian', $settings['kuesioner_warna_judulbagian'] ?? ($settings['kuesioner_warna_aksen'] ?? '#fbbf24')) }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Tombol Kirim</label>
                            <input type="color" name="kuesioner_warna_tombol" value="{{ old('kuesioner_warna_tombol', $settings['kuesioner_warna_tombol'] ?? '#0f172a') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Footer</label>
                            <input type="color" name="kuesioner_warna_footer" value="{{ old('kuesioner_warna_footer', $settings['kuesioner_warna_footer'] ?? '#4b5563') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Pesan Sukses</label>
                            <input type="color" name="kuesioner_warna_sukses" value="{{ old('kuesioner_warna_sukses', $settings['kuesioner_warna_sukses'] ?? '#6ee7b7') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Pesan Error / Ditutup</label>
                            <input type="color" name="kuesioner_warna_error" value="{{ old('kuesioner_warna_error', $settings['kuesioner_warna_error'] ?? '#fda4af') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Validasi Isian &amp; Pesan</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Domain Email yang Boleh Mengisi (tanpa @)</label>
                            <input type="text" name="kuesioner_email_domain" value="{{ old('kuesioner_email_domain', $settings['kuesioner_email_domain'] ?? 'gmail.com') }}" class="inp" placeholder="gmail.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Tahun Lulus Awal (dropdown)</label>
                            <input type="number" name="kuesioner_tahun_mulai" value="{{ old('kuesioner_tahun_mulai', $settings['kuesioner_tahun_mulai'] ?? '2020') }}" class="inp" placeholder="2020">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Tombol Kirim</label>
                            <input type="text" name="kuesioner_teks_tombol" value="{{ old('kuesioner_teks_tombol', $settings['kuesioner_teks_tombol'] ?? 'SIMPAN DAN KIRIM DATA KUESIONER') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Tab Browser Kuesioner</label>
                            <input type="text" name="kuesioner_judul_browser" value="{{ old('kuesioner_judul_browser', $settings['kuesioner_judul_browser'] ?? 'Tracer Study - LPKM UMMY') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Ikon Header (emoji / simbol pendek)</label>
                            <input type="text" name="kuesioner_ikon" value="{{ old('kuesioner_ikon', $settings['kuesioner_ikon'] ?? '🎓') }}" class="inp" placeholder="🎓">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Pesan Saat Data Tidak Terdaftar</label>
                            <textarea name="kuesioner_pesan_tidak_terdaftar" rows="3" class="inp">{{ old('kuesioner_pesan_tidak_terdaftar', $settings['kuesioner_pesan_tidak_terdaftar'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= TAMPILAN DASHBOARD ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">📊 Tampilan Dashboard</h2>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Judul Halaman & Warna Aksen</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Dashboard (Navbar)</label>
                            <input type="text" name="dashboard_judul" value="{{ old('dashboard_judul', $settings['dashboard_judul'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Aksen</label>
                            <input type="color" name="dashboard_aksen" value="{{ old('dashboard_aksen', $settings['dashboard_aksen'] ?? '#fbbf24') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar 1 (atas)</label>
                            <input type="color" name="dashboard_warna_latar" value="{{ old('dashboard_warna_latar', $settings['dashboard_warna_latar'] ?? '#0f172a') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar 2 (bawah)</label>
                            <input type="color" name="dashboard_warna_latar2" value="{{ old('dashboard_warna_latar2', $settings['dashboard_warna_latar2'] ?? '#1e1b4b') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Footer Dashboard</label>
                            <input type="text" name="dashboard_footer" value="{{ old('dashboard_footer', $settings['dashboard_footer'] ?? '') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Opsi Kurva Garis (berlaku untuk semua grafik garis)</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Kelengkungan (0 - 1)</label>
                            <input type="number" name="chart_kurva_tension" step="0.05" min="0" max="1" value="{{ old('chart_kurva_tension', $settings['chart_kurva_tension'] ?? '0.35') }}" class="inp">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="chart_kurva_fill" value="0">
                                <input type="checkbox" name="chart_kurva_fill" value="1" class="rounded text-amber-400 bg-slate-800 border-slate-600" {{ (old('chart_kurva_fill', $settings['chart_kurva_fill'] ?? '1') == '1') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-400">Isi area di bawah kurva garis</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Bentuk &amp; Warna Setiap Grafik</div>
                    @php
                        $grafikSettings = [
                            'chart_status' => ['Status Kesibukan', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_pendapatan' => ['Distribusi Pendapatan', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_perusahaan' => ['Jenis Perusahaan', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_dana' => ['Sumber Dana Kuliah', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_lokasi' => ['Sebaran Provinsi', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_lokasi_kota' => ['Sebaran Kab/Kota', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_jabatan' => ['Jenis Jabatan', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_tingkat' => ['Tingkat Tempat Kerja', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_kuliah' => ['Destinasi Kampus', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_kompetensi' => ['Perbandingan Kompetensi (2 seri)', ['radar', 'line', 'bar']],
                            'chart_metode' => ['Metode Pembelajaran (5 seri)', ['bar', 'line']],
                            'chart_kurva' => ['Masa Tunggu Kerja', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_cara' => ['Saluran Cari Kerja (15 kategori)', ['pie', 'doughnut', 'bar']],
                            'chart_rasio' => ['Rasio Lamaran', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_keaktifan' => ['Keaktifan Cari Kerja', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_alasan' => ['Alasan Pekerjaan', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_perguruan' => ['Perguruan Tinggi Studi Lanjut', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_prodi' => ['Program Studi Studi Lanjut', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                        ];
                        $tipeLabel = ['line' => 'Garis (Line)', 'bar' => 'Batang (Bar)', 'pie' => 'Lingkaran (Pie)', 'doughnut' => 'Donat (Doughnut)', 'radar' => 'Jaring (Radar)'];
                    @endphp
                    <div class="space-y-3">
                        @foreach ($grafikSettings as $base => [$label, $tipeOptions])
                        @php $slugItem = substr($base, 6); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end border-b border-slate-700/40 pb-3 last:border-b-0">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-300 font-medium">{{ $label }}</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Grafik</label>
                                <select name="{{ $base }}_tipe" class="inp">
                                    @foreach ($tipeOptions as $t)
                                        <option value="{{ $t }}" {{ old($base . '_tipe', $settings[$base . '_tipe'] ?? '') == $t ? 'selected' : '' }}>{{ $tipeLabel[$t] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Warna Utama</label>
                                <input type="color" name="{{ $base }}_warna" value="{{ old($base . '_warna', $settings[$base . '_warna'] ?? '') }}" class="inp">
                            </div>
                        </div>
                        @if (in_array($base, ['chart_status', 'chart_pendapatan', 'chart_perusahaan', 'chart_dana', 'chart_kurva', 'chart_cara', 'chart_keaktifan', 'chart_kompetensi', 'chart_metode']))
                        <details class="border-b border-slate-700/40 pb-3">
                            <summary class="text-xs cursor-pointer text-amber-400/90 hover:text-amber-300 font-semibold">
                                🎨 Atur nama &amp; warna tiap irisan/seri
                            </summary>
                            <div class="mt-3 space-y-2">
                                @foreach(\App\Models\Setting::items($slugItem) as $i => $item)
                                <div class="grid grid-cols-1 md:grid-cols-[1fr_4fr_1fr] gap-3 items-end">
                                    <span class="text-xs text-slate-500">#{{ $i + 1 }}</span>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama di grafik</label>
                                        <input type="text" name="{{ $base }}_item_{{ $i }}_nama" value="{{ old($base . '_item_' . $i . '_nama', $settings[$base . '_item_' . $i . '_nama'] ?? $item['nama']) }}" class="inp text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Warna</label>
                                        <input type="color" name="{{ $base }}_item_{{ $i }}_warna" value="{{ old($base . '_item_' . $i . '_warna', $settings[$base . '_item_' . $i . '_warna'] ?? $item['warna']) }}" class="inp">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </details>
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Judul Setiap Grafik</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'judul_chart_status' => 'Status Kesibukan',
                            'judul_chart_pendapatan' => 'Distribusi Pendapatan',
                            'judul_chart_perusahaan' => 'Jenis Perusahaan',
                            'judul_chart_dana' => 'Sumber Dana Kuliah',
                            'judul_chart_jabatan' => 'Jenis Jabatan',
                            'judul_chart_tingkat' => 'Tingkat Tempat Kerja',
                            'judul_chart_lokasi' => 'Sebaran Provinsi',
                            'judul_chart_lokasi_kota' => 'Sebaran Kab/Kota',
                            'judul_chart_kuliah' => 'Destinasi Kampus',
                            'judul_chart_kompetensi' => 'Perbandingan Kompetensi',
                            'judul_chart_metode' => 'Metode Pembelajaran',
                            'judul_chart_waktu' => 'Masa Tunggu Kerja',
                            'judul_chart_cara' => 'Saluran Cari Kerja',
                            'judul_chart_rasio' => 'Rasio Lamaran',
                            'judul_chart_keaktifan' => 'Keaktifan Cari Kerja',
                            'judul_chart_alasan' => 'Alasan Pekerjaan',
                            'judul_chart_perguruan' => 'Perguruan Tinggi Studi Lanjut',
                            'judul_chart_prodi' => 'Program Studi Studi Lanjut',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="inp text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-2 fade-up">
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-3.5 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm md:text-base hover:-translate-y-0.5 hover:shadow-amber-500/30">
                    💾 Simpan Pengaturan
                </button>
            </div>
        </form>

        <footer class="text-center text-xs text-slate-500 py-4">
            © {{ date('Y') }} Tracer Study LPKM UMMY Solok — Halaman Pengaturan
        </footer>
    </div>

    <!-- MODAL GANTI PASSWORD -->
    <div id="modalGantiPassword" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 animate-fade-in">
        <div class="glass w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all border-white/10">
            <div class="bg-slate-900/60 p-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-md font-bold text-amber-400 flex items-center gap-2">
                    🔑 Ganti Password
                </h3>
                <button type="button" onclick="tutupGantiPassword()" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                @if(session('password_sukses'))
                    <div class="bg-emerald-950 border border-emerald-800 text-emerald-400 p-3 rounded text-sm mb-2">
                        {{ session('password_sukses') }}
                    </div>
                @endif
                @if($errors->has('password_lama') || $errors->has('password') || $errors->has('password_confirmation'))
                    <div class="bg-red-500/10 border border-red-500 text-red-300 p-3 rounded text-sm mb-2 font-medium">
                        {{ $errors->first('password_lama') ?: $errors->first('password') ?: $errors->first('password_confirmation') }}
                    </div>
                @endif
                <form action="{{ route('akun.gantiPassword') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Password Lama</label>
                        <input type="password" name="password_lama" placeholder="Password lama Anda" class="inp">
                    </div>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="min. 8 karakter" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Ulangi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="inp">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm hover:-translate-y-0.5">
                        🔒 Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function bukaGantiPassword() {
            const modal = document.getElementById('modalGantiPassword');
            if (modal) modal.classList.remove('hidden');
        }
        function tutupGantiPassword() {
            const modal = document.getElementById('modalGantiPassword');
            if (modal) modal.classList.add('hidden');
        }
        @if(session('password_sukses') || $errors->has('password_lama') || $errors->has('password') || $errors->has('password_confirmation'))
            bukaGantiPassword();
        @endif
    </script>

</body>
</html>
