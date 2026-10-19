@extends('layouts.app')
@section('judul', 'Masuk')
@section('isi')
<div class="max-w-md mx-auto mt-10 bg-white rounded-xl shadow p-8">
    <h1 class="text-2xl font-bold text-teal-700 mb-1">🏥 {{ config('klinik.nama') }}</h1>
    <p class="text-slate-500 mb-6">Masuk untuk melanjutkan</p>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">@csrf
        <div><label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm mb-1">Kata sandi</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2"></div>
        <button class="w-full bg-teal-700 hover:bg-teal-800 text-white rounded py-2">Masuk</button>
    </form>
    <p class="text-sm text-center mt-4">Pasien baru? <a class="text-teal-700 underline" href="{{ route('register') }}">Daftar akun</a></p>

    <div class="mt-6 text-xs bg-slate-50 border rounded p-3 text-slate-600">
        <b>Akun demo</b> (kata sandi: <code>password</code>)<br>
        admin@klinik.test · dokter1@klinik.test · dokter2@klinik.test<br>
        apoteker@klinik.test · pasien@klinik.test
    </div>
</div>
@endsection
