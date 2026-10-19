@extends('layouts.app')
@section('judul', 'Stok Obat')
@section('isi')
<h1 class="text-2xl font-bold mb-4">Inventaris Obat</h1>

<form method="POST" action="{{ route('apotek.obat.store') }}" class="bg-white rounded-xl shadow p-4 mb-6 grid grid-cols-2 md:grid-cols-6 gap-2">@csrf
    <input name="nama" placeholder="Nama obat" required class="border rounded px-2 py-1 col-span-2">
    <input name="satuan" placeholder="Satuan" value="tablet" required class="border rounded px-2 py-1">
    <input type="number" name="stok" placeholder="Stok" min="0" required class="border rounded px-2 py-1">
    <input type="number" name="stok_minimum" placeholder="Stok min" value="10" min="0" required class="border rounded px-2 py-1">
    <input type="number" name="harga" placeholder="Harga" min="0" required class="border rounded px-2 py-1">
    <button class="bg-teal-700 text-white rounded px-3 py-1 col-span-2 md:col-span-6">+ Tambah Obat</button>
</form>

<div class="bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-slate-50 text-left"><tr><th class="p-3">Obat</th><th>Stok</th><th>Minimum</th><th>Harga</th><th>Tambah stok</th></tr></thead>
    <tbody>
    @foreach ($obat as $o)
        <tr class="border-t {{ $o->menipis() ? 'bg-red-50' : '' }}">
            <td class="p-3 font-medium">{{ $o->nama }}</td>
            <td><b class="{{ $o->menipis() ? 'text-red-600' : '' }}">{{ $o->stok }}</b> {{ $o->satuan }}
                @if ($o->menipis())<span class="ml-1 text-xs bg-red-100 text-red-700 rounded px-2">menipis</span>@endif</td>
            <td>{{ $o->stok_minimum }}</td>
            <td>Rp {{ number_format($o->harga, 0, ',', '.') }}</td>
            <td><form method="POST" action="{{ route('apotek.restok', $o) }}" class="flex gap-1">@csrf
                <input type="number" name="tambah" min="1" required class="border rounded px-2 py-1 w-24">
                <button class="bg-slate-700 text-white rounded px-3">Tambah</button></form></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
