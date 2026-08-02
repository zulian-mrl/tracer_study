<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun Admin - Tracer Study</title>
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
            <h1 class="text-xl font-extrabold tracking-wider grad-text uppercase">👥 Kelola Akun Admin</h1>
            <div class="flex items-center space-x-2">
                <a href="{{ route('kuesioner.dashboard') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">← Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-600 hover:to-rose-500 px-4 py-2 rounded-lg border border-rose-500/50 shadow-lg">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        @if(session('success') || session('password_sukses'))
            <div class="fade-up bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 px-4 py-3 rounded-xl shadow-lg">
                {{ session('success') ?? session('password_sukses') }}
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
            Halaman ini hanya bisa dibuka oleh <span class="text-amber-400 font-semibold">super admin</span> (akun pertama).
            Akun admin biasa yang dibuat di sini bisa <span class="text-amber-400">login dan melihat dashboard</span>, tapi
            <span class="text-amber-400">tidak bisa</span> membuka halaman ini. Tidak ada pendaftaran publik.
        </div>

        <!-- ================= FORM TAMBAH AKUN ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">➕ Tambah Akun Admin</h2>
            <form action="{{ route('akun.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="cth: Budi Santoso" class="inp">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="cth: budi@gmail.com" class="inp">
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                        <input type="password" name="password" placeholder="min. 8 karakter" class="inp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Ulangi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" class="inp">
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer select-none bg-slate-900/40 border border-slate-700/60 rounded-xl px-4 py-3 transition hover:border-amber-500/50">
                    <input type="checkbox" name="is_super" value="1" @checked(old('is_super')) class="w-4 h-4 accent-amber-400 cursor-pointer">
                    <span class="text-sm text-gray-300">⭐ Jadikan <span class="text-amber-400 font-semibold">Super Admin</span>
                        <span class="block text-xs text-gray-500">Bisa login & kelola akun admin (hati-hati, hanya beri ke orang terpercaya)</span>
                    </span>
                </label>
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-3 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm hover:-translate-y-0.5 hover:shadow-amber-500/30">
                    👤 Simpan Akun
                </button>
            </form>
        </div>

        <!-- ================= DAFTAR AKUN ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">📋 Daftar Akun ({{ $akun->count() }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 uppercase tracking-wider text-xs border-b border-slate-600/60">
                            <th class="py-3 pr-3">Nama</th>
                            <th class="py-3 pr-3">Email</th>
                            <th class="py-3 pr-3">Tipe</th>
                            <th class="py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akun as $a)
                            <tr class="border-b border-slate-700/40 hover:bg-slate-800/30 transition">
                                <td class="py-3 pr-3 font-medium text-gray-200">
                                    {{ $a->name }}
                                    @if($a->id === auth()->id())
                                        <span class="ml-1 text-[10px] text-slate-400">(Anda)</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-3 text-gray-400">{{ $a->email }}</td>
                                <td class="py-3 pr-3">
                                    @if($a->is_super)
                                        <span class="text-[11px] bg-amber-500/15 text-amber-300 border border-amber-500/40 rounded-full px-2 py-0.5">⭐ Super Admin</span>
                                    @else
                                        <span class="text-[11px] bg-sky-500/15 text-sky-300 border border-sky-500/40 rounded-full px-2 py-0.5">Admin Biasa</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    @if($a->id === auth()->id())
                                        <span class="text-xs text-slate-500">(Akun Anda)</span>
                                    @elseif(!$a->is_super)
                                        <form action="{{ route('akun.super', $a) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs bg-amber-700/70 hover:bg-amber-600 border border-amber-500/50 px-3 py-2 rounded-lg">⬆ Jadikan Super</button>
                                        </form>
                                        <form action="{{ route('akun.reset', $a) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="password" name="password" placeholder="Password baru" class="inp !w-32 text-xs inline-block">
                                            <input type="password" name="password_confirmation" placeholder="Ulangi" class="inp !w-28 text-xs inline-block">
                                            <button type="submit" class="text-xs bg-slate-700 hover:bg-slate-600 border border-slate-500/50 px-3 py-2 rounded-lg">Reset</button>
                                        </form>
                                        <form action="{{ route('akun.hapus', $a) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin hapus akun {{ $a->email }}?')">
                                            @csrf
                                            <button type="submit" class="text-xs bg-rose-700/80 hover:bg-rose-600 border border-rose-500/50 px-3 py-2 rounded-lg">Hapus</button>
                                        </form>
                                    @else
                                        @php
                                            $superAkun = $akun->where('is_super', true);
                                            $utamaId = $superAkun->sortBy('id')->first()?->id;
                                            $hanyaSatu = $superAkun->count() <= 1;
                                        @endphp
                                        @if($a->id === $utamaId || $hanyaSatu)
                                            <span class="text-xs text-slate-500">{{ $a->id === $utamaId ? 'Akun utama' : 'Super terakhir' }}</span>
                                        @else
                                            <form action="{{ route('akun.super', $a) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs bg-slate-700 hover:bg-slate-600 border border-slate-500/50 px-3 py-2 rounded-lg">⬇ Turunkan</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500">Belum ada akun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= GANTI PASSWORD SENDIRI ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">🔑 Ganti Password Saya</h2>
            <p class="text-xs text-gray-500 mb-4">Ganti password akun Anda sendiri (wajib memasukkan password lama yang benar).</p>
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

        <!-- ================= KODE PEMULIHAN SUPER ADMIN ================= -->
        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">🔐 Kode Pemulihan Super Admin</h2>
            <p class="text-xs text-gray-500 mb-4">
                Jika super admin lupa password, gunakan tautan <span class="text-amber-400">"Lupa Password Super Admin?"</span>
                di halaman login dengan kode ini. Kode disimpan sebagai hash (aman). Jika kolom dibiarkan kosong,
                kode lama tetap berlaku.
            </p>
            <form action="{{ route('akun.kodePemulihan') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Kode Pemulihan (min. 8 karakter)</label>
                    <div class="flex gap-2">
                        <input type="password" name="kode_pemulihan" id="kode_pemulihan"
                               placeholder="Kosongkan untuk mempertahankan kode lama" class="inp">
                        <button type="button" id="tombolLihatKode" onclick="lihatKode()" title="Lihat/sembunyikan kode yang diketik"
                                class="shrink-0 text-xs bg-slate-700 hover:bg-slate-600 border border-slate-500/50 px-3 py-2 rounded-lg">
                            👁️
                        </button>
                        <button type="button" onclick="hasilKode()"
                                class="shrink-0 text-xs bg-slate-700 hover:bg-slate-600 border border-slate-500/50 px-3 py-2 rounded-lg">
                            🎲 Acak
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="kode_pemulihan_hapus" value="1" class="w-4 h-4 accent-amber-400 cursor-pointer">
                    <span class="text-sm text-gray-300">Hapus kode pemulihan (nonaktifkan fitur ini)</span>
                </label>
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-3 px-4 rounded-xl shadow-xl transition duration-200 cursor-pointer tracking-wide uppercase text-sm hover:-translate-y-0.5 hover:shadow-amber-500/30">
                    💾 Simpan Kode Pemulihan
                </button>
            </form>
        </div>

        <footer class="text-center text-xs text-slate-500 py-4">
            © {{ date('Y') }} Tracer Study LPKM UMMY Solok — Kelola Akun Admin
        </footer>
    </div>

    <script>
        function hasilKode() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
            let kode = '';
            for (let i = 0; i < 12; i++) {
                kode += chars[Math.floor(Math.random() * chars.length)];
            }
            document.getElementById('kode_pemulihan').value = kode;
        }

        function lihatKode() {
            const input = document.getElementById('kode_pemulihan');
            const btn = document.getElementById('tombolLihatKode');
            const sedangTerlihat = input.type === 'text';
            input.type = sedangTerlihat ? 'password' : 'text';
            btn.textContent = sedangTerlihat ? '👁️' : '🙈';
        }
    </script>

</body>
</html>
