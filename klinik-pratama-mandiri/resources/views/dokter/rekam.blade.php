@extends('layouts.app')
@section('judul', 'Detail Rekam Medis')
@section('isi')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8">
    <a href="{{ route('dokter.riwayat') }}" class="text-sm text-teal-700 underline">← Kembali</a>
    <h1 class="text-xl font-bold mt-2">{{ $rekam->pasien->nama }} <span class="text-slate-500 text-base">· {{ $rekam->pasien->no_rm }}</span></h1>
    <p class="text-sm text-slate-500 mb-4">{{ $rekam->created_at->translatedFormat('l, d F Y H:i') }}</p>
    <dl class="grid gap-3">
        <div><dt class="text-xs uppercase text-slate-500">Keluhan</dt><dd>{{ $rekam->keluhan }}</dd></div>
        <div><dt class="text-xs uppercase text-slate-500">Diagnosa</dt><dd>{{ $rekam->diagnosa }}</dd></div>
        <div><dt class="text-xs uppercase text-slate-500">Tindakan</dt><dd>{{ $rekam->tindakan ?: '-' }}</dd></div>
        <div><dt class="text-xs uppercase text-slate-500">Resep</dt>
            <dd>@if ($rekam->resep)
                <ul class="list-disc ml-5">@foreach ($rekam->resep->items as $i)
                    <li>{{ $i->obat->nama }} — {{ $i->jumlah }} {{ $i->obat->satuan }} <span class="text-slate-500">({{ $i->aturan_pakai ?: '-' }})</span></li>@endforeach</ul>
                <span class="text-xs px-2 py-1 rounded bg-slate-100">Status: {{ $rekam->resep->status }}</span>
            @else - @endif</dd></div>
    </dl>
</div>
@endsection
