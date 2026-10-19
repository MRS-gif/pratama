<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $guarded = [];

    public function menipis(): bool { return $this->stok <= $this->stok_minimum; }
}
