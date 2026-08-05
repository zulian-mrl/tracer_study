@extends('layouts.admin')

@section('title', 'Riwayat Login & Password - Tracer Study')
@section('judul', '📜 Riwayat Login & Password')

@section('nav_container', 'max-w-7xl')

@section('nav_right')
            <div class="flex items-center space-x-2">
                <a href="{{ route('kuesioner.dashboard') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">← Dashboard</a>
                <a href="{{ route('akun.index') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">👥 Kelola Akun</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-600 hover:to-rose-500 px-4 py-2 rounded-lg border border-rose-500/50 shadow-lg">Keluar</button>
                </form>
            </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-6">

        <div class="glass rounded-2xl p-5 text-sm text-gray-400 shadow-xl fade-up">
            Halaman ini hanya bisa dibuka oleh <span class="text-amber-400 font-semibold">super admin utama</span>.
            Mencatat perubahan password serta login/logout admin (login &amp; logout ditampilkan dalam satu baris), beserta perangkat dan IP.
        </div>

        <div class="glass rounded-2xl p-5 shadow-xl fade-up">
            <form action="{{ route('akun.riwayat') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="tanggal" class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal ?? '' }}"
                           class="bg-slate-800/80 border border-slate-600/60 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400/50 [color-scheme:dark]">
                </div>
                <button type="submit" class="text-sm bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-500 px-4 py-2 rounded-lg font-semibold shadow-lg text-slate-900">🔍 Filter</button>
                @if($tanggal)
                    <a href="{{ route('akun.riwayat') }}" class="text-sm bg-slate-700/70 hover:bg-slate-600 px-4 py-2 rounded-lg border border-slate-500/50">✖ Tampilkan Semua</a>
                @endif
            </form>
            @if($tanggal)
                <p class="mt-3 text-sm text-amber-300">
                    Menampilkan riwayat untuk:
                    <span class="font-semibold">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $tanggal)->format('d M Y') }}</span>
                </p>
            @endif
        </div>

        <div class="glass rounded-2xl p-6 shadow-xl fade-up">
            <h2 class="sec-title">📋 Riwayat ({{ $log->total() }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 uppercase tracking-wider text-xs border-b border-slate-600/60">
                            <th class="py-3 pr-3">Waktu Login</th>
                            <th class="py-3 pr-3">Waktu Logout</th>
                            <th class="py-3 pr-3">Pelaku</th>
                            <th class="py-3 pr-3">Target</th>
                            <th class="py-3 pr-3">Kejadian</th>
                            <th class="py-3 pr-3">Keterangan</th>
                            <th class="py-3 pr-3">Perangkat</th>
                            <th class="py-3 text-right">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($log as $l)
                            @php
                                $badge = [
                                    'buat_akun'       => ['bg-sky-500/15 text-sky-300 border-sky-500/40', 'Akun dibuat'],
                                    'ganti_password'  => ['bg-slate-500/15 text-slate-300 border-slate-500/40', 'Ganti password sendiri'],
                                    'reset_password'  => ['bg-amber-500/15 text-amber-300 border-amber-500/40', 'Reset password oleh admin'],
                                    'reset_pemulihan' => ['bg-amber-500/15 text-amber-300 border-amber-500/40', 'Reset via kode pemulihan'],
                                    'sesi'            => ['bg-indigo-500/15 text-indigo-300 border-indigo-500/40', 'Login → Logout'],
                                    'login'           => ['bg-emerald-500/15 text-emerald-300 border-emerald-500/40', 'Login'],
                                    'logout'          => ['bg-indigo-500/15 text-indigo-300 border-indigo-500/40', 'Logout'],
                                    'login_gagal'     => ['bg-rose-500/15 text-rose-300 border-rose-500/40', 'Login gagal'],
                                ];
                                [$cls, $label] = $badge[$l->jenis] ?? ['bg-slate-500/15 text-slate-300 border-slate-500/40', $l->jenis];
                                $fmt = fn ($t) => $t ? \Illuminate\Support\Carbon::parse($t)->format('d M Y H:i:s') : null;
                            @endphp
                            <tr class="border-b border-slate-700/40 hover:bg-slate-800/30 transition align-top">
                                <td class="py-3 pr-3 whitespace-nowrap text-gray-400">{{ $fmt($l->masuk) ?? '—' }}</td>
                                <td class="py-3 pr-3 whitespace-nowrap text-gray-400">{{ $fmt($l->keluar) ?? '—' }}</td>
                                <td class="py-3 pr-3 font-medium text-gray-200">
                                    {{ $l->actor_nama ?? '<sistem>' }}
                                    @if($l->actor_id === auth()->id())
                                        <span class="text-[10px] text-slate-400">(Anda)</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-3 text-gray-400">{{ $l->target_nama ?? '—' }}</td>
                                <td class="py-3 pr-3">
                                    <span class="text-[11px] rounded-full px-2 py-0.5 border {{ $cls }}">{{ $label }}</span>
                                </td>
                                <td class="py-3 pr-3 text-gray-400">{{ $l->keterangan ?? '—' }}</td>
                                <td class="py-3 pr-3 text-gray-400 max-w-xs break-words">
                                    {{ $l->device ? \Illuminate\Support\Str::limit($l->device, 60) : '—' }}
                                </td>
                                <td class="py-3 text-right text-gray-400 whitespace-nowrap">{{ $l->ip_address ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-slate-500">
                                    {{ $tanggal ? 'Tidak ada riwayat pada tanggal tersebut.' : 'Belum ada riwayat.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $log->links() }}
            </div>
        </div>

        <footer class="text-center text-xs text-slate-500 py-4">
            © {{ date('Y') }} Tracer Study LPKM UMMY Solok — Riwayat Login & Password
        </footer>
    </div>
@endsection
