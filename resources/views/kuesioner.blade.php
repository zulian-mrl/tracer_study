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
            <h1 class="text-2xl font-bold tracking-wide uppercase">Kuesioner Tracer Study DIKTI UMMY</h1>
            <p class="text-sm mt-1 text-blue-200">Lembaga Pengembangan Karir dan Mahasiswa (LPKM)</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md mb-6 mt-4" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('kuesioner.store') }}" method="POST" class="bg-blue-500 border-x border-b border-black p-8 rounded-b-xl shadow-md space-y-10">
            @csrf 

            <!-- F1: IDENTITAS UTAMA ALUMNI -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-4 border-l-4 border-blue-800 pl-3">Identitas Alumni</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" name="no_mahasiswa" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Perguruan Tinggi *</label>
                        <input type="text" name="kode_PT" value="101004" required class="w-full bg-gray-50 border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
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
                            <option value="{{  $tahun }}"> {{ $tahun }} </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Program Studi *</label>
                        <select name="kode_prodi" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                            <option value="" disabled selected>-- Pilih Prodi --</option>
                            <option value="54211">54211 Agroteknologi</option>
                            <option value="62201">62201 Akuntansi</option>
                            <option value="74201">74201 Ilmu Hukum</option>
                            <option value="61201">61201 Manajemen</option>
                            <option value="88201">88201 Pendidikan Bahasa Indonesia</option>
                            <option value="88203">88203 Pendidikan Bahasa Inggris</option>
                            <option value="84205">84205 Pendidikan Biologi</option>
                            <option value="87203">87203 Pendidikan Ekonomi</option>
                            <option value="54231">54231 Peternakan</option>
                            <option value="84202">84202 Pendidikan Matematika</option>
                            <option value="57401">57401 Manajemen Informatika</option>
                            <option value="54201">54201 Agribisnis</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / HP *</label>
                        <input type="text" name="no_hp" minlength="10" maxlength="13" pattern="08[0-9]*" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email *</label>
                        <input type="email" name="email" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan) *</label>
                        <input type="text" name="nik" id="nik" oninput="validasiAngkaNIK(this)" minlength="16" maxlength="16" required class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                        <input type="text" name="npwp" class="w-full border border-gray-500 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-amber-50">
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
                    <label class="flex items-center space-x-3 cursor-pointer bg-white p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition shadow-xs">
                        <input type="radio" name="f8_status_saat_ini" value="{{ $value }}" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
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
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_ya" value="1" required class="mt-1 w-4 h-4 text-blue-600">
                    <label for="kerja_ya" class="font-medium cursor-pointer w-full">
                        <span>Ya</span>

                        <div class="mt-2 flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0">

                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Dalam berapa bulan anda mendapatkan pekerjaan? (bagi yang sudah bekerja)</span>
                                <select name="f502_bulan_dapat_kerja_ya" id="input_bulan_ya" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                                    <option value="" disabled selected>-- Pilih Bulan --</option>
                                    @for ($i = 0; $i <= 6; $i++)
                                        <option value="{{ $i }}">{{ $i }} Bulan {{ $i == 0 ? '(Sebelum Lulus)' : '' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Berapa rata-rata pendapatan per bulan? (take home pay)</span>
                                <input type="number" name="f505_pendapatan_per_bulan" id="input_gaji_ya" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                            </div>

                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <div class="flex items-start space-x-3">
                    <input type="radio" name="f504_mendapat_pekerjaan_6_bulan" id="kerja_tidak" value="2" class="mt-1 w-4 h-4 text-blue-600">
                    <label for="kerja_tidak" class="font-medium cursor-pointer w-full">
                        <span>Tidak</span>
                        
                        <div class="mmt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="flex-1 shadow-sm p-3 rounded border border-gray-500 bg-blue-200">
                                <span class="block text-xs text-gray-600 mb-1">Di isi jika lebih dari 6 bulan mendapat pekerjaan</span>
                                <select name="f502_bulan_dapat_kerja_tidak" id="input_bulan_tidak" required class="w-full border border-gray-500 rounded px-2 py-1 text-sm outline-none bg-amber-50">
                                    <option value="" disabled selected>-- Pilih Bulan --</option>
                                    @for ($i = 6; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} Bulan</option>
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
                                <option value="" disabled selected>-- Pilih Provinsi --</option>
                                <option value="Belum Bekerja">Belum Bekerja</option>
                                <option value="Prov. D.K.I. Jakarta">Prov. D.K.I. Jakarta</option>
                                <option value="Prov. Jawa Barat">Prov. Jawa Barat</option>
                                <option value="Prov. Jawa Tengah">Prov. Jawa Tengah</option>
                                <option value="Prov. D.I. Yogyakarta">Prov. D.I. Yogyakarta</option>
                                <option value="Prov. Jawa Timur">Prov. Jawa Timur</option>
                                <option value="Prov. Aceh">Prov. Aceh</option>
                                <option value="Prov. Sumatera Utara">Prov. Sumatera Utara</option>
                                <option value="Prov. Sumatera Barat">Prov. Sumatera Barat</option>
                                <option value="Prov. Sumatera Selatan">Prov. Sumatera Selatan</option>
                                <option value="Prov. Riau">Prov. Riau</option>
                                <option value="Prov. Jambi">Prov. Jambi</option>
                                <option value="Prov. Lampung">Prov. Lampung</option>
                                <option value="Prov. Kalimantan Barat">Prov. Kalimantan Barat</option>
                                <option value="Prov. Kalimantan Tengah">Prov. Kalimantan Tengah</option>
                                <option value="Prov. Kalimantan Timur">Prov. Kalimantan Timur</option>
                                <option value="Prov. Kalimantan Selatan">Prov. Kalimantan Selatan</option>
                                <option value="Prov. Kalimantan Utara">Prov. Kalimantan Utara</option>
                                <option value="Prov. Sulawesi Barat">Prov. Sulawesi Barat</option>
                                <option value="Prov. Sulawesi Tengah">Prov. Sulawesi Tengah</option>
                                <option value="Prov. Sulawesi Utara">Prov. Sulawesi Utara</option>
                                <option value="Prov. Sulawesi Selatan">Prov. Sulawesi Selatan</option>
                                <option value="Prov. Sulawesi Tenggara">Prov. Sulawesi Tenggara</option>
                                <option value="Prov. Maluku">Prov. Maluku</option>
                                <option value="Prov. Maluku Utara">Prov. Maluku Utara</option>
                                <option value="Prov. Bali">Prov. Bali</option>
                                <option value="Prov. NTB">Prov. NTB</option>
                                <option value="Prov. NTT">Prov. NTT</option>
                                <option value="Prov. Papua">Prov. Papua</option>
                                <option value="Prov. Papua Barat">Prov. Papua Barat</option>
                                <option value="Prov. Bengkulu">Prov. Bengkulu</option>
                                <option value="Prov. Banten">Prov. Banten</option>
                                <option value="Prov. Bangka Belitung">Prov. Bangka Belitung</option>
                                <option value="Prov. Gorontalo">Prov. Gorontalo</option>
                                <option value="Prov. Kepulauan Riau">Prov. Kepulauan Riau</option>
                                <option value="Luar Negeri">Luar Negeri</option>
                            </select>

                            <select name="f510_kab_kota" id="kab_kota" required disabled class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50 disabled:opacity-50">
                                <option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="border border-gray-500 bg-amber-50 p-4 rounded-lg">
                            <span class="block text-sm font-medium text-gray-700 mb-2">Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="1" class="w-4 h-4 text-blue-600"><span>Instansi pemerintah</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="2" class="w-4 h-4 text-blue-600"><span>BUMN/BUMD</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="3" class="w-4 h-4 text-blue-600"><span>Institusi/Organisasi Multilateral</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="4" class="w-4 h-4 text-blue-600"><span>Organisasi non-profit/Lembaga Swadaya Masyarakat</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="5" class="w-4 h-4 text-blue-600"><span>Perusahaan swasta</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f11_jenis_instansi" value="6" class="w-4 h-4 text-blue-600"><span>Wiraswasta/Perusahaan sendiri</span></label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="f11_02" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama perusahaan/kantor</label>
                            <input type="text" name="f5b_nama_perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bila berwiraswasta, posisi/jabatan</label>
                            <select name="f5c_posisi" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                                <option value="" disabled selected>Pilih Posisi</option>
                                <option value="Founder">Founder</option>
                                <option value="Co-Founder">Co-Founder</option>
                                <option value="Staff">Staff</option>
                                <option value="Freelance">Freelance / Kerja Lepas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat tempat kerja anda</label>
                            <select name="f5d_tingkat" class="w-full border border-gray-500 rounded px-3 py-2 outline-none bg-amber-50">
                                <option value="" disabled selected>Pilih Tingkatan</option>
                                <option value="Lokal">Lokal/Wilayah/wiraswasta tidak berbadan hukum</option>
                                <option value="Nasional">Nasional/Wiraswasta berbadan hukum</option>
                                <option value="Internasional">Multinasional/internasional</option>
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
                                <option value="" disabled selected>-- Pilih sumber biaya --</option>
                                <option value="">Biaya Sendiri</option>
                                <option value="">Beasiswa</option>
                            </select>    
                        <input type="text" name="f18b_perguruan_tinggi_studi" placeholder="Perguruan Tinggi" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none">
                        <input type="text" name="f18c_program_studi" placeholder="Program Studi" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none">
                        <div class="bg-gray-50 p-3 rounded-lg space-y-1 shadow-inner border border-gray-300">
                            <label class="block font-semibold text-xs text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="f18d_tanggal_masuk" class="w-full border border-gray-500 bg-white rounded px-3 py-2 text-sm outline-none">
                        </div>
                    </div>

                    <div class="border border-gray-500 bg-amber-50 p-4 rounded-lg space-y-3 shadow-inner">
                        <span class="block font-semibold text-sm text-gray-700 mb-2">Sebutkan sumberdana dalam pembiayaan kuliah?</span>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="1" class="w-4 h-4 text-blue-600"><span>Biaya Sendiri / Keluarga</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="2" class="w-4 h-4 text-blue-600"><span>Beasiswa ADIK</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="3" class="w-4 h-4 text-blue-600"><span>Beasiswa BIDIKMISI</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="4" class="w-4 h-4 text-blue-600"><span>Beasiswa PPA</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="5" class="w-4 h-4 text-blue-600"><span>Beasiswa AFIRMASI</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f12_01" value="6" class="w-4 h-4 text-blue-600"><span>Beasiswa Perusahaan/Swasta</span></label>
                        </div>
                        <input type="text" name="f12_02" placeholder="Lainnya, tuliskan:" class="w-full border border-gray-500 rounded px-3 py-1.5 text-sm mt-3 outline-none bg-white">
                    </div>
                </div>
            </div>

            <!-- SECTION F14 & F15: KESELARASAN KERJA -->
            <div class="border border-gray-500 grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-100 p-4 rounded-lg shadow-sm">
                <div class="border border-gray-500 p-4 rounded-lg bg-amber-50">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?</span>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="1" class="w-4 h-4 text-blue-600"><span>Sangat Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="2" class="w-4 h-4 text-blue-600"><span>Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="3" class="w-4 h-4 text-blue-600"><span>Cukup Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="4" class="w-4 h-4 text-blue-600"><span>Kurang Erat</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f14" value="5" class="w-4 h-4 text-blue-600"><span>Tidak Sama Sekali</span></label>
                    </div>
                </div>

                <div class="border border-gray-500 p-4 rounded-lg bg-amber-50">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?</span>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="1" class="w-4 h-4 text-blue-600"><span>Setingkat Lebih Tinggi</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="2" class="w-4 h-4 text-blue-600"><span>Tingkat yang Sama</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="3" class="w-4 h-4 text-blue-600"><span>Setingkat Lebih Rendah</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f15" value="4" class="w-4 h-4 text-blue-600"><span>Tidak Perlu Pendidikan Tinggi</span></label>
                    </div>
                </div>
            </div>

            <!-- SECTION F17: MATRIKS KOMPETENSI (A & B) -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm overflow-x-auto">
                <h2 class="text-xl font-bold text-blue-800 mb-1 border-l-4 border-blue-800 pl-3">Kompetensi dikuasai dan diperlukan saat bekerja</h2>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-700 mb-4 font-medium">
                    <span>(1: Sangat Tinggi)</span>
                    <span>(2: Tinggi)</span>
                    <span>(3: Cukup Tinggi)</span>
                    <span>(4: Rendah)</span>
                    <span>(5: Sangat Rendah)</span>
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
                                <input type="radio" name="comp_a_{{ $key }}" value="{{ $i }}" class="text-blue-600">
                            </td>
                            @endfor
                            <!-- Kebutuhan B -->
                            @for($i = 1; $i <= 5; $i++)
                            <td class="p-1 text-center border border-gray-500 last:border-r-0">
                                <input type="radio" name="comp_b_{{ $key }}" value="{{ $i }}" class="text-blue-600">
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- SECTION F2: PENEKANAN METODE PEMBELAJARAN -->
            <div class="border border-gray-500 bg-blue-100 p-4 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-blue-800 mb-1 border-l-4 border-blue-800 pl-3">F2: Penekanan Metode Pembelajaran</h2>
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
                                <input type="radio" name="{{ $code }}" value="{{ $v }}" class="text-blue-600 w-3.5 h-3.5">
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
                        <input type="radio" name="f301" id="f301_1" value="1" class="w-4 h-4 text-blue-600">
                        <label for="f301_1" class="flex items-center space-x-2 cursor-pointer text-gray-700">
                            <input type="number" name="f302" class="w-16 border border-gray-500 rounded px-2 py-1 text-center outline-none bg-white">
                            <span class="text-xs">bulan sebelum lulus</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_2" value="2" class="w-4 h-4 text-blue-600">
                        <label for="f301_2" class="flex items-center space-x-2 cursor-pointer text-gray-700">
                            <input type="number" name="f303" class="w-16 border border-gray-500 rounded px-2 py-1 text-center outline-none bg-white">
                            <span class="text-xs">bulan sesudah lulus</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="radio" name="f301" id="f301_3" value="3" class="w-4 h-4 text-blue-600">
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
                            'f414' => 'Bekerja di tempat yang sama dengan tempat kerja semasa kuliah'
                        ];
                    @endphp
                    @foreach($cara_cari as $code => $text)
                    <label class="flex items-start space-x-2 cursor-pointer">
                        <input type="checkbox" name="{{ $code }}" value="1" class="mt-1 rounded text-blue-600">
                        <span>{{ $text }}</span>
                    </label>
                    @endforeach
                    <div class="md:col-span-2 mt-1">
                        <input type="text" name="f415_lainnya" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </div>
                </div>
            </div>

            <!-- SECTION F6, F7, F17a: JUMLAH LAMARAN -->
            <div class="border border-gray-500 grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-100 p-4 rounded-lg shadow-sm">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa perusahaan/instansi yang sudah anda lamar sebelum memperoleh pekerjaan pertama?</span>
                        <input type="number" name="f6_jumlah_lamaran" placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa banyak perusahaan/instansi yang merespons lamaran anda selama ini?</span>
                        <input type="number" name="f7_jumlah_respons" placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 border border-gray-500 bg-blue-200 p-4 rounded-lg h-full flex flex-col justify-between"> 
                        <span class="mb-2 block">Berapa banyak perusahaan/instansi yang mengundang anda untuk wawancara?</span>
                        <input type="number" name="f17a_jumlah_wawancara" placeholder="... perusahaan" class="w-full border border-gray-500 rounded px-3 py-2 text-sm outline-none bg-amber-50">
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
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="1" required class="w-4 h-4 text-blue-600"><span>Tidak</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="2" required class="w-4 h-4 text-blue-600"><span>Tidak, tapi saya sedang menunggu hasil lamaran kerja</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="3" required class="w-4 h-4 text-blue-600"><span>Ya, saya akan mulai bekerja dalam 2 minggu ke depan</span></label>
                        <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="f10_aktif" value="4" required class="w-4 h-4 text-blue-600"><span>Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan</span></label>
                    </div>
                    <input type="text" name="f10_lainnya" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-1.5 text-sm mt-3 outline-none bg-amber-50">
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
                                'f1612' => 'Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya.'
                            ];
                        @endphp
                        @foreach($alasan_f16 as $code => $text)
                        <label class="flex items-start space-x-2 cursor-pointer">
                            <input type="checkbox" name="{{ $code }}" value="1" class="mt-1 rounded text-blue-600">
                            <span>{{ $text }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="f1613" placeholder="Lainnya:" class="w-full border border-gray-500 rounded px-3 py-2 text-sm mt-3 outline-none bg-amber-50">
                </div>
            </div>

            <!-- BUTTON SUBMIT -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-800 border border-black hover:bg-blue-900 text-white font-bold py-3.5 px-4 rounded-lg shadow-lg transition duration-200 cursor-pointer tracking-wide uppercase text-sm md:text-base">
                    SIMPAN DAN KIRIM DATA KUESIONER DIKTI
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

        "Prov. D.K.I. Jakarta": ["Jakarta Pusat", "Jakarta Utara", "Jakarta Barat", "Jakarta Selatan", "Jakarta Timur", "Kepulauan Seribu"],

        "Prov. Jawa Barat": ["Kota Bandung", "Kota Bogor", "Kota Bekasi", "Kota Depok", "Kota Tasikmalaya", "Kab. Garut", "Kab. Sumedang", "Kab. Cirebon"],

        "Prov. Jawa Tengah": ["Kota Semarang", "Kota Surakarta (Solo)", "Kota Magelang", "Kab. Banyumas", "Kab. Kudus", "Kab. Brebes"],

        "Prov. D.I. Yogyakarta": ["Kota Yogyakarta", "Kab. Sleman", "Kab. Bantul", "Kab. Kulon Progo", "Kab. Gunungkidul"],

        "Prov. Jawa Timur": ["Kota Surabaya", "Kota Malang", "Kota Kediri", "Kota Madiun", "Kab. Sidoarjo", "Kab. Jember", "Kab. Banyuwangi"],

        "Prov. Aceh": ["Kota Banda Aceh", "Kota Lhokseumawe", "Kota Sabang", "Kab. Aceh Besar", "Kab. Pidie"],

        "Prov. Sumatera Utara": ["Kota Medan", "Kota Binjai", "Kota Pematangsiantar", "Kab. Deli Serdang", "Kab. Langkat", "Kab. Karo"],

        "Prov. Sumatera Barat": [

            "Kota Solok", "Kabupaten Solok", "Kabupaten Solok Selatan", "Kota Padang", "Kota Bukittinggi",

            "Kota Payakumbuh", "Kota Padang Panjang", "Kota Sawahlunto", "Kota Pariaman", "Kabupaten Agam",

            "Kabupaten Tanah Datar", "Kabupaten Pesisir Selatan", "Kabupaten Padang Pariaman", "Kabupaten Pasaman",

            "Kabupaten Pasaman Barat", "Kabupaten 50 Kota", "Kabupaten Sijunjung", "Kabupaten Dharmasraya", "Kabupaten Kepulauan Mentawai"

        ],

        "Prov. Sumatera Selatan": ["Kota Palembang", "Kota Prabumulih", "Kota Lubuklinggau", "Kab. Ogan Komering Ilir", "Kab. Muara Enim"],

        "Prov. Riau": ["Kota Pekanbaru", "Kota Dumai", "Kab. Kampar", "Kab. Bengkalis", "Kab. Siak", "Kab. Pelalawan"],

        "Prov. Jambi": ["Kota Jambi", "Kota Sungai Penuh", "Kab. Muaro Jambi", "Kab. Bungo", "Kab. Kerinci"],

        "Prov. Lampung": ["Kota Bandar Lampung", "Kota Metro", "Kab. Lampung Selatan", "Kab. Lampung Tengah"],

        "Prov. Kalimantan Barat": ["Kota Pontianak", "Kota Singkawang", "Kab. Kubu Raya", "Kab. Mempawah"],

        "Prov. Kalimantan Tengah": ["Kota Palangka Raya", "Kab. Kotawaringin Timur", "Kab. Kapuas"],

        "Prov. Kalimantan Timur": ["Kota Samarinda", "Kota Balikpapan", "Kota Bontang", "Kab. Kutai Kartanegara"],

        "Prov. Kalimantan Selatan": ["Kota Banjarmasin", "Kota Banjarbaru", "Kab. Banjar", "Kab. Tanah Laut"],

        "Prov. Kalimantan Utara": ["Kota Tarakan", "Kab. Bulungan", "Kab. Malinau", "Kab. Nunukan"],

        "Prov. Sulawesi Barat": ["Kab. Mamuju", "Kab. Majene", "Kab. Polewali Mandar"],

        "Prov. Sulawesi Tengah": ["Kota Palu", "Kab. Donggala", "Kab. Banggai", "Kab. Poso"],

        "Prov. Sulawesi Utara": ["Kota Manado", "Kota Bitung", "Kota Tomohon", "Kab. Minahasa"],

        "Prov. Sulawesi Selatan": ["Kota Makassar", "Kota Parepare", "Kota Palopo", "Kab. Gowa", "Kab. Maros", "Kab. Bone"],

        "Prov. Sulawesi Tenggara": ["Kota Kendari", "Kota Bau-Bau", "Kab. Kolaka", "Kab. Muna"],

        "Prov. Maluku": ["Kota Ambon", "Kota Tual", "Kab. Maluku Tengah"],

        "Prov. Maluku Utara": ["Kota Ternate", "Kota Tidore Kepulauan", "Kab. Halmahera Utara"],

        "Prov. Bali": ["Kota Denpasar", "Kab. Badung", "Kab. Gianyar", "Kab. Buleleng", "Kab. Tabanan"],

        "Prov. NTB": ["Kota Mataram", "Kota Bima", "Kab. Lombok Barat", "Kab. Lombok Timur", "Kab. Sumbawa"],

        "Prov. NTT": ["Kota Kupang", "Kab. Sikka", "Kab. Manggarai", "Kab. Ende"],

        "Prov. Papua": ["Kota Jayapura", "Kab. Jayapura", "Kab. Biak Numfor"],

        "Prov. Papua Barat": ["Kota Sorong", "Kab. Manokwari", "Kab. Fakfak"],

        "Prov. Bengkulu": ["Kota Bengkulu", "Kab. Rejang Lebong", "Kab. Muko-Muko"],

        "Prov. Banten": ["Kota Tangerang", "Kota Serang", "Kota Cilegon", "Kab. Tangerang", "Kab. Serang", "Kab. Lebak"],

        "Prov. Bangka Belitung": ["Kota Pangkal Pinang", "Kab. Bangka", "Kab. Belitung"],

        "Prov. Gorontalo": ["Kota Gorontalo", "Kab. Gorontalo", "Kab. Boalemo"],

        "Prov. Kepulauan Riau": ["Kota Batam", "Kota Tanjungpinang", "Kab. Bintan", "Kab. Karimun", "Kab. Natuna"],

        "Luar Negeri": ["Asia", "Eropa", "Amerika", "Australia", "Afrika"]

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

