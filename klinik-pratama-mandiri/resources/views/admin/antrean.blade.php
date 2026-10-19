@extends('layouts.app')
@section('judul', 'Antrean Hari Ini')
@section('isi')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <h1 class="text-2xl font-bold">Antrean {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h1>
    <form method="GET" class="flex gap-2">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="border rounded px-2 py-1">
        <button class="bg-slate-700 text-white rounded px-3">Lihat</button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    @foreach ([['Total antrean', $stat['total'], 'text-teal-700'], ['Menunggu', $stat['menunggu'], 'text-yellow-600'], ['Selesai', $stat['selesai'], 'text-green-600'], ['Obat menipis', $stat['obat_menipis'], 'text-red-600']] as [$l, $v, $c])
        <div class="bg-white rounded-xl shadow p-4"><div class="text-sm text-slate-500">{{ $l }}</div><div class="text-3xl font-bold {{ $c }}">{{ $v }}</div></div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-slate-50 text-left"><tr>
        <th class="p-3">No</th><th>Pasien</th><th>Dokter</th><th>Estimasi</th><th>Sumber</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse ($antrean as $a)
        <tr class="border-t">
            <td class="p-3 font-bold text-lg">{{ $a->nomor }}</td>
            <td>{{ $a->pasien->nama }}<div class="text-xs text-slate-500">{{ $a->pasien->no_rm }}</div></td>
            <td>{{ $a->dokter->user->name }}</td>
            <td>{{ $a->estimasi->format('H:i') }}</td>
            <td>{{ $a->sumber }}</td>
            <td><span class="text-xs px-2 py-1 rounded {{ $a->warna }}">{{ $a->status }}</span></td>
            <td>@if (in_array($a->status, ['menunggu', 'dipanggil']))
                <form method="POST" action="{{ route('admin.batal', $a) }}" onsubmit="return confirm('Batalkan antrean?')">@csrf
                    <button class="text-red-600 underline">Batalkan</button></form>@endif</td>
        </tr>
    @empty
        <tr><td colspan="7" class="p-6 text-center text-slate-500">Belum ada antrean pada tanggal ini.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
