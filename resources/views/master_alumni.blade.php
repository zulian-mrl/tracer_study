@extends('layouts.admin')

@section('title', 'Data Master Alumni - Tracer Study')
@section('judul', '🗂 Data Master Alumni')

@section('nav_right')
            <div class="flex items-center space-x-2">
                <a href="{{ route('kuesioner.dashboard') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">← Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-600 hover:to-rose-500 px-4 py-2 rounded-lg border border-rose-500/50 shadow-lg">Keluar</button>
                </form>
            </div>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="fade-up bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 px-4 py-3 rounded-xl shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="fade-up bg-rose-950/80 border border-rose-700/60 text-rose-300 px-4 py-3 rounded-xl shadow-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="glass rounded-2xl p-5 text-sm text-gray-400 shadow-xl fade-up">
            Halaman ini untuk <span class="text-amber-400 font-semibold">melihat dan mengubah data master alumni</span> yang
            dipakai sebagai acuan validasi pengisi kuesioner (NIM &amp; NIK). Untuk menambah data dalam jumlah besar,
            gunakan tombol <span class="text-amber-400">"+ Kelola Data Alumni"</span> di dashboard (import Excel).
        </div>

        <!-- ================= PENCARIAN + DAFTAR ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">📋 Daftar Alumni ({{ number_format($alumni->total(), 0, ',', '.') }} data)</h2>

            <form method="GET" action="{{ route('master.index') }}" class="flex flex-col sm:flex-row gap-2 mb-4">
                <input type="text" name="q" value="{{ $cari }}" placeholder="Cari NIM, nama, NIK, kode prodi, atau tahun lulus..."
                       class="inp flex-1">
                <select name="kode_prodi" class="inp sm:!w-36 shrink-0 cursor-pointer" title="Filter Kode Prodi">
                    <option value="">-- Semua Prodi --</option>
                    @foreach($listProdi as $prd)
                        <option value="{{ $prd }}" @selected($prodiTerpilih === (string) $prd)>{{ $prd }}</option>
                    @endforeach
                </select>
                <select name="tahun_lulus" class="inp sm:!w-40 shrink-0 cursor-pointer" title="Filter Tahun Lulus">
                    <option value="">-- Semua Tahun Lulus --</option>
                    @foreach($listTahun as $th)
                        <option value="{{ $th }}" @selected($tahunTerpilih === (string) $th)>{{ $th }}</option>
                    @endforeach
                </select>
                <button type="submit" class="shrink-0 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold px-5 py-2 rounded-xl shadow transition text-sm">
                    🔍 Cari
                </button>
                @if($cari !== '' || $tahunTerpilih !== '' || $prodiTerpilih !== '')
                    <a href="{{ route('master.index') }}" class="shrink-0 text-center bg-slate-700 hover:bg-slate-600 border border-slate-500/50 px-4 py-2 rounded-lg text-sm">Reset</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 uppercase tracking-wider text-xs border-b border-slate-600/60">
                            <th class="py-3 pr-3">NIM</th>
                            <th class="py-3 pr-3">Prodi</th>
                            <th class="py-3 pr-3">Nama</th>
                            <th class="py-3 pr-3">NIK</th>
                            <th class="py-3 pr-3">Tahun</th>
                            <th class="py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumni as $a)
                            <tr class="border-b border-slate-700/40 hover:bg-slate-800/30 transition">
                                <td class="py-3 pr-3 font-mono text-gray-200">{{ $a->no_mahasiswa }}</td>
                                <td class="py-3 pr-3 text-gray-400">{{ $a->kode_prodi }}</td>
                                <td class="py-3 pr-3 font-medium text-gray-200">{{ $a->nama }}</td>
                                <td class="py-3 pr-3 font-mono text-gray-400">{{ $a->nik }}</td>
                                <td class="py-3 pr-3 text-gray-400">{{ $a->tahun_lulus }}</td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <button type="button"
                                            onclick="bukaEdit(this)"
                                            data-url="{{ route('master.update', $a->no_mahasiswa) }}"
                                            data-nim="{{ $a->no_mahasiswa }}"
                                            data-prodi="{{ $a->kode_prodi }}"
                                            data-nama="{{ $a->nama }}"
                                            data-nik="{{ $a->nik }}"
                                            data-tahun="{{ $a->tahun_lulus }}"
                                            class="text-xs bg-sky-700/80 hover:bg-sky-600 border border-sky-500/50 px-3 py-2 rounded-lg">
                                        ✏️ Ubah
                                    </button>
                                    <form action="{{ route('master.destroy', $a->no_mahasiswa) }}" method="POST" class="inline form-hapus"
                                          data-alumni="{{ $a->nama }} ({{ $a->no_mahasiswa }})">
                                        @csrf
                                        <input type="hidden" name="q" value="{{ $cari }}">
                                        <input type="hidden" name="filter_tahun" value="{{ $tahunTerpilih }}">
                                        <input type="hidden" name="filter_prodi" value="{{ $prodiTerpilih }}">
                                        <input type="hidden" name="page" value="{{ request('page') }}">
                                        <button type="submit" class="text-xs bg-rose-700/80 hover:bg-rose-600 border border-rose-500/50 px-3 py-2 rounded-lg">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">
                                    {{ $cari !== '' ? 'Tidak ada alumni yang cocok dengan pencarian "'.$cari.'".' : 'Belum ada data master alumni. Impor lewat dashboard.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $alumni->links() }}
            </div>
        </div>

        <footer class="text-center text-xs text-slate-500 py-4">
            © {{ date('Y') }} Tracer Study LPKM UMMY Solok — Data Master Alumni
        </footer>
    </div>

    <!-- ================= MODAL UBAH DATA ================= -->
    <div id="modalEdit" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-slate-900/60 p-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-md font-bold text-amber-400">✏️ Ubah Data Master Alumni</h3>
                <button type="button" onclick="tutupEdit()" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>
            <form id="formEdit" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="q" value="{{ $cari }}">
                <input type="hidden" name="filter_tahun" value="{{ $tahunTerpilih }}">
                <input type="hidden" name="filter_prodi" value="{{ $prodiTerpilih }}">
                <input type="hidden" name="page" value="{{ request('page') }}">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">NIM (tidak bisa diubah)</label>
                    <input type="text" id="edit_nim" readonly class="inp opacity-60 cursor-not-allowed font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" required maxlength="60" class="inp">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">NIK (16 digit)</label>
                    <input type="text" name="nik" id="edit_nik" required minlength="16" maxlength="16" inputmode="numeric" pattern="[0-9]{16}" class="inp font-mono">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Kode Prodi</label>
                        <input type="text" name="kode_prodi" id="edit_prodi" maxlength="6" class="inp font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Tahun Lulus</label>
                        <input type="text" name="tahun_lulus" id="edit_tahun" required maxlength="4" inputmode="numeric" class="inp font-mono">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="tutupEdit()" class="bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 text-xs font-bold px-5 py-2 rounded shadow">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function bukaEdit(tombol) {
            document.getElementById('formEdit').action = tombol.dataset.url;
            document.getElementById('edit_nim').value = tombol.dataset.nim;
            document.getElementById('edit_nama').value = tombol.dataset.nama;
            document.getElementById('edit_nik').value = tombol.dataset.nik;
            document.getElementById('edit_prodi').value = tombol.dataset.prodi;
            document.getElementById('edit_tahun').value = tombol.dataset.tahun;
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function tutupEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        document.getElementById('modalEdit').addEventListener('click', function (e) {
            if (e.target === this) tutupEdit();
        });

        document.querySelectorAll('form.form-hapus').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!window.confirm('Yakin hapus data alumni ' + form.dataset.alumni + '?\n\nCatatan: jika alumni ini sudah mengisi kuesioner, datanya akan muncul sebagai "belum terdaftar" di dashboard.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
