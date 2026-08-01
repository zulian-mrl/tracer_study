<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Study - LPKM UMMY</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen pb-16">

    <div class="max-w-4xl mx-auto px-4 pt-8">
        
        <!-- HEADER -->
        <div class="border border-black bg-blue-800 text-white p-6 rounded-t-xl shadow-md text-center">
            <h1 class="text-2xl font-bold tracking-wide uppercase">KUESIONER TRACER STUDY UNIVERSITAS MAHAPUTRA MUHAMMAD YAMIN</h1>
            <p class="text-sm mt-1 text-blue-200">Lembaga Pengembangan Karir dan Mahasiswa (LPKM)</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md mb-6 mt-4" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('kuesioner.store') }}" method="POST" class="bg-blue-500 border-x border-b border-black p-8 rounded-b-xl shadow-md space-y-10">
            @csrf 

            @if($errors->has('autentikasi'))
                <div style="background-color: #ef4444; color: white; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-weight: bold;">
                    {{ $errors->first('autentikasi') }}
                </div>
            @endif

            <!-- F1: IDENTITAS UTAMA ALUMNI -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-4 border-l-4 border-blue-800 pl-3">Identitas Alumni</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" name="no_mahasiswa" value="{{ old('no_mahasiswa') }}" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Perguruan Tinggi *</label>
                        <input type="text" name="kode_PT" value="101004" value="{{ old('kode_PT', '101004') }}" required class="w-full bg-gray-50 border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus *</label>
                        <select type="number" name="tahun_lulus" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                            <option value="" disabled selected>-- Pilih Tahun Lulus --</option>
                            @php
                                $tahunMulai = 2020;
                                $tahunSekarang = date ('Y');
                            @endphp
                            @for ($tahun = $tahunMulai; $tahun <= $tahunSekarang; $tahun++)
                            <option value="{{  $tahun }}" {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}> {{ $tahun }} </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Program Studi *</label>
                        <select name="kode_prodi" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                            <option value="" disabled {{ old('kode_prodi') == '' ? 'selected' : '' }}>-- Pilih Prodi --</option>
                            <option value="54211" {{ old('kode_prodi') == '54211' ? 'selected' : '' }}>54211 Agroteknologi</option>
                            <option value="62201" {{ old('kode_prodi') == '62201' ? 'selected' : '' }}>62201 Akuntansi</option>
                            <option value="74201" {{ old('kode_prodi') == '74201' ? 'selected' : '' }}>74201 Ilmu Hukum</option>
                            <option value="61201" {{ old('kode_prodi') == '61201' ? 'selected' : '' }}>61201 Manajemen</option>
                            <option value="88201" {{ old('kode_prodi') == '88201' ? 'selected' : '' }}>88201 Pendidikan Bahasa Indonesia</option>
                            <option value="88203" {{ old('kode_prodi') == '88203' ? 'selected' : '' }}>88203 Pendidikan Bahasa Inggris</option>
                            <option value="84205" {{ old('kode_prodi') == '84205' ? 'selected' : '' }}>84205 Pendidikan Biologi</option>
                            <option value="87203" {{ old('kode_prodi') == '87203' ? 'selected' : '' }}>87203 Pendidikan Ekonomi</option>
                            <option value="54231" {{ old('kode_prodi') == '54231' ? 'selected' : '' }}>54231 Peternakan</option>
                            <option value="84202" {{ old('kode_prodi') == '84202' ? 'selected' : '' }}>84202 Pendidikan Matematika</option>
                            <option value="57401" {{ old('kode_prodi') == '57401' ? 'selected' : '' }}>57401 Manajemen Informatika</option>
                            <option value="54201" {{ old('kode_prodi') == '54201' ? 'selected' : '' }}>54201 Agribisnis</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / HP *</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" minlength="10" maxlength="13" pattern="08[0-9]*" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan) *</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" oninput="validasiAngkaNIK(this)" minlength="16" maxlength="16" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                </div>
            </div>

            <!-- SECTION F8: STATUS SAAT INI -->
             <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-2 border-l-4 border-blue-800 pl-3">Jelaskan status Anda saat ini?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    @foreach([
                        '1' => 'Bekerja (full time/part time)',
                        '3' => 'Wiraswasta',
                        '4' => 'Melanjutkan Pendidikan',
                        '5' => 'Tidak Kerja tetapi sedang mencari kerja',
                        '2' => 'Belum memungkinkan bekerja'
                    ] as $value => $label)
                    <label class="flex items-center space-x-3 cursor-pointer bg-white p-3 rounded-lg border border-gray-500 hover:bg-blue-50 hover:border-blue-300 transition shadow-xs">
                        <input type="radio" name="f8_status_saat_ini" value="{{ $value }}" {{ old('f8_status_saat_ini') == $value ? 'checked' : '' }} required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <!-- SECTION F504: MENDAPAT PEKERJAAN 6 BULAN SETELAH LULUS -->
    <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
        <h2 class="text-xl font-bold text-blue-800 mb-2 border-l-4 border-blue-800 pl-3">Apakah anda telah mendapatkan pekerjaan <= 6 bulan / termasuk bekerja sebelum lulus ?</h2>
        <div class="space-y-4 mt-3">
            
            <!-- PILIHAN: YA -->
            <div>
                <div class="flex items-start space-x-3">
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_ya" value="1" {{ old('f504_mendapat_pekerjaan_6_bulan') == '1' ? 'checked' : '' }} required class="mt-1 w-4 h-4 text-blue-600">
                    <label for="kerja_ya" class="font-medium cursor-pointer w-full">
                        <span>Ya</span>

                        <div class="mt-2 flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0">

                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Dalam berapa bulan anda mendapatkan pekerjaan? (bagi yang sudah bekerja)</span>
                                <select name="f502_bulan_dapat_kerja_ya" id="input_bulan_ya" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                                    <option value="" disabled selected>-- Pilih Bulan --</option>
                                    @for ($i = 0; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('f502_bulan_dapat_kerja_ya') !== null && old('f502_bulan_dapat_kerja_ya') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan {{ $i == 0 ? '(Sebelum Lulus)' : '' }}
                                    </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Berapa rata-rata pendapatan per bulan? (take home pay)</span>
                                <input type="number" name="f505_pendapatan_per_bulan" id="input_gaji_ya" value="{{ old('f505_pendapatan_per_bulan') }}" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                            </div>

                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <div class="flex items-start space-x-3">
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_tidak" value="2" {{ old('f504_mendapat_pekerjaan_6_bulan') == '2' ? 'checked' : '' }} class="mt-1 w-4 h-4 text-blue-600">
                    <label for="kerja_tidak" class="font-medium cursor-pointer w-full">
                        <span>Tidak</span>
                        
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Di isi jika lebih dari 6 bulan belum mendapatkan pekerjaan</span>
                                <select name="f502_bulan_dapat_kerja_tidak" id="input_bulan_tidak" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                                    <option value="" disabled selected>-- Pilih Bulan --</option>
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
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-4 border-l-4 border-blue-800 pl-3">Detail Tempat Bekerja</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dimana lokasi tempat Anda bekerja?</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="f510_provinsi" id="provinsi" required class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                                <option value="" disabled {{ old('f510_provinsi') === null ? 'selected' : '' }}>-- Pilih Provinsi --</option>
                                <option value="Belum Bekerja" {{ old('f510_provinsi') == 'Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                                <option value="Prov. D.K.I. Jakarta" {{ old('f510_provinsi') == 'Prov. D.K.I. Jakarta' ? 'selected' : '' }}>Prov. D.K.I. Jakarta</option>
                                <option value="Prov. Jawa Barat" {{ old('f510_provinsi') == 'Prov. Jawa Barat' ? 'selected' : '' }}>Prov. Jawa Barat</option>
                                <option value="Prov. Jawa Tengah" {{ old('f510_provinsi') == 'Prov. Jawa Tengah' ? 'selected' : '' }}>Prov. Jawa Tengah</option>
                                <option value="Prov. D.I. Yogyakarta" {{ old('f510_provinsi') == 'Prov. D.I. Yogyakarta' ? 'selected' : '' }}>Prov. D.I. Yogyakarta</option>
                                <option value="Prov. Jawa Timur" {{ old('f510_provinsi') == 'Prov. Jawa Timur' ? 'selected' : '' }}>Prov. Jawa Timur</option>
                                <option value="Prov. Aceh" {{ old('f510_provinsi') == 'Prov. Aceh' ? 'selected' : '' }}>Prov. Aceh</option>
                                <option value="Prov. Sumatera Utara" {{ old('f510_provinsi') == 'Prov. Sumatera Utara' ? 'selected' : '' }}>Prov. Sumatera Utara</option>
                                <option value="Prov. Sumatera Barat" {{ old('f510_provinsi') == 'Prov. Sumatera Barat' ? 'selected' : '' }}>Prov. Sumatera Barat</option>
                                <option value="Prov. Sumatera Selatan" {{ old('f510_provinsi') == 'Prov. Sumatera Selatan' ? 'selected' : '' }}>Prov. Sumatera Selatan</option>
                                <option value="Prov. Riau" {{ old('f510_provinsi') == 'Prov. Riau' ? 'selected' : '' }}>Prov. Riau</option>
                                <option value="Prov. Jambi" {{ old('f510_provinsi') == 'Prov. Jambi' ? 'selected' : '' }}>Prov. Jambi</option>
                                <option value="Prov. Lampung" {{ old('f510_provinsi') == 'Prov. Lampung' ? 'selected' : '' }}>Prov. Lampung</option>
                                <option value="Prov. Kalimantan Barat" {{ old('f510_provinsi') == 'Prov. Kalimantan Barat' ? 'selected' : '' }}>Prov. Kalimantan Barat</option>
                                <option value="Prov. Kalimantan Tengah" {{ old('f510_provinsi') == 'Prov. Kalimantan Tengah' ? 'selected' : '' }}>Prov. Kalimantan Tengah</option>
                                <option value="Prov. Kalimantan Timur" {{ old('f510_provinsi') == 'Prov. Kalimantan Timur' ? 'selected' : '' }}>Prov. Kalimantan Timur</option>
                                <option value="Prov. Kalimantan Selatan" {{ old('f510_provinsi') == 'Prov. Kalimantan Selatan' ? 'selected' : '' }}>Prov. Kalimantan Selatan</option>
                                <option value="Prov. Kalimantan Utara" {{ old('f510_provinsi') == 'Prov. Kalimantan Utara' ? 'selected' : '' }}>Prov. Kalimantan Utara</option>
                                <option value="Prov. Sulawesi Barat" {{ old('f510_provinsi') == 'Prov. Sulawesi Barat' ? 'selected' : '' }}>Prov. Sulawesi Barat</option>
                                <option value="Prov. Sulawesi Tengah" {{ old('f510_provinsi') == 'Prov. Sulawesi Tengah' ? 'selected' : '' }}>Prov. Sulawesi Tengah</option>
                                <option value="Prov. Sulawesi Utara" {{ old('f510_provinsi') == 'Prov. Sulawesi Utara' ? 'selected' : '' }}>Prov. Sulawesi Utara</option>
                                <option value="Prov. Sulawesi Selatan" {{ old('f510_provinsi') == 'Prov. Sulawesi Selatan' ? 'selected' : '' }}>Prov. Sulawesi Selatan</option>
                                <option value="Prov. Sulawesi Tenggara" {{ old('f510_provinsi') == 'Prov. Sulawesi Tenggara' ? 'selected' : '' }}>Prov. Sulawesi Tenggara</option>
                                <option value="Prov. Maluku" {{ old('f510_provinsi') == 'Prov. Maluku' ? 'selected' : '' }}>Prov. Maluku</option>
                                <option value="Prov. Maluku Utara" {{ old('f510_provinsi') == 'Prov. Maluku Utara' ? 'selected' : '' }}>Prov. Maluku Utara</option>
                                <option value="Prov. Bali" {{ old('f510_provinsi') == 'Prov. Bali' ? 'selected' : '' }}>Prov. Bali</option>
                                <option value="Prov. Nusa Tenggara Barat" {{ old('f510_provinsi') == 'Prov. Nusa Tenggara Barat' ? 'selected' : '' }}>Prov. NTB</option>
                                <option value="Prov. Nusa Tenggara Timur" {{ old('f510_provinsi') == 'Prov. Nusa Tenggara Timur' ? 'selected' : '' }}>Prov. NTT</option>
                                <option value="Prov. Papua" {{ old('f510_provinsi') == 'Prov. Papua' ? 'selected' : '' }}>Prov. Papua</option>
                                <option value="Prov. Papua Barat" {{ old('f510_provinsi') == 'Prov. Papua Barat' ? 'selected' : '' }}>Prov. Papua Barat</option>
                                <option value="Prov. Bengkulu" {{ old('f510_provinsi') == 'Prov. Bengkulu' ? 'selected' : '' }}>Prov. Bengkulu</option>
                                <option value="Prov. Banten" {{ old('f510_provinsi') == 'Prov. Banten' ? 'selected' : '' }}>Prov. Banten</option>
                                <option value="Prov. Kepulauan Bangka Belitung" {{ old('f510_provinsi') == 'Prov. Kepulauan Bangka Belitung' ? 'selected' : '' }}>Prov. Kepulauan Bangka Belitung</option>
                                <option value="Prov. Gorontalo" {{ old('f510_provinsi') == 'Prov. Gorontalo' ? 'selected' : '' }}>Prov. Gorontalo</option>
                                <option value="Prov. Kepulauan Riau" {{ old('f510_provinsi') == 'Prov. Kepulauan Riau' ? 'selected' : '' }}>Prov. Kepulauan Riau</option>
                                <option value="Luar Negeri" {{ old('f510_provinsi') == 'Luar Negeri' ? 'selected' : '' }}>Luar Negeri</option>
                            </select>

                            <select name="f510_kab_kota" id="kab_kota" required disabled data-old="{{ old('f510_kab_kota') }}" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50 disabled:opacity-50">
                                <option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="border border-gray-500 bg-amber-50 p-4 rounded-lg">
                            <span class="block text-sm font-medium text-gray-700 mb-2">Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="1" {{ old('f11_jenis_instansi') == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Instansi pemerintah</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="2" {{ old('f11_jenis_instansi') == '6' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>BUMN/BUMD</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="3" {{ old('f11_jenis_instansi') == '7' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Institusi/Organisasi Multilateral</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="4" {{ old('f11_jenis_instansi') == '2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Organisasi non-profit/Lembaga Swadaya Masyarakat</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="5" {{ old('f11_jenis_instansi') == '3' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Perusahaan swasta</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="6" {{ old('f11_jenis_instansi') == '4' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Wiraswasta/Perusahaan sendiri</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="7" {{ old('f11_jenis_instansi') == '5' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Lainnya</span></label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="f11_02" value="{{ old('f11_02') }}" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama perusahaan/kantor</label>
                            <input type="text" name="f5b_nama_perusahaan" value="{{ old('f5b_nama_perusahaan') }}" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bila berwiraswasta, posisi/jabatan</label>
                            <select name="f5c_posisi" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                                <option value="" disabled {{ old('f5c_posisi') === null ? 'selected' : '' }}>Pilih Posisi</option>
                                <option value="Founder" {{ old('f5c_posisi') == 'Founder' ? 'selected' : '' }}>Founder</option>
                                <option value="Co-Founder" {{ old('f5c_posisi') == 'Co-Founder' ? 'selected' : '' }}>Co-Founder</option>
                                <option value="Staff" {{ old('f5c_posisi') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Freelance" {{ old('f5c_posisi') == 'Freelance' ? 'selected' : '' }}>Freelance / Kerja Lepas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat tempat kerja anda</label>
                            <select name="f5d_tingkat" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                                <option value="" disabled {{ old('f5d_tingkat') === null ? 'selected' : '' }}>Pilih Tingkatan</option>
                                <option value="Lokal" {{ old('f5d_tingkat') == 'Lokal' ? 'selected' : '' }}>Lokal/Wilayah/wiraswasta tidak berbadan hukum</option>
                                <option value="Nasional" {{ old('f5d_tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional/Wiraswasta berbadan hukum</option>
                                <option value="Internasional" {{ old('f5d_tingkat') == 'Internasional' ? 'selected' : '' }}>Multinasional/internasional</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION F18 & F12: KULIAH LANJUT & PEMBIAYAAN -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-4 border-l-4 border-blue-800 pl-3">Riwayat Studi Lanjut & Pembiayaan Kuliah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-500 bg-amber-50 p-4 rounded-lg space-y-3 shadow-inner">
                        <span class="block font-semibold text-sm text-gray-700">Pertanyaan Studi Lanjut</span>
                            <select name="f18a_sumber_biaya_studi" placeholder="Sumber Biaya" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none">
                                <option value="" disabled {{ old('f18a_sumber_biaya_studi') === null ? 'selected' : '' }}>-- Pilih sumber biaya --</option>
                                <option value="Biaya Sendiri" {{ old('f18a_sumber_biaya_studi') == 'Biaya Sendiri' ? 'selected' : '' }}>Biaya Sendiri</option>
                                <option value="Beasiswa" {{ old('f18a_sumber_biaya_studi') == 'Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                            </select>   
                        <input type="text" name="f18b_perguruan_tinggi_studi" value="{{ old('f18b_perguruan_tinggi_studi') }}" placeholder="Perguruan Tinggi" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none" style="text-transform: uppercase"; oninput="this.value = this.value.toUpperCase()">
                        <input type="text" name="f18c_program_studi" value="{{ old('f18c_program_studi') }}" placeholder="Program Studi" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none" style="text-transform: uppercase"; oninput="this.value = this.value.toUpperCase()">
                        <div class="bg-gray-50 p-3 rounded-lg space-y-1 shadow-inner border border-gray-300">
                            <label class="block font-semibold text-xs text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="f18d_tanggal_masuk" value="{{ old('f18d_tanggal_masuk') }}" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none">
                        </div>
                    </div>

                    <div class="border border-gray-500 bg-amber-50 p-4 rounded-lg space-y-3 shadow-inner">
                        <span class="block font-semibold text-sm text-gray-700 mb-2">Sebutkan sumberdana dalam pembiayaan kuliah?</span>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="1" {{ old('f12_01') == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Biaya Sendiri / Keluarga</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="2" {{ old('f12_01') == '2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Beasiswa ADIK</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="3" {{ old('f12_01') == '3' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Beasiswa BIDIKMISI</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="4" {{ old('f12_01') == '4' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Beasiswa PPA</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="5" {{ old('f12_01') == '5' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Beasiswa AFIRMASI</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="6" {{ old('f12_01') == '6' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Beasiswa Perusahaan/Swasta</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="7" {{ old('f12_01') == '7' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Lainnya</span></label>
                        </div>
                        <input type="text" name="f12_02" value="{{ old('f12_02') }}" placeholder="Lainnya, tuliskan:" class="w-full border border-gray-500 rounded px-3 py-1.5 text-sm mt-3 outline-none bg-white" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </div>

            <!-- SECTION F14 & F15: KESELARASAN KERJA -->
            <div class="border border-gray-500 grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-100 p-4 rounded-lg shadow-sm">
                <div class="border border-gray-500 p-4 rounded-lg bg-amber-50">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?</span>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="1" {{ old('f14') == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Sangat Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="2" {{ old('f14') == '2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="3" {{ old('f14') == '3' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Cukup Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="4" {{ old('f14') == '4' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Kurang Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="5" {{ old('f14') == '5' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Tidak Sama Sekali</span></label>
                    </div>
                </div>

                <div class="border border-gray-500 p-4 rounded-lg bg-amber-50">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?</span>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="1" {{ old('f15') == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Setingkat Lebih Tinggi</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="2" {{ old('f15') == '2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Tingkat yang Sama</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="3" {{ old('f15') == '3' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Setingkat Lebih Rendah</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="4" {{ old('f15') == '4' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"><span>Tidak Perlu Pendidikan Tinggi</span></label>
                    </div>
                </div>
            </div>

            <!-- SECTION F17: MATRIKS KOMPETENSI (A & B) -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm overflow-x-auto">
                <h2 class="text-xl font-bold text-blue-800 mb-1 border-l-4 border-blue-800 pl-3">Kompetensi dikuasai dan diperlukan saat bekerja</h2>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-700 mb-4 font-medium">
                    <span>(1: Sangat Rendah)</span>
                    <span>(2: Rendah)</span>
                    <span>(3: Cukup Tinggi)</span>
                    <span>(4: Tinggi)</span>
                    <span>(5: Sangat Tinggi)</span>
                </div>
                
                <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-100 border- border-gray-500">
                            <th class="p-3 text-center font-semibold text-gray-700 border border-gray-500" rowspan="2">Aspek Kompetensi</th>
                            <th class="p-2 text-center font-semibold text-gray-700 border border-gray-500" colspan="5">A: Kompetensi Saat Lulus</th>
                            <th class="p-2 text-center font-semibold text-gray-700 border border-gray-500" colspan="5">B: Kebutuhan di Pekerjaan</th>
                        </tr>
                        <tr class="bg-gray-50 text-[11px] text-center">
                            @for($k = 0; $k < 2; $k++)
                                @for($i = 1; $i <= 5; $i++)
                                    <th class="p-1 font-normal text-gray-500 border border-gray-500">{{ $i }}</th>
                                @endfor
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="border border-gray-500 bg-amber-50">
                        @php
                            $kompetensi = [
                                'f1761_f1762' => 'Etika',
                                'f1763_f1764' => 'Keahlian berdasarkan bidang ilmu',
                                'f1765_f1766' => 'Bahasa Inggris',
                                'f1767_f1768' => 'Penggunaan Teknologi Informasi',
                                'f1769_f1770' => 'Komunikasi',
                                'f1771_f1772' => 'Kerja sama tim',
                                'f1773_f1774' => 'Pengembangan Diri'
                            ];
                        @endphp
                        @foreach($kompetensi as $key => $label)
                        <tr class="hover:bg-amber-100 border border-gray-500">
                            <td class="p-3 font-medium text-gray-800 border-r border-gray-500">{{ $label }}</td>
                            <!-- Kompetensi A -->
                            @for($i = 1; $i <= 5; $i++)
                            <td class="p-1 text-center border border-gray-500 ">
                                <input type="radio" name="comp_a_{{ $key }}" value="{{ $i }}" {{ old('comp_a_' . $key) == $i ? 'checked' : '' }} required class="text-blue-600">
                            </td>
                            @endfor
                            <!-- Kebutuhan B -->
                            @for($i = 1; $i <= 5; $i++)
                            <td class="p-1 text-center border border-gray-500 last:border-r-0">
                                <input type="radio" name="comp_b_{{ $key }}" value="{{ $i }}" {{ old('comp_b_' . $key) == $i ? 'checked' : '' }} required class="text-blue-600">
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- SECTION F2: PENEKANAN METODE PEMBELAJARAN -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-1 border-l-4 border-blue-800 pl-3">Penekanan Metode Pembelajaran</h2>
                <p class="text-xs text-gray-500 mb-4">Menurut anda seberapa besar penekanan pada metode pembelajaran di bawah ini dilaksanakan di program studi anda?</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs md:text-sm">
                    @php
                        $metode = [
                            'f21' => 'Perkuliahan', 'f22' => 'Demonstrasi', 'f23' => 'Partisipasi dalam proyek riset',
                            'f24' => 'Magang', 'f25' => 'Praktikum', 'f26' => 'Kerja Lapangan', 'f27' => 'Diskusi'
                        ];
                    @endphp
                    @foreach($metode as $code => $title)
                    <div class="p-3 border border-gray-500 rounded-lg bg-amber-50 shadow-sm">
                        <span class="font-semibold block text-gray-800 mb-2">{{ $title }}</span>
                        <div class="flex flex-wrap gap-x-4 gap-y-1">
                            @foreach([1=>'Sangat Besar', 2=>'Besar', 3=>'Cukup', 4=>'Kurang', 5=>'Tidak Sama Sekali'] as $v => $l)
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="{{ $code }}" value="{{ $v }}" {{ old($code) == $v ? 'checked' : '' }} required class="text-blue-600 w-3.5 h-3.5">
                                <span class="text-gray-600 text-[11px]">{{ $l }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION F3: KAPAN MULAI MENCARI PEKERJAAN -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-2 border-l-4 border-blue-800 pl-3">Kapan Anda Mulai Mencari Pekerjaan?</h2>
                <p class="text-xs text-gray-500 mb-4">(Tidak termasuk pekerjaan sambilan)</p>
                
                <div class="space-y-4 text-sm">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_1" value="1" {{ old('f301') == '1' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600">
                        <label for="f301_1" class="flex items-center space-x-2 cursor-pointer text-gray-700">
                            <input type="number" name="f302" value="{{ old('f302') }}" class="w-16 border border-gray-500 rounded px-2 py-1 text-center outline-none bg-white">
                            <span class="text-xs">bulan sebelum lulus</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_2" value="2" {{ old('f301') == '2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                        <label for="f301_2" class="flex items-center space-x-2 cursor-pointer text-gray-700">
                            <input type="number" name="f303" value="{{ old('f303') }}" class="w-16 border border-gray-500 rounded px-2 py-1 text-center outline-none bg-white">
                            <span class="text-xs">bulan sesudah lulus</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_3" value="3" {{ old('f301') == '3' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                        <label for="f301_3" class="font-medium text-gray-700 cursor-pointer">
                            <span>Saya tidak mencari kerja</span> 
                            <span class="text-xs text-gray-400 font-normal">(Langsung ke pertanyaan selanjutnya)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION F4: BAGAIMANA CARA MENCARI PEKERJAAN -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-2 border-l-4 border-blue-800 pl-3">Bagaimana cara anda mencari pekerjaan tersebut?</h2>
                <p class="text-xs text-gray-500 mb-3">(Jawaban bisa lebih dari satu)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    @php
                        $cara_cari = [
                            'f401' => 'Melalui iklan di koran/majalah, brosur', 
                            'f402' => 'Melamar ke perusahaan tanpa mengetahui lowongan yang ada',
                            'f403' => 'Pergi ke bursa/pameran kerja', 
                            'f404' => 'Mencari lewat internet/iklan online/milis',
                            'f405' => 'Dihubungi oleh perusahaan', 
                            'f406' => 'Menghubungi Kemenakertrans',
                            'f407' => 'Menghubungi agen tenaga kerja komersial/swasta', 
                            'f408' => 'Memperoleh informasi dari pusat/kantor pengembangan karir fakultas/universitas',
                            'f409' => 'Menghubungi kantor kemahasiswaan/hubungan alumni', 
                            'f410' => 'Membangun jejaring (network) sejak masih kuliah',
                            'f411' => 'Melalui relasi (misalnya dosen, orang tua, saudara, teman, dll.)', 
                            'f412' => 'Membangun bisnis sendiri',
                            'f413' => 'Melalui penempatan kerja atau magang', 
                            'f414' => 'Bekerja di tempat yang sama dengan tempat kerja semasa kuliah',
                            'f415' => 'Lainnya'
                        ];
                    @endphp
                    @foreach($cara_cari as $code => $text)
                    <label class="flex items-start space-x-2 cursor-pointer">
                        <input type="checkbox" name="{{ $code }}" value="1" {{ old($code) == '1' ? 'checked' : '' }} class="mt-1 rounded text-blue-600">
                        <span>{{ $text }}</span>
                    </label>
                    @endforeach
                    <div class="md:col-span-2 mt-1">
                        <input type="text" name="f416_tuliskan" value="{{ old('f416_tuliskan') }}" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </div>

            <!-- SECTION F6, F7, F17a: JUMLAH LAMARAN -->
            <div class="border border-gray-500 grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-100 p-4 rounded-lg shadow-sm">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa perusahaan/instansi yang sudah anda lamar sebelum memperoleh pekerjaan pertama?</span>
                        <input type="number" name="f6_jumlah_lamaran" value="{{ old('f6_jumlah_lamaran') }}" required placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa banyak perusahaan/instansi yang merespons lamaran anda selama ini?</span>
                        <input type="number" name="f7_jumlah_respons" value="{{ old('f7_jumlah_respons') }}" required placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa banyak perusahaan/instansi yang mengundang anda untuk wawancara?</span>
                        <input type="number" name="f17a_jumlah_wawancara" value="{{ old('f17a_jumlah_wawancara') }}" required placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </label>
                </div>
            </div>

            <!-- SECTION F10 & F16: KEAKTIFAN & ALASAN (BARU) -->
            <div class="border border-gray-500 space-y-6 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-2 border-l-4 border-blue-800 pl-3">Keaktifan Mencari Pekerjaan & Alasan Pekerjaan</h2>
                
                <div class="border border-gray-500 bg-blue-200 p-4 rounded-lg">
                    <span class="block text-sm font-semibold text-gray-800">Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?</span>
                    <p class="text-xs text-gray-500 mb-3">(pilih 1 jawaban)</p>
                    <div class="space-y-1.5 text-sm font-normal">
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="1" {{ old('f10_aktif') == '1' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600"><span>Tidak</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="2" {{ old('f10_aktif') == '2' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600"><span>Tidak, tapi saya sedang menunggu hasil lamaran kerja</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="3" {{ old('f10_aktif') == '3' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600"><span>Ya, saya akan mulai bekerja dalam 2 minggu ke depan</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="4" {{ old('f10_aktif') == '4' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600"><span>Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="5" {{ old('f10_aktif') == '5' ? 'checked' : '' }} required class="w-4 h-4 text-blue-600"><span>Lainnya</span></label>
                    </div>
                    <input type="text" name="f10_lainnya" value="{{ old('f10_lainnya') }}" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-1.5 text-sm mt-3 outline-none bg-amber-50" oninput="this.value = this.value.toUpperCase()">
                </div>

                <div class="border border-gray-500 bg-blue-200 p-4 rounded-lg">
                    <span class="block text-sm font-semibold text-gray-800">Jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda, mengapa anda mengambilnya?</span>
                    <p class="text-xs text-gray-500 mb-3">(Jawaban bisa lebih dari satu)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 text-xs md:text-sm font-normal">
                        @php
                            $alasan_f16 = [
                                'f1601' => 'Pertanyaan tidak sesuai; pekerjaan saya sekarang sudah sesuai dengan pendidikan saya.',
                                'f1602' => 'Saya belum mendapatkan pekerjaan yang lebih sesuai.',
                                'f1603' => 'Di pekerjaan ini saya memperoleh prospek karir yang baik.',
                                'f1604' => 'Saya lebih suka bekerja di area pekerjaan yang tidak ada hubungannya dengan pendidikan saya.',
                                'f1605' => 'Saya dipromosikan ke posisi yang kurang berhubungan dengan pendidikan saya dibanding posisi sebelumnya.',
                                'f1606' => 'Saya dapat memeroleh pendapatan yang lebih tinggi di pekerjaan ini.',
                                'f1607' => 'Pekerjaan saya saat ini lebih aman/terjamin/secure',
                                'f1608' => 'Pekerjaan saya saat ini lebih menarik',
                                'f1609' => 'Pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel, dll.',
                                'f1610' => 'Pekerjaan saya saat ini lokasinya lebih dekat dari rumah saya.',
                                'f1611' => 'Pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga saya.',
                                'f1612' => 'Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya.',
                                'f1613' => 'Lainnya'
                            ];
                        @endphp
                        @foreach($alasan_f16 as $code => $text)
                        <label class="flex items-start space-x-2 cursor-pointer">
                            <input type="checkbox" name="{{ $code }}" value="1" {{ old($code) == '1' ? 'checked' : '' }} class="mt-1 rounded text-blue-600">
                            <span>{{ $text }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="f1614" value="{{ old('f1614') }}" placeholder="Tuliskan:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm mt-3 outline-none bg-amber-50" oninput="this.value = this.value.toUpperCase()">
                </div>
            </div>

            <!-- BUTTON SUBMIT -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-800 border border-black hover:bg-blue-900 text-white font-bold py-3.5 px-4 rounded-lg shadow-lg transition duration-200 cursor-pointer tracking-wide uppercase text-sm md:text-base">
                    SIMPAN DAN KIRIM DATA KUESIONER
                </button>
            </div>
        </form>
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



    // ========================================================

    // 3. LOGIKA SINKRONISASI STATUS SAAT INI (SECTION F8)

    // ========================================================

    const radioStatus = document.querySelectorAll('.status-saat-ini');

    const radioYa = document.getElementById('kerja_ya');

    const radioTidak = document.getElementById('kerja_tidak');

   

    const inputBulanYa = document.getElementById('input_bulan_ya');

    const inputGajiYa = document.getElementById('input_gaji_ya');

    const inputBulanTidak = document.getElementById('input_bulan_tidak');



    function evaluasiStatusUtama() {

        let statusTerpilih = "";

        radioStatus.forEach(radio => {

            if(radio.checked) statusTerpilih = radio.value;

        });



        // Jika memilih Melanjutkan Pendidikan (3) atau Tidak Kerja (4)

        if (statusTerpilih === "3" || statusTerpilih === "4") {

            if(radioYa) { radioYa.disabled = true; radioYa.checked = false; }

            if(radioTidak) { radioTidak.disabled = true; radioTidak.checked = false; }

            matikanSemuaInputWaktu();

        } else {

            // Jika memilih Bekerja (1) or Wiraswasta (2)

            if(radioYa) radioYa.disabled = false;

            if(radioTidak) radioTidak.disabled = false;

            logikaKunciWaktuTunggu();

        }

    }



    function logikaKunciWaktuTunggu() {

        let statusTerpilih = "";

        radioStatus.forEach(radio => { if(radio.checked) statusTerpilih = radio.value; });

        if (statusTerpilih === "3" || statusTerpilih === "4") return;



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

   

    // Jalankan inisialisasi awal F8

    evaluasiStatusUtama();



    // ========================================================

    // 4. DROPDOWN WILAYAH PROVINSI & KOTA DINAMIS

    // ========================================================

    const dataWilayah = {

        "Prov. D.K.I. Jakarta": [
            "Kab. Kepulauan Seribu", "Kota Jakarta Selatan", "Kota Jakarta Pusat", "Kota Jakarta Utara", "Kota Jakarta Barat", "Kota Jakarta Timur"
        ],
        "Prov. Jawa Barat": [
            "Kab. Bandung", "Kab. Bandung Barat", "Kab. Bekasi", "Kab. Bogor", "Kab. Ciamis", "Kab. Cianjur", "Kab. Cirebon", "Kab. Garut", "Kab. Indramayu", "Kab. Karawang",
            "Kab. Kuningan", "Kab. Majalengka", "Kab. Pangandaran", "Kab. Purwakarta", "Kab. Subang", "Kab. Sukabumi", "Kab. Sumedang", "Kab. Tasikmalaya", "Kota Bandung", 
            "Kota Banjar", "Kota Bogor", "Kota Bekasi", "Kota Cimahi", "Kota Cirebon", "Kota Depok", "Kota Sukabumi", "Kota Tasikmalaya" // <-- Koma sudah diperbaiki di sini
        ],
        "Prov. Jawa Tengah": [
            "Kab. Banjarnegara", "Kab. Banyumas", "Kab. Batang", "Kab. Blora", "Kab. Boyolali", "Kab. Brebes", "Kab. Cilacap", "Kab. Demak", "Kab. Grobogan", "Kab. Jepara", 
            "Kab. Karanganyar", "Kab. Kebumen", "Kab. Kendal", "Kab. Klaten", "Kab. Kudus", "Kab. Magelang", "Kab. Pati", "Kab. Pekalongan", "Kab. Pemalang", "Kab. Purbalingga", 
            "Kab. Purworejo", "Kab. Rembang", "Kab. Semarang", "Kab. Sragen", "Kab. Sukoharjo", "Kab. Tegal", "Kab. Temanggung", "Kab. Wonogiri", "Kab. Wonosobo", "Kota Semarang",
            "Kota Magelang", "Kota Pekalongan", "Kota Salatiga", "Kota Tegal"
        ],
        "Prov. D.I. Yogyakarta": [
            "Kab. Gunung Kidul", "Kab. Kulon Progo", "Kab. Sleman", "Kota Yogyakarta"
        ],
        "Prov. Jawa Timur": [
            "Kab. Bangkalan", "Kab. Banyuwangi", "Kab. Blitar", "Kab. Bojonegoro", "Kab. Bondowoso", "Kab. Gresik", "Kab. Jember", "Kab. Jombang", "Kab. Kediri", "Kab. Lamongan", 
            "Kab. Lumajang", "Kab. Madiun", "Kab. Magetan", "Kab. Malang", "Kab. Mojokerto", "Kab. Nganjuk", "Kab. Ngawi", "Kab. Pacitan", "Kab. Pamekasan", "Kab. Pasuruan", 
            "Kab. Ponorogo", "Kab. Probolinggo", "Kab. Sampang", "Kab. Sidoarjo", "Kab. Situbondo", "Kab. Sumenep", "Kab. Trenggalek", "Kab. Tuban", "Kab. Tulungagung", 
            "Kota Batu", "Kota Blitar", "Kota Kediri", "Kota Madiun", "Kota Malang", "Kota Mojokerto", "Kota Pasuruan", "Kota Probolinggo", "Kota Surabaya"
        ],
        "Prov. Aceh": [
            "Kab. Aceh Barat", "Kab. Aceh Barat Daya", "Kab. Aceh Besar", "Kab. Aceh Jaya", "Kab. Aceh Selatan", "Kab. Aceh Singkil", "Kab. Aceh Tamiang", "Kab. Aceh Tengah", "Kab. Aceh Tenggara", 
            "Kab. Aceh Timur", "Kab. Aceh Utara", "Kab. Bener Meriah", "Kab. Bireuen", "Kab. Gayo Lues", "Kab. Nagan Raya", "Kab. Pidie", "Kab. Pidie Jaya", "Kab. Simeulue", "Kota Banda Aceh", 
            "Kota Langsa", "Kota Lhokseumawe", "Kota Sabang", "Kota Subulussalam"
        ],
        "Prov. Sumatera Utara": [
            "Kab. Asahan", "Kab. Batu Bara", "Kab. Dairi", "Kab. Deli Serdang", "Kab. Humbang Hasundutan", "Kab. Karo", "Kab. Labuhanbatu", "Kab. Labuhanbatu Selatan", "Kab. Labuhanbatu Utara", 
            "Kab. Langkat", "Kab. Mandailing Natal", "Kab. Nias", "Kab. Nias Barat", "Kab. Nias Selatan", "Kab. Nias Utara", "Kab. Padang Lawas", "Kab. Padang Lawas Utara", "Kab. Pakpak Bharat", 
            "Kab. Samosir", "Kab. Serdang Bedagai", "Kab. Simalungun", "Kab. Tapanuli Selatan", "Kab. Tapanuli Tengah", "Kab. Tapanuli Utara", "Kab. Toba Samosir", 
            "Kota Binjai", "Kota Gunungsitoli", "Kota Medan", "Kota Padangsidimpuan", "Kota Pematangsiantar", "Kota Sibolga", "Kota Tanjungbalai", "Kota Tebing Tinggi"
        ],
        "Prov. Sumatera Barat": [
            "Kab. Agam", "Kab. Tanah Datar", "Kab. Pesisir Selatan", "Kab. Padang Pariaman", "Kab. Pasaman",
            "Kab. Pasaman Barat", "Kab. 50 Kota", "Kab. Sijunjung", "Kab. Dharmasraya", "Kab. Kepulauan Mentawai",
            "Kab. Solok", "Kab. Solok Selatan", "Kota Solok", "Kota Padang", "Kota Bukittinggi",
            "Kota Payakumbuh", "Kota Padang Panjang", "Kota Sawahlunto", "Kota Pariaman"
        ],
        "Prov. Sumatera Selatan": [
            "Kab. Banyuasin", "Kab. Empat Lawang", "Kab. Lahat", "Kab. Muara Enim", "Kab. Musi Banyuasin", "Kab. Musi Rawas", "Kab. Musi Rawas Utara", 
            "Kab. Ogan Ilir", "Kab. Ogan Komering Ilir", "Kab. Ogan Komering Ulu", "Kab. Ogan Komering Ulu Selatan", "Kab. Ogan Komering Ulu Timur", 
            "Kab. Penukal Abab Lematang Ilir", "Kota Lubuk Linggau", "Kota Pagar Alam", "Kota Palembang", "Kota Prabumulih"
        ],
        "Prov. Riau": [
            "Kab. Bengkalis", "Kab. Indragiri Hilir", "Kab. Indragiri Hulu", "Kab. Kampar", "Kab. Kepulauan Meranti", "Kab. Kuantan Singingi", "Kab. Pelalawan", 
            "Kab. Rokan Hilir", "Kab. Rokan Hulu", "Kab. Siak", "Kota Pekanbaru", "Kota Dumai"
        ],
        "Prov. Jambi": [
            "Kab. Batanghari", "Kab. Bungo", "Kab. Kerinci", "Kab. Merangin", "Kab. Muaro Jambi", "Kab. Tebo", 
            "Kab. Tanjung Jabung Timur", "Kab. Tanjung Jabung Barat", "Kota Jambi", "Kota Sungai Penuh"
        ],
        "Prov. Lampung": [
            "Kab. Lampung Barat", "Kab. Lampung Selatan", "Kab. Lampung Tengah", "Kab. Lampung Timur", "Kab. Lampung Utara", "Kab. Mesuji", "Kab. Pesawaran", 
            "Kab. Pesisir Barat", "Kab. Pringsewu", "Kab. Tanggamus", "Kab. Tulang Bawang", "Kab. Tulang Bawang Barat", "Kab. Way Kanan", "Kota Bandar Lampung", "Kota Metro"
        ],
        "Prov. Kalimantan Barat": [
            "Kab. Bengkayang", "Kab. Kapuas Hulu", "Kab. Kayong Utara", "Kab. Ketapang", "Kab. Kubu Raya", "Kab. Landak", "Kab. Melawi", 
            "Kab. Mempawah", "Kab. Sambas", "Kab. Sanggau", "Kab. Sekadau", "Kab. Sintang", "Kota Pontianak", "Kota Singkawang"
        ],
        "Prov. Kalimantan Tengah": [
            "Kab. Barito Selatan", "Kab. Barito Timur", "Kab. Barito Utara", "Kab. Gunung Mas", "Kab. Kapuas", "Kab. Katingan", "Kab. Kotawaringin Barat", "Kab. Kotawaringin Timur", "Kab. Lamandau", "Kab. Murung Raya", "Kab. Pulang Pisau", "Kab. Seruyan", "Kab. Sukamara", "Kota Palangka Raya"
        ],
        "Prov. Kalimantan Timur": [
            "Kab. Berau", "Kab. Kutai Barat", "Kab. Kutai Kartanegara", "Kab. Kutai Timur", "Kab. Mahakam Ulu", "Kab. Paser", "Kab. Penajam Paser Utara", 
            "Kota Balikpapan", "Kota Bontang", "Kota Samarinda"
        ],
        "Prov. Kalimantan Selatan": [
            "Kab. Balangan", "Kab. Banjar", "Kab. Barito Kuala", "Kab. Hulu Sungai Selatan", "Kab. Hulu Sungai Tengah", "Kab. Hulu Sungai Utara", "Kab. Kotabaru",
            "Kab. Tabalong", "Kab. Tanah Bumbu", "Kab. Tanah Laut", "Kab. Tapin", "Kota Banjarbaru", "Kota Banjarmasin"
        ],
        "Prov. Kalimantan Utara": [
            "Kab. Bulungan", "Kab. Malinau", "Kab. Nunukan", "Kab. Tana Tidung", "Kota Tarakan"
        ],
        "Prov. Sulawesi Barat": [
            "Kab. Majene", "Kab. Mamasa", "Kab. Mamuju", "Kab. Mamuju Tengah", "Kab. Pasangkayu", "Kab. Polewali Mandar"
        ],
        "Prov. Sulawesi Tengah": [
            "Kab. Banggai", "Kab. Banggai Kepulauan", "Kab. Banggai Laut", "Kab. Buol", "Kab. Donggala", "Kab. Morowali", "Kab. Morowali Utara", 
            "Kab. Parigi Moutong", "Kab. Poso", "Kab. Sigi", "Kab. Tojo Una-Una", "Kab. Tolitoli", "Kota Palu"
        ],
        "Prov. Sulawesi Utara": [
            "Kab. Bolaang Mongondow", "Kab. Bolaang Mongondow Selatan", "Kab. Bolaang Mongondow Timur", "Kab. Bolaang Mongondow Utara", "Kab. Kep. Sangihe", "Kab. Kepulauan Siau Tagulandang Biaro", 
            "Kab. Kepulauan Talaud", "Kab. Minahasa", "Kab. Minahasa Selatan", "Kab. Minahasa Tenggara", "Kab. Minahasa Utara", "Kota Bitung", "Kota Kotamobagu", "Kota Manado", "Kota Tomohon"
        ],
        "Prov. Sulawesi Selatan": [
            "Kab. Bantaeng", "Kab. Barru", "Kab. Bone", "Kab. Bulukumba", "Kab. Enrekang", "Kab. Gowa", "Kab. Jeneponto", "Kab. Kepulauan Selayar", "Kab. Luwu Timur", "Kab. Luwu Utara", 
            "Kab. Maros", "Kab. Pangkajene Kepulauan", "Kab. Pinrang", "Kab. Sidenreng Rappang", "Kab. Sinjai", "Kab. Soppeng", "Kab. Takalar", "Kab. Tana Toraja", "Kab. Toraja Utara", 
            "Kab. Wajo", "Kota Makassar", "Kota Palopo", "Kota Parepare"
        ],
        "Prov. Sulawesi Tenggara": [
            "Kab. Bombana", "Kab. Buton", "Kab. Buton Selatan", "Kab. Buton Tengah", "Kab. Buton Utara", "Kab. Kolaka", "Kab. Kolaka Timur", "Kab. Kolaka Utara", "Kab. Konawe",
            "Kab. Konawe Kepulauan", "Kab. Konawe Selatan", "Kab. Konawe Utara", "Kab. Muna", "Kab. Muna Barat", "Kab. Wakatobi", "Kota Bau-Bau", "Kota Kendari"
        ],
        "Prov. Maluku": [
            "Kab. Buru", "Kab. Buru Selatan", "Kab. Kepulauan Aru", "Kab. Kepulauan Tanimbar", "Kab. Maluku Barat Daya", "Kab. Maluku Tengah", 
            "Kab. Maluku Tenggara", "Kab. Seram Bagian Barat", "Kab. Seram Bagian Timur", "Kota Ambon", "Kota Tual"
        ],
        "Prov. Maluku Utara": [
            "Kab. Halmahera Barat", "Kab. Halmahera Selatan", "Kab. Halmahera Tengah", "Kab. Halmahera Timur", "Kab. Halmahera Utara", "Kab. Kepulauan Morotai", 
            "Kab. Kepulauan Sula", "Kab. Pulau Taliabu", "Kota Ternate", "Kota Tidore Kepulauan"
        ],
        "Prov. Bali": [
            "Kab. Badung", "Kab. Bangli", "Kab. Buleleng", "Kab. Gianyar", "Kab. Jembrana", "Kab. Karang Asem", "Kab. Klungkung", "Kab. Tabanan", "Kota Denpasar"
        ],
        "Prov. Nusa Tenggara Barat": [
            "Kab. Bima", "Kab. Dompu", "Kab. Lombok Barat", "Kab. Lombok Tengah", "Kab. Lombok Timur", "Kab. Lombok Utara", "Kab. Sumbawa", "Kab. Sumbawa Barat", 
            "Kota Mataram", "Kota Bima"
        ],
        "Prov. Nusa Tenggara Timur": [
            "Kab. Alor", "Kab. Belu", "Kab. Ende", "Kab. Flores Timur", "Kab. Kupang", "Kab. Lembata", "Kab. Malaka", "Kab. Manggarai", "Kab. Manggarai Barat", 
            "Kab. Manggarai Timur", "Kab. Nagekeo", "Kab. Ngada", "Kab. Rote-Ndao", "Kab. Sabu Raijua", "Kab. Sikka", "Kab. Sumba Barat", "Kab. Sumba Barat Daya", 
            "Kab. Sumba Tengah", "Kab. Sumba Timur", "Kab. Timor Tengah Selatan", "Kab. Timor Tengah Utara", "Kota Kupang"
        ],
        "Prov. Papua": [
            "Kab. Asmat", "Kab. Biak Numfor", "Kab. Boven Digoel", "Kab. Deiyai", "Kab. Dogiyai", "Kab. Intan Jaya", "Kab. Jayapura", "Kab. Jaya Wijaya", "Kab. Keerom", 
            "Kab. Kepulauan Yapen", "Kab. Lanny Jaya", "Kab. Mappi", "Kab. Mamberamo Raya", "Kab. Mamberamo Tengah", "Kab. Merauke", "Kab. Mimika", "Kab. Nabire", "Kab. Nduga", 
            "Kab. Paniai", "Kab. Pegunungan Bintang", "Kab. Puncak", "Kab. Puncak Jaya", "Kab. Sarmi", "Kab. Supiori", "Kab. Tolikara", "Kab. Waropen", "Kab. Yahukimo", "Kab. Yalimo", "Kota Jayapura"
        ],
        "Prov. Papua Barat": [
            "Kab. Fak-Fak", "Kab. Kaimana", "Kab. Manokwari", "Kab. Manokwari Selatan", "Kab. Maybrat", "Kab. Pegunungan Arfak", "Kab. Raja Ampat", "Kab. Sorong", "Kab. Sorong Selatan", "Kab. Tambrauw", "Kab. Teluk Bintuni", "Kab. Teluk Wondama", "Kota Sorong"
        ],
        "Prov. Bengkulu": [
            "Kab. Bengkulu Selatan", "Kab. Bengkulu Utara", "Kab. Bengkulu Tengah", "Kab. Kaur", "Kab. Kepahiang", "Kab. Lebong", "Kab. Muko-muko", "Kab. Rejang Lebong", "Kab. Seluma", "Kota Bengkulu"
        ],
        "Prov. Banten": [
            "Kab. Lebak", "Kab. Pandeglang", "Kab. Serang", "Kab. Tangerang", "Kota Cilegon", "Kota Tangerang", "Kota Serang", "Kota Tangerang Selatan"
        ],
        "Prov. Kepulauan Bangka Belitung": [
            "Kab. Bangka", "Kab. Bangka Barat", "Kab. Bangka Selatan", "Kab. Bangka Tengah", "Kab. Belitung", "Kab. Belitung Timur", "Kota Pangkal Pinang"
        ],
        "Prov. Gorontalo": [
            "Kab. Boalemo", "Kab. Bone Bolango", "Kab. Gorontalo", "Kab. Gorontalo Utara", "Kab. Pohuwato", "Kota Gorontalo"
        ],
        "Prov. Kepulauan Riau": [
            "Kab. Bintan", "Kab. Karimun", "Kab. Kepulauan Anambas", "Kab. Lingga", "Kab. Natuna", "Kota Batam", "Kota Tanjungpinang"
        ],
        "Luar Negeri": [
            "Arab Saudi", "Belanda", "Brunei Darussalam", "Cina", "Filipina", "Jepang", "Malaysia", "Mesir", "Myanmar", "Rusia", "Singapura", "Taiwan", "Thailand"
        ]
    };



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

