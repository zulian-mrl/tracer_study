<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private function cekSuper(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_super, 403);
    }

    public function index()
    {
        $this->cekSuper();

        $akun = User::orderByDesc('is_super')->orderBy('email')->get();
        $utamaId = User::where('is_super', true)->orderBy('id')->value('id');

        return view('accounts', compact('akun', 'utamaId'));
    }

    public function store(Request $request)
    {
        $this->cekSuper();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_super' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_super' => $request->boolean('is_super'),
        ]);

        AuditLog::catat('buat_akun', Auth::user(), $user, "Akun {$data['name']} ({$data['email']}) dibuat" . ($user->is_super ? ' sebagai Super Admin' : ''));

        return redirect()->route('akun.index')->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function toggleSuper(Request $request, User $user)
    {
        $this->cekSuper();

        $utama = User::where('is_super', true)->orderBy('id')->first();

        abort_if($user->id === Auth::id(), 403, 'Tidak bisa mengubah status super akun sendiri.');
        abort_if($user->is_super && $utama && $user->id === $utama->id, 403, 'Akun utama super admin tidak bisa diturunkan.');
        abort_if($user->is_super && User::where('is_super', true)->count() <= 1, 403, 'Tidak bisa menurunkan super admin terakhir.');

        $user->update(['is_super' => !$user->is_super]);

        $status = $user->is_super ? 'dijadikan Super Admin' : 'diturunkan menjadi Admin Biasa';

        return redirect()->route('akun.index')->with('success', "Status {$user->email} {$status}.");
    }

    public function gantiPassword(Request $request)
    {
        $data = $request->validate([
            'password_lama' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Ulangi password baru tidak sinkron.',
        ]);

        if (!Hash::check($data['password_lama'], Auth::user()->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama yang Anda masukkan salah.',
            ])->withInput();
        }

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        AuditLog::catat('ganti_password', Auth::user(), Auth::user(), 'Mengganti password sendiri');

        return back()->with('password_sukses', 'Password Anda berhasil diganti.');
    }

    public function simpanKodePemulihan(Request $request)
    {
        $this->cekSuper();

        $utamaId = User::where('is_super', true)->orderBy('id')->value('id');
        abort_unless(Auth::id() === $utamaId, 403, 'Hanya super admin utama yang dapat mengelola kode pemulihan.');

        if ($request->boolean('kode_pemulihan_hapus')) {
            Setting::forget('kode_pemulihan');
        } elseif ($request->filled('kode_pemulihan')) {
            $request->validate([
                'kode_pemulihan' => 'string|min:8',
            ]);

            Setting::set('kode_pemulihan', Hash::make($request->input('kode_pemulihan')));
        }

        return redirect()->route('akun.index')->with('success', 'Kode pemulihan berhasil disimpan.');
    }

    public function reset(Request $request, User $user)
    {
        $this->cekSuper();

        abort_if($user->id === Auth::id(), 403, 'Tidak bisa mereset password akun sendiri dari sini.');

        $data = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLog::catat('reset_password', Auth::user(), $user, "Password {$user->name} ({$user->email}) direset oleh admin");

        return redirect()->route('akun.index')->with('success', 'Password berhasil direset.');
    }

    public function hapus(Request $request, User $user)
    {
        $this->cekSuper();

        abort_if($user->id === Auth::id(), 403, 'Tidak bisa menghapus akun sendiri.');
        abort_if($user->is_super, 403, 'Tidak bisa menghapus akun super admin.');
        abort_if(User::where('is_super', true)->count() <= 1, 403, 'Tidak bisa menghapus super admin terakhir.');

        $user->delete();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $user = Auth::user();
        $dir = public_path('uploads/fotos');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        if ($user->foto && File::exists($dir . DIRECTORY_SEPARATOR . $user->foto)) {
            File::delete($dir . DIRECTORY_SEPARATOR . $user->foto);
        }

        $nama = 'user_' . $user->id . '_' . time() . '.' . $request->foto->getClientOriginalExtension();
        $request->foto->move($dir, $nama);

        $user->foto = $nama;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['foto' => asset('uploads/fotos/' . $nama)]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diubah.');
    }

    public function updateNama(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $data['nama'];
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['nama' => $user->name]);
        }

        return redirect()->back()->with('success', 'Nama admin berhasil diubah.');
    }

    public function riwayat(Request $request)
    {
        $this->cekSuper();

        $utamaId = User::where('is_super', true)->orderBy('id')->value('id');
        abort_unless(Auth::id() === $utamaId, 403, 'Hanya super admin utama yang dapat membuka riwayat.');

        $tanggal = $request->query('tanggal');
        $cek = $tanggal ? \DateTime::createFromFormat('Y-m-d', $tanggal) : false;
        if ($tanggal && (! $cek || $cek->format('Y-m-d') !== $tanggal)) {
            $tanggal = null;
        }

        $logs = AuditLog::orderBy('created_at')->get();

        $baris = [];
        $menunggu = [];

        foreach ($logs as $l) {
            if ($l->jenis === 'login') {
                $baris[] = (object) [
                    'jenis' => 'sesi',
                    'actor_id' => $l->actor_id,
                    'actor_nama' => $l->actor_nama,
                    'target_nama' => $l->target_nama,
                    'keterangan' => 'Login berhasil',
                    'device' => $l->device,
                    'ip_address' => $l->ip_address,
                    'masuk' => $l->created_at,
                    'keluar' => null,
                ];
                $menunggu[$l->actor_id] = count($baris) - 1;
            } elseif ($l->jenis === 'logout') {
                $idx = $menunggu[$l->actor_id] ?? null;
                if ($idx !== null) {
                    $baris[$idx]->keluar = $l->created_at;
                    $baris[$idx]->keterangan = 'Login berhasil, logout berhasil';
                    unset($menunggu[$l->actor_id]);
                } else {
                    $baris[] = (object) [
                        'jenis' => 'logout',
                        'actor_id' => $l->actor_id,
                        'actor_nama' => $l->actor_nama,
                        'target_nama' => $l->target_nama,
                        'keterangan' => 'Logout berhasil',
                        'device' => $l->device,
                        'ip_address' => $l->ip_address,
                        'masuk' => null,
                        'keluar' => $l->created_at,
                    ];
                }
            } else {
                $baris[] = (object) [
                    'jenis' => $l->jenis,
                    'actor_id' => $l->actor_id,
                    'actor_nama' => $l->actor_nama,
                    'target_nama' => $l->target_nama,
                    'keterangan' => $l->keterangan,
                    'device' => $l->device,
                    'ip_address' => $l->ip_address,
                    'masuk' => $l->created_at,
                    'keluar' => null,
                ];
            }
        }

        if ($tanggal) {
            $baris = array_values(array_filter(
                $baris,
                fn ($b) => (($b->masuk ?? $b->keluar)?->format('Y-m-d')) === $tanggal
            ));
        }

        usort($baris, fn ($a, $b) => (($b->keluar ?? $b->masuk) ?? now()) <=> (($a->keluar ?? $a->masuk) ?? now()));

        $perHalaman = 50;
        $halaman = LengthAwarePaginator::resolveCurrentPage();
        $items = array_slice($baris, ($halaman - 1) * $perHalaman, $perHalaman);

        $log = new LengthAwarePaginator(
            $items,
            count($baris),
            $perHalaman,
            $halaman,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        if ($tanggal) {
            $log->appends(['tanggal' => $tanggal]);
        }

        return view('riwayat', compact('log', 'tanggal'));
    }
}
