<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DokterController extends Controller
{
    private function dokter() { return auth()->user()->dokter; }

    /** Dokter hanya boleh menyentuh antrean miliknya sendiri. */
    private function milikSaya(Antrean $a): void
    {
        abort_unless($a->dokter_id === $this->dokter()->id, 403, 'Pasien ini bukan pasien Anda.');
    }

    public function antrean()
    {
        $antrean = Antrean::with('pasien')->where('dokter_id', $this->dokter()->id)
            ->whereDate('tanggal', today())->orderBy('nomor')->get();
        return view('dokter.antrean', compact('antrean'));
    }

    public function panggil(Antrean $antrean)
    {
        $this->milikSaya($antrean);
        abort_unless($antrean->status === 'menunggu', 422);
        $antrean->update(['status' => 'dipanggil']);
        return back()->with('ok', "Nomor {$antrean->nomor} dipanggil.");
    }

    public function periksa(Antrean $antrean)
    {
        $this->milikSaya($antrean);
        abort_if(in_array($antrean->status, ['selesai', 'batal']), 422, 'Antrean sudah selesai/batal.');
        $antrean->update(['status' => 'diperiksa']);
        $antrean->load('pasien');
        $obat = Obat::where('stok', '>', 0)->orderBy('nama')->get();
        $riwayat = RekamMedis::where('pasien_id', $antrean->pasien_id)->where('dokter_id', $this->dokter()->id)
            ->latest()->take(5)->get();
        return view('dokter.periksa', compact('antrean', 'obat', 'riwayat'));
    }

    public function simpan(Request $r, Antrean $antrean)
    {
        $this->milikSaya($antrean);
        abort_if(in_array($antrean->status, ['selesai', 'batal']), 422, 'Antrean sudah selesai/batal.');

        $d = $r->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|array',
            'resep.*.obat_id' => 'nullable|exists:obat,id',
            'resep.*.jumlah' => 'nullable|integer|min:1',
            'resep.*.aturan' => 'nullable|string|max:100',
        ]);
        $baris = collect($d['resep'] ?? [])->filter(fn ($i) => !empty($i['obat_id']) && !empty($i['jumlah']));

        DB::transaction(function () use ($d, $antrean, $baris) {
            $rm = RekamMedis::create([
                'antrean_id' => $antrean->id, 'pasien_id' => $antrean->pasien_id, 'dokter_id' => $antrean->dokter_id,
                'keluhan' => $d['keluhan'], 'diagnosa' => $d['diagnosa'], 'tindakan' => $d['tindakan'] ?? null,
            ]);

            if ($baris->isNotEmpty()) {
                $resep = Resep::create(['rekam_medis_id' => $rm->id]);
                foreach ($baris as $i) {
                    // Kunci baris obat -> cegah stok minus saat dua dokter meresepkan bersamaan
                    $obat = Obat::lockForUpdate()->find($i['obat_id']);
                    if ($obat->stok < $i['jumlah']) {
                        throw ValidationException::withMessages(['resep' => "Stok {$obat->nama} tidak cukup (tersisa {$obat->stok} {$obat->satuan})."]);
                    }
                    $obat->decrement('stok', $i['jumlah']); // integrasi otomatis resep -> stok apotek
                    $resep->items()->create(['obat_id' => $obat->id, 'jumlah' => $i['jumlah'], 'aturan_pakai' => $i['aturan'] ?? null]);
                }
            }
            $antrean->update(['status' => 'selesai']);
        });

        return redirect()->route('dokter.antrean')->with('ok', 'Rekam medis tersimpan. Resep otomatis diteruskan ke apotek & stok obat berkurang.');
    }

    public function riwayat(Request $r)
    {
        $q = $r->query('q');
        $rekam = RekamMedis::with('pasien')->where('dokter_id', $this->dokter()->id)
            ->when($q, fn ($s) => $s->whereHas('pasien', fn ($p) => $p->where('nama', 'like', "%$q%")->orWhere('no_rm', 'like', "%$q%")))
            ->latest()->paginate(15)->withQueryString();
        return view('dokter.riwayat', compact('rekam', 'q'));
    }

    public function rekam(RekamMedis $rekam)
    {
        // RME terproteksi: hanya dokter yang menangani
        abort_unless($rekam->dokter_id === $this->dokter()->id, 403, 'Rekam medis ini hanya dapat dibuka oleh dokter yang menangani.');
        $rekam->load(['pasien', 'resep.items.obat']);
        return view('dokter.rekam', compact('rekam'));
    }
}
