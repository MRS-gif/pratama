@extends('layouts.app')
@section('judul', 'Daftar di Loket')
@section('isi')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-8">
    <h1 class="text-xl font-bold text-teal-700 mb-4">Pendaftaran Pasien di Loket</h1>
    <form method="POST" action="{{ route('admin.walkin.store') }}" class="grid gap-3">@csrf
        <label class="text-sm">Dokter
            <select name="dokter_id" required class="border rounded px-3 py-2 w-full">
                @foreach ($dokter as $d)<option value="{{ $d->id }}">{{ $d->user->name }} — {{ $d->spesialis }}</option>@endforeach
            </select></label>

        <label class="text-sm">Pasien lama (pilih bila sudah terdaftar)
            <select name="pasien_id" class="border rounded px-3 py-2 w-full">
                <option value="">— Pasien baru (isi data di bawah) —</option>
                @foreach ($pasien as $p)<option value="{{ $p->id }}">{{ $p->no_rm }} · {{ $p->nama }}</option>@endforeach
            </select></label>

        <div class="border-t pt-3 grid gap-3">
            <p class="text-sm text-slate-500">Data pasien baru</p>
            <input name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" class="border rounded px-3 py-2">
            <input name="nik" value="{{ old('nik') }}" placeholder="NIK (16 digit, opsional)" class="border rounded px-3 py-2">
            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="border rounded px-3 py-2">
            <input name="telp" value="{{ old('telp') }}" placeholder="No. HP" class="border rounded px-3 py-2">
        </div>
        <input name="keluhan_awal" placeholder="Keluhan singkat (opsional)" class="border rounded px-3 py-2">
        <button class="bg-teal-700 hover:bg-teal-800 text-white rounded py-2">Buat Nomor Antrean</button>
    </form>
</div>
@endsection
