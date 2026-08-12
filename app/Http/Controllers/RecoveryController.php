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
            'email' => 'required|email',
            'kode' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();
        $stored = Setting::get('kode_pemulihan', '');

        if (! $user || ! $user->is_super || $stored === '' || ! Hash::check($data['kode'], $stored)) {
            return back()->withErrors([
                'email' => 'Email atau kode pemulihan yang Anda masukkan tidak valid. Silakan coba lagi.',
            ])->withInput();
        }

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLog::catat('reset_pemulihan', null, $user, 'Password direset via kode pemulihan');

        return redirect()->route('login')->with('success', 'Password super admin berhasil direset. Silakan login.');
    }
}
