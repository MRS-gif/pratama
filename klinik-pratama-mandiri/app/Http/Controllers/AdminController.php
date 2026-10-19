<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Services\AntreanService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function antrean(Request $r)
    {
        $tanggal = $r->query('tanggal', today()->toDateString());
        $antrean = Antrean::with(['pasien', 'dokter.user'])->whereDate('tanggal', $tanggal)
            ->orderBy('dokter_id')->orderBy('nomor')->get();
        $stat = [
            'total' => $antrean->count(),
            'menunggu' => $antrean->where('status', 'menunggu')->count(),
            'selesai' => $antrean->where('status', 'selesai')->count(),
            'obat_menipis' => Obat::whereColumn('stok', '<=', 'stok_minimum')->count(),
        ];
        return view('admin.antrean', compact('antrean', 'tanggal', 'stat'));
    }

    public function batal(Antrean $antrean)
    {
        abort_unless(in_array($antrean->status, ['menunggu', 'dipanggil']), 422, 'Antrean tidak dapat dibatalkan.');
        $antrean->update(['status' => 'batal']);
        return back()->with('ok', 'Antrean dibatalkan.');
    }

    public function walkin()
    {
        return view('admin.walkin', ['dokter' => Dokter::with('user')->get(), 'pasien' => Pasien::orderBy('nama')->get()]);
    }

    public function walkinStore(Request $r, AntreanService $svc)
    {
        $d = $r->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'pasien_id' => 'nullable|exists:pasien,id',
            'nama' => 'required_without:pasien_id|nullable|string|max:100',
            'nik' => 'nullable|digits:16',
            'tgl_lahir' => 'nullable|date|before:today',
            'telp' => 'nullable|string|max:20',
            'keluhan_awal' => 'nullable|string|max:255',
        ]);
        $pasien = !empty($d['pasien_id'])
            ? Pasien::findOrFail($d['pasien_id'])
            : Pasien::create(['nama' => $d['nama'], 'nik' => $d['nik'] ?? null, 'tgl_lahir' => $d['tgl_lahir'] ?? null, 'telp' => $d['telp'] ?? null]);

        $a = $svc->booking($pasien, Dokter::findOrFail($d['dokter_id']), today()->toDateString(), $d['keluhan_awal'] ?? null, 'loket');
        return redirect()->route('admin.antrean')->with('ok', "{$pasien->nama} ({$pasien->no_rm}) mendapat nomor antrean {$a->nomor}, estimasi {$a->estimasi->format('H:i')}.");
    }
}
