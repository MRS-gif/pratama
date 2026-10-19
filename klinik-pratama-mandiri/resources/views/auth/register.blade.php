@extends('layouts.app')
@section('judul', 'Daftar Pasien')
@section('isi')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h1 class="text-xl font-bold text-teal-700 mb-4">Pendaftaran Akun Pasien</h1>
    <form method="POST" action="{{ route('register') }}" class="grid gap-3">@csrf
        <input name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required class="border rounded px-3 py-2">
        <input name="nik" value="{{ old('nik') }}" placeholder="NIK (16 digit, opsional)" class="border rounded px-3 py-2">
        <label class="text-sm text-slate-500">Tanggal lahir
            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required class="border rounded px-3 py-2 w-full text-slate-800"></label>
        <input name="telp" value="{{ old('telp') }}" placeholder="No. HP" required class="border rounded px-3 py-2">
        <textarea name="alamat" placeholder="Alamat" class="border rounded px-3 py-2">{{ old('alamat') }}</textarea>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="border rounded px-3 py-2">
        <input type="password" name="password" placeholder="Kata sandi (min. 6)" required class="border rounded px-3 py-2">
        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required class="border rounded px-3 py-2">
        <button class="bg-teal-700 hover:bg-teal-800 text-white rounded py-2">Daftar</button>
    </form>
    <p class="text-sm text-center mt-4"><a class="text-teal-700 underline" href="{{ route('login') }}">Sudah punya akun? Masuk</a></p>
</div>
@endsection
