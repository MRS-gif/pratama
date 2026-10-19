@extends('layouts.app')
@section('judul', 'Pemeriksaan')
@section('isi')
<div class="grid lg:grid-cols-3 gap-4">
<div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-bold text-teal-700">Pemeriksaan — No. {{ $antrean->nomor }}</h1>
    <p class="text-slate-600 mb-4">{{ $antrean->pasien->nama }} · {{ $antrean->pasien->no_rm }}
        @if ($antrean->pasien->tgl_lahir) · {{ $antrean->pasien->tgl_lahir->age }} th @endif</p>

    <form method="POST" action="{{ route('dokter.simpan', $antrean) }}" class="grid gap-3">@csrf
        <label class="text-sm">Keluhan
            <textarea name="keluhan" rows="2" required class="border rounded px-3 py-2 w-full">{{ old('keluhan', $antrean->keluhan_awal) }}</textarea></label>
        <label class="text-sm">Diagnosa
            <textarea name="diagnosa" rows="2" required class="border rounded px-3 py-2 w-full">{{ old('diagnosa') }}</textarea></label>
        <label class="text-sm">Tindakan (opsional)
            <textarea name="tindakan" rows="2" class="border rounded px-3 py-2 w-full">{{ old('tindakan') }}</textarea></label>

        <div class="border-t pt-3">
            <p class="font-semibold mb-2">Resep Obat <span class="text-xs font-normal text-slate-500">(stok apotek otomatis berkurang saat disimpan)</span></p>
            @for ($i = 0; $i < 4; $i++)
                <div class="grid grid-cols-12 gap-2 mb-2">
                    <select name="resep[{{ $i }}][obat_id]" class="col-span-5 border rounded px-2 py-1">
                        <option value="">— pilih obat —</option>
                        @foreach ($obat as $o)<option value="{{ $o->id }}" @selected(old("resep.$i.obat_id") == $o->id)>{{ $o->nama }} (stok {{ $o->stok }})</option>@endforeach
                    </select>
                    <input type="number" min="1" name="resep[{{ $i }}][jumlah]" value="{{ old("resep.$i.jumlah") }}" placeholder="Jml" class="col-span-2 border rounded px-2 py-1">
                    <input name="resep[{{ $i }}][aturan]" value="{{ old("resep.$i.aturan") }}" placeholder="Aturan pakai, mis. 3x1 sesudah makan" class="col-span-5 border rounded px-2 py-1">
                </div>
            @endfor
        </div>
        <button class="bg-teal-700 hover:bg-teal-800 text-white rounded py-2">Simpan Rekam Medis & Selesaikan</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="font-bold mb-2">Riwayat pasien ini (oleh Anda)</h2>
    @forelse ($riwayat as $r)
        <a href="{{ route('dokter.rekam', $r) }}" class="block border-b py-2 text-sm hover:bg-slate-50">
            <div class="text-xs text-slate-500">{{ $r->created_at->translatedFormat('d M Y') }}</div>{{ \Illuminate\Support\Str::limit($r->diagnosa, 60) }}</a>
    @empty
        <p class="text-sm text-slate-500">Belum ada riwayat.</p>
    @endforelse
</div>
</div>
@endsection
