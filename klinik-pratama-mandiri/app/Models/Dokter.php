<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $guarded = [];

    public function user()    { return $this->belongsTo(User::class); }
    public function antrean() { return $this->hasMany(Antrean::class); }
}
