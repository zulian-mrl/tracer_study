<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analitik Admin - Tracer Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .glass-hover {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .glass-hover:hover {
            transform: translateY(-5px);
            border-color: rgba(251, 191, 36, 0.5);
            box-shadow: 0 20px 45px -14px rgba(0, 0, 0, 0.65), 0 0 28px -8px rgba(251, 191, 36, 0.28);
        }

        /* Efek 3D pada kanvas kurva tanpa tilt, sehingga teks tetap tajam/tidak blur */
        .chart-3d {
            padding-bottom: 10px;
        }
        .chart-3d canvas {
            filter: drop-shadow(0 14px 12px rgba(0, 0, 0, 0.35));
        }
        .chart-sharp canvas {
            filter: none;
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

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        :root { --ak-sen: {{ \App\Models\Setting::get('dashboard_aksen', '#fbbf24') }}; }
        .ak-nav { border-bottom: 2px solid var(--ak-sen); }
    </style>
</head>
<body class="text-white font-sans">

    <!-- NAVBAR HEADER -->
    <nav class="ak-nav sticky top-0 z-40 border-b bg-slate-900/70 backdrop-blur-lg shadow-2xl">
        <div class="max-w-7xl mx-auto p-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="bukaBiodata()" title="Lihat Biodata Admin"
                        class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-slate-900 font-extrabold text-lg flex items-center justify-center shadow-lg border border-amber-300/60 hover:scale-105 transition duration-200 cursor-pointer overflow-hidden">
                    @if(auth()->user()->foto)
                        <img id="avatarNavbar" src="{{ asset('uploads/fotos/' . auth()->user()->foto) }}" alt="Foto Admin" class="w-full h-full object-cover">
                    @else
                        <span id="avatarNavbar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </button>
                <h1 class="text-base sm:text-lg lg:text-xl font-extrabold tracking-wider grad-text uppercase leading-tight whitespace-nowrap">📊 {{ \App\Models\Setting::get('dashboard_judul', 'Analitik Tracer Study UMMY Solok') }}</h1>
            </div>
            <div class="flex flex-wrap justify-end items-center gap-2 text-white">
                <a href="{{ route('pengaturan.index') }}"
                   class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50 inline-flex items-center gap-1.5">
                    ⚙️ Pengaturan
                </a>
                <button type="button"
                        onclick="openModalAlumni()"
                        class="text-sm bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-semibold px-4 py-2 rounded-lg shadow-lg transition duration-200">
                    + Kelola Data Alumni
                </button>
                <a href="{{ route('kuesioner.index') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">Lihat Form Kuesioner</a>
                <div class="inline-flex items-center gap-2">
                    @if(auth()->check() && auth()->user()->is_super)
                        <a href="{{ route('akun.index') }}"
                           class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50 inline-flex items-center gap-1.5">
                            👥 Akun Admin
                        </a>
                    @endif
                    <button type="button"
                            onclick="bukaGantiPassword()"
                            class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">
                        🔑 Ganti Password
                    </button>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-600 hover:to-rose-500 px-4 py-2 rounded-lg border border-rose-500/50 shadow-lg">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

        <!-- FILTER FORM -->
        <div class="glass rounded-2xl p-4 sm:p-6 shadow-xl fade-up">
            <h2 class="text-lg font-semibold mb-4 text-blue-400 flex items-center">🔍 Filter Analisis Responden</h2>
            <form action="{{ route('kuesioner.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">1. Pilih Tahun Lulus</label>
                    <select name="tahun_lulus" class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30">
                        <option value="">-- Semua Tahun Lulus --</option>
                        @foreach($listTahun as $th)
                            <option value="{{ $th }}" {{ $tahunTerpilih == $th ? 'selected' : '' }}>Tahun Lulus {{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">2. Pilihan Program Studi</label>
                    <select name="kode_prodi" class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30">
                        <option value="">-- Semua Program Studi --</option>
                        @foreach($prodiLabels as $kode => $nama)
                            <option value="{{ $kode }}" {{ $prodiTerpilih == $kode ? 'selected' : '' }}>[{{ $kode }}] {{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-2 px-4 rounded-lg shadow-lg transition duration-150 uppercase tracking-wide">
                        ⚡ Cek & Buka Kurva Analitik
                    </button>
                </div>
            </form>
        </div>

        <!-- JIKA DASHBOARD BELUM DIFILTER -->
        @if(!$tahunTerpilih && !$prodiTerpilih)
            <div class="bg-blue-950/60 border border-blue-800/60 text-blue-300 p-10 rounded-2xl text-center shadow-2xl fade-up">
                <div class="text-5xl mb-3">📈</div>
                <h3 class="text-xl font-bold mb-2">Silakan Gunakan Filter di Atas</h3>
                <p class="text-sm text-gray-400">Pilih Tahun Lulus beserta Program Studi terlebih dahulu, kemudian klik tombol "Cek & Buka Kurva Analitik" untuk melihat grafik penelusuran alumni.</p>
            </div>
        @else

            <!-- INFO FILTER TERPILIH & DOWNLOAD EXCEL -->
            <div class="glass rounded-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4 shadow-xl fade-up">
                <div class="text-sm text-gray-300">
                    Menampilkan Analisis: <span class="text-amber-400 font-bold">{{ $tahunTerpilih ?? 'Semua Tahun' }}</span> | 
                    Prodi: <span class="text-blue-400 font-bold">
                        @if(isset($prodiTerpilih) && (is_string($prodiTerpilih) || is_numeric($prodiTerpilih)) && isset($prodiLabels[$prodiTerpilih]))
                            {{ $prodiLabels[$prodiTerpilih] }}
                        @else
                            Semua Prodi
                        @endif
                    </span>
                </div>
                
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <div class="text-md font-bold bg-slate-900/80 px-4 py-1.5 border border-emerald-500/40 rounded-lg text-emerald-400 shadow-inner">
                        Total Responden: {{ $totalAlumni }} Alumni
                    </div>
                    
                    <a href="{{ route('kuesioner.export', ['tahun_lulus' => $tahunTerpilih, 'kode_prodi' => $prodiTerpilih]) }}" class="inline-flex items-center gap-2 text-sm bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 font-bold px-4 py-2 rounded-lg border border-emerald-400/50 transition duration-150 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh Excel {{ $tahunTerpilih ? 'Tahun ' . $tahunTerpilih : '(Semua Tahun)' }}
                    </a>
                </div>
            </div>

            <!-- STAT CARD RINGKASAN -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 fade-up">
                <div class="glass glass-hover rounded-2xl p-3 sm:p-5 flex items-center gap-2 sm:gap-4 shadow-xl cursor-pointer" onclick="bukaNamaAlumni('total')" title="Lihat daftar nama alumni">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-2xl shadow-lg">🧑‍🎓</div>
                    <div>
                        <div class="text-xl sm:text-2xl font-extrabold text-amber-400">{{ $totalAlumni }}</div>
                        <div class="text-xs uppercase tracking-wider text-gray-400">Total Responden</div>
                    </div>
                </div>
                <div class="glass glass-hover rounded-2xl p-3 sm:p-5 flex items-center gap-2 sm:gap-4 shadow-xl cursor-pointer" onclick="bukaNamaAlumni('bekerja')" title="Lihat daftar nama alumni bekerja">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-2xl shadow-lg">💼</div>
                    <div>
                        <div class="text-xl sm:text-2xl font-extrabold text-sky-400">{{ $statusKerja['Bekerja'] ?? 0 }}</div>
                        <div class="text-xs uppercase tracking-wider text-gray-400">Bekerja</div>
                    </div>
                </div>
                <div class="glass glass-hover rounded-2xl p-3 sm:p-5 flex items-center gap-2 sm:gap-4 shadow-xl cursor-pointer" onclick="bukaNamaAlumni('aktif')" title="Lihat daftar nama alumni aktif mencari kerja">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center text-2xl shadow-lg">🔥</div>
                    <div>
                        <div class="text-xl sm:text-2xl font-extrabold text-emerald-400">{{ $keaktifan['Aktif'] ?? 0 }}</div>
                        <div class="text-xs uppercase tracking-wider text-gray-400">Aktif Mencari Kerja</div>
                    </div>
                </div>
                <div class="glass glass-hover rounded-2xl p-3 sm:p-5 flex items-center gap-2 sm:gap-4 shadow-xl cursor-pointer" onclick="bukaNamaAlumni('lanjut')" title="Lihat daftar nama alumni lanjut kuliah">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-fuchsia-400 to-purple-600 flex items-center justify-center text-2xl shadow-lg">🏫</div>
                    <div>
                        <div class="text-xl sm:text-2xl font-extrabold text-fuchsia-400">{{ $statusKerja['Lanjut Kuliah'] ?? 0 }}</div>
                        <div class="text-xs uppercase tracking-wider text-gray-400">Lanjut Kuliah</div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK / CHART SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_status') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartStatusKerja"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_pendapatan') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_perusahaan') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartPerusahaanKerja"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_dana') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartSumberDana"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_jabatan') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartPosisiJabatan"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_tingkat') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartPilihTingkat"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_lokasi') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartLokasi"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_lokasi_kota') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartLokasiKota"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_kuliah') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartTempatKuliah"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_perguruan') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartPerguruanTinggiStudi"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_prodi') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartProgramStudiStudi"></canvas>
                    </div>
                </div>
            </div>

            <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_kompetensi') }}</h3>
                <div class="relative h-80 flex justify-center chart-3d">
                    <canvas id="chartKompetensi"></canvas>
                </div>
            </div>

            <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_metode') }}</h3>
                <div class="relative h-72 chart-3d">
                    <canvas id="chartMetodeBelajar"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_waktu') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartWaktuCariKerja"></canvas>
                    </div>
                </div>

                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_cara') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d">
                        <canvas id="chartCaraCariKerja"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl col-span-1 lg:col-span-2 fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_rasio') }}</h3>
                    <div class="relative h-64 chart-3d">
                        <canvas id="chartRasioLamaran"></canvas>
                    </div>
                </div>
                <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_keaktifan') }}</h3>
                    <div class="relative h-64 flex justify-center chart-3d chart-sharp">
                        <canvas id="chartKeaktifan"></canvas>
                    </div>
                </div>
            </div>

            <div class="glass glass-hover rounded-2xl p-5 shadow-xl fade-up">
                <h3 class="text-md font-semibold text-gray-300 mb-4">{{ \App\Models\Setting::get('judul_chart_alasan') }}</h3>
                <div class="relative h-72 chart-3d">
                    <canvas id="chartAlasanTidakSesuai"></canvas>
                </div>
            </div>

            <footer class="text-center text-xs text-slate-500 py-4">
                © {{ date('Y') }} Tracer Study LPKM UMMY Solok — Dashboard Analitik Alumni
            </footer>

            <!-- SCRIPT INISIALISASI CHART (HANYA JIKA FILTER SUDAH DIPILIH) -->
            <script>
                // Tangkap data dari Blade array
                const kotaLabels = @js(array_keys($lokasiKota));
                const kotaData = @js(array_values($lokasiKota));

                const PosisiJabatan = @js(array_keys($PosisiJabatan));
                const lokasiJabatan = @js(array_values($PosisiJabatan));

                const PilihTingkat = @js(array_keys($PilihTingkat));
                const lokasiTingkat = @js(array_values($PilihTingkat));

                const statusKerjaData = @js(array_values($statusKerja));
                const pendapatanData = @js(array_values($pendapatan));

                const statusPerusahaanKerja = @js(array_values($statusPerusahaanKerja));
                const SumberDana = @js(array_values($SumberDana));
                
                const lokasiLabels = @js(array_keys($lokasiKerja));
                const lokasiData = @js(array_values($lokasiKerja));

                const kuliahLabels = @js(array_keys($tempatKuliah));
                const kuliahData = @js(array_values($tempatKuliah));

                const prodiLanjutLabels = @js(array_keys($programStudiLanjut));
                const prodiLanjutData = @js(array_values($programStudiLanjut));

                const kDikuasai = @js($kompetensiDikuasai);
                const kDiperlukan = @js($kompetensiDiperlukan);

                const metodeLabels = ['Perkuliahan', 'Demonstrasi', 'Riset', 'Magang', 'Praktikum', 'Kerja Lapangan', 'Diskusi'];
                
                const dataSangatBesar  = @js(array_values($metodeSangatBesar ?? []));
                const dataBesar        = @js(array_values($metodeBesar ?? []));
                const dataCukupBesar   = @js(array_values($metodeCukupBesar ?? []));
                const dataKurang       = @js(array_values($metodeKurang ?? []));
                const dataTidakSama    = @js(array_values($metodeTidakSama ?? []));

                const waktuLabels = @js(array_keys($waktuCariKerja));
                const waktuData = @js(array_values($waktuCariKerja));

                const caraLabels = @js(array_keys($caraCariKerja));
                const caraData = @js(array_values($caraCariKerja));

                const avgLamaran = @js(array_values($avgLamaran));
                const keaktifanLabels = @js(array_keys($keaktifan));
                const keaktifanData = @js(array_values($keaktifan));
                
                const alasanLabels = @js(array_keys($alasanTidakSesuai));
                const alasanData = @js(array_values($alasanTidakSesuai));

                // Pengaturan bentuk & warna tiap grafik (dari halaman Pengaturan)
                @php
                    $daftarOpsiGrafik = [
                        'status' => ['chart_status_tipe', 'chart_status_warna'],
                        'pendapatan' => ['chart_pendapatan_tipe', 'chart_pendapatan_warna'],
                        'perusahaan' => ['chart_perusahaan_tipe', 'chart_perusahaan_warna'],
                        'dana' => ['chart_dana_tipe', 'chart_dana_warna'],
                        'lokasi' => ['chart_lokasi_tipe', 'chart_lokasi_warna'],
                        'lokasi_kota' => ['chart_lokasi_kota_tipe', 'chart_lokasi_kota_warna'],
                        'jabatan' => ['chart_jabatan_tipe', 'chart_jabatan_warna'],
                        'tingkat' => ['chart_tingkat_tipe', 'chart_tingkat_warna'],
                        'kuliah' => ['chart_kuliah_tipe', 'chart_kuliah_warna'],
                        'kompetensi' => ['chart_kompetensi_tipe', 'chart_kompetensi_warna'],
                        'metode' => ['chart_metode_tipe', 'chart_metode_warna'],
                        'waktu' => ['chart_kurva_tipe', 'chart_kurva_warna'],
                        'cara' => ['chart_cara_tipe', 'chart_cara_warna'],
                        'rasio' => ['chart_rasio_tipe', 'chart_rasio_warna'],
                        'keaktifan' => ['chart_keaktifan_tipe', 'chart_keaktifan_warna'],
                        'alasan' => ['chart_alasan_tipe', 'chart_alasan_warna'],
                        'perguruan' => ['chart_perguruan_tipe', 'chart_perguruan_warna'],
                        'prodi' => ['chart_prodi_tipe', 'chart_prodi_warna'],
                    ];
                    $opsiGrafikJs = [];
                    foreach ($daftarOpsiGrafik as $nama => $kunci) {
                        $opsiGrafikJs[$nama] = [
                            'tipe' => \App\Models\Setting::get($kunci[0], 'bar'),
                            'warna' => \App\Models\Setting::get($kunci[1], '#10b981'),
                        ];
                    }
                    $daftarItemGrafik = ['status', 'pendapatan', 'perusahaan', 'dana', 'kurva', 'cara', 'keaktifan', 'kompetensi', 'metode'];
                    $itemGrafikJs = [];
                    foreach ($daftarItemGrafik as $slug) {
                        $itemGrafikJs[$slug] = \App\Models\Setting::items($slug);
                    }
                @endphp
                const kurvaFill = @js((int) \App\Models\Setting::get('chart_kurva_fill', 1));
                const kurvaTension = @js((float) \App\Models\Setting::get('chart_kurva_tension', 0.35));
                const opsiGrafik = @js($opsiGrafikJs);
                const itemGrafik = @js($itemGrafikJs);

                // Helper warna untuk efek 3D gradasi kedalaman
                function hexToRgb(hex) {
                    const n = parseInt(hex.replace('#', ''), 16);
                    return { r: (n >> 16) & 255, g: (n >> 8) & 255, b: n & 255 };
                }
                function lightenHex(hex, pct) {
                    const c = hexToRgb(hex);
                    const r = Math.min(255, Math.round(c.r + (255 - c.r) * pct / 100));
                    const g = Math.min(255, Math.round(c.g + (255 - c.g) * pct / 100));
                    const b = Math.min(255, Math.round(c.b + (255 - c.b) * pct / 100));
                    return `rgb(${r}, ${g}, ${b})`;
                }
                function darkenHex(hex, pct) {
                    const c = hexToRgb(hex);
                    const r = Math.round(c.r * (100 - pct) / 100);
                    const g = Math.round(c.g * (100 - pct) / 100);
                    const b = Math.round(c.b * (100 - pct) / 100);
                    return `rgb(${r}, ${g}, ${b})`;
                }
                function hexToRgba(hex, alpha) {
                    const c = hexToRgb(hex);
                    return `rgba(${c.r}, ${c.g}, ${c.b}, ${alpha})`;
                }
                // Gradasi vertikal agar bar terlihat seperti batang 3D (terang di atas, gelap di dasar)
                function barGrad(canvasId, hex) {
                    const cv = document.getElementById(canvasId);
                    const ctx = cv ? cv.getContext('2d') : null;
                    if (!ctx) return hex;
                    const g = ctx.createLinearGradient(0, 0, 0, 240);
                    g.addColorStop(0, lightenHex(hex, 45));
                    g.addColorStop(0.5, hex);
                    g.addColorStop(1, darkenHex(hex, 20));
                    return g;
                }
                function hexToHsl(hex) {
                    const r = parseInt(hex.slice(1, 3), 16) / 255;
                    const g = parseInt(hex.slice(3, 5), 16) / 255;
                    const b = parseInt(hex.slice(5, 7), 16) / 255;
                    const max = Math.max(r, g, b), min = Math.min(r, g, b);
                    let h = 0, s = 0;
                    const l = (max + min) / 2;
                    if (max !== min) {
                        const d = max - min;
                        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                        switch (max) {
                            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                            case g: h = (b - r) / d + 2; break;
                            default: h = (r - g) / d + 4;
                        }
                        h /= 6;
                    }
                    return { h: h * 360, s: s * 100, l: l * 100 };
                }
                // Palet warna selaras yang diturunkan dari satu warna dasar
                function paletDariWarna(warna, n) {
                    const hsl = hexToHsl(warna);
                    const hasil = [];
                    for (let i = 0; i < n; i++) {
                        const h = (hsl.h + i * 137.508) % 360;
                        const s = Math.min(92, hsl.s + 12);
                        const l = 52 + (i % 3) * 5;
                        hasil.push(`hsl(${h.toFixed(0)}, ${s.toFixed(0)}%, ${l.toFixed(0)}%)`);
                    }
                    return hasil;
                }

                // Opsi Dasar Tema Gelap
                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            labels: { color: '#cbd5e1', usePointStyle: true, boxWidth: 8, padding: 14 } 
                        }
                    }
                };

                // Opsi Bar & Line Chart
                const linearScaleOptions = {
                    ...baseOptions,
                    scales: {
                        x: { 
                            ticks: { color: '#cbd5e1' }, 
                            grid: { color: 'rgba(51, 65, 85, 0.3)' } 
                        },
                        y: { 
                            ticks: { color: '#cbd5e1' }, 
                            grid: { color: 'rgba(51, 65, 85, 0.3)' } 
                        }
                    }
                };

                // Opsi Radar Chart
                const radarScaleOptions = {
                    ...baseOptions,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(51, 65, 85, 0.5)' },
                            grid: { color: 'rgba(51, 65, 85, 0.3)' },
                            pointLabels: { 
                                color: '#cbd5e1', 
                                font: { size: 11 } 
                            },
                            ticks: { 
                                backdropColor: 'transparent', 
                                color: '#cbd5e1',
                                showLabelBackdrop: false
                            }
                        }
                    }
                };

                // Konfigurasi Grafik Dinamis (menyesuaikan jenis & warna dari halaman Pengaturan)
                function konfigGrafik({ id, tipe, warna, labels, data, label, axisY, slug }) {
                    if (tipe === 'pie' || tipe === 'doughnut') {
                        const item = slug && itemGrafik[slug] ? itemGrafik[slug] : null;
                        const lbl = item ? item.map(x => x.nama) : labels;
                        const col = item ? item.map(x => x.warna) : paletDariWarna(warna, Math.max(labels.length, data.length));
                        return {
                            type: tipe,
                            data: { labels: lbl, datasets: [{ label: label, data: data, backgroundColor: col, borderColor: '#0f172a', borderWidth: 3, hoverOffset: 14, offset: tipe === 'doughnut' ? 3 : 0, borderRadius: 4 }] },
                            options: baseOptions
                        };
                    }
                    if (tipe === 'radar') {
                        return {
                            type: 'radar',
                            data: { labels: labels, datasets: [{ label: label, data: data, borderColor: warna, backgroundColor: hexToRgba(warna, 0.2), tension: kurvaTension, pointRadius: 4 }] },
                            options: radarScaleOptions
                        };
                    }
                    if (tipe === 'line') {
                        return {
                            type: 'line',
                            data: { labels: labels, datasets: [{ label: label, data: data, borderColor: warna, backgroundColor: kurvaFill ? hexToRgba(warna, 0.15) : 'transparent', tension: kurvaTension, fill: !!kurvaFill, pointRadius: 4, pointHoverRadius: 7, borderRadius: 6, borderSkipped: false }] },
                            options: linearScaleOptions
                        };
                    }
                    return {
                        type: 'bar',
                        data: { labels: labels, datasets: [{ label: label, data: data, backgroundColor: barGrad(id, warna), borderRadius: 6, borderSkipped: false, barThickness: axisY ? 22 : undefined }] },
                        options: axisY ? { ...linearScaleOptions, indexAxis: 'y' } : linearScaleOptions
                    };
                }

                // Helper Function Render Chart
                function renderChartSafe(elementId, config) {
                    const canvas = document.getElementById(elementId);
                    if (canvas) {
                        const existingChart = Chart.getChart(canvas);
                        if (existingChart) {
                            existingChart.destroy();
                        }
                        // Bar horizontal: tinggi kartu mengikuti jumlah kategori agar bar & label tetap terbaca
                        const grafikHorisontal = ['chartLokasi', 'chartLokasiKota', 'chartPosisiJabatan', 'chartPilihTingkat', 'chartAlasanTidakSesuai'];
                        if (grafikHorisontal.includes(elementId) && config && config.type === 'bar' && config.data && config.data.labels && config.data.labels.length > 0) {
                            const tinggi = Math.max(256, config.data.labels.length * 34 + 40);
                            if (canvas.parentElement) {
                                canvas.parentElement.style.height = tinggi + 'px';
                            }
                        }
                        // Klik pada irisan/bar membuka daftar nama alumni kategori tersebut
                        config.options = { ...(config.options || {}) };
                        config.options.onClick = (event, elements) => {
                            if (!elements || elements.length === 0) return;
                            const idx = elements[0].index;
                            const label = config.data && config.data.labels ? config.data.labels[idx] : null;
                            if (label === undefined || label === null) return;
                            bukaNamaGrafik(elementId, String(label));
                        };
                        config.options.onHover = (event, elements) => {
                            const target = event && event.native && event.native.target;
                            if (target) target.style.cursor = (elements && elements.length) ? 'pointer' : 'default';
                        };
                        return new Chart(canvas, config);
                    }
                }

                // Render Seluruh Chart
                renderChartSafe('chartStatusKerja', konfigGrafik({
                    id: 'chartStatusKerja', tipe: opsiGrafik.status.tipe, warna: opsiGrafik.status.warna,
                    labels: ['Bekerja', 'Wiraswasta', 'Lanjut Kuliah', 'Cari Kerja', 'Belum Bekerja'],
                    data: statusKerjaData, label: 'Jumlah Alumni', slug: 'status'
                }));

                renderChartSafe('chartPendapatan', konfigGrafik({
                    id: 'chartPendapatan', tipe: opsiGrafik.pendapatan.tipe, warna: opsiGrafik.pendapatan.warna,
                    labels: ['< 2 Juta', '2 - 5 Juta', '> 5 Juta'],
                    data: pendapatanData, label: 'Jumlah Alumni', slug: 'pendapatan'
                }));

                renderChartSafe('chartPerusahaanKerja', konfigGrafik({
                    id: 'chartPerusahaanKerja', tipe: opsiGrafik.perusahaan.tipe, warna: opsiGrafik.perusahaan.warna,
                    labels: ['Instansi Pemerintah', 'BUMN/BUMD', 'Institusi', 'Lembaga Swadaya', 'Swasta', 'Wiraswasta', 'Lainnya'],
                    data: statusPerusahaanKerja, label: 'Jumlah Alumni', slug: 'perusahaan'
                }));

                renderChartSafe('chartSumberDana', konfigGrafik({
                    id: 'chartSumberDana', tipe: opsiGrafik.dana.tipe, warna: opsiGrafik.dana.warna,
                    labels: ['Biaya Sendiri', 'Beasiswa ADIK', 'Beasiswa BIDIKMISI', 'Beasiswa PPA', 'Beasiswa AFIRMASI', 'Beasiswa Swasta', 'Lainnya'],
                    data: SumberDana, label: 'Jumlah Alumni', slug: 'dana'
                }));

                renderChartSafe('chartLokasi', konfigGrafik({
                    id: 'chartLokasi', tipe: opsiGrafik.lokasi.tipe, warna: opsiGrafik.lokasi.warna,
                    labels: lokasiLabels.length ? lokasiLabels : ['Belum Ada'],
                    data: lokasiData.length ? lokasiData : [0], label: 'Jumlah Responden provinsi', axisY: true
                }));

                renderChartSafe('chartLokasiKota', konfigGrafik({
                    id: 'chartLokasiKota', tipe: opsiGrafik.lokasi_kota.tipe, warna: opsiGrafik.lokasi_kota.warna,
                    labels: kotaLabels.length ? kotaLabels : ['Belum Ada Data Kota'],
                    data: kotaData.length ? kotaData : [0], label: 'Jumlah Responden Kab/Kota', axisY: true
                }));

                renderChartSafe('chartPosisiJabatan', konfigGrafik({
                    id: 'chartPosisiJabatan', tipe: opsiGrafik.jabatan.tipe, warna: opsiGrafik.jabatan.warna,
                    labels: lokasiJabatan.length ? PosisiJabatan : ['Belum Ada Jabatan'],
                    data: lokasiJabatan.length ? lokasiJabatan : [0], label: 'Posisi/Jabatan', axisY: true
                }));

                renderChartSafe('chartPilihTingkat', konfigGrafik({
                    id: 'chartPilihTingkat', tipe: opsiGrafik.tingkat.tipe, warna: opsiGrafik.tingkat.warna,
                    labels: lokasiTingkat.length ? PilihTingkat : ['Belum Ada Data Tingkat'],
                    data: lokasiTingkat.length ? lokasiTingkat : [0], label: 'Tingkat Tempat Kerja', axisY: true
                }));

                renderChartSafe('chartTempatKuliah', konfigGrafik({
                    id: 'chartTempatKuliah', tipe: opsiGrafik.kuliah.tipe, warna: opsiGrafik.kuliah.warna,
                    labels: kuliahLabels.length ? kuliahLabels : ['Tidak Ada Data Kuliah'],
                    data: kuliahData.length ? kuliahData : [0], label: 'Alumni'
                }));

                renderChartSafe('chartPerguruanTinggiStudi', konfigGrafik({
                    id: 'chartPerguruanTinggiStudi', tipe: opsiGrafik.perguruan.tipe, warna: opsiGrafik.perguruan.warna,
                    labels: kuliahLabels.length ? kuliahLabels : ['Tidak Ada Data'],
                    data: kuliahData.length ? kuliahData : [0], label: 'Jumlah Alumni'
                }));

                renderChartSafe('chartProgramStudiStudi', konfigGrafik({
                    id: 'chartProgramStudiStudi', tipe: opsiGrafik.prodi.tipe, warna: opsiGrafik.prodi.warna,
                    labels: prodiLanjutLabels.length ? prodiLanjutLabels : ['Tidak Ada Data'],
                    data: prodiLanjutData.length ? prodiLanjutData : [0], label: 'Jumlah Alumni'
                }));

                renderChartSafe('chartKompetensi', (function () {
                    const kTipe = opsiGrafik.kompetensi.tipe;
                    const kItem = itemGrafik.kompetensi;
                    const labelsK = ['Etika', 'Keahlian Inti', 'Bahasa Inggris', 'TIK', 'Komunikasi', 'Kerjasama Tim', 'Pengembangan Diri'];
                    return {
                        type: kTipe,
                        data: {
                            labels: labelsK,
                            datasets: [
                                { label: kItem[0].nama, data: kDikuasai, borderColor: kItem[0].warna, backgroundColor: hexToRgba(kItem[0].warna, 0.2), tension: kurvaTension },
                                { label: kItem[1].nama, data: kDiperlukan, borderColor: kItem[1].warna, backgroundColor: hexToRgba(kItem[1].warna, 0.2), tension: kurvaTension }
                            ]
                        },
                        options: kTipe === 'radar' ? radarScaleOptions : linearScaleOptions
                    };
                })());

                renderChartSafe('chartMetodeBelajar', (function () {
                    const mTipe = opsiGrafik.metode.tipe;
                    const dataSeri = [dataSangatBesar, dataBesar, dataCukupBesar, dataKurang, dataTidakSama];
                    return {
                        type: mTipe,
                        data:  {
                            labels: metodeLabels,
                            datasets: itemGrafik.metode.map((it, idx) => ({
                                label: it.nama,
                                data: dataSeri[idx],
                                backgroundColor: barGrad('chartMetodeBelajar', it.warna),
                                borderRadius: 5,
                                borderSkipped: false
                            }))
                        },
                        options: linearScaleOptions
                    };
                })());

                renderChartSafe('chartWaktuCariKerja', konfigGrafik({
                    id: 'chartWaktuCariKerja', tipe: opsiGrafik.waktu.tipe, warna: opsiGrafik.waktu.warna,
                    labels: waktuLabels.length ? waktuLabels : ['Belum Ada Data'],
                    data: waktuData.length ? waktuData : [0], label: 'Alumni', slug: 'kurva'
                }));

                renderChartSafe('chartCaraCariKerja', (function () {
                    const cTipe = opsiGrafik.cara.tipe;
                    const cWarna = opsiGrafik.cara.warna;
                    const lblC = itemGrafik.cara.map(x => x.nama);
                    const colC = itemGrafik.cara.map(x => x.warna);
                    const dtC = caraData.length ? caraData : [0];
                    if (cTipe === 'bar') {
                        return {
                            type: 'bar',
                            data: { labels: lblC, datasets: [{ label: 'Pilihan Metode Pencarian', data: dtC, backgroundColor: barGrad('chartCaraCariKerja', cWarna), borderRadius: 6, borderSkipped: false }] },
                            options: linearScaleOptions
                        };
                    }
                    return {
                        type: cTipe,
                        data: { labels: lblC, datasets: [{ label: 'Pilihan Metode Pencarian', data: dtC, backgroundColor: colC, borderColor: '#0f172a', borderWidth: 2, hoverOffset: 14, offset: 3, borderRadius: 4 }] },
                        options: baseOptions
                    };
                })());

                renderChartSafe('chartRasioLamaran', konfigGrafik({
                    id: 'chartRasioLamaran', tipe: opsiGrafik.rasio.tipe, warna: opsiGrafik.rasio.warna,
                    labels: ['Perusahaan Dilamar', 'Mendapat Respons', 'Diundang Wawancara'],
                    data: avgLamaran, label: 'Rata-rata Jumlah Perusahaan'
                }));

                renderChartSafe('chartKeaktifan', konfigGrafik({
                    id: 'chartKeaktifan', tipe: opsiGrafik.keaktifan.tipe, warna: opsiGrafik.keaktifan.warna,
                    labels: keaktifanLabels.length ? keaktifanLabels : ['Belum Ada Data'],
                    data: keaktifanData.length ? keaktifanData : [0], label: 'Jumlah Alumni', slug: 'keaktifan'
                }));

                renderChartSafe('chartAlasanTidakSesuai', konfigGrafik({
                    id: 'chartAlasanTidakSesuai', tipe: opsiGrafik.alasan.tipe, warna: opsiGrafik.alasan.warna,
                    labels: alasanLabels.length ? alasanLabels : ['Belum Ada Data'],
                    data: alasanData.length ? alasanData : [0], label: 'Frekuensi Alasan Terbanyak', axisY: true
                }));
            </script>
        @endif
    </div>

    <!-- MODAL KELOLA DATA ALUMNI (DI LUAR IF/ELSE) -->
    <div id="modalAlumni" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 animate-fade-in">
        <div class="glass w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all border-white/10">
            
            <!-- Header Modal -->
            <div class="bg-slate-900/60 p-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-md font-bold text-amber-400 flex items-center gap-2">
                    📁 Import Data Master Alumni (Excel)
                </h3>
                <button type="button" onclick="closeModalAlumni()" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="p-6 space-y-4">
                <!-- Notifikasi Status Sukses/Gagal -->
                @if(session('success'))
                    <div class="bg-emerald-950 border border-emerald-800 text-emerald-400 p-3 rounded text-sm mb-2">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->has('file_excel'))
                    <div class="bg-rose-950 border border-rose-800 text-rose-400 p-3 rounded text-sm mb-2">
                        {{ $errors->first('file_excel') }}
                    </div>
                @endif

                <p class="text-xs text-slate-400 leading-relaxed">
                    Unggah berkas spreadsheet (.xlsx atau .xls) untuk memperbarui data acuan kelulusan alumni. Sistem akan menggunakan berkas ini untuk memvalidasi NIM & NIK pengisi kuesioner.
                </p>

                <!-- Form Pengiriman -->
                <form action="{{ route('alumni.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="border-2 border-dashed border-slate-600 hover:border-amber-500 rounded-lg p-4 text-center cursor-pointer bg-slate-900/50 transition">
                        <input type="file" id="file_excel" name="file_excel" required class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-slate-900 hover:file:bg-amber-600 cursor-pointer">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" onclick="closeModalAlumni()" class="bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold px-4 py-2 rounded">
                            Batal
                        </button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-5 py-2 rounded shadow">
                            ⚡ Jalankan Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DAFTAR NAMA ALUMNI PER KATEGORI (DI LUAR IF/ELSE) -->
    <div id="modalNamaAlumni" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all border-white/10">
            <div class="bg-slate-900/60 p-4 border-b border-white/10 flex justify-between items-center">
                <h3 id="judulModalNama" class="text-md font-bold text-amber-400 flex items-center gap-2">👥 Alumni</h3>
                <button type="button" onclick="tutupNamaAlumni()" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                <ul id="listNamaAlumni" class="space-y-1.5 text-sm text-gray-300"></ul>
            </div>
            <div id="totalNamaAlumni" class="p-4 border-t border-white/10 text-xs text-gray-500"></div>
        </div>
    </div>

    <!-- MODAL GANTI PASSWORD (DI LUAR IF/ELSE) -->
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
                        <input type="password" name="password_lama" placeholder="Password lama Anda" class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white outline-none focus:border-amber-400">
                    </div>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="min. 8 karakter" class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white outline-none focus:border-amber-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Ulangi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white outline-none focus:border-amber-400">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm hover:-translate-y-0.5">
                        🔒 Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT UTAMA KONTROL MODAL (ALWAYS ACTIVE) -->
    <script>
        const daftarNamaAlumni = @js($daftarNama);
        const daftarNamaGrafik = @js($namaPerGrafik);

        function tampilkanModalNama(judul, nama) {
            const modal = document.getElementById('modalNamaAlumni');
            if (!modal) return;
            document.getElementById('judulModalNama').textContent = '👥 ' + judul;
            const list = document.getElementById('listNamaAlumni');
            list.innerHTML = '';
            if (nama.length === 0) {
                list.innerHTML = '<li class="text-gray-500 italic">Tidak ada data alumni.</li>';
            } else {
                nama.forEach((n, i) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2 bg-slate-800/60 border border-slate-700/60 rounded-lg px-3 py-2';
                    li.innerHTML = '<span class="w-5 text-xs text-slate-500 shrink-0">' + (i + 1) + '.</span><span>' + n + '</span>';
                    list.appendChild(li);
                });
            }
            document.getElementById('totalNamaAlumni').textContent = 'Total: ' + nama.length + ' alumni';
            modal.classList.remove('hidden');
        }

        function bukaNamaGrafik(chartId, label) {
            const group = daftarNamaGrafik[chartId] || {};
            tampilkanModalNama(label, group[label] || []);
        }

        function bukaNamaAlumni(kategori) {
            const judul = {
                total: 'Total Responden',
                bekerja: 'Alumni Bekerja',
                aktif: 'Alumni Aktif Mencari Kerja',
                lanjut: 'Alumni Lanjut Kuliah'
            };
            const modal = document.getElementById('modalNamaAlumni');
            if (!modal) return;
            document.getElementById('judulModalNama').textContent = '👥 ' + (judul[kategori] || 'Alumni');
            const list = document.getElementById('listNamaAlumni');
            const nama = daftarNamaAlumni[kategori] || [];
            list.innerHTML = '';
            if (nama.length === 0) {
                list.innerHTML = '<li class="text-gray-500 italic">Tidak ada data alumni.</li>';
            } else {
                nama.forEach((n, i) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2 bg-slate-800/60 border border-slate-700/60 rounded-lg px-3 py-2';
                    li.innerHTML = '<span class="w-5 text-xs text-slate-500 shrink-0">' + (i + 1) + '.</span><span>' + n + '</span>';
                    list.appendChild(li);
                });
            }
            document.getElementById('totalNamaAlumni').textContent = 'Total: ' + nama.length + ' alumni';
            modal.classList.remove('hidden');
        }

        function tutupNamaAlumni() {
            const modal = document.getElementById('modalNamaAlumni');
            if (modal) modal.classList.add('hidden');
        }

        function bukaGantiPassword() {
            const modal = document.getElementById('modalGantiPassword');
            if (modal) modal.classList.remove('hidden');
        }

        function tutupGantiPassword() {
            const modal = document.getElementById('modalGantiPassword');
            if (modal) modal.classList.add('hidden');
        }

        function openModalAlumni() {
            const modal = document.getElementById('modalAlumni');
            if(modal) modal.classList.remove('hidden');
        }

        function closeModalAlumni() {
            const modal = document.getElementById('modalAlumni');
            if(modal) modal.classList.add('hidden');
        }

        // Buka otomatis jika ada alert sukses/error saat reload halaman
        @if(session('success') || $errors->has('file_excel'))
            openModalAlumni();
        @endif

        // Buka otomatis modal ganti password jika ada sukses/error dari form tersebut
        @if(session('password_sukses') || $errors->has('password_lama') || $errors->has('password') || $errors->has('password_confirmation'))
            bukaGantiPassword();
        @endif
    </script>

    <!-- MODAL BIODATA ADMIN -->
    <div id="modalBiodata" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="tutupBiodata()"></div>
        <div class="relative glass w-full max-w-sm rounded-2xl p-6 shadow-2xl fade-up border-white/10">
            <button type="button" onclick="tutupBiodata()"
                    class="absolute top-3 right-4 text-gray-400 hover:text-white text-3xl leading-none transition">&times;</button>
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-slate-900 font-extrabold text-3xl flex items-center justify-center shadow-lg border border-amber-300/60 mb-4 overflow-hidden">
                    @if(auth()->user()->foto)
                        <img id="avatarModal" src="{{ asset('uploads/fotos/' . auth()->user()->foto) }}" alt="Foto Admin" class="w-full h-full object-cover">
                    @else
                        <span id="avatarModal">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <h2 id="namaTampil" class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
                    <button type="button" onclick="editNama()" title="Ubah Nama"
                            class="text-gray-400 hover:text-amber-300 transition text-sm">✏️</button>
                </div>
                <div id="formEditNama" class="hidden w-full mt-2">
                    <input type="text" id="inputNama" value="{{ auth()->user()->name }}"
                           maxlength="255" placeholder="Nama admin"
                           class="w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:border-amber-400 text-center">
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="simpanNama()"
                                class="flex-1 text-xs bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-semibold px-3 py-2 rounded-lg transition">💾 Simpan</button>
                        <button type="button" onclick="batalNama()"
                                class="flex-1 text-xs bg-slate-700/70 hover:bg-slate-600 px-3 py-2 rounded-lg border border-slate-500/50 transition">✖ Batal</button>
                    </div>
                </div>
                <p id="statusNama" class="mt-2 text-xs text-emerald-400 hidden">✅ Nama berhasil diubah</p>
                <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                <span class="mt-3 text-[11px] rounded-full px-3 py-1 {{ auth()->user()->is_super ? 'bg-amber-500/15 text-amber-300 border border-amber-500/40' : 'bg-sky-500/15 text-sky-300 border border-sky-500/40' }}">
                    {{ auth()->user()->is_super ? '⭐ Super Admin' : 'Admin Biasa' }}
                </span>
                <p class="mt-3 text-xs text-slate-500">Terdaftar sejak {{ \Carbon\Carbon::parse(auth()->user()->created_at)->translatedFormat('d F Y') }}</p>
                <input type="file" id="inputFoto" accept="image/*" class="hidden" onchange="unggahFoto(this)">
                <button type="button" onclick="document.getElementById('inputFoto').click()"
                        class="mt-3 text-xs bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50 transition">📷 Ganti Foto</button>
                <p id="statusFoto" class="mt-2 text-xs text-emerald-400 hidden">✅ Foto profil berhasil diubah</p>
                @if(auth()->user()->is_super)
                    <a href="{{ route('akun.index') }}" class="mt-4 w-full text-center text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50 transition">👥 Kelola Akun Admin</a>
                @endif
                <a href="{{ route('pengaturan.index') }}" class="mt-2 w-full text-center text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50 transition">⚙️ Pengaturan</a>
            </div>
        </div>
    </div>

    <script>
        function bukaBiodata() {
            const modal = document.getElementById('modalBiodata');
            if (modal) modal.classList.remove('hidden');
        }

        function tutupBiodata() {
            const modal = document.getElementById('modalBiodata');
            if (modal) modal.classList.add('hidden');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') tutupBiodata();
        });

        const CSRF_FOTO = '{{ csrf_token() }}';

        function editNama() {
            const form = document.getElementById('formEditNama');
            const input = document.getElementById('inputNama');
            const status = document.getElementById('statusNama');
            if (!form) return;
            form.classList.remove('hidden');
            input.focus();
            input.select();
            status.classList.add('hidden');
        }

        function batalNama() {
            const form = document.getElementById('formEditNama');
            const input = document.getElementById('inputNama');
            const status = document.getElementById('statusNama');
            if (input) input.value = document.getElementById('namaTampil').textContent.trim();
            if (form) form.classList.add('hidden');
            if (status) status.classList.add('hidden');
        }

        function simpanNama() {
            const form = document.getElementById('formEditNama');
            const input = document.getElementById('inputNama');
            const status = document.getElementById('statusNama');
            const nama = input.value.trim();
            if (!nama) {
                input.focus();
                return;
            }

            fetch('{{ route('nama.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_FOTO
                },
                body: JSON.stringify({ nama: nama })
            })
            .then(r => r.json())
            .then(d => {
                if (d.nama) {
                    document.getElementById('namaTampil').textContent = d.nama;
                    const nav = document.getElementById('avatarNavbar');
                    const mod = document.getElementById('avatarModal');
                    const inisial = d.nama.charAt(0).toUpperCase();
                    if (nav && nav.tagName === 'SPAN') nav.textContent = inisial;
                    if (mod && mod.tagName === 'SPAN') mod.textContent = inisial;
                    if (form) form.classList.add('hidden');
                    status.textContent = '✅ Nama berhasil diubah';
                    status.classList.remove('hidden', 'text-rose-400');
                    status.classList.add('text-emerald-400');
                }
            })
            .catch(() => {
                status.textContent = '❌ Gagal mengubah nama';
                status.classList.remove('hidden', 'text-emerald-400');
                status.classList.add('text-rose-400');
            });
        }

        function unggahFoto(input) {
            const status = document.getElementById('statusFoto');
            if (!input.files || !input.files[0]) return;

            const formData = new FormData();
            formData.append('foto', input.files[0]);

            fetch('{{ route('foto.upload') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_FOTO },
                body: formData
            })
            .then(r => r.json())
            .then(d => {
                if (d.foto) {
                    const url = d.foto + '?t=' + Date.now();
                    const nav = document.getElementById('avatarNavbar');
                    const mod = document.getElementById('avatarModal');
                    if (nav) nav.outerHTML = '<img id="avatarNavbar" src="' + url + '" alt="Foto Admin" class="w-full h-full object-cover">';
                    if (mod) mod.outerHTML = '<img id="avatarModal" src="' + url + '" alt="Foto Admin" class="w-full h-full object-cover">';
                    status.textContent = '✅ Foto profil berhasil diubah';
                    status.classList.remove('hidden', 'text-rose-400');
                    status.classList.add('text-emerald-400');
                }
            })
            .catch(() => {
                status.textContent = '❌ Gagal mengubah foto';
                status.classList.remove('hidden', 'text-emerald-400');
                status.classList.add('text-rose-400');
            })
            .finally(() => {
                input.value = '';
            });
        }
    </script>

</body>
</html>
