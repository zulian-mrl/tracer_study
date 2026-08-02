<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
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

        return view('accounts', compact('akun'));
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

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_super' => $request->boolean('is_super'),
        ]);

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
        ]);

        if (!Hash::check($data['password_lama'], Auth::user()->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama yang Anda masukkan salah.',
            ])->withInput();
        }

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('password_sukses', 'Password Anda berhasil diganti.');
    }

    public function simpanKodePemulihan(Request $request)
    {
        $this->cekSuper();

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
}
