@extends('layouts.auth')

@section('title', 'Pemulihan Password - Tracer Study')

@section('content')

    <div class="w-full max-w-md fade-up">
        <div class="glass rounded-2xl overflow-hidden border-white/10 shadow-2xl">

            <div class="p-6 text-center border-b border-white/10 bg-gradient-to-br from-slate-800/80 to-slate-900/40">
                <div class="text-5xl mb-2">🔑</div>
                <h1 class="text-2xl font-extrabold tracking-wide grad-text uppercase">Pemulihan Password</h1>
                <p class="text-sm text-gray-400 mt-1">Khusus Super Admin yang lupa password</p>
            </div>

            <div class="p-6 space-y-4">
                @if($errors->any())
                    <div class="bg-rose-950 border border-rose-800 text-rose-400 p-3 rounded text-sm shadow-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('pemulihan.reset') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Email Super Admin</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2.5 text-white outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Kode Pemulihan</label>
                        <input type="password" name="kode" required autocomplete="off"
                               class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2.5 text-white outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Password Baru</label>
                        <input type="password" name="password" required autocomplete="new-password"
                               class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2.5 text-white outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Ulangi Password Baru</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               class="input-3d w-full bg-slate-800/80 border border-slate-600 rounded-lg px-3 py-2.5 text-white outline-none focus:border-amber-400">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-900 font-bold py-2.5 px-4 rounded-lg shadow-lg transition duration-150 uppercase tracking-wide hover:shadow-amber-500/30 hover:-translate-y-0.5">
                        Reset Password
                    </button>
                </form>

                <p class="text-center text-xs text-gray-500">
                    Kode pemulihan hanya diketahui oleh super admin dan bisa diganti/dimatikan di halaman Pengaturan.
                </p>

                <a href="{{ route('login') }}" class="block text-center text-sm text-gray-400 hover:text-amber-400 transition">
                    ← Kembali ke Login
                </a>
            </div>
        </div>
    </div>
@endsection
