<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $guarded = [];

    public function antrean() { return $this->belongsTo(Antrean::class); }
    public function pasien()  { return $this->belongsTo(Pasien::class); }
    public function dokter()  { return $this->belongsTo(Dokter::class); }
    public function resep()   { return $this->hasOne(Resep::class); }
}
