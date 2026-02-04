# Jempol Futsal

Aplikasi pemesanan lapangan futsal dengan pembayaran online (Midtrans), QR check-in, dan panel admin.

**Stack**
- PHP 8.2+
- MySQL/MariaDB
- Apache (XAMPP/LAMPP)
- Composer

**Lokasi proyek**
- `/opt/lampp/htdocs/JempolFutsal`

## Cara Menjalankan

1. Jalankan Apache dan MySQL di XAMPP/LAMPP.
2. Buat database `dbfutsal`.
3. Import database dari file `dbfutsal.sql`.
4. Install dependency Composer:

```bash
cd /opt/lampp/htdocs/JempolFutsal
composer install
cd admin
composer install
```

5. Pastikan folder `img/` bisa ditulis oleh web server (upload foto dan bukti bayar).
6. Akses aplikasi:
- User: `http://localhost/JempolFutsal/index.php`
- Admin: `http://localhost/JempolFutsal/admin/login.php`

## Konfigurasi Midtrans

Konfigurasi default ada di `config.php`. Untuk konfigurasi lokal, edit `config.local.php`.

**Opsi konfigurasi**
- `server_key`
- `client_key`
- `is_sandbox`
- `snap_url`
- `api_base`

**Catatan**
- File `config.local.php` sudah berisi contoh key sandbox. Ganti dengan key milik Anda.
- Endpoint notifikasi Midtrans: `http://<domain>/JempolFutsal/midtrans_notify.php`

## Fitur Utama (User)

- Registrasi dan login.
- Lihat daftar lapangan dan detail harga.
- Pesan lapangan dengan pengecekan bentrok jadwal.
- Lihat daftar pesanan dan status pembayaran.
- Pembayaran via Midtrans Snap.
- Upload bukti pembayaran manual.
- QR check-in untuk pesanan yang sudah dibayar.
- Find Match: daftar jadwal lapangan yang sudah terkonfirmasi.
- Membership dan informasi paket lapangan.
- Edit profil pengguna.

## Fitur Admin

- Dashboard ringkasan data (member, pesanan, lapangan).
- CRUD data member.
- CRUD data lapangan.
- CRUD data admin.
- Atur harga per jam.
- Kelola pesanan dan konfirmasi pembayaran.
- Export data pesanan ke PDF.
- QR scan check-in.
- Data match terkonfirmasi.

## Struktur Singkat

- `index.php` halaman utama.
- `user/` halaman dan fitur user.
- `admin/` panel admin.
- `functions.php` helper dan query database.
- `dbfutsal.sql` dump database.
- `config.php` dan `config.local.php` konfigurasi Midtrans.
- `img/` upload foto profil dan bukti pembayaran.

## Akun Demo (Opsional)

Data awal ada di `dbfutsal.sql`. Contoh:
- Admin: lihat tabel `admin_212279`
- User: lihat tabel `user_212279`

## Catatan Teknis

- Upload file mengarah ke folder `img/`.
- Pastikan `img/` memiliki permission write untuk Apache.
- Jika ingin pakai environment variable, isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY`.
