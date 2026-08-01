<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analitik Admin - Tracer Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-900 text-white font-sans">

    <!-- NAVBAR HEADER -->
    <nav class="bg-slate-800 p-4 border-b border-slate-700 shadow-xl">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wider text-amber-400 uppercase">📊 Analitik Tracer Study UMMY Solok</h1>
            <div class="flex items-center space-x-2 text-white">
                <button type="button" 
                        onclick="openModalAlumni()" 
                        class="text-sm bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold px-4 py-2 rounded shadow transition duration-200">
                    + Kelola Data Alumni
                </button>
                <a href="{{ route('kuesioner.index') }}" class="text-sm bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded border border-slate-500">Lihat Form Kuesioner</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        <!-- FILTER FORM -->
        <div class="bg-slate-800 border border-slate-700 p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-blue-400 flex items-center">🔍 Filter Analisis Responden</h2>
            <form action="{{ route('kuesioner.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">1. Pilih Tahun Lulus</label>
                    <select name="tahun_lulus" class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white outline-none focus:border-amber-400">
                        <option value="">-- Semua Tahun Lulus --</option>
                        @foreach($listTahun as $th)
                            <option value="{{ $th }}" {{ $tahunTerpilih == $th ? 'selected' : '' }}>Tahun Lulus {{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">2. Pilihan Program Studi</label>
                    <select name="kode_prodi" class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white outline-none focus:border-amber-400">
                        <option value="">-- Semua Program Studi --</option>
                        @foreach($prodiLabels as $kode => $nama)
                            <option value="{{ $kode }}" {{ $prodiTerpilih == $kode ? 'selected' : '' }}>[{{ $kode }}] {{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-2 px-4 rounded transition duration-150 uppercase tracking-wide">
                        ⚡ Cek & Buka Kurva Analitik
                    </button>
                </div>
            </form>
        </div>

        <!-- JIKA DUSTBOARD BELUM DIFILTER -->
        @if(!$tahunTerpilih && !$prodiTerpilih)
            <div class="bg-blue-950 border border-blue-800 text-blue-300 p-8 rounded-xl text-center shadow-lg">
                <h3 class="text-xl font-bold mb-2">Silakan Gunakan Filter di Atas</h3>
                <p class="text-sm text-gray-400">Pilih Tahun Lulus beserta Program Studi terlebih dahulu, kemudian klik tombol "Cek & Buka Kurva Analitik" untuk melihat grafik penelusuran alumni.</p>
            </div>
        @else

            <!-- INFO FILTER TERPILIH & DOWNLOAD EXCEL -->
            <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl flex flex-col md:flex-row justify-between items-center gap-4 shadow-md">
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
                
                <div class="flex items-center gap-3">
                    <div class="text-md font-bold bg-slate-900 px-4 py-1.5 border border-slate-700 rounded-lg text-emerald-400/90 shadow-inner">
                        Total Responden: {{ $totalAlumni }} Alumni
                    </div>
                    
                    <a href="{{ route('kuesioner.export', ['tahun_lulus' => $tahunTerpilih, 'kode_prodi' => $prodiTerpilih]) }}" class="inline-flex items-center gap-2 text-sm bg-emerald-600 hover:bg-emerald-500 font-bold px-4 py-2 rounded-lg border border-emerald-500 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh Excel {{ $tahunTerpilih ? 'Tahun ' . $tahunTerpilih : '(Semua Tahun)' }}
                    </a>
                </div>
            </div>

            <!-- GRAFIK / CHART SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💼 Status Kesibukan Alumni Saat Ini</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartStatusKerja"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💰 Distribusi Pendapatan Per Bulan</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💼 Jenis Perusahaan Tempat Bekerja</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartPerusahaanKerja"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💰 Sumber Dana Lanjut Kuliah</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartSumberDana"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💼 Jenis Jabatan Tempat Bekerja</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartPosisiJabatan"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💼 Jenis Tingkat Tempat Kerja</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartPilihTingkat"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">📍 Sebaran Provinsi Wilayah Kerja</h3>
                    <div class="relative h-64">
                        <canvas id="chartLokasi"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">📍 Sebaran Kab/Kota Wilayah Kerja</h3>
                    <div class="relative h-64">
                        <canvas id="chartLokasiKota"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🎓 Destinasi Kampus Alumni Lanjut Studi</h3>
                    <div class="relative h-64">
                        <canvas id="chartTempatKuliah"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">🧠 Perbandingan Kompetensi: Dikuasai (A) vs Diperlukan Dunia Kerja (B)</h3>
                <div class="relative h-80 flex justify-center">
                    <canvas id="chartKompetensi"></canvas>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">🏫 Penekanan Metode Pembelajaran Saat Kuliah </h3>
                <div class="relative h-72">
                    <canvas id="chartMetodeBelajar"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">⏱️ Masa Tunggu Mendapat Pekerjaan (1-12 Bulan)</h3>
                    <div class="relative h-64">
                        <canvas id="chartWaktuCariKerja"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">📣 Saluran/Metode Utama Mencari Pekerjaan</h3>
                    <div class="relative h-64">
                        <canvas id="chartCaraCariKerja"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md col-span-1 lg:col-span-2">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🏢 Rata-rata Rasio Proses Lamaran Kerja Per Alumni</h3>
                    <div class="relative h-64">
                        <canvas id="chartRasioLamaran"></canvas>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🔥 Keaktifan Mencari Kerja</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartKeaktifan"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">⚠️ Alasan Mengambil Pekerjaan Yang Tidak Sesuai Bidang Pendidikan</h3>
                <div class="relative h-72">
                    <canvas id="chartAlasanTidakSesuai"></canvas>
                </div>
            </div>

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
                const keaktifanData = @js(array_values($keaktifan));
                
                const alasanLabels = @js(array_keys($alasanTidakSesuai));
                const alasanData = @js(array_values($alasanTidakSesuai));

                // Opsi Dasar Tema Gelap
                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            labels: { color: '#cbd5e1' } 
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

                // Helper Function Render Chart
                function renderChartSafe(elementId, config) {
                    const canvas = document.getElementById(elementId);
                    if (canvas) {
                        const existingChart = Chart.getChart(canvas);
                        if (existingChart) {
                            existingChart.destroy();
                        }
                        return new Chart(canvas, config);
                    }
                }

                // Render Seluruh Chart
                renderChartSafe('chartStatusKerja', {
                    type: 'pie',
                    data: { labels: ['Bekerja', 'Wiraswasta', 'Lanjut Kuliah', 'Cari Kerja', 'Belum Bekerja'], datasets: [{ data: statusKerjaData, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#000000'] }] },
                    options: baseOptions
                });

                renderChartSafe('chartPendapatan', {
                    type: 'doughnut',
                    data: { labels: ['< 2 Juta', '2 - 5 Juta', '> 5 Juta'], datasets: [{ data: pendapatanData, backgroundColor: ['#494d53','#7e48fc','#ec4899'] }] },
                    options: baseOptions
                });

                renderChartSafe('chartPerusahaanKerja', {
                    type: 'pie',
                    data: { labels: ['Instansi Pemerintah', 'BUMN/BUMD', 'Institusi', 'Lembaga Swadaya', 'Swasta', 'Wiraswasta', 'Lainnya'], datasets: [{ data: statusPerusahaanKerja, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#000000', '#e6dede', '#f100dd'] }] },
                    options: baseOptions
                });

                renderChartSafe('chartSumberDana', {
                    type: 'pie',
                    data: { labels: ['Biaya Sendiri', 'Beasiswa ADIK', 'Beasiswa BIDIKMISI', 'Beasiswa PPA', 'Beasiswa AFIRMASI', 'Beasiswa Swasta', 'Lainnya'], datasets: [{ data: SumberDana, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#000000', '#e6dede', '#f100dd'] }] },
                    options: baseOptions
                });

                renderChartSafe('chartLokasi', {
                    type: 'bar', indexAxis: 'y',
                    data: { labels: lokasiLabels.length ? lokasiLabels : ['Belum Ada'], datasets: [{ label: 'Jumlah Responden provinsi', data: lokasiData.length ? lokasiData : [0], backgroundColor: '#14b8a6' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartLokasiKota', {
                    type: 'bar', indexAxis: 'y',
                    data: { labels: lokasiLabels.length ? kotaLabels : ['Belum Ada Data Kota'], datasets: [{ label: 'Jumlah Responden Kab/Kota', data: kotaData.length ? kotaData : [0], backgroundColor: '#d6f0ed' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartPosisiJabatan', {
                    type: 'bar', indexAxis: 'y',
                    data: { labels: lokasiJabatan.length ? PosisiJabatan : ['Belum Ada Jabatan'], datasets: [{ label: 'Posisi/Jabatan', data: lokasiJabatan.length ? lokasiJabatan : [0], backgroundColor: '#dd1313' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartPilihTingkat', {
                    type: 'bar', indexAxis: 'y',
                    data: { labels: lokasiTingkat.length ? PilihTingkat : ['Belum Ada Data Tingkat'], datasets: [{ label: 'Tingkat Tempat Kerja', data: lokasiTingkat.length ? lokasiTingkat : [0], backgroundColor: '#55ff11' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartTempatKuliah', {
                    type: 'bar',
                    data: { labels: kuliahLabels.length ? kuliahLabels : ['Tidak Ada Data Kuliah'], datasets: [{ label: 'Alumni', data: kuliahData.length ? kuliahData : [0], backgroundColor: '#a855f7' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartKompetensi', {
                    type: 'radar',
                    data: {
                        labels: ['Etika', 'Keahlian Inti', 'Bahasa Inggris', 'TIK', 'Komunikasi', 'Kerjasama Tim', 'Pengembangan Diri'],
                        datasets: [
                            { label: 'Kompetensi Dikuasai (A)', data: kDikuasai, borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.2)' },
                            { label: 'Diperlukan Dunia Kerja (B)', data: kDiperlukan, borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.2)' }
                        ]
                    },
                    options: radarScaleOptions
                });

                renderChartSafe('chartMetodeBelajar', {
                    type: 'bar',
                    data:  {
                        labels: metodeLabels,
                        datasets: [
                            { label: 'Jumlah Responden Memilih "Sangat Besar"', data: dataSangatBesar, backgroundColor: '#f43f5e' },
                            { label: 'Jumlah Responden Memilih "Besar"', data: dataBesar, backgroundColor: '#ffa601' },
                            { label: 'Jumlah Responden Memilih "Cukup Besar"', data: dataCukupBesar, backgroundColor: '#d1ff02' },
                            { label: 'Jumlah Responden Memilih "Kurang"', data: dataKurang, backgroundColor: '#09ff00' },
                            { label: 'Jumlah Responden Memilih "Tidak Sama Sekali"', data: dataTidakSama, backgroundColor: '#04ffde' }
                        ]
                    },
                    options: linearScaleOptions
                });

                renderChartSafe('chartWaktuCariKerja', {
                    type: 'line',
                    data: { labels: waktuLabels, datasets: [{ label: 'Alumni', data: waktuData, borderColor: '#10b981', tension: 0.2, fill:false }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartCaraCariKerja', {
                    type: 'doughnut',
                    data: { labels: caraLabels, datasets: [{ label: 'Pilihan Metode Pencarian', data: caraData, backgroundColor: [
                        '#ff0505', '#ff6004', '#f59e0b', '#48ff00', '#07a70fa2',
                        '#00ff95', '#0093f5', '#0206f3', '#4c00ff', '#ff02ff',
                        'rgb(255, 5, 143)', '#fc003f', '#353b42', '#f7f7f7', '#000000'] }] },
                    options: baseOptions
                });

                renderChartSafe('chartRasioLamaran', {
                    type: 'bar',
                    data: { labels: ['Perusahaan Dilamar', 'Mendapat Respons', 'Diundang Wawancara'], datasets: [{ label: 'Rata-rata Jumlah Perusahaan', data: avgLamaran, backgroundColor: '#eab308' }] },
                    options: linearScaleOptions
                });

                renderChartSafe('chartKeaktifan', {
                    type: 'pie',
                    data: { 
                        labels: ['Aktif Mencari', 'Tidak Aktif'], 
                        datasets: [{ 
                            data: [
                                {{ (int)($keaktifan['Aktif'] ?? 0) }}, 
                                {{ (int)($keaktifan['Tidak Aktif'] ?? 0) }}
                            ], 
                            backgroundColor: ['#22c55e', '#64748b'] 
                        }] 
                    },
                    options: baseOptions
                });

                renderChartSafe('chartAlasanTidakSesuai', {
                    type: 'bar',
                    data: { labels: alasanLabels, datasets: [{ label: 'Frekuensi Alasan Terbanyak', data: alasanData, backgroundColor: '#f97316' }] },
                    options: linearScaleOptions
                });
            </script>
        @endif
    </div>

    <!-- MODAL KELOLA DATA ALUMNI (DI LUAR IF/ELSE) -->
    <div id="modalAlumni" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-lg rounded-xl shadow-2xl overflow-hidden transform transition-all">
            
            <!-- Header Modal -->
            <div class="bg-slate-750 p-4 border-b border-slate-700 flex justify-between items-center">
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

    <!-- SCRIPT UTAMA KONTROL MODAL (ALWAYS ACTIVE) -->
    <script>
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
    </script>

</body>
</html>