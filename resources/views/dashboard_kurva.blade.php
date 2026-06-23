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

    <nav class="bg-slate-800 p-4 border-b border-slate-700 shadow-xl">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wider text-amber-400 uppercase">📊 Analitik Tracer Study UMMY Solok</h1>
            <a href="{{ route('kuesioner.index') }}" class="text-sm bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded border border-slate-500">Lihat Form Kuesioner</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

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

        @if(!$tahunTerpilih && !$prodiTerpilih)
            <div class="bg-blue-950 border border-blue-800 text-blue-300 p-8 rounded-xl text-center shadow-lg">
                <h3 class="text-xl font-bold mb-2">Silakan Gunakan Filter di Atas</h3>
                <p class="text-sm text-gray-400">Pilih Tahun Lulus beserta Program Studi terlebih dahulu, kemudian klik tombol "Cek & Buka Kurva Analitik" untuk melihat grafik penelusuran alumni.</p>
            </div>
        @else

            <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl flex flex-col md:flex-row justify-between items-center gap-4 shadow-md">
                <div class="text-sm text-gray-300">
                    Menampilkan Analisis: <span class="text-amber-400 font-bold">{{ $tahunTerpilih ?? 'Semua Tahun' }}</span> | Prodi: <span class="text-blue-400 font-bold">{{ $prodiLabels[$prodiTerpilih] ?? 'Semua Prodi' }}</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💼 Status Kesibukan Alumni Saat Ini</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartStatusKerja"></canvas></div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">💰 Distribusi Pendapatan Per Bulan</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartPendapatan"></canvas></div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">📍 Sebaran Provinsi Wilayah Kerja</h3>
                    <div class="relative h-64">
                        <canvas id="chartLokasi"></canvas></div>
                </div>

                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🎓 Destinasi Kampus Alumni Lanjut Studi</h3>
                    <div class="relative h-64">
                        <canvas id="chartTempatKuliah"></canvas></div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">🧠 Perbandingan Kompetensi: Dikuasai (A) vs Diperlukan Dunia Kerja (B)</h3>
                <div class="relative h-80 flex justify-center">
                    <canvas id="chartKompetensi"></canvas></div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">🏫 Penekanan Metode Pembelajaran Saat Kuliah (Kategori Pilihan: "Besar/Sangat Besar")</h3>
                <div class="relative h-72">
                    <canvas id="chartMetodeBelajar"></canvas></div>
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
                        <canvas id="chartCaraCariKerja"></canvas></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md col-span-1 lg:col-span-2">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🏢 Rata-rata Rasio Proses Lamaran Kerja Per Alumni</h3>
                    <div class="relative h-64">
                        <canvas id="chartRasioLamaran"></canvas></div>
                </div>
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                    <h3 class="text-md font-semibold text-gray-300 mb-4">🔥 Keaktifan Mencari Kerja</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartKeaktifan"></canvas></div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl shadow-md">
                <h3 class="text-md font-semibold text-gray-300 mb-4">⚠️ Alasan Mengambil Pekerjaan Yang Tidak Sesuai Bidang Pendidikan</h3>
                <div class="relative h-72">
                    <canvas id="chartAlasanTidakSesuai"></canvas></div>
            </div>

            <script>
                // Tangkap data dari Blade array
                const statusKerjaData = @js(array_values($statusKerja));
                const pendapatanData = @js(array_values($pendapatan));
                
                const lokasiLabels = @js(array_keys($lokasiKerja));
                const lokasiData = @js(array_values($lokasiKerja));

                const kuliahLabels = @js(array_keys($tempatKuliah));
                const kuliahData = @js(array_values($tempatKuliah));

                const kDikuasai = @js($kompetensiDikuasai);
                const kDiperlukan = @js($kompetensiDiperlukan);

                const metodeLabels = @js(array_keys($metodeBelajar));
                const metodeData = @js(array_values($metodeBelajar));

                const waktuLabels = @js(array_keys($waktuCariKerja));
                const waktuData = @js(array_values($waktuCariKerja));

                const caraLabels = @js(array_keys($caraCariKerja));
                const caraData = @js(array_values($caraCariKerja));

                const avgLamaran = @js(array_values($avgLamaran));
                const keaktifanData = @js(array_values($keaktifan));
                
                const alasanLabels = @js(array_keys($alasanTidakSesuai));
                const alasanData = @js(array_values($alasanTidakSesuai));

                // 2. Opsi Dasar Tema Gelap (Untuk Pie / Doughnut yang tidak memiliki sumbu X & Y)
                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            labels: { color: '#cbd5e1' } 
                        }
                    }
                };

                // 3. Opsi Spesifik untuk Bar & Line Chart (Definisikan sumbu X dan Y dengan tegas)
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

                // 4. Opsi Spesifik untuk Radar Chart (Gunakan sumbu melingkar 'r', jangan campur dengan X/Y)
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

                // 5. Helper Function untuk menghapus sisa instance chart lama (Mencegah glitch tumpang tindih)
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

                // --- Mulai Inisialisasi Seluruh Chart ---

                // 1. Status Kerja (Pie Chart)
                renderChartSafe('chartStatusKerja', {
                    type: 'pie',
                    data: { labels: ['Bekerja', 'Wiraswasta', 'Lanjut Kuliah', 'Mencari Kerja'], datasets: [{ data: statusKerjaData, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444'] }] },
                    options: baseOptions
                });

                // 2. Pendapatan (Doughnut)
                renderChartSafe('chartPendapatan', {
                    type: 'doughnut',
                    data: { labels: ['< 2 Juta', '2 - 5 Juta', '> 5 Juta'], datasets: [{ data: pendapatanData, backgroundColor: ['#64748b','#8b5cf6','#ec4899'] }] },
                    options: baseOptions
                });

                // 3. Lokasi Provinsi (Horizontal Bar)
                renderChartSafe('chartLokasi', {
                    type: 'bar', indexAxis: 'y',
                    data: { labels: lokasiLabels.length ? lokasiLabels : ['Belum Ada'], datasets: [{ label: 'Jumlah Responden', data: lokasiData.length ? lokasiData : [0], backgroundColor: '#14b8a6' }] },
                    options: linearScaleOptions
                });

                // 4. Tempat Kuliah Lanjut
                renderChartSafe('chartTempatKuliah', {
                    type: 'bar',
                    data: { labels: kuliahLabels.length ? kuliahLabels : ['Tidak Ada Data Kuliah'], datasets: [{ label: 'Alumni', data: kuliahData.length ? kuliahData : [0], backgroundColor: '#a855f7' }] },
                    options: linearScaleOptions
                });

                // 5. Radar Kompetensi (Menggunakan radarScaleOptions secara eksklusif)
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

                // 6. Metode Belajar
                renderChartSafe('chartMetodeBelajar', {
                    type: 'bar',
                    data: { labels: metodeLabels, datasets: [{ label: 'Jumlah Responden Memilih "Sangat Berpengaruh"', data: metodeData, backgroundColor: '#f43f5e' }] },
                    options: linearScaleOptions
                });

                // 7. Waktu Cari Kerja
                renderChartSafe('chartWaktuCariKerja', {
                    type: 'line',
                    data: { labels: waktuLabels, datasets: [{ label: 'Alumni', data: waktuData, borderColor: '#10b981', tension: 0.2, fill:false }] },
                    options: linearScaleOptions
                });

                // 8. Cara Mencari Pekerjaan
                renderChartSafe('chartCaraCariKerja', {
                    type: 'pie',
                    data: { labels: caraLabels, datasets: [{ label: 'Pilihan Metode Pencarian', data: caraData, backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#10eb1b', '#8b5cf6',
                '#0e0d0d', '#14b8a6', '#a855f7', '#06b6d4', '#f43f5e',
                '#eab308', '#22c55e', '#64748b', '#f97316', '#475569'] }] },
                    options: baseOptions
                });

                // 9. Rasio Proses Lamaran
                renderChartSafe('chartRasioLamaran', {
                    type: 'bar',
                    data: { labels: ['Perusahaan Dilamar', 'Mendapat Respons', 'Diundang Wawancara'], datasets: [{ label: 'Rata-rata Jumlah Perusahaan', data: avgLamaran, backgroundColor: '#eab308' }] },
                    options: linearScaleOptions
                });

                // 10. Keaktifan
                renderChartSafe('chartKeaktifan', {
                type: 'pie',
                data: { labels: ['Aktif Mencari', 'Tidak Aktif'], datasets: [{ data: keaktifanData, backgroundColor: ['#22c55e', '#64748b'] }] },
                options: baseOptions
                });

                // 11. Alasan Tidak Sesuai
                renderChartSafe('chartAlasanTidakSesuai', {
                    type: 'bar',
                    data: { labels: alasanLabels, datasets: [{ label: 'Frekuensi Alasan Terbanyak', data: alasanData, backgroundColor: '#f97316' }] },
                    options: linearScaleOptions
                });
            </script>
        @endif
    </div>
</body>
</html>