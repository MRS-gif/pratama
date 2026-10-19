<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $guarded = [];
    protected $casts = ['tgl_lahir' => 'date'];

    public function antrean() { return $this->hasMany(Antrean::class); }

    protected static function booted(): void
    {
        static::created(function (Pasien $p) {
            $p->update(['no_rm' => 'RM-' . str_pad($p->id, 5, '0', STR_PAD_LEFT)]);
        });
    }
}
