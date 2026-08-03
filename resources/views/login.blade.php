<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Tracer Study</title>
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
                radial-gradient(circle at 20% 25%, rgba(251, 191, 36, 0.16), transparent 42%),
                radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.18), transparent 42%),
                radial-gradient(circle at 50% 90%, rgba(168, 85, 247, 0.15), transparent 48%);
        }

        .glass {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.78));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.2);
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

        /* Kartu login melayang (3D) tanpa membuat teks miring/blur */
        .float-3d {
            animation: float3d 5s ease-in-out infinite;
            filter: drop-shadow(0 28px 40px rgba(0, 0, 0, 0.55));
        }
        @keyframes float3d {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .lock-3d {
            display: inline-block;
            text-shadow: 0 8px 22px rgba(251, 191, 36, 0.55), 0 2px 6px rgba(0, 0, 0, 0.6);
            transform: perspective(600px) rotateX(8deg) rotateY(-8deg);
            filter: drop-shadow(0 10px 12px rgba(0, 0, 0, 0.5));
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .6s ease both; }

        .input-3d {
            transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease;
        }
        .input-3d:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.25), 0 10px 20px -8px rgba(0, 0, 0, 0.6);
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
    </style>
</head>
<body class="text-white font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md float-3d fade-up">
        <div class="glass rounded-2xl overflow-hidden border-white/10 shadow-2xl">

            <div class="p-6 text-center border-b border-white/10 bg-gradient-to-br from-slate-800/80 to-slate-900/40">
                <div class="text-5xl mb-2 lock-3d">🔐</div>
                <h1 class="text-2xl font-extrabold tracking-wide grad-text uppercase">Login Admin</h1>
                <p class="text-sm text-gray-400 mt-1">Tracer Study LPKM UMMY Solok</p>
            </div>

            <div class="p-6 space-y-4">
                @if($errors->any())
                    <div class="bg-rose-950 border border-rose-800 text-rose-400 p-3 rounded text-sm shadow-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2.5 text-white outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                   class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg pl-3 pr-10 py-2.5 text-white outline-none focus:border-amber-400">
                            <button type="button" id="tombolPassword" onclick="lihatPassword()" title="Lihat/sembunyikan kata sandi"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-lg text-gray-400 hover:text-amber-400 transition">
                                👁️
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center space-x-2 text-sm text-gray-300">
                        <input type="checkbox" name="remember" class="rounded text-amber-500 bg-slate-800 border-slate-600">
                        <span>Ingat saya</span>
                    </label>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-2.5 px-4 rounded-lg shadow-lg transition duration-150 uppercase tracking-wide hover:shadow-amber-500/30 hover:-translate-y-0.5">
                        Masuk
                    </button>
                </form>

                <a href="{{ route('pemulihan.index') }}" class="block text-center text-sm text-gray-400 hover:text-amber-400 transition">
                    🔑 Lupa Password Super Admin?
                </a>

                <a href="{{ route('akun.riwayat') }}" class="block text-center text-sm text-gray-400 hover:text-amber-400 transition">
                    📜 Riwayat Login & Password
                </a>

                @php
                    $intended = session('url.intended');
                    $dariRiwayat = $intended && parse_url($intended, PHP_URL_PATH) === '/admin/riwayat';
                @endphp
                @if($dariRiwayat)
                    <a href="{{ route('login', ['kembali' => 1]) }}"
                       class="block text-center text-sm bg-slate-700/70 hover:bg-slate-600 border border-slate-500/50 px-4 py-2 rounded-lg text-gray-200 transition">
                        ⬅️ Kembali ke Halaman Login
                    </a>
                @endif

                <a href="{{ route('kuesioner.index') }}" class="block text-center text-sm text-gray-400 hover:text-amber-400 transition">
                    ← Kembali ke Form Kuesioner
                </a>
            </div>
        </div>
    </div>

    <script>
        function lihatPassword() {
            const input = document.getElementById('password');
            const btn = document.getElementById('tombolPassword');
            const terlihat = input.type === 'text';
            input.type = terlihat ? 'password' : 'text';
            btn.textContent = terlihat ? '👁️' : '🙈';
        }
    </script>
</body>
</html>
