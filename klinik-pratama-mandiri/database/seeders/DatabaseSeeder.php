<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $mk = fn ($nama, $email, $role) => User::create(['name' => $nama, 'email' => $email, 'password' => Hash::make('password'), 'role' => $role]);

        $mk('Rina Resepsionis', 'admin@klinik.test', 'admin');
        $mk('Apt. Budi Apoteker', 'apoteker@klinik.test', 'apoteker');

        Dokter::create(['user_id' => $mk('dr. Andi Pratama', 'dokter1@klinik.test', 'dokter')->id, 'spesialis' => 'Dokter Umum', 'menit_per_pasien' => 15]);
        Dokter::create(['user_id' => $mk('drg. Sari Wulandari', 'dokter2@klinik.test', 'dokter')->id, 'spesialis' => 'Dokter Gigi', 'menit_per_pasien' => 20]);

        $p = $mk('Siti Aminah', 'pasien@klinik.test', 'pasien');
        Pasien::create(['user_id' => $p->id, 'nama' => 'Siti Aminah', 'nik' => '3517000000000001', 'tgl_lahir' => '1995-04-12', 'telp' => '081234567890', 'alamat' => 'Jombang']);

        foreach ([
            ['Paracetamol 500 mg', 'tablet', 200, 30, 500],
            ['Amoxicillin 500 mg', 'kapsul', 120, 30, 1500],
            ['Ibuprofen 400 mg', 'tablet', 80, 20, 1000],
            ['Cetirizine 10 mg', 'tablet', 60, 20, 800],
            ['Antasida DOEN', 'tablet', 15, 20, 400],
            ['Vitamin C 500 mg', 'tablet', 150, 30, 600],
        ] as [$nama, $sat, $stok, $min, $harga]) {
            Obat::create(['nama' => $nama, 'satuan' => $sat, 'stok' => $stok, 'stok_minimum' => $min, 'harga' => $harga]);
        }
    }
}
