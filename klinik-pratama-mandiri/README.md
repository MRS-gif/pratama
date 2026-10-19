# Klinik Pratama Mandiri — Laravel (Laragon + MySQL)

Aplikasi antrean daring, Rekam Medis Elektronik (RME) terproteksi, dan stok obat otomatis.
Folder ini adalah **lapisan kode** yang ditimpa/ditambahkan ke proyek Laravel 11/12 yang baru.

## Instalasi (Laragon)
1. Jalankan Laragon → **Start All** (Apache/Nginx + MySQL).
2. Buka phpMyAdmin → buat database kosong bernama `klinik_db` (collation `utf8mb4_unicode_ci`).
3. Buka Terminal Laragon, lalu:
       cd C:\laragon\www
       composer create-project laravel/laravel klinik
4. **Salin seluruh isi folder ini ke `C:\laragon\www\klinik`** (pilih *Replace/Timpa* bila ditanya).
5. Edit `.env` di proyek:
       APP_NAME="Klinik Pratama Mandiri"
       APP_TIMEZONE=Asia/Jakarta
       APP_LOCALE=id
       DB_CONNECTION=mysql
       DB_HOST=127.0.0.1
       DB_PORT=3306
       DB_DATABASE=klinik_db
       DB_USERNAME=root
       DB_PASSWORD=
6. Di terminal (dalam folder `klinik`):
       php artisan key:generate
       php artisan migrate:fresh --seed
7. Buka `http://klinik.test` (Laragon otomatis membuat virtual host) atau jalankan `php artisan serve` → `http://127.0.0.1:8000`.

Buka folder di VS Code: `code C:\laragon\www\klinik`.

## Akun demo (kata sandi: `password`)
| Peran | Email |
|---|---|
| Resepsionis/Admin | admin@klinik.test |
| Dokter Umum | dokter1@klinik.test |
| Dokter Gigi | dokter2@klinik.test |
| Pengelola Obat | apoteker@klinik.test |
| Pasien | pasien@klinik.test |

## Skenario demo presentasi (±5 menit)
1. **Pasien** login → *Booking Antrean* → pilih dokter → muncul nomor + estimasi jam dipanggil.
2. **Admin** → lihat antrean masuk & statistik; *Daftar di Loket* untuk pasien datang langsung.
3. **Dokter** → *Panggil* → *Periksa* → isi diagnosa + resep → Simpan.
4. **Apoteker** → *Stok Obat*: stok sudah berkurang otomatis; *Resep Masuk* → *Serahkan Obat*.
5. **Proteksi RME**: login dokter lain → buka URL rekam medis dokter pertama → **403**. Admin/apoteker/pasien tidak punya menu RME.

## Struktur singkat
- `database/migrations/…create_klinik_tables.php` — tabel dokter, pasien, antrean, rekam_medis, obat, resep, resep_item (+ kolom `role` di users)
- `app/Services/AntreanService.php` — nomor antrean + estimasi waktu (jam buka + (nomor−1) × menit/pasien; hari ini dihitung dari sisa antrean aktif)
- `app/Http/Middleware/RoleMiddleware.php` — pembatas hak akses per peran
- `DokterController::simpan` — transaksi DB: simpan RME + resep + kurangi stok (dengan `lockForUpdate`, tanpa stok minus)
- `config/klinik.php` — jam buka/tutup & batas hari booking
