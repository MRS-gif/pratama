@extends('layouts.app')
@section('judul', 'Booking Antrean')
@section('isi')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h1 class="text-xl font-bold text-teal-700 mb-1">Booking Nomor Antrean</h1>
    <p class="text-sm text-slate-500 mb-4">Jam layanan {{ config('klinik.jam_buka') }} – {{ config('klinik.jam_tutup') }}. Estimasi dihitung dari durasi periksa rata-rata dokter.</p>
    <form method="POST" action="{{ route('pasien.store') }}" class="grid gap-3">@csrf
        <label class="text-sm">Dokter
            <select name="dokter_id" required class="border rounded px-3 py-2 w-full">
                @foreach ($dokter as $d)
                    <option value="{{ $d->id }}" @selected(old('dokter_id') == $d->id)>{{ $d->user->name }} — {{ $d->spesialis }} (±{{ $d->menit_per_pasien }} menit/pasien)</option>
                @endforeach
            </select></label>
        <label class="text-sm">Tanggal kunjungan
            <input type="date" name="tanggal" required value="{{ old('tanggal', today()->toDateString()) }}"
                   min="{{ today()->toDateString() }}" max="{{ today()->addDays(config('klinik.maks_hari_booking'))->toDateString() }}"
                   class="border rounded px-3 py-2 w-full"></label>
        <label class="text-sm">Keluhan singkat (opsional)
            <input name="keluhan_awal" value="{{ old('keluhan_awal') }}" class="border rounded px-3 py-2 w-full"></label>
        <button class="bg-teal-700 hover:bg-teal-800 text-white rounded py-2">Ambil Nomor Antrean</button>
    </form>
</div>
@endsection
