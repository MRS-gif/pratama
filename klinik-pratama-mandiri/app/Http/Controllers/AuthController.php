<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $r)
    {
        $cred = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (!Auth::attempt($cred, $r->boolean('ingat'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }
        $r->session()->regenerate();
        return redirect()->route('home');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|string|max:100',
            'nik' => 'nullable|digits:16',
            'tgl_lahir' => 'required|date|before:today',
            'telp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = DB::transaction(function () use ($d) {
            $u = User::create(['name' => $d['name'], 'email' => $d['email'], 'password' => Hash::make($d['password']), 'role' => 'pasien']);
            Pasien::create(['user_id' => $u->id, 'nama' => $d['name'], 'nik' => $d['nik'] ?? null,
                'tgl_lahir' => $d['tgl_lahir'], 'telp' => $d['telp'], 'alamat' => $d['alamat'] ?? null]);
            return $u;
        });

        Auth::login($user);
        return redirect()->route('home')->with('ok', 'Pendaftaran berhasil. Selamat datang!');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}
