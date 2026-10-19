<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';
    protected $guarded = [];
    protected $casts = ['diserahkan_at' => 'datetime'];

    public function rekamMedis() { return $this->belongsTo(RekamMedis::class); }
    public function items()      { return $this->hasMany(ResepItem::class); }
}
