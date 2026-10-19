<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function dokter()  { return $this->hasOne(Dokter::class); }
    public function pasien()  { return $this->hasOne(Pasien::class); }
}
