<?php

namespace App\Services;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\Pasien;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AntreanService
{
    /** Booking nomor antrean + hitung estimasi waktu periksa. */
    public function booking(Pasien $pasien, Dokter $dokter, string $tanggal, ?string $keluhan, string $sumber = 'online'): Antrean
    {
        $tgl = Carbon::parse($tanggal)->startOfDay();

        if ($tgl->lt(today()) || $tgl->gt(today()->addDays(config('klinik.maks_hari_booking')))) {
            throw ValidationException::withMessages(['tanggal' => 'Tanggal booking harus hari ini sampai ' . config('klinik.maks_hari_booking') . ' hari ke depan.']);
        }

        return DB::transaction(function () use ($pasien, $dokter, $tgl, $keluhan, $sumber) {
            $sudahAda = Antrean::where('pasien_id', $pasien->id)->where('dokter_id', $dokter->id)
                ->whereDate('tanggal', $tgl)->whereIn('status', Antrean::AKTIF)->exists();
            if ($sudahAda) {
                throw ValidationException::withMessages(['dokter_id' => 'Pasien sudah memiliki antrean aktif pada dokter & tanggal tersebut.']);
            }

            $nomor = (Antrean::where('dokter_id', $dokter->id)->whereDate('tanggal', $tgl)->lockForUpdate()->max('nomor') ?? 0) + 1;
            $buka = $tgl->copy()->setTimeFromTimeString(config('klinik.jam_buka'));

            if ($tgl->isToday()) {
                $aktif = Antrean::where('dokter_id', $dokter->id)->whereDate('tanggal', $tgl)->whereIn('status', Antrean::AKTIF)->count();
                $mulai = now()->lt($buka) ? $buka : now();
                $estimasi = $mulai->copy()->addMinutes($aktif * $dokter->menit_per_pasien);
            } else {
                $estimasi = $buka->copy()->addMinutes(($nomor - 1) * $dokter->menit_per_pasien);
            }

            $tutup = $tgl->copy()->setTimeFromTimeString(config('klinik.jam_tutup'));
            if ($estimasi->gte($tutup)) {
                throw ValidationException::withMessages(['dokter_id' => 'Kuota antrean dokter pada tanggal tersebut sudah penuh. Silakan pilih hari/dokter lain.']);
            }

            return Antrean::create([
                'pasien_id' => $pasien->id, 'dokter_id' => $dokter->id, 'tanggal' => $tgl,
                'nomor' => $nomor, 'estimasi' => $estimasi, 'keluhan_awal' => $keluhan, 'sumber' => $sumber,
            ]);
        });
    }
}
