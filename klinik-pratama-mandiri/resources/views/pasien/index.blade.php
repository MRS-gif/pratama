@extends('layouts.app')
@section('judul', 'Antrean Saya')
@section('isi')
<div class="flex justify-between items-center mb-4">
    <div><h1 class="text-2xl font-bold">Halo, {{ $pasien->nama }}</h1>
        <p class="text-slate-500">No. RM: <b>{{ $pasien->no_rm }}</b></p></div>
    <a href="{{ route('pasien.booking') }}" class="bg-teal-700 hover:bg-teal-800 text-white rounded px-4 py-2">+ Booking Antrean</a>
</div>

<div class="grid md:grid-cols-2 gap-4">
@forelse ($antrean as $a)
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex justify-between">
            <div class="text-4xl font-extrabold text-teal-700">{{ $a->nomor }}</div>
            <span class="self-start text-xs px-2 py-1 rounded {{ $a->warna }}">{{ strtoupper($a->status) }}</span>
        </div>
        <p class="mt-2 font-semibold">{{ $a->dokter->user->name }} <span class="font-normal text-slate-500">· {{ $a->dokter->spesialis }}</span></p>
        <p class="text-sm text-slate-600">{{ $a->tanggal->translatedFormat('l, d F Y') }} · estimasi <b>{{ $a->estimasi->format('H:i') }}</b></p>
        @if (in_array($a->status, \App\Models\Antrean::AKTIF))
            <p class="text-sm mt-1">Sisa antrean di depan Anda: <b>{{ $a->di_depan }}</b> pasien</p>
        @endif
        @if ($a->status === 'menunggu')
            <form method="POST" action="{{ route('pasien.batal', $a) }}" class="mt-3" onsubmit="return confirm('Batalkan antrean ini?')">@csrf
                <button class="text-sm text-red-600 underline">Batalkan</button></form>
        @endif
    </div>
@empty
    <p class="text-slate-500">Belum ada antrean. Silakan booking nomor antrean.</p>
@endforelse
</div>
@endsection
