<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('kuesioner_judul_browser', 'Tracer Study - LPKM UMMY') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --kac-aksen: {{ \App\Models\Setting::get('kuesioner_warna_aksen', '#fbbf24') }};
            --kac-latar1: {{ \App\Models\Setting::get('kuesioner_warna_latar', '#0f172a') }};
            --kac-latar2: {{ \App\Models\Setting::get('kuesioner_warna_latar2', '#1e1b4b') }};

            --kac-univ: {{ \App\Models\Setting::get('kuesioner_warna_univ', '#7dd3fc') }};
            --kac-subjudul: {{ \App\Models\Setting::get('kuesioner_warna_subjudul', '#94a3b8') }};
            --kac-inst: {{ \App\Models\Setting::get('kuesioner_warna_instruksi', '#6b7280') }};
            --kac-label: {{ \App\Models\Setting::get('kuesioner_warna_label', '#94a3b8') }};
            --kac-pilih: {{ \App\Models\Setting::get('kuesioner_warna_pilihan', '#d1d5db') }};
            --kac-judulbagian: {{ \App\Models\Setting::get('kuesioner_warna_judulbagian', '#fbbf24') }};
            --kac-tombol: {{ \App\Models\Setting::get('kuesioner_warna_tombol', '#0f172a') }};
            --kac-footer: {{ \App\Models\Setting::get('kuesioner_warna_footer', '#4b5563') }};
            --kac-sukses: {{ \App\Models\Setting::get('kuesioner_warna_sukses', '#6ee7b7') }};
            --kac-error: {{ \App\Models\Setting::get('kuesioner_warna_error', '#fda4af') }};

            --color-amber-300: color-mix(in srgb, var(--kac-aksen) 80%, white);
            --color-amber-400: var(--kac-aksen);
            --color-amber-500: color-mix(in srgb, var(--kac-aksen) 78%, black);
            --color-amber-600: color-mix(in srgb, var(--kac-aksen) 62%, black);
            --color-amber-700: color-mix(in srgb, var(--kac-aksen) 48%, black);
        }
        body {
            background: linear-gradient(135deg, var(--kac-latar1) 0%, var(--kac-latar2) 50%, var(--kac-latar1) 100%);
            background-attachment: fixed;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 20% 15%, color-mix(in srgb, var(--kac-aksen) 13%, transparent), transparent 42%),
                radial-gradient(circle at 85% 10%, rgba(56, 189, 248, 0.15), transparent 42%),
                radial-gradient(circle at 50% 95%, rgba(168, 85, 247, 0.12), transparent 48%);
        }

        .glass {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.88), rgba(15, 23, 42, 0.78));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .grad-text {
            background: linear-gradient(90deg, var(--kac-aksen), #f472b6, #38bdf8, var(--kac-aksen));
            background-size: 300% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shine 6s linear infinite;
        }
        @keyframes shine { to { background-position: 300% 0; } }

        @keyframes float3d {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float-3d {
            animation: float3d 6s ease-in-out infinite;
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .6s ease both; }

        /* Input gelap seragam */
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
            border-color: var(--kac-aksen);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--kac-aksen) 22%, transparent), 0 10px 20px -10px rgba(0, 0, 0, 0.6);
            transform: translateY(-1px);
        }
        .inp::placeholder { color: #64748b; }
        select.inp option { background: #1e293b; color: #fff; }
        input[type="date"].inp::-webkit-calendar-picker-indicator { filter: invert(0.7); }

        .card {
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: rgba(30, 41, 59, 0.42);
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            box-shadow: 0 16px 40px -18px rgba(0, 0, 0, 0.6);
            transition: border-color .25s ease, transform .25s ease, box-shadow .25s ease;
        }
        .card:hover {
            border-color: color-mix(in srgb, var(--kac-aksen) 35%, transparent);
            box-shadow: 0 22px 48px -18px rgba(0, 0, 0, 0.7), 0 0 24px -10px color-mix(in srgb, var(--kac-aksen) 20%, transparent);
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--kac-judulbagian);
            border-left: 4px solid var(--kac-judulbagian);
            padding-left: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .color-scheme-dark { color-scheme: dark; }

        .choice {
            transition: background .2s ease, border-color .2s ease, transform .2s ease;
            cursor: pointer;
        }
        .choice:hover {
            background: rgba(51, 65, 85, 0.7);
            border-color: color-mix(in srgb, var(--kac-aksen) 50%, transparent);
            transform: translateY(-1px);
        }

        /* Warna teks kuesioner (bisa diatur dari Pengaturan) */
        .text-blue-300 { color: var(--kac-univ); }
        .text-gray-300 { color: var(--kac-pilih); }
        .text-gray-400 { color: var(--kac-label); }
        .text-gray-500 { color: var(--kac-inst); }
        .text-gray-600 { color: var(--kac-footer); }
        .text-slate-900 { color: var(--kac-tombol); }
        .text-emerald-300 { color: var(--kac-sukses); }
        .text-rose-300 { color: var(--kac-error); }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="text-gray-200 font-sans antialiased min-h-screen pb-16">

    <div class="max-w-4xl mx-auto px-4 pt-8">

        <!-- HEADER -->
        <div class="glass rounded-2xl shadow-2xl overflow-hidden fade-up">
            <div class="relative p-6 md:p-9 text-center">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 via-slate-800/30 to-fuchsia-900/30 pointer-events-none"></div>
                <div class="relative">
                    <div class="text-5xl md:text-6xl mb-3 float-3d" style="filter: drop-shadow(0 12px 16px rgba(0,0,0,0.45));">{{ \App\Models\Setting::get('kuesioner_ikon', '🎓') }}</div>
                    <h1 class="text-xl md:text-3xl font-extrabold tracking-wide uppercase grad-text">{{ \App\Models\Setting::get('kuesioner_judul') }}</h1>
                    <p class="text-sm mt-2 text-blue-300">{{ \App\Models\Setting::get('kuesioner_univ') }}</p>
                    <p class="text-xs text-gray-400 mt-1" style="color: var(--kac-subjudul)">{{ \App\Models\Setting::get('kuesioner_subjudul') }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="fade-up bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 px-4 py-3 rounded-xl shadow-lg mb-6 mt-6" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="mt-6 text-center text-xs text-gray-500 fade-up">
            {{ \App\Models\Setting::get('kuesioner_instruksi') }}
        </div>

        @if($kuesionerDitutup ?? false)
            <div class="fade-up bg-rose-950/70 border border-rose-700/60 text-rose-300 px-4 py-6 rounded-xl shadow-lg text-center mt-4">
                <div class="text-5xl mb-3">📋</div>
                <p class="font-semibold text-base">{{ $kuesionerPesan ?? 'Kuesioner ditutup. Anda akan diberitahu ketika dibuka kembali.' }}</p>
                @if(\App\Models\Setting::get('kuesioner_kontak'))
                    <p class="text-sm mt-3 text-gray-400">{{ \App\Models\Setting::get('label_hubungi_admin', '📞 Hubungi admin:') }} {{ \App\Models\Setting::get('kuesioner_kontak') }}</p>
                @endif
            </div>
        @else
        <form action="{{ route('kuesioner.store') }}" method="POST" class="space-y-6 mt-4">
            @csrf

            @if($errors->has('autentikasi'))
                <div class="bg-rose-950/80 border border-rose-700/60 text-rose-300 px-4 py-3 rounded-xl shadow-lg font-semibold">
                    {{ $errors->first('autentikasi') }}
                </div>
                @if(\App\Models\Setting::get('kuesioner_kontak'))
                    <div class="bg-slate-800/70 border border-slate-700/60 text-gray-300 px-4 py-3 rounded-xl shadow-lg text-sm mt-3">
                        {{ \App\Models\Setting::get('label_hubungi_admin', '📞 Hubungi admin:') }} {{ \App\Models\Setting::get('kuesioner_kontak') }}
                    </div>
                @endif
            @endif

            <!-- F1: IDENTITAS UTAMA ALUMNI -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_identitas') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_nim') }} <span class="text-amber-400">*</span></label>
                        <input type="text" name="no_mahasiswa" value="{{ old('no_mahasiswa') }}" required class="inp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_kode_pt') }} <span class="text-amber-400">*</span></label>
                        <input type="text" name="kode_PT" value="{{ old('kode_PT', \App\Models\Setting::get('kode_pt_default', '101004')) }}" required class="inp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_tahun_lulus') }} <span class="text-amber-400">*</span></label>
                        <select name="tahun_lulus" required class="inp">
                            <option value="" disabled selected>{{ \App\Models\Setting::get('placeholder_tahun_lulus', '-- Pilih Tahun Lulus --') }}</option>
                            @php
                                $tahunMulai = (int) \App\Models\Setting::get('kuesioner_tahun_mulai', 2020);
                                $tahunSekarang = date ('Y');
                            @endphp
                            @for ($tahun = $tahunMulai; $tahun <= $tahunSekarang; $tahun++)
                            <option value="{{  $tahun }}" {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}> {{ $tahun }} </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_kode_prodi') }} <span class="text-amber-400">*</span></label>
                        <select name="kode_prodi" required class="inp">
                            <option value="" disabled {{ old('kode_prodi') == '' ? 'selected' : '' }}>{{ \App\Models\Setting::get('placeholder_prodi', '-- Pilih Prodi --') }}</option>
                            @foreach(\App\Models\Setting::optionList('prodi_list', '') as $kodeProdi => $namaProdi)
                            <option value="{{ $kodeProdi }}" {{ old('kode_prodi') == $kodeProdi ? 'selected' : '' }}>{{ $kodeProdi }} {{ $namaProdi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_nama') }} <span class="text-amber-400">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="inp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_no_hp') }} <span class="text-amber-400">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" minlength="10" maxlength="13" pattern="08[0-9]*" required class="inp">
                        <span id="hp_error" class="hidden text-rose-400 text-xs mt-1">Nomor HP harus diawali 08 dan minimal 10 digit.</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_email') }} <span class="text-amber-400">*</span></label>
                        @php $emailDomain = ltrim(trim(\App\Models\Setting::get('kuesioner_email_domain', 'gmail.com')), '@'); @endphp
                        <input type="email" name="email" id="email" value="{{ old('email') }}" pattern="{{ '[a-zA-Z0-9._%+-]+@' . preg_quote($emailDomain, '/') }}" title="Email harus menggunakan {{ $emailDomain }}" required class="inp">
                        <span id="email_error" class="hidden text-rose-400 text-xs mt-1">Email harus menggunakan {{ $emailDomain }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_nik') }} <span class="text-amber-400">*</span></label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" minlength="16" maxlength="16" required class="inp">
                        <span id="nik_error" class="hidden text-rose-400 text-xs mt-1">NIK harus berjumlah tepat 16 digit angka.</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_npwp') }}</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" class="inp">
                    </div>
                </div>
            </div>

            <!-- SECTION F8: STATUS SAAT INI -->
             <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_status') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    @foreach(\App\Models\Setting::optionList('opsi_f8_status', "1|Bekerja (full time/part time)\n3|Wiraswasta\n4|Melanjutkan Pendidikan\n5|Tidak Kerja tetapi sedang mencari kerja\n2|Belum memungkinkan bekerja") as $value => $label)
                    <label class="choice flex items-center space-x-3 bg-slate-800/60 p-3 rounded-xl border border-slate-600">
                        <input type="radio" name="f8_status_saat_ini" value="{{ $value }}" {{ old('f8_status_saat_ini') == $value ? 'checked' : '' }} required class="w-4 h-4 text-amber-400 border-gray-500 focus:ring-amber-400">
                        <span class="text-sm font-medium text-gray-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <!-- SECTION F504: MENDAPAT PEKERJAAN 6 BULAN SETELAH LULUS -->
    <div class="card p-5 md:p-6 fade-up">
        <h2 class="section-title">{{ \App\Models\Setting::get('judul_kerja6bulan') }}</h2>
        <div class="space-y-4 mt-3">
            
            <!-- PILIHAN: YA -->
            <div>
                <div class="flex items-start space-x-3">
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_ya" value="1" {{ old('f504_mendapat_pekerjaan_6_bulan') == '1' ? 'checked' : '' }} required class="mt-1 w-4 h-4 text-amber-400">
                    <label for="kerja_ya" class="font-medium cursor-pointer w-full text-gray-200">
                        <span>{{ \App\Models\Setting::get('label_kerja_ya', 'Ya') }}</span>

                        <div class="mt-2 flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0">

                            <div class="flex-1 bg-slate-800/60 p-3 rounded-xl border border-slate-600">
                                <span class="block text-xs text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f502_bulan_ya', 'Dalam berapa bulan anda mendapatkan pekerjaan? (bagi yang sudah bekerja)') }}</span>
                                <select name="f502_bulan_dapat_kerja_ya" id="input_bulan_ya" required class="inp text-sm">
                                    <option value="" disabled selected>{{ \App\Models\Setting::get('placeholder_bulan', '-- Pilih Bulan --') }}</option>
                                    @for ($i = 0; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('f502_bulan_dapat_kerja_ya') !== null && old('f502_bulan_dapat_kerja_ya') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan {{ $i == 0 ? '(Sebelum Lulus)' : '' }}
                                    </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex-1 bg-slate-800/60 p-3 rounded-xl border border-slate-600">
                                <span class="block text-xs text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f505_pendapatan', 'Berapa rata-rata pendapatan per bulan? (take home pay)') }}</span>
                                <input type="number" name="f505_pendapatan_per_bulan" id="input_gaji_ya" value="{{ old('f505_pendapatan_per_bulan') }}" required class="inp text-sm">
                            </div>

                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <div class="flex items-start space-x-3">
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_tidak" value="2" {{ old('f504_mendapat_pekerjaan_6_bulan') == '2' ? 'checked' : '' }} class="mt-1 w-4 h-4 text-amber-400">
                    <label for="kerja_tidak" class="font-medium cursor-pointer w-full text-gray-200">
                        <span>{{ \App\Models\Setting::get('label_kerja_tidak', 'Tidak') }}</span>
                        
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="flex-1 bg-slate-800/60 p-3 rounded-xl border border-slate-600">
                                <span class="block text-xs text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f502_bulan_tidak', 'Di isi jika lebih dari 6 bulan belum mendapatkan pekerjaan') }}</span>
                                <select name="f502_bulan_dapat_kerja_tidak" id="input_bulan_tidak" required class="inp text-sm">
                                    <option value="" disabled selected>{{ \App\Models\Setting::get('placeholder_bulan', '-- Pilih Bulan --') }}</option>
                                    @for ($i = 6; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('f502_bulan_dapat_kerja_tidak') !== null && old('f502_bulan_dapat_kerja_tidak') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan
                                    </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
            <!-- SECTION F10 & F5b/c/d: SEPUTAR TEMPAT BEKERJA -->
            <div id="detailTempatBekerja" class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_tempat_bekerja') }}</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">{{ \App\Models\Setting::get('label_f510_lokasi', 'Dimana lokasi tempat Anda bekerja?') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="f510_provinsi" id="provinsi" required class="inp">
                                <option value="" disabled {{ old('f510_provinsi') === null ? 'selected' : '' }}>{{ \App\Models\Setting::get('placeholder_provinsi', '-- Pilih Provinsi --') }}</option>
                                <option value="Belum Bekerja" {{ old('f510_provinsi') == 'Belum Bekerja' ? 'selected' : '' }}>{{ \App\Models\Setting::get('label_provinsi_belum_bekerja', 'Belum Bekerja') }}</option>
                                @foreach (\App\Models\Wilayah::provinsiList() as $namaProvinsi => $kodeProvinsi)
                                    <option value="{{ $namaProvinsi }}" {{ old('f510_provinsi') == $namaProvinsi ? 'selected' : '' }}>{{ $namaProvinsi }}</option>
                                @endforeach
                            </select>

                            <select name="f510_kab_kota" id="kab_kota" required disabled data-old="{{ old('f510_kab_kota') }}" class="inp disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="" disabled selected>{{ \App\Models\Setting::get('placeholder_kab_kota', '-- Pilih Kabupaten / Kota --') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600">
                            <span class="block text-sm font-medium text-gray-400 mb-2">{{ \App\Models\Setting::get('label_f11_jenis', 'Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?') }}</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                @foreach(\App\Models\Setting::optionList('opsi_f11_instansi', "1|Instansi pemerintah\n2|BUMN/BUMD\n3|Institusi/Organisasi Multilateral\n4|Organisasi non-profit/Lembaga Swadaya Masyarakat\n5|Perusahaan swasta\n6|Wiraswasta/Perusahaan sendiri\n7|Lainnya") as $f11val => $f11label)
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="{{ $f11val }}" {{ old('f11_jenis_instansi') == $f11val ? 'checked' : '' }} class="w-4 h-4 text-amber-400"><span class="text-gray-300">{{ $f11label }}</span></label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="f11_02" value="{{ old('f11_02') }}" placeholder="{{ \App\Models\Setting::get('label_lainnya', 'Lainnya:') }}" class="inp text-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f5b', 'Nama perusahaan/kantor') }}</label>
                            <input type="text" name="f5b_nama_perusahaan" value="{{ old('f5b_nama_perusahaan') }}" class="inp" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f5c', 'Bila berwiraswasta, posisi/jabatan') }}</label>
                            <select name="f5c_posisi" class="inp">
                                <option value="" disabled {{ old('f5c_posisi') === null ? 'selected' : '' }}>{{ \App\Models\Setting::get('placeholder_posisi', 'Pilih Posisi') }}</option>
                                @foreach(\App\Models\Setting::optionList('opsi_f5c_posisi', "Founder|Founder\nCo-Founder|Co-Founder\nStaff|Staff\nFreelance|Freelance / Kerja Lepas") as $f5cval => $f5clabel)
                                <option value="{{ $f5cval }}" {{ old('f5c_posisi') == $f5cval ? 'selected' : '' }}>{{ $f5clabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ \App\Models\Setting::get('label_f5d', 'Tingkat tempat kerja anda') }}</label>
                            <select name="f5d_tingkat" class="inp">
                                <option value="" disabled {{ old('f5d_tingkat') === null ? 'selected' : '' }}>{{ \App\Models\Setting::get('placeholder_tingkat', 'Pilih Tingkatan') }}</option>
                                @foreach(\App\Models\Setting::optionList('opsi_f5d_tingkat', "Lokal|Lokal/Wilayah/wiraswasta tidak berbadan hukum\nNasional|Nasional/Wiraswasta berbadan hukum\nInternasional|Multinasional/internasional") as $f5dval => $f5dlabel)
                                <option value="{{ $f5dval }}" {{ old('f5d_tingkat') == $f5dval ? 'selected' : '' }}>{{ $f5dlabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION F18 & F12: KULIAH LANJUT & PEMBIAYAAN -->
            <div id="riwayatStudiLanjut" class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_studi_lanjut') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div id="bagianStudiLanjut" class="bg-slate-800/60 p-4 rounded-xl border border-slate-600 space-y-3">
                        <span class="block font-semibold text-sm text-gray-300">{{ \App\Models\Setting::get('label_f18_header', 'Pertanyaan Studi Lanjut') }}</span>
                            <select name="f18a_sumber_biaya_studi" placeholder="Sumber Biaya" class="inp text-sm">
                                <option value="" disabled {{ old('f18a_sumber_biaya_studi') === null ? 'selected' : '' }}>{{ \App\Models\Setting::get('placeholder_sumber_biaya', '-- Pilih sumber biaya --') }}</option>
                                @foreach(\App\Models\Setting::optionList('opsi_f18a_biaya', "Biaya Sendiri|Biaya Sendiri\nBeasiswa|Beasiswa") as $f18aval => $f18alabel)
                                <option value="{{ $f18aval }}" {{ old('f18a_sumber_biaya_studi') == $f18aval ? 'selected' : '' }}>{{ $f18alabel }}</option>
                                @endforeach
                            </select>
                        <input type="text" name="f18b_perguruan_tinggi_studi" value="{{ old('f18b_perguruan_tinggi_studi') }}" placeholder="{{ \App\Models\Setting::get('label_f18b_placeholder', 'Perguruan Tinggi') }}" class="inp text-sm" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                        <input type="text" name="f18c_program_studi" value="{{ old('f18c_program_studi') }}" placeholder="{{ \App\Models\Setting::get('label_f18c_placeholder', 'Program Studi') }}" class="inp text-sm" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                        <div class="bg-slate-900/70 p-3 rounded-xl space-y-1 border border-slate-700">
                            <label class="block font-semibold text-xs text-gray-400">{{ \App\Models\Setting::get('label_f18d', 'Tanggal Masuk') }}</label>
                            <input type="date" name="f18d_tanggal_masuk" value="{{ old('f18d_tanggal_masuk') }}" class="inp text-sm">
                        </div>
                    </div>

                    <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600 space-y-3">
                        <span class="block font-semibold text-sm text-gray-300 mb-2">{{ \App\Models\Setting::get('label_f12_01', 'Sebutkan sumberdana dalam pembiayaan kuliah?') }}</span>
                        <div class="space-y-2 text-sm">
                            @foreach(\App\Models\Setting::optionList('opsi_f12_dana', "1|Biaya Sendiri / Keluarga\n2|Beasiswa ADIK\n3|Beasiswa BIDIKMISI\n4|Beasiswa PPA\n5|Beasiswa AFIRMASI\n6|Beasiswa Perusahaan/Swasta\n7|Lainnya") as $f12val => $f12label)
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="{{ $f12val }}" {{ old('f12_01') == $f12val ? 'checked' : '' }} class="w-4 h-4 text-amber-400"><span class="text-gray-300">{{ $f12label }}</span></label>
                            @endforeach
                        </div>
                        <input type="text" name="f12_02" value="{{ old('f12_02') }}" placeholder="{{ \App\Models\Setting::get('label_lainnya_tuliskan', 'Lainnya, tuliskan:') }}" class="inp text-sm mt-3" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </div>

            <!-- SECTION F14 & F15: KESELARASAN KERJA -->
            <div id="keselarasanKerja" class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_keselarasan') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600">
                        <span class="block text-sm font-semibold text-gray-300 mb-2">{{ \App\Models\Setting::get('label_f14', 'Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?') }}</span>
                        <div class="space-y-2 text-sm">
                            @foreach(\App\Models\Setting::optionList('opsi_f14', "1|Sangat Erat\n2|Erat\n3|Cukup Erat\n4|Kurang Erat\n5|Tidak Sama Sekali") as $f14val => $f14label)
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="{{ $f14val }}" {{ old('f14') == $f14val ? 'checked' : '' }} class="w-4 h-4 text-amber-400"><span class="text-gray-300">{{ $f14label }}</span></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600">
                        <span class="block text-sm font-semibold text-gray-300 mb-2">{{ \App\Models\Setting::get('label_f15', 'Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?') }}</span>
                        <div class="space-y-2 text-sm">
                            @foreach(\App\Models\Setting::optionList('opsi_f15', "1|Setingkat Lebih Tinggi\n2|Tingkat yang Sama\n3|Setingkat Lebih Rendah\n4|Tidak Perlu Pendidikan Tinggi") as $f15val => $f15label)
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="{{ $f15val }}" {{ old('f15') == $f15val ? 'checked' : '' }} class="w-4 h-4 text-amber-400"><span class="text-gray-300">{{ $f15label }}</span></label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION F17: MATRIKS KOMPETENSI (A & B) -->
            <div id="kompetensiSection" class="card p-5 md:p-6 fade-up overflow-x-auto">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_kompetensi') }}</h2>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 mb-4 font-medium">
                    @foreach(\App\Models\Setting::optionList('opsi_skala_kompetensi', "1|1: Sangat Rendah\n2|2: Rendah\n3|3: Cukup Tinggi\n4|4: Tinggi\n5|5: Sangat Tinggi") as $skalaLabel)
                    <span>({{ $skalaLabel }})</span>
                    @endforeach
                </div>
                
                <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-800 border-slate-600">
                            <th class="p-3 text-center font-semibold text-gray-300 border border-slate-600" rowspan="2">{{ \App\Models\Setting::get('label_kompetensi_aspek', 'Aspek Kompetensi') }}</th>
                            <th class="p-2 text-center font-semibold text-gray-300 border border-slate-600" colspan="5">{{ \App\Models\Setting::get('label_kompetensi_a', 'A: Kompetensi Saat Lulus') }}</th>
                            <th class="p-2 text-center font-semibold text-gray-300 border border-slate-600" colspan="5">{{ \App\Models\Setting::get('label_kompetensi_b', 'B: Kebutuhan di Pekerjaan') }}</th>
                        </tr>
                        <tr class="bg-slate-800/60 text-[11px] text-center">
                            @for($k = 0; $k < 2; $k++)
                                @for($i = 1; $i <= 5; $i++)
                                    <th class="p-1 font-normal text-gray-500 border border-slate-600">{{ $i }}</th>
                                @endfor
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="border border-slate-600 bg-slate-800/40">
                        @php
                            $kompetensiFields = ['f1761_f1762', 'f1763_f1764', 'f1765_f1766', 'f1767_f1768', 'f1769_f1770', 'f1771_f1772', 'f1773_f1774'];
                            $kompetensiLabels = array_values(\App\Models\Setting::optionList('opsi_f17_kompetensi', "Etika\nKeahlian berdasarkan bidang ilmu\nBahasa Inggris\nPenggunaan Teknologi Informasi\nKomunikasi\nKerja sama tim\nPengembangan Diri"));
                            $kompetensi = [];
                            foreach ($kompetensiFields as $kIdx => $kField) {
                                $kompetensi[$kField] = $kompetensiLabels[$kIdx] ?? $kField;
                            }
                        @endphp
                        @foreach($kompetensi as $key => $label)
                        <tr class="hover:bg-slate-700/40 border border-slate-600 transition">
                            <td class="p-3 font-medium text-gray-300 border-r border-slate-600">{{ $label }}</td>
                            <!-- Kompetensi A -->
                            @for($i = 1; $i <= 5; $i++)
                            <td class="p-1 text-center border border-slate-600">
                                <input type="radio" name="comp_a_{{ $key }}" value="{{ $i }}" {{ old('comp_a_' . $key) == $i ? 'checked' : '' }} required class="text-amber-400">
                            </td>
                            @endfor
                            <!-- Kebutuhan B -->
                            @for($i = 1; $i <= 5; $i++)
                            <td class="p-1 text-center border border-slate-600 last:border-r-0">
                                <input type="radio" name="comp_b_{{ $key }}" value="{{ $i }}" {{ old('comp_b_' . $key) == $i ? 'checked' : '' }} required class="text-amber-400">
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- SECTION F2: PENEKANAN METODE PEMBELAJARAN -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_metode') }}</h2>
                <p class="text-xs text-gray-500 mb-4">{{ \App\Models\Setting::get('label_metode_instruksi', 'Menurut anda seberapa besar penekanan pada metode pembelajaran di bawah ini dilaksanakan di program studi anda?') }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs md:text-sm">
                    @php
                        $metodeFields = ['f21', 'f22', 'f23', 'f24', 'f25', 'f26', 'f27'];
                        $metodeLabels = array_values(\App\Models\Setting::optionList('opsi_f21_metode', "Perkuliahan\nDemonstrasi\nPartisipasi dalam proyek riset\nMagang\nPraktikum\nKerja Lapangan\nDiskusi"));
                        $metode = [];
                        foreach ($metodeFields as $mIdx => $mCode) {
                            $metode[$mCode] = $metodeLabels[$mIdx] ?? $mCode;
                        }
                    @endphp
                    @foreach($metode as $code => $title)
                    <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-600 transition hover:border-amber-400/40">
                        <span class="font-semibold block text-gray-300 mb-2">{{ $title }}</span>
                        <div class="flex flex-wrap gap-x-4 gap-y-1">
                            @foreach(\App\Models\Setting::optionList('opsi_metode_penekanan', "1|Sangat Besar\n2|Besar\n3|Cukup\n4|Kurang\n5|Tidak Sama Sekali") as $v => $l)
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="{{ $code }}" value="{{ $v }}" {{ old($code) == $v ? 'checked' : '' }} required class="text-amber-400 w-3.5 h-3.5">
                                <span class="text-gray-500 text-[11px]">{{ $l }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION F3: KAPAN MULAI MENCARI PEKERJAAN -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_mulai_cari') }}</h2>
                <p class="text-xs text-gray-500 mb-4">{{ \App\Models\Setting::get('label_mulai_cari_note', '(Tidak termasuk pekerjaan sambilan)') }}</p>
                
                <div class="space-y-4 text-sm">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_1" value="1" {{ old('f301') == '1' ? 'checked' : '' }} required class="w-4 h-4 text-amber-400">
                        <label for="f301_1" class="flex items-center space-x-2 cursor-pointer text-gray-300">
                            <input type="number" name="f302" value="{{ old('f302') }}" class="w-20 bg-slate-800/80 border border-slate-600 rounded-lg px-2 py-1 text-center outline-none focus:border-amber-400 color-scheme-dark">
                            <span class="text-xs text-gray-500">{{ \App\Models\Setting::get('label_f301_1', 'bulan sebelum lulus') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_2" value="2" {{ old('f301') == '2' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                        <label for="f301_2" class="flex items-center space-x-2 cursor-pointer text-gray-300">
                            <input type="number" name="f303" value="{{ old('f303') }}" class="w-20 bg-slate-800/80 border border-slate-600 rounded-lg px-2 py-1 text-center outline-none focus:border-amber-400 color-scheme-dark">
                            <span class="text-xs text-gray-500">{{ \App\Models\Setting::get('label_f301_2', 'bulan sesudah lulus') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_3" value="3" {{ old('f301') == '3' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                        <label for="f301_3" class="font-medium text-gray-300 cursor-pointer">
                            <span>{{ \App\Models\Setting::get('label_f301_3', 'Saya tidak mencari kerja') }}</span> 
                            <span class="text-xs text-gray-500 font-normal">{{ \App\Models\Setting::get('label_f301_3_note', '(Langsung ke pertanyaan selanjutnya)') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION F4: BAGAIMANA CARA MENCARI PEKERJAAN -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_cara_cari') }}</h2>
                <p class="text-xs text-gray-500 mb-3">{{ \App\Models\Setting::get('label_cara_cari_note', '(Jawaban bisa lebih dari satu)') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    @foreach(\App\Models\Setting::optionList('opsi_f401_cara', "f401|Melalui iklan di koran/majalah, brosur\nf402|Melamar ke perusahaan tanpa mengetahui lowongan yang ada\nf403|Pergi ke bursa/pameran kerja\nf404|Mencari lewat internet/iklan online/milis\nf405|Dihubungi oleh perusahaan\nf406|Menghubungi Kemenakertrans\nf407|Menghubungi agen tenaga kerja komersial/swasta\nf408|Memperoleh informasi dari pusat/kantor pengembangan karir fakultas/universitas\nf409|Menghubungi kantor kemahasiswaan/hubungan alumni\nf410|Membangun jejaring (network) sejak masih kuliah\nf411|Melalui relasi (misalnya dosen, orang tua, saudara, teman, dll.)\nf412|Membangun bisnis sendiri\nf413|Melalui penempatan kerja atau magang\nf414|Bekerja di tempat yang sama dengan tempat kerja semasa kuliah\nf415|Lainnya") as $code => $text)
                    <label class="flex items-start space-x-2 cursor-pointer">
                        <input type="checkbox" name="{{ $code }}" value="1" {{ old($code) == '1' ? 'checked' : '' }} class="mt-1 rounded text-amber-400 bg-slate-800 border-slate-600">
                        <span class="text-gray-300">{{ $text }}</span>
                    </label>
                    @endforeach
                    <div class="md:col-span-2 mt-1">
                        <input type="text" name="f416_tuliskan" value="{{ old('f416_tuliskan') }}" placeholder="{{ \App\Models\Setting::get('label_lainnya', 'Lainnya:') }}" class="inp text-sm" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </div>

            <!-- SECTION F6, F7, F17a: JUMLAH LAMARAN -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_lamaran') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 bg-slate-800/60 p-4 rounded-xl border border-slate-600 h-full flex flex-col justify-between"> 
                            <span class="mb-2 block">{{ \App\Models\Setting::get('label_f6', 'Berapa perusahaan/instansi yang sudah anda lamar sebelum memperoleh pekerjaan pertama?') }}</span>
                            <input type="number" name="f6_jumlah_lamaran" value="{{ old('f6_jumlah_lamaran') }}" required placeholder="{{ \App\Models\Setting::get('placeholder_jumlah', '... perusahaan') }}" class="inp text-sm">
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 bg-slate-800/60 p-4 rounded-xl border border-slate-600 h-full flex flex-col justify-between"> 
                            <span class="mb-2 block">{{ \App\Models\Setting::get('label_f7', 'Berapa banyak perusahaan/instansi yang merespons lamaran anda selama ini?') }}</span>
                            <input type="number" name="f7_jumlah_respons" value="{{ old('f7_jumlah_respons') }}" required placeholder="{{ \App\Models\Setting::get('placeholder_jumlah', '... perusahaan') }}" class="inp text-sm">
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 bg-slate-800/60 p-4 rounded-xl border border-slate-600 h-full flex flex-col justify-between"> 
                            <span class="mb-2 block">{{ \App\Models\Setting::get('label_f17a', 'Berapa banyak perusahaan/instansi yang mengundang anda untuk wawancara?') }}</span>
                            <input type="number" name="f17a_jumlah_wawancara" value="{{ old('f17a_jumlah_wawancara') }}" required placeholder="{{ \App\Models\Setting::get('placeholder_jumlah', '... perusahaan') }}" class="inp text-sm">
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION F10 & F16: KEAKTIFAN & ALASAN (BARU) -->
            <div class="card p-5 md:p-6 fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get('judul_keaktifan') }}</h2>
                
                <div class="space-y-6 mt-4">
                <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600">
                    <span class="block text-sm font-semibold text-gray-300">{{ \App\Models\Setting::get('label_f10', 'Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?') }}</span>
                    <p class="text-xs text-gray-500 mb-3">{{ \App\Models\Setting::get('label_f10_note', '(pilih 1 jawaban)') }}</p>
                    <div class="space-y-1.5 text-sm font-normal">
                        @foreach(\App\Models\Setting::optionList('opsi_f10_aktif', "1|Tidak\n2|Tidak, tapi saya sedang menunggu hasil lamaran kerja\n3|Ya, saya akan mulai bekerja dalam 2 minggu ke depan\n4|Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan\n5|Lainnya") as $f10val => $f10label)
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="{{ $f10val }}" {{ old('f10_aktif') == $f10val ? 'checked' : '' }} required class="w-4 h-4 text-amber-400"><span class="text-gray-300">{{ $f10label }}</span></label>
                        @endforeach
                    </div>
                    <input type="text" name="f10_lainnya" value="{{ old('f10_lainnya') }}" placeholder="{{ \App\Models\Setting::get('label_lainnya', 'Lainnya:') }}" class="inp text-sm mt-3" oninput="this.value = this.value.toUpperCase()">
                </div>

                <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-600">
                    <span class="block text-sm font-semibold text-gray-300">{{ \App\Models\Setting::get('label_f16', 'Jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda, mengapa anda mengambilnya?') }}</span>
                    <p class="text-xs text-gray-500 mb-3">{{ \App\Models\Setting::get('label_f16_note', '(Jawaban bisa lebih dari satu)') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 text-xs md:text-sm font-normal">
                        @foreach(\App\Models\Setting::optionList('opsi_f1601_alasan', "f1601|Pertanyaan tidak sesuai; pekerjaan saya sekarang sudah sesuai dengan pendidikan saya.\nf1602|Saya belum mendapatkan pekerjaan yang lebih sesuai.\nf1603|Di pekerjaan ini saya memperoleh prospek karir yang baik.\nf1604|Saya lebih suka bekerja di area pekerjaan yang tidak ada hubungannya dengan pendidikan saya.\nf1605|Saya dipromosikan ke posisi yang kurang berhubungan dengan pendidikan saya dibanding posisi sebelumnya.\nf1606|Saya dapat memeroleh pendapatan yang lebih tinggi di pekerjaan ini.\nf1607|Pekerjaan saya saat ini lebih aman/terjamin/secure\nf1608|Pekerjaan saya saat ini lebih menarik\nf1609|Pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel, dll.\nf1610|Pekerjaan saya saat ini lokasinya lebih dekat dari rumah saya.\nf1611|Pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga saya.\nf1612|Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya.\nf1613|Lainnya") as $code => $text)
                        <label class="flex items-start space-x-2 cursor-pointer">
                            <input type="checkbox" name="{{ $code }}" value="1" {{ old($code) == '1' ? 'checked' : '' }} class="mt-1 rounded text-amber-400 bg-slate-800 border-slate-600">
                            <span class="text-gray-300">{{ $text }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="f1614" value="{{ old('f1614') }}" placeholder="{{ \App\Models\Setting::get('label_tuliskan', 'Tuliskan:') }}" class="inp text-sm mt-3" oninput="this.value = this.value.toUpperCase()">
                </div>
                </div>
            </div>

            <!-- BUTTON SUBMIT -->
            <div class="pt-2 fade-up">
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-3.5 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm md:text-base hover:-translate-y-0.5 hover:shadow-amber-500/30">
                    {{ \App\Models\Setting::get('kuesioner_teks_tombol', 'SIMPAN DAN KIRIM DATA KUESIONER') }}
                </button>
            </div>
        </form>
        @endif

        <div class="mt-8 text-center text-xs text-gray-600">
            {{ \App\Models\Setting::get('kuesioner_footer') }}
            @if(\App\Models\Setting::get('kuesioner_kontak'))
                · {{ \App\Models\Setting::get('label_kontak_ikon', '📞') }} {{ \App\Models\Setting::get('kuesioner_kontak') }}
            @endif
        </div>
    </div>

</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function () {

   

    // ========================================================

    // 1. EVENT LISTENERS UNTUK INPUT NIK & NO HP

    // ========================================================

    const inputNIK = document.getElementById('nik');

    const inputHP = document.getElementById('no_hp');

    const inputEmail = document.getElementById('email');



    if (inputNIK) {

        inputNIK.addEventListener('input', function() {

            validasiAngkaNIK(this);

        });

    }



    if (inputHP) {

        inputHP.addEventListener('input', function() {

            validasiNoHP(this);

        });

    }

    if (inputEmail) {

        inputEmail.addEventListener('input', function() {

            validasiEmailGmail(this);

        });

    }

    const formKuesioner = document.querySelector('form[method="POST"]');

    if (formKuesioner) {

        formKuesioner.addEventListener('submit', function(e) {

            if (inputEmail) {

                normalisasiEmailGmail(inputEmail);

            }

            if (inputEmail && !regexEmail().test(inputEmail.value.trim().toLowerCase())) {

                e.preventDefault();

                validasiEmailGmail(inputEmail);

                inputEmail.focus();

            }

        });

    }



    // ========================================================

    // 2. LOGIKA VALIDASI REAL-TIME (NIK & NO HP)

    // ========================================================

    function validasiAngkaNIK(input) {

        // Hapus karakter non-angka

        input.value = input.value.replace(/[^0-9]/g, '');

        const errorLabel = document.getElementById('nik_error');

       

        if (input.value.length < 16 && input.value.length > 0) {

            if(errorLabel) errorLabel.classList.remove('hidden');

            input.classList.add('border-red-500');

        } else {

            if(errorLabel) errorLabel.classList.add('hidden');

            input.classList.remove('border-red-500');

        }

    }



    function validasiNoHP(input) {

        let value = input.value.replace(/[^0-9]/g, '');

        const errorLabel = document.getElementById('hp_error');



        if (value.length > 0) {

            if (value.length === 1 && value !== '0') {

                value = '08';

            } else if (value.length === 2 && value !== '08') {

                value = '08';

            } else if (value.length >= 2 && !value.startsWith('08')) {

                value = '08' + value.substring(2);

            }

        }

        input.value = value;



        if (value.length >= 2 && !value.startsWith('08')) {

            if(errorLabel) errorLabel.classList.remove('hidden');

            input.classList.add('border-red-500');

        } else {

            if(errorLabel) errorLabel.classList.add('hidden');

            input.classList.remove('border-red-500');

        }

    }

    const EMAIL_DOMAIN = @json(ltrim(trim(\App\Models\Setting::get('kuesioner_email_domain', 'gmail.com')), '@'));

    function regexEmail() {
        const d = EMAIL_DOMAIN.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return new RegExp('^[a-zA-Z0-9._%+-]+@' + d + '$', 'i');
    }

    function normalisasiEmailGmail(input) {

        const value = input.value;

        const atIndex = value.lastIndexOf('@');

        if (atIndex !== -1) {

            const local = value.substring(0, atIndex);

            const domain = value.substring(atIndex + 1).toLowerCase();

            const baru = local + '@' + domain;

            if (baru !== value) {

                input.value = baru;

            }

        }

    }

    function validasiEmailGmail(input) {

        const errorLabel = document.getElementById('email_error');

        normalisasiEmailGmail(input);

        const value = input.value.trim().toLowerCase();

        const valid = regexEmail().test(value);

        if (value.length > 0 && !valid) {

            if(errorLabel) errorLabel.classList.remove('hidden');

            input.classList.add('border-red-500');

        } else {

            if(errorLabel) errorLabel.classList.add('hidden');

            input.classList.remove('border-red-500');

        }

    }



    // ========================================================

    // 3. LOGIKA SINKRONISASI STATUS SAAT INI (SECTION F8)

    // ========================================================

    const radioStatus = document.querySelectorAll('input[name="f8_status_saat_ini"]');

    const radioYa = document.getElementById('kerja_ya');

    const radioTidak = document.getElementById('kerja_tidak');

   

    const inputBulanYa = document.getElementById('input_bulan_ya');

    const inputGajiYa = document.getElementById('input_gaji_ya');

    const inputBulanTidak = document.getElementById('input_bulan_tidak');



    const sectionTempatKerja = document.getElementById('detailTempatBekerja');

    const sectionStudiLanjut = document.getElementById('riwayatStudiLanjut');

    const sectionKeselarasanKerja = document.getElementById('keselarasanKerja');

    function matikanBagianTempatKerja(nonaktif) {

        if (!sectionTempatKerja) return;

        sectionTempatKerja.classList.toggle('opacity-50', nonaktif);

        sectionTempatKerja.querySelectorAll('input, select, textarea').forEach(function (e) {

            if (nonaktif) {

                e.disabled = true;

                if (e.type === 'radio' || e.type === 'checkbox') { e.checked = false; }

                else if (e.tagName === 'SELECT') { e.selectedIndex = 0; }

                else { e.value = ''; }

            } else {

                if (e.id === 'kab_kota') {

                    e.disabled = e.options.length <= 1;

                } else {

                    e.disabled = false;

                }

            }

        });

    }

    function getStatusTerpilih() {

        let status = "";

        radioStatus.forEach(radio => {

            if(radio.checked) status = radio.value;

        });

        return status;

    }

    function evaluasiStatusUtama() {

        const statusTerpilih = getStatusTerpilih();

        // F504 ("Apakah anda telah mendapatkan pekerjaan <= 6 bulan")

        // tetap aktif dan WAJIB diisi untuk semua status, termasuk

        // Tidak Kerja/Cari Kerja (5) dan Belum Memungkinkan Bekerja (2).

        if(radioYa) radioYa.disabled = false;

        if(radioTidak) radioTidak.disabled = false;

        logikaKunciWaktuTunggu();

        // Detail Tempat Bekerja hanya dinonaktifkan untuk status

        // Tidak Kerja/Cari Kerja (5) atau Belum Memungkinkan Bekerja (2).

        // Melanjutkan Pendidikan (4) tetap bisa diisi.

        matikanBagianTempatKerja(statusTerpilih === "5" || statusTerpilih === "2");

        // Pertanyaan Studi Lanjut (F18) hanya bisa diisi & wajib jika status

        // Melanjutkan Pendidikan (4) dipilih; selain itu tidak bisa diisi.

        // Sumber dana pembiayaan kuliah (F12) tidak berkaitan dengan studi

        // lanjut, sehingga selalu aktif dan wajib diisi untuk semua status.

        if (sectionStudiLanjut) {

            const wajibF18 = statusTerpilih === "4";

            const bagianF18 = document.getElementById('bagianStudiLanjut');

            if (bagianF18) bagianF18.classList.toggle('opacity-50', !wajibF18);

            sectionStudiLanjut.querySelectorAll('input, select').forEach(function (e) {

                if (e.name.startsWith('f18')) {

                    if (wajibF18) {

                        e.disabled = false;

                        e.required = true;

                    } else {

                        e.disabled = true;

                        e.required = false;

                        if (e.type === 'radio' || e.type === 'checkbox') { e.checked = false; }

                        else if (e.tagName === 'SELECT') { e.selectedIndex = 0; }

                        else { e.value = ''; }

                    }

                } else {

                    e.disabled = false;

                    e.required = (e.name !== 'f12_02');

                }

            });

        }

        // Kompetensi (F17): atur status wajib/disabled kolom A & B

        aturKompetensi();

        // Keselarasan Kerja (F14 & F15)

        aturKeselarasanKerja();

    }

    // Keselarasan Kerja (F14 & F15) wajib diisi untuk Bekerja (1) / Wiraswasta (3),

    // dan jika Melanjutkan Pendidikan (4) alumni mengisi wilayah kerja.

    // Tidak bisa diisi untuk Tidak Kerja (5) / Belum Memungkinkan (2); opsional lainnya.

    function aturKeselarasanKerja() {

        if (!sectionKeselarasanKerja) return;

        const status = getStatusTerpilih();

        const nonaktif = status === "5" || status === "2";

        const wajib = status === "1" || status === "3"

                   || (status === "4" && detailTempatKerjaTerisi());

        sectionKeselarasanKerja.classList.toggle('opacity-50', nonaktif);

        sectionKeselarasanKerja.querySelectorAll('input').forEach(function (e) {

            if (nonaktif) {

                e.disabled = true;

                e.required = false;

                if (e.type === 'radio' || e.type === 'checkbox') { e.checked = false; }

            } else {

                e.disabled = false;

                e.required = wajib;

            }

        });

    }

    // Kolom B (Kebutuhan di Pekerjaan) wajib & aktif untuk Bekerja (1) / Wiraswasta (3),

    // dan jika Melanjutkan Pendidikan (4) alumni mengisi wilayah kerja.

    // Untuk Tidak Kerja (5) / Belum Memungkinkan (2), hanya kolom A yang wajib;

    // kolom B dinonaktifkan.

    function aturKompetensi() {

        const status = getStatusTerpilih();

        const compA = document.querySelectorAll('input[name^="comp_a_"]');

        const compB = document.querySelectorAll('input[name^="comp_b_"]');

        if (status === "5" || status === "2") {

            compA.forEach(function (e) { e.disabled = false; e.required = true; });

            compB.forEach(function (e) { e.disabled = true; e.checked = false; e.required = false; });

            return;

        }

        if (status === "1" || status === "3") {

            compA.forEach(function (e) { e.disabled = false; e.required = true; });

            compB.forEach(function (e) { e.disabled = false; e.required = true; });

            return;

        }

        if (status === "4") {

            const terisi = detailTempatKerjaTerisi();

            compA.forEach(function (e) { e.disabled = false; e.required = terisi; });

            compB.forEach(function (e) { e.disabled = false; e.required = terisi; });

            return;

        }

        compA.forEach(function (e) { e.disabled = false; e.required = false; });

        compB.forEach(function (e) { e.disabled = false; e.required = false; });

    }

    function detailTempatKerjaTerisi() {

        if (!sectionTempatKerja) return false;

        let terisi = false;

        sectionTempatKerja.querySelectorAll('input, select, textarea').forEach(function (e) {

            if (e.type === 'radio' || e.type === 'checkbox') {

                if (e.checked) terisi = true;

            } else if (e.tagName === 'SELECT') {

                if (e.value && e.value !== 'Belum Bekerja') terisi = true;

            } else if (e.value && e.value.trim() !== '') {

                terisi = true;

            }

        });

        return terisi;

    }

    // F3: Kapan Mulai Mencari Pekerjaan. Jika memilih "bulan sebelum lulus" (f301=1)

    // maka f302 wajib & f303 tidak bisa diisi; sebaliknya untuk "bulan sesudah lulus" (f301=2).

    // Jika "Saya tidak mencari kerja" (f301=3) keduanya dinonaktifkan.

    function aturMulaiCariKerja() {

        const f302 = document.querySelector('input[name="f302"]');

        const f303 = document.querySelector('input[name="f303"]');

        if (!f302 || !f303) return;

        const radioSebelum = document.getElementById('f301_1');

        const radioSesudah = document.getElementById('f301_2');

        if (radioSebelum && radioSebelum.checked) {

            f302.disabled = false; f302.required = true;

            f303.disabled = true; f303.required = false; f303.value = "";

        } else if (radioSesudah && radioSesudah.checked) {

            f302.disabled = true; f302.required = false; f302.value = "";

            f303.disabled = false; f303.required = true;

        } else {

            f302.disabled = true; f302.required = false; f302.value = "";

            f303.disabled = true; f303.required = false; f303.value = "";

        }

    }



    function logikaKunciWaktuTunggu() {

        let statusTerpilih = "";

        radioStatus.forEach(radio => { if(radio.checked) statusTerpilih = radio.value; });



        if (radioYa && radioYa.checked) {

            if(inputBulanYa) { inputBulanYa.disabled = false; inputBulanYa.required = true; }

            if(inputGajiYa) { inputGajiYa.disabled = false; inputGajiYa.required = true; }

            if(inputBulanTidak) { inputBulanTidak.disabled = true; inputBulanTidak.required = false; inputBulanTidak.value = ""; }

        } else if (radioTidak && radioTidak.checked) {

            if(inputBulanYa) { inputBulanYa.disabled = true; inputBulanYa.required = false; inputBulanYa.value = ""; }

            if(inputGajiYa) { inputGajiYa.disabled = true; inputGajiYa.required = false; inputGajiYa.value = ""; }

            if(inputBulanTidak) { inputBulanTidak.disabled = false; inputBulanTidak.required = true; }

        } else {

            matikanSemuaInputWaktu();

        }

    }



    function matikanSemuaInputWaktu() {

        if(inputBulanYa) { inputBulanYa.disabled = true; inputBulanYa.required = false; inputBulanYa.value = ""; }

        if(inputGajiYa) { inputGajiYa.disabled = true; inputGajiYa.required = false; inputGajiYa.value = ""; }

        if(inputBulanTidak) { inputBulanTidak.disabled = true; inputBulanTidak.required = false; inputBulanTidak.value = ""; }

    }



    // Trigger event listeners status

    radioStatus.forEach(radio => radio.addEventListener('change', evaluasiStatusUtama));

    if(radioYa) radioYa.addEventListener('change', logikaKunciWaktuTunggu);

    if(radioTidak) radioTidak.addEventListener('change', logikaKunciWaktuTunggu);

    document.querySelectorAll('input[name="f301"]').forEach(radio => {

        radio.addEventListener('change', aturMulaiCariKerja);

    });

    if (sectionTempatKerja) {

        sectionTempatKerja.addEventListener('change', function () {

            aturKompetensi();

            aturKeselarasanKerja();

        });

        sectionTempatKerja.addEventListener('input', function () {

            aturKompetensi();

            aturKeselarasanKerja();

        });

    }

   

    // Jalankan inisialisasi awal F8

    evaluasiStatusUtama();

    aturMulaiCariKerja();



    // ========================================================

    // 4. DROPDOWN WILAYAH PROVINSI & KOTA DINAMIS

    // ========================================================

    const dataWilayah = @json(\App\Models\Wilayah::provinsiKabKotaList());



    const selectProvinsi = document.getElementById('provinsi');

    const selectKabKota = document.getElementById('kab_kota');



    if (selectProvinsi && selectKabKota) {

        selectProvinsi.addEventListener('change', function () {

            const provinsiTerpilih = this.value;



            selectKabKota.innerHTML = '<option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>';

           

            if (provinsiTerpilih && dataWilayah[provinsiTerpilih]) {

                selectKabKota.disabled = false;

                dataWilayah[provinsiTerpilih].forEach(function (kota) {

                    const opsi = document.createElement('option');

                    opsi.value = kota;

                    opsi.textContent = kota;

                    selectKabKota.appendChild(opsi);

                });

            } else {

                selectKabKota.disabled = true;

            }

        });

    }



    // ========================================================

    // 5. VALIDASI KETAT SAAT FORM SUBMIT

    // ========================================================

    const form = document.querySelector('form');

    if (form) {

        form.addEventListener('submit', function (event) {

            if (inputNIK && inputNIK.value.length !== 16) {

                event.preventDefault();

                alert('Form gagal dikirim! NIK harus berjumlah tepat 16 digit.');

                inputNIK.focus();

                return;

            }

            if (inputHP && (inputHP.value.length < 10 || !inputHP.value.startsWith('08'))) {

                event.preventDefault();

                alert('Form gagal dikirim! Nomor HP harus diawali 08 dan minimal 10 digit.');

                inputHP.focus();

                return;

            }

        });

    }

});

</script>
