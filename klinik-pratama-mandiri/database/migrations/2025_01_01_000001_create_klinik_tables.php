<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 20)->default('pasien')->after('password'); // admin|dokter|apoteker|pasien
        });

        Schema::create('dokter', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->string('spesialis')->default('Umum');
            $t->unsignedSmallInteger('menit_per_pasien')->default(15);
            $t->timestamps();
        });

        Schema::create('pasien', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('no_rm')->nullable()->unique();
            $t->string('nama');
            $t->string('nik', 20)->nullable();
            $t->date('tgl_lahir')->nullable();
            $t->string('telp', 20)->nullable();
            $t->text('alamat')->nullable();
            $t->timestamps();
        });

        Schema::create('antrean', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pasien_id')->constrained('pasien')->cascadeOnDelete();
            $t->foreignId('dokter_id')->constrained('dokter')->cascadeOnDelete();
            $t->date('tanggal');
            $t->unsignedInteger('nomor');
            $t->dateTime('estimasi');
            $t->string('keluhan_awal')->nullable();
            $t->string('sumber', 10)->default('online'); // online|loket
            $t->string('status', 15)->default('menunggu'); // menunggu|dipanggil|diperiksa|selesai|batal
            $t->timestamps();
            $t->unique(['dokter_id', 'tanggal', 'nomor']);
        });

        Schema::create('rekam_medis', function (Blueprint $t) {
            $t->id();
            $t->foreignId('antrean_id')->constrained('antrean')->cascadeOnDelete();
            $t->foreignId('pasien_id')->constrained('pasien')->cascadeOnDelete();
            $t->foreignId('dokter_id')->constrained('dokter')->cascadeOnDelete();
            $t->text('keluhan');
            $t->text('diagnosa');
            $t->text('tindakan')->nullable();
            $t->timestamps();
        });

        Schema::create('obat', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('satuan', 20)->default('tablet');
            $t->unsignedInteger('stok')->default(0);
            $t->unsignedInteger('stok_minimum')->default(10);
            $t->unsignedInteger('harga')->default(0);
            $t->timestamps();
        });

        Schema::create('resep', function (Blueprint $t) {
            $t->id();
            $t->foreignId('rekam_medis_id')->constrained('rekam_medis')->cascadeOnDelete();
            $t->string('status', 15)->default('baru'); // baru|diserahkan
            $t->timestamp('diserahkan_at')->nullable();
            $t->timestamps();
        });

        Schema::create('resep_item', function (Blueprint $t) {
            $t->id();
            $t->foreignId('resep_id')->constrained('resep')->cascadeOnDelete();
            $t->foreignId('obat_id')->constrained('obat');
            $t->unsignedInteger('jumlah');
            $t->string('aturan_pakai')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep_item');
        Schema::dropIfExists('resep');
        Schema::dropIfExists('obat');
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('antrean');
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('dokter');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
