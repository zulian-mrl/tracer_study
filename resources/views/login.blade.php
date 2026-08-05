@extends('layouts.auth')

@section('title', 'Login Admin - Tracer Study')

@section('content')

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
@endsection

@section('script')
    <script>
        function lihatPassword() {
            const input = document.getElementById('password');
            const btn = document.getElementById('tombolPassword');
            const terlihat = input.type === 'text';
            input.type = terlihat ? 'password' : 'text';
            btn.textContent = terlihat ? '👁️' : '🙈';
        }
    </script>
@endsection
