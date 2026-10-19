<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApotekController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Middleware\RoleMiddleware as Role;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('home'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', fn () => redirect()->route(match (auth()->user()->role) {
        'admin' => 'admin.antrean', 'dokter' => 'dokter.antrean', 'apoteker' => 'apotek.resep', default => 'pasien.index',
    }))->name('home');

    Route::middleware(Role::class . ':pasien')->prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [PasienController::class, 'index'])->name('index');
        Route::get('/booking', [PasienController::class, 'create'])->name('booking');
        Route::post('/booking', [PasienController::class, 'store'])->name('store');
        Route::post('/antrean/{antrean}/batal', [PasienController::class, 'batal'])->name('batal');
    });

    Route::middleware(Role::class . ':admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/antrean', [AdminController::class, 'antrean'])->name('antrean');
        Route::post('/antrean/{antrean}/batal', [AdminController::class, 'batal'])->name('batal');
        Route::get('/walkin', [AdminController::class, 'walkin'])->name('walkin');
        Route::post('/walkin', [AdminController::class, 'walkinStore'])->name('walkin.store');
    });

    Route::middleware(Role::class . ':dokter')->prefix('dokter')->name('dokter.')->group(function () {
        Route::get('/antrean', [DokterController::class, 'antrean'])->name('antrean');
        Route::post('/antrean/{antrean}/panggil', [DokterController::class, 'panggil'])->name('panggil');
        Route::get('/antrean/{antrean}/periksa', [DokterController::class, 'periksa'])->name('periksa');
        Route::post('/antrean/{antrean}/periksa', [DokterController::class, 'simpan'])->name('simpan');
        Route::get('/rekam-medis', [DokterController::class, 'riwayat'])->name('riwayat');
        Route::get('/rekam-medis/{rekam}', [DokterController::class, 'rekam'])->name('rekam');
    });

    Route::middleware(Role::class . ':apoteker')->prefix('apotek')->name('apotek.')->group(function () {
        Route::get('/resep', [ApotekController::class, 'resep'])->name('resep');
        Route::post('/resep/{resep}/serahkan', [ApotekController::class, 'serahkan'])->name('serahkan');
        Route::get('/obat', [ApotekController::class, 'obat'])->name('obat');
        Route::post('/obat', [ApotekController::class, 'obatStore'])->name('obat.store');
        Route::post('/obat/{obat}/restok', [ApotekController::class, 'restok'])->name('restok');
    });
});
