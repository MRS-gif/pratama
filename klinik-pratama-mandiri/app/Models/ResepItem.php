<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepItem extends Model
{
    protected $table = 'resep_item';
    protected $guarded = [];

    public function obat()  { return $this->belongsTo(Obat::class); }
    public function resep() { return $this->belongsTo(Resep::class); }
}
