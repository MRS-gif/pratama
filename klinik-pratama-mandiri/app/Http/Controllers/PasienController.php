<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Services\AntreanService;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = auth()->user()->pasien;
        $antrean = Antrean::with('dokter.user')->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal')->orderByDesc('nomor')->get();
        return view('pasien.index', compact('pasien', 'antrean'));
    }

    public function create()
    {
        $dokter = Dokter::with('user')->get();
        return view('pasien.booking', compact('dokter'));
    }

    public function store(Request $r, AntreanService $svc)
    {
        $d = $r->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'tanggal' => 'required|date',
            'keluhan_awal' => 'nullable|string|max:255',
        ]);
        $a = $svc->booking(auth()->user()->pasien, Dokter::findOrFail($d['dokter_id']), $d['tanggal'], $d['keluhan_awal'] ?? null);
        return redirect()->route('pasien.index')
            ->with('ok', "Booking berhasil! Nomor antrean Anda {$a->nomor}, estimasi dipanggil pukul {$a->estimasi->format('H:i')}.");
    }

    public function batal(Antrean $antrean)
    {
        abort_unless($antrean->pasien_id === auth()->user()->pasien->id, 403);
        abort_unless($antrean->status === 'menunggu', 422, 'Antrean tidak dapat dibatalkan.');
        $antrean->update(['status' => 'batal']);
        return back()->with('ok', 'Antrean dibatalkan.');
    }
}
