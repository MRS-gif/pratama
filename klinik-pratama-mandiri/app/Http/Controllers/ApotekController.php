<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Resep;
use Illuminate\Http\Request;

class ApotekController extends Controller
{
    public function resep()
    {
        // Apoteker hanya melihat obat & pasien (tanpa diagnosa/keluhan) -> RME tetap terlindungi
        $resep = Resep::with(['items.obat', 'rekamMedis.pasien', 'rekamMedis.dokter.user'])
            ->orderByRaw("status = 'diserahkan'")->latest()->take(50)->get();
        return view('apotek.resep', compact('resep'));
    }

    public function serahkan(Resep $resep)
    {
        abort_unless($resep->status === 'baru', 422);
        $resep->update(['status' => 'diserahkan', 'diserahkan_at' => now()]);
        return back()->with('ok', 'Obat telah diserahkan kepada pasien.');
    }

    public function obat()
    {
        return view('apotek.obat', ['obat' => Obat::orderBy('nama')->get()]);
    }

    public function obatStore(Request $r)
    {
        $d = $r->validate([
            'nama' => 'required|string|max:100', 'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0', 'stok_minimum' => 'required|integer|min:0', 'harga' => 'required|integer|min:0',
        ]);
        Obat::create($d);
        return back()->with('ok', 'Obat baru ditambahkan.');
    }

    public function restok(Request $r, Obat $obat)
    {
        $d = $r->validate(['tambah' => 'required|integer|min:1']);
        $obat->increment('stok', $d['tambah']);
        return back()->with('ok', "Stok {$obat->nama} bertambah {$d['tambah']}.");
    }
}
