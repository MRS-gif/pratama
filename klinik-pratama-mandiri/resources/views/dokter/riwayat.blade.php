@extends('layouts.app')
@section('judul', 'Rekam Medis')
@section('isi')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Rekam Medis Pasien Saya</h1>
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $q }}" placeholder="Cari nama / No. RM" class="border rounded px-3 py-1">
        <button class="bg-slate-700 text-white rounded px-3">Cari</button></form>
</div>
<p class="text-xs text-slate-500 mb-3">🔒 Hanya rekam medis yang Anda tangani yang tampil di sini.</p>
<div class="bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-slate-50 text-left"><tr><th class="p-3">Tanggal</th><th>Pasien</th><th>Diagnosa</th><th></th></tr></thead>
    <tbody>
    @forelse ($rekam as $r)
        <tr class="border-t">
            <td class="p-3">{{ $r->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $r->pasien->nama }}<div class="text-xs text-slate-500">{{ $r->pasien->no_rm }}</div></td>
            <td>{{ \Illuminate\Support\Str::limit($r->diagnosa, 70) }}</td>
            <td><a class="text-teal-700 underline" href="{{ route('dokter.rekam', $r) }}">Buka</a></td>
        </tr>
    @empty
        <tr><td colspan="4" class="p-6 text-center text-slate-500">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-4">{{ $rekam->links() }}</div>
@endsection
