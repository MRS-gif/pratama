@extends('layouts.app')
@section('judul', 'Resep Masuk')
@section('isi')
<h1 class="text-2xl font-bold mb-1">Resep Masuk dari Dokter</h1>
<p class="text-xs text-slate-500 mb-4">Stok sudah otomatis terpotong saat dokter menyimpan resep. Diagnosa tidak ditampilkan di apotek.</p>
<div class="grid md:grid-cols-2 gap-4">
@forelse ($resep as $r)
    <div class="bg-white rounded-xl shadow p-5 {{ $r->status === 'diserahkan' ? 'opacity-60' : '' }}">
        <div class="flex justify-between">
            <div><b>{{ $r->rekamMedis->pasien->nama }}</b> <span class="text-xs text-slate-500">{{ $r->rekamMedis->pasien->no_rm }}</span>
                <div class="text-xs text-slate-500">{{ $r->rekamMedis->dokter->user->name }} · {{ $r->created_at->format('d/m/Y H:i') }}</div></div>
            <span class="self-start text-xs px-2 py-1 rounded {{ $r->status === 'baru' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $r->status }}</span>
        </div>
        <ul class="list-disc ml-5 my-3 text-sm">
            @foreach ($r->items as $i)<li>{{ $i->obat->nama }} × {{ $i->jumlah }} {{ $i->obat->satuan }} <span class="text-slate-500">— {{ $i->aturan_pakai ?: '-' }}</span></li>@endforeach
        </ul>
        @if ($r->status === 'baru')
            <form method="POST" action="{{ route('apotek.serahkan', $r) }}">@csrf
                <button class="bg-teal-700 hover:bg-teal-800 text-white rounded px-4 py-1 text-sm">Serahkan Obat</button></form>
        @endif
    </div>
@empty
    <p class="text-slate-500">Belum ada resep.</p>
@endforelse
</div>
@endsection
