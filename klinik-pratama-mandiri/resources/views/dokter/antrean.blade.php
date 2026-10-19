@extends('layouts.app')
@section('judul', 'Antrean Saya')
@section('isi')
<h1 class="text-2xl font-bold mb-4">Antrean Pasien Hari Ini</h1>
<div class="bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-slate-50 text-left"><tr><th class="p-3">No</th><th>Pasien</th><th>Keluhan awal</th><th>Estimasi</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse ($antrean as $a)
        <tr class="border-t">
            <td class="p-3 font-bold text-lg">{{ $a->nomor }}</td>
            <td>{{ $a->pasien->nama }}<div class="text-xs text-slate-500">{{ $a->pasien->no_rm }}</div></td>
            <td>{{ $a->keluhan_awal ?: '-' }}</td>
            <td>{{ $a->estimasi->format('H:i') }}</td>
            <td><span class="text-xs px-2 py-1 rounded {{ $a->warna }}">{{ $a->status }}</span></td>
            <td class="flex gap-2 py-2">
                @if ($a->status === 'menunggu')
                    <form method="POST" action="{{ route('dokter.panggil', $a) }}">@csrf
                        <button class="bg-blue-600 text-white rounded px-3 py-1">Panggil</button></form>
                @endif
                @if (in_array($a->status, ['menunggu', 'dipanggil', 'diperiksa']))
                    <a href="{{ route('dokter.periksa', $a) }}" class="bg-teal-700 text-white rounded px-3 py-1">Periksa</a>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="p-6 text-center text-slate-500">Belum ada pasien.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
