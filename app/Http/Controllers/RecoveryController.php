<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RecoveryController extends Controller
{
    public function index()
    {
        return view('pemulihan');
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'kode' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user->is_super) {
            return back()->withErrors([
                'email' => 'Akun ini bukan super admin, gunakan menu Kelola Akun untuk meresetnya.',
            ])->withInput();
        }

        $stored = Setting::get('kode_pemulihan', '');

        if ($stored === '' || !Hash::check($data['kode'], $stored)) {
            return back()->withErrors([
                'kode' => 'Kode pemulihan salah atau tidak tersedia. Silakan coba lagi.',
            ])->withInput();
        }

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLog::catat('reset_pemulihan', null, $user, 'Password direset via kode pemulihan');

        return redirect()->route('login')->with('success', 'Password super admin berhasil direset. Silakan login.');
    }
}
