<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    protected $table = 'antrean';
    protected $guarded = [];
    protected $casts = ['tanggal' => 'date', 'estimasi' => 'datetime'];

    public const AKTIF = ['menunggu', 'dipanggil', 'diperiksa'];

    public function pasien()      { return $this->belongsTo(Pasien::class); }
    public function dokter()      { return $this->belongsTo(Dokter::class); }
    public function rekamMedis()  { return $this->hasOne(RekamMedis::class); }

    public function getDiDepanAttribute(): int
    {
        if (!in_array($this->status, self::AKTIF)) return 0;
        return static::where('dokter_id', $this->dokter_id)
            ->whereDate('tanggal', $this->tanggal)
            ->where('nomor', '<', $this->nomor)
            ->whereIn('status', self::AKTIF)->count();
    }

    public function getWarnaAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'bg-yellow-100 text-yellow-800',
            'dipanggil' => 'bg-blue-100 text-blue-800',
            'diperiksa' => 'bg-indigo-100 text-indigo-800',
            'selesai'   => 'bg-green-100 text-green-800',
            default     => 'bg-gray-200 text-gray-600',
        };
    }
}
