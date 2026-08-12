@extends('layouts.admin')

@section('title', 'Pengaturan - Tracer Study')
@section('judul', '⚙️ Pengaturan Aplikasi')

@section('nav_right')
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
@endsection

@section('content')
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

            <!-- ================= LABEL & PILIHAN PERTANYAAN ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">🏷️ Label & Pilihan Pertanyaan Kuesioner</h2>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Label Bagian Identitas</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label NIM</label>
                            <input type="text" name="label_nim" value="{{ old('label_nim', $settings['label_nim'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Kode Perguruan Tinggi</label>
                            <input type="text" name="label_kode_pt" value="{{ old('label_kode_pt', $settings['label_kode_pt'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Tahun Lulus</label>
                            <input type="text" name="label_tahun_lulus" value="{{ old('label_tahun_lulus', $settings['label_tahun_lulus'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Kode Program Studi</label>
                            <input type="text" name="label_kode_prodi" value="{{ old('label_kode_prodi', $settings['label_kode_prodi'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Nama Lengkap</label>
                            <input type="text" name="label_nama" value="{{ old('label_nama', $settings['label_nama'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label No. Telepon / HP</label>
                            <input type="text" name="label_no_hp" value="{{ old('label_no_hp', $settings['label_no_hp'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Alamat Email</label>
                            <input type="text" name="label_email" value="{{ old('label_email', $settings['label_email'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label NIK</label>
                            <input type="text" name="label_nik" value="{{ old('label_nik', $settings['label_nik'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label NPWP</label>
                            <input type="text" name="label_npwp" value="{{ old('label_npwp', $settings['label_npwp'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Placeholder Pilih Tahun Lulus</label>
                            <input type="text" name="placeholder_tahun_lulus" value="{{ old('placeholder_tahun_lulus', $settings['placeholder_tahun_lulus'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Placeholder Pilih Prodi</label>
                            <input type="text" name="placeholder_prodi" value="{{ old('placeholder_prodi', $settings['placeholder_prodi'] ?? '') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Daftar Program Studi (tambah prodi baru cukup tambah baris)</div>
                    <div class="text-xs text-gray-500 mb-2">Format satu baris: <code class="text-amber-400">kode|nama</code>. Contoh: <code class="text-amber-400">54211|Agroteknologi</code></div>
                    <textarea name="prodi_list" rows="12" class="inp font-mono text-xs">{{ old('prodi_list', $settings['prodi_list'] ?? '') }}</textarea>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Label Pertanyaan & Placeholder Lain</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'label_kerja_ya' => 'Label "Ya" (F504)',
                            'label_kerja_tidak' => 'Label "Tidak" (F504)',
                            'label_f502_bulan_ya' => 'Pertanyaan Bulan (Sudah Bekerja)',
                            'label_f505_pendapatan' => 'Pertanyaan Pendapatan',
                            'label_f502_bulan_tidak' => 'Pertanyaan Bulan (Belum Bekerja)',
                            'placeholder_bulan' => 'Placeholder Pilih Bulan',
                            'label_f510_lokasi' => 'Pertanyaan Lokasi Kerja',
                            'placeholder_provinsi' => 'Placeholder Pilih Provinsi',
                            'placeholder_kab_kota' => 'Placeholder Pilih Kab/Kota',
                            'label_provinsi_belum_bekerja' => 'Opsi "Belum Bekerja" (provinsi)',
                            'label_f11_jenis' => 'Pertanyaan Jenis Perusahaan',
                            'label_f5b' => 'Label Nama Perusahaan',
                            'label_f5c' => 'Label Posisi/Jabatan',
                            'label_f5d' => 'Label Tingkat Tempat Kerja',
                            'placeholder_posisi' => 'Placeholder Pilih Posisi',
                            'placeholder_tingkat' => 'Placeholder Pilih Tingkatan',
                            'label_f18_header' => 'Judul "Pertanyaan Studi Lanjut"',
                            'placeholder_sumber_biaya' => 'Placeholder Sumber Biaya',
                            'label_f18b_placeholder' => 'Placeholder Perguruan Tinggi',
                            'label_f18c_placeholder' => 'Placeholder Program Studi',
                            'label_f18d' => 'Label Tanggal Masuk',
                            'label_f12_01' => 'Pertanyaan Sumber Dana Kuliah',
                            'label_f14' => 'Pertanyaan Keselarasan (F14)',
                            'label_f15' => 'Pertanyaan Tingkat Pendidikan (F15)',
                            'label_kompetensi_aspek' => 'Judul Kolom Aspek Kompetensi',
                            'label_kompetensi_a' => 'Judul Kolom Kompetensi Saat Lulus',
                            'label_kompetensi_b' => 'Judul Kolom Kebutuhan di Pekerjaan',
                            'label_metode_instruksi' => 'Instruksi Metode Pembelajaran',
                            'label_mulai_cari_note' => 'Catatan Mulai Mencari Kerja',
                            'label_f301_1' => 'Label "bulan sebelum lulus"',
                            'label_f301_2' => 'Label "bulan sesudah lulus"',
                            'label_f301_3' => 'Label "Saya tidak mencari kerja"',
                            'label_f301_3_note' => 'Catatan "Langsung ke pertanyaan"',
                            'label_cara_cari_note' => 'Catatan Cara Mencari Kerja',
                            'label_f6' => 'Pertanyaan Jumlah Lamaran (F6)',
                            'label_f7' => 'Pertanyaan Respons Lamaran (F7)',
                            'label_f17a' => 'Pertanyaan Wawancara (F17a)',
                            'placeholder_jumlah' => 'Placeholder "... perusahaan"',
                            'label_f10' => 'Pertanyaan Keaktifan (F10)',
                            'label_f10_note' => 'Catatan "pilih 1 jawaban"',
                            'label_f16' => 'Pertanyaan Alasan (F16)',
                            'label_f16_note' => 'Catatan Jawaban F16',
                            'label_lainnya' => 'Placeholder "Lainnya:"',
                            'label_lainnya_tuliskan' => 'Placeholder "Lainnya, tuliskan:"',
                            'label_tuliskan' => 'Placeholder "Tuliskan:"',
                            'label_kontak_ikon' => 'Ikon Kontak Admin',
                            'label_hubungi_admin' => 'Teks "Hubungi admin"',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="inp text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Opsi / Pilihan Jawaban</div>
                    <p class="text-xs text-gray-500 mb-3">Format satu baris: <code class="text-amber-400">nilai|label</code>. Jumlah baris sebaiknya tidak dikurangi karena analisis grafik menghitung berdasarkan nilai.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([
                            'opsi_f8_status' => 'Opsi Status Saat Ini (F8)',
                            'opsi_f12_dana' => 'Opsi Sumber Dana Kuliah (F12)',
                            'opsi_f18a_biaya' => 'Opsi Sumber Biaya Studi Lanjut (F18a)',
                            'opsi_f11_instansi' => 'Opsi Jenis Perusahaan (F11)',
                            'opsi_f14' => 'Opsi Keselarasan (F14)',
                            'opsi_f15' => 'Opsi Tingkat Pendidikan (F15)',
                            'opsi_f10_aktif' => 'Opsi Keaktifan (F10)',
                            'opsi_f17_kompetensi' => 'Aspek Kompetensi (F17)',
                            'opsi_f21_metode' => 'Metode Pembelajaran (F21-27)',
                            'opsi_skala_kompetensi' => 'Skala 1-5 Kompetensi',
                            'opsi_metode_penekanan' => 'Skala Penekanan Metode',
                            'opsi_f5c_posisi' => 'Opsi Posisi Wiraswasta (F5c)',
                            'opsi_f5d_tingkat' => 'Opsi Tingkat Tempat Kerja (F5d)',
                            'opsi_f401_cara' => 'Cara Mencari Kerja (F401-415)',
                            'opsi_f1601_alasan' => 'Alasan Pekerjaan Tidak Sesuai (F16)',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <textarea name="{{ $key }}" rows="7" class="inp font-mono text-xs">{{ old($key, $settings[$key] ?? '') }}</textarea>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ================= PESAN ERROR VALIDASI ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">⚠️ Pesan Error Validasi Kuesioner</h2>
                <p class="text-xs text-gray-500 mb-4">Pesan ini muncul saat alumni mengisi form salah atau tidak lengkap.</p>
                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'pesan_email_required' => 'Email wajib diisi',
                            'pesan_email_format' => 'Format email tidak valid',
                            'pesan_email_domain' => 'Pesan email harus domain (tanpa titik)',
                            'pesan_nik_digits' => 'NIK harus 16 digit',
                            'pesan_no_hp_regex' => 'No. HP harus diawali 08',
                            'pesan_bulan_ya' => 'Bulan mendapat kerja (F502 ya)',
                            'pesan_pendapatan' => 'Pendapatan per bulan',
                            'pesan_bulan_tidak' => 'Bulan belum mendapat kerja (F502 tidak)',
                            'pesan_provinsi' => 'Lokasi provinsi',
                            'pesan_kab_kota' => 'Kabupaten/kota',
                            'pesan_instansi' => 'Jenis perusahaan',
                            'pesan_f5c' => 'Posisi wiraswasta (F5c)',
                            'pesan_f5d' => 'Tingkat tempat kerja (F5d)',
                            'pesan_sumber_biaya' => 'Sumber biaya studi lanjut',
                            'pesan_perguruan_tinggi' => 'Perguruan tinggi studi lanjut',
                            'pesan_program_studi' => 'Program studi studi lanjut',
                            'pesan_tanggal_masuk' => 'Tanggal masuk studi lanjut',
                            'pesan_sumber_dana' => 'Sumber dana kuliah',
                            'pesan_f14' => 'Keselarasan bidang (F14)',
                            'pesan_f15' => 'Tingkat pendidikan (F15)',
                            'pesan_f302' => 'Mulai cari kerja sebelum lulus',
                            'pesan_f303' => 'Mulai cari kerja setelah lulus',
                            'pesan_f6' => 'Jumlah perusahaan dilamar (F6)',
                            'pesan_f7' => 'Jumlah perusahaan merespons (F7)',
                            'pesan_f17a' => 'Jumlah perusahaan wawancara (F17a)',
                            'pesan_f11_lainnya' => 'Jenis instansi lainnya (F11)',
                            'pesan_f416_lainnya' => 'Cara mencari kerja lainnya (F4)',
                            'pesan_f10_lainnya' => 'Keaktifan mencari kerja lainnya (F10)',
                            'pesan_f1614_lainnya' => 'Alasan pekerjaan lainnya (F16)',
                            'pesan_f12_lainnya' => 'Sumber dana kuliah lainnya (F12)',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="inp text-sm">
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

            <!-- ================= HALAMAN LOGIN ================= -->
            <div class="glass rounded-2xl p-6 shadow-xl fade-up">
                <h2 class="sec-title">🔐 Halaman Login Admin</h2>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Teks Halaman Login</div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Ikon Login</label>
                            <input type="text" name="login_ikon" value="{{ old('login_ikon', $settings['login_ikon'] ?? '🔐') }}" class="inp" placeholder="🔐">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Login</label>
                            <input type="text" name="login_judul" value="{{ old('login_judul', $settings['login_judul'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Subjudul Login</label>
                            <input type="text" name="login_subjudul" value="{{ old('login_subjudul', $settings['login_subjudul'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Email</label>
                            <input type="text" name="login_label_email" value="{{ old('login_label_email', $settings['login_label_email'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Label Kata Sandi</label>
                            <input type="text" name="login_label_password" value="{{ old('login_label_password', $settings['login_label_password'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks "Ingat saya"</label>
                            <input type="text" name="login_ingat_saya" value="{{ old('login_ingat_saya', $settings['login_ingat_saya'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teks Tombol Masuk</label>
                            <input type="text" name="login_teks_tombol" value="{{ old('login_teks_tombol', $settings['login_teks_tombol'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Link "Lupa Password Super Admin?"</label>
                            <input type="text" name="login_link_lupa" value="{{ old('login_link_lupa', $settings['login_link_lupa'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Link "Riwayat Login & Password"</label>
                            <input type="text" name="login_link_riwayat" value="{{ old('login_link_riwayat', $settings['login_link_riwayat'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Link "Kembali ke Halaman Login"</label>
                            <input type="text" name="login_link_kembali" value="{{ old('login_link_kembali', $settings['login_link_kembali'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Link "Kembali ke Form Kuesioner"</label>
                            <input type="text" name="login_link_kuesioner" value="{{ old('login_link_kuesioner', $settings['login_link_kuesioner'] ?? '') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Judul Tab Browser</label>
                            <input type="text" name="login_judul_browser" value="{{ old('login_judul_browser', $settings['login_judul_browser'] ?? '') }}" class="inp">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Warna Halaman Login</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar (utama)</label>
                            <input type="color" name="login_warna_latar" value="{{ old('login_warna_latar', $settings['login_warna_latar'] ?? '#0f172a') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Latar (kedua)</label>
                            <input type="color" name="login_warna_latar2" value="{{ old('login_warna_latar2', $settings['login_warna_latar2'] ?? '#1e1b4b') }}" class="inp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Warna Aksen</label>
                            <input type="color" name="login_warna_aksen" value="{{ old('login_warna_aksen', $settings['login_warna_aksen'] ?? '#fbbf24') }}" class="inp">
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
                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Teks di Halaman Dashboard</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'dashboard_judul_browser' => 'Judul Tab Browser',
                            'dashboard_ikon' => 'Ikon Judul Dashboard',
                            'dashboard_nav_pengaturan' => 'Menu "Pengaturan"',
                            'dashboard_nav_alumni' => 'Tombol "Kelola Data Alumni"',
                            'dashboard_nav_kuesioner' => 'Menu "Lihat Form Kuesioner"',
                            'dashboard_nav_akun' => 'Menu "Akun Admin"',
                            'dashboard_nav_ganti_password' => 'Tombol "Ganti Password"',
                            'dashboard_nav_keluar' => 'Tombol "Keluar"',
                            'dashboard_filter_judul' => 'Judul Filter Analisis',
                            'dashboard_filter_tahun' => 'Label Filter Tahun Lulus',
                            'dashboard_filter_semua_tahun' => 'Opsi "Semua Tahun Lulus"',
                            'dashboard_filter_prodi' => 'Label Filter Program Studi',
                            'dashboard_filter_semua_prodi' => 'Opsi "Semua Program Studi"',
                            'dashboard_tombol_kurva' => 'Tombol "Cek & Buka Kurva Analitik"',
                            'dashboard_kosong_ikon' => 'Ikon Pesan Belum Filter',
                            'dashboard_kosong_judul' => 'Judul Pesan Belum Filter',
                            'dashboard_kosong_teks' => 'Teks Pesan Belum Filter',
                            'dashboard_stat_menampilkan' => 'Teks "Menampilkan Analisis:"',
                            'dashboard_stat_semua_tahun' => 'Teks "Semua Tahun"',
                            'dashboard_stat_prodi' => 'Teks "Prodi:"',
                            'dashboard_stat_semua_prodi' => 'Teks "Semua Prodi"',
                            'dashboard_stat_total' => 'Teks "Total Responden:"',
                            'dashboard_stat_alumni' => 'Kata "Alumni"',
                            'dashboard_stat_total_kartu' => 'Label Kartu Total Responden',
                            'dashboard_stat_bekerja' => 'Label Kartu Bekerja',
                            'dashboard_stat_mencari' => 'Label Kartu Aktif Mencari Kerja',
                            'dashboard_stat_lanjut' => 'Label Kartu Lanjut Kuliah',
                            'dashboard_ikon_total' => 'Ikon Kartu Total Responden',
                            'dashboard_ikon_bekerja' => 'Ikon Kartu Bekerja',
                            'dashboard_ikon_mencari' => 'Ikon Kartu Mencari Kerja',
                            'dashboard_ikon_lanjut' => 'Ikon Kartu Lanjut Kuliah',
                            'dashboard_unduh_excel' => 'Tombol "Unduh Excel"',
                            'dashboard_unduh_tahun' => 'Kata "Tahun" (tombol unduh)',
                            'dashboard_unduh_semua' => 'Teks "(Semua Tahun)"',
                        ] as $key => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="inp text-sm">
                        </div>
                        @endforeach
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
                            'chart_sumber_biaya' => ['Sumber Biaya Lanjut Kuliah', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                            'chart_prodi' => ['Program Studi Studi Lanjut', ['line', 'bar', 'pie', 'doughnut', 'radar']],
                        ];
                        $tipeLabel = ['line' => 'Garis (Line)', 'bar' => 'Batang (Bar)', 'pie' => 'Lingkaran (Pie)', 'doughnut' => 'Donat (Doughnut)', 'radar' => 'Jaring (Radar)'];
                    @endphp
                    <div class="space-y-3">
                        <p class="text-xs text-gray-500 -mb-1">Grafik <b>Status Kesibukan</b>, <b>Jenis Perusahaan</b>, dan <b>Sumber Dana Kuliah</b> menampilkan label sesuai daftar opsi jawaban kuesioner (bagian <b>Opsi Jawaban</b> di atas) dan warna dihitung otomatis — tidak perlu diatur per irisan.</p>
                        @foreach ($grafikSettings as $base => [$label, $tipeOptions])
                        @php $slugItem = substr($base, 6); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end border-b border-slate-700/40 pb-3 last:border-b-0">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-300 font-medium">{{ $label }}</span>
                                <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer ml-auto">
                                    <input type="hidden" name="{{ $base }}_tampil" value="0">
                                    <input type="checkbox" name="{{ $base }}_tampil" value="1" class="rounded text-amber-400 bg-slate-800 border-slate-600" {{ (old($base . '_tampil', $settings[$base . '_tampil'] ?? '1') == '1') ? 'checked' : '' }}>
                                    Tampilkan
                                </label>
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
                        @if (in_array($base, ['chart_pendapatan', 'chart_kurva', 'chart_cara', 'chart_keaktifan', 'chart_kompetensi', 'chart_metode']))
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
                            'judul_chart_sumber_biaya' => 'Sumber Biaya Lanjut Kuliah',
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

        <!-- ================= DATA WILAYAH ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up mt-6">
            <h2 class="sec-title">🗺️ Data Wilayah (Provinsi & Kabupaten/Kota)</h2>
            <p class="text-xs text-gray-500 mb-4">Data ini dipakai untuk dropdown lokasi kerja di kuesioner dan label grafik lokasi di dashboard. Perubahan langsung tersimpan tanpa menekan "Simpan Pengaturan".</p>

            <form action="{{ route('wilayah.provinsi.store') }}" method="POST" class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4 mb-5">
                @csrf
                <h3 class="text-sm font-bold text-amber-400 mb-3">➕ Tambah Provinsi Baru</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Provinsi</label>
                        <input type="text" name="nama_provinsi" required class="inp text-sm" placeholder="cth: Prov. Sumatera Barat">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kode Provinsi</label>
                        <input type="text" name="kode_provinsi" required class="inp text-sm" placeholder="cth: 80000">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold py-2.5 px-4 rounded-xl transition duration-200 cursor-pointer text-sm">
                            ➕ Tambah Provinsi
                        </button>
                    </div>
                </div>
                @error('nama_provinsi')
                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </form>

            <div class="space-y-3">
                @foreach($provinsiRows as $prov)
                    <div class="bg-slate-900/50 border border-slate-700/60 rounded-xl p-4">
                        <details class="group">
                            <summary class="list-none cursor-pointer">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-slate-200">{{ $prov->nama_provinsi }}</span>
                                    <span class="text-xs text-gray-500">({{ $prov->kode_provinsi }})</span>
                                    <span class="text-xs bg-slate-700/60 px-2 py-0.5 rounded-full">{{ $prov->kabRows->count() }} kab/kota</span>
                                    <span class="ml-auto text-xs text-amber-400 group-open:hidden">▾ Kelola</span>
                                    <span class="ml-auto text-xs text-amber-400 hidden group-open:inline">▴ Tutup</span>
                                </div>
                            </summary>
                            <div class="mt-3 pt-3 border-t border-slate-700/60 space-y-3">
                                <form action="{{ route('wilayah.provinsi.update', $prov->id) }}" method="POST" class="flex flex-wrap items-end gap-2">
                                    @csrf
                                    <div class="flex-1 min-w-[200px]">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Provinsi</label>
                                        <input type="text" name="nama_provinsi" value="{{ old('nama_provinsi', $prov->nama_provinsi) }}" class="inp text-sm">
                                    </div>
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Kode</label>
                                        <input type="text" name="kode_provinsi" value="{{ old('kode_provinsi', $prov->kode_provinsi) }}" class="inp text-sm">
                                    </div>
                                    <button type="submit" class="bg-sky-500 hover:bg-sky-400 text-white font-semibold py-2.5 px-4 rounded-xl transition duration-200 cursor-pointer text-sm">💾 Simpan</button>
                                    <button type="submit" form="hapus-provinsi-{{ $prov->id }}" onclick="return confirm('Hapus provinsi beserta seluruh kab/kota-nya?')" class="bg-red-600 hover:bg-red-500 text-white font-semibold py-2.5 px-4 rounded-xl transition duration-200 cursor-pointer text-sm">🗑️ Hapus</button>
                                </form>
                                <form id="hapus-provinsi-{{ $prov->id }}" action="{{ route('wilayah.provinsi.destroy', $prov->id) }}" method="POST">
                                    @csrf
                                </form>

                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 mb-2">Kabupaten/Kota ({{ $prov->kabRows->count() }})</h4>
                                    <div class="space-y-1.5">
                                        @foreach($prov->kabRows as $kab)
                                            <form action="{{ route('wilayah.kabkota.update', $kab->id) }}" method="POST" class="flex flex-wrap items-end gap-2 bg-slate-800/40 rounded-lg p-2">
                                                @csrf
                                                <div class="flex-1 min-w-[180px]">
                                                    <input type="text" name="nama_kab_kota" value="{{ old('nama_kab_kota', $kab->nama_kab_kota) }}" class="inp text-sm">
                                                </div>
                                                <div class="w-32">
                                                    <input type="text" name="kode_kab_kota" value="{{ old('kode_kab_kota', $kab->kode_kab_kota) }}" class="inp text-sm">
                                                </div>
                                                <button type="submit" class="bg-sky-500 hover:bg-sky-400 text-white font-semibold py-2 px-3 rounded-lg transition duration-200 cursor-pointer text-xs">💾</button>
                                                <button type="submit" form="hapus-kab-{{ $kab->id }}" onclick="return confirm('Hapus kabupaten/kota ini?')" class="bg-red-600 hover:bg-red-500 text-white font-semibold py-2 px-3 rounded-lg transition duration-200 cursor-pointer text-xs">🗑️</button>
                                            </form>
                                            <form id="hapus-kab-{{ $kab->id }}" action="{{ route('wilayah.kabkota.destroy', $kab->id) }}" method="POST">
                                                @csrf
                                            </form>
                                        @endforeach
                                    </div>
                                </div>

                                <form action="{{ route('wilayah.kabkota.store') }}" method="POST" class="bg-slate-800/40 rounded-lg p-3">
                                    @csrf
                                    <input type="hidden" name="nama_provinsi" value="{{ $prov->nama_provinsi }}">
                                    <input type="hidden" name="kode_provinsi" value="{{ $prov->kode_provinsi }}">
                                    <label class="block text-xs font-medium text-gray-400 mb-1">➕ Tambah Kab/Kota (format <code class="text-amber-400">kode|nama</code> per baris)</label>
                                    <textarea name="daftar_kab_kota" rows="3" class="inp text-sm" placeholder="100100|Kab. Batanghari&#10;100200|Kab. Bungo"></textarea>
                                    <button type="submit" class="mt-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold py-2 px-4 rounded-xl transition duration-200 cursor-pointer text-sm">➕ Tambah ke "{{ $prov->nama_provinsi }}"</button>
                                </form>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>

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
@endsection

@section('script')
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
@endsection
