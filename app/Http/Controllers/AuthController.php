<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->has('kembali')) {
            $request->session()->forget('url.intended');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            AuditLog::catat('login', $user, $user, 'Login berhasil');

            return redirect()->intended(route('kuesioner.dashboard'));
        }

        AuditLog::catat('login_gagal', null, null, 'Percobaan login gagal untuk email: ' . $credentials['email']);

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        AuditLog::catat('logout', $user, $user, 'Logout berhasil');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
