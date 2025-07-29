# waspas-web

Aplikasi web berbasis PHP untuk perhitungan dan pengelolaan data menggunakan metode WASPAS (Weighted Aggregated Sum Product Assessment).

## Fitur

- Autentikasi pengguna (login/logout)
- Input, edit, dan hapus data transaksi
- Perhitungan metode WASPAS secara otomatis
- Laporan hasil perhitungan
- Dashboard interaktif
- Ekspor data laporan ke berbagai format

## Struktur Folder

- `controller/` — Logika backend (autentikasi, transaksi, laporan)
- `model/` — Model data PHP
- `css/`, `scss/` — File style dan tema
- `js/` — Script JavaScript
- `img/` — Gambar dan ilustrasi
- `vendor/` — Library pihak ketiga (Bootstrap, jQuery, dsb)
- `database/` — Berisi file SQL untuk instalasi database
- `index.php`, `login.php`, dll — Halaman utama aplikasi

## Instalasi

1. **Clone repository ke folder `htdocs` XAMPP:**
   ```
   git clone https://github.com/username/waspas-web.git
   ```

2. **Jalankan XAMPP dan aktifkan Apache & MySQL.**

3. **Pemasangan Database:**
   - Buka `phpMyAdmin` melalui browser:  
     `http://localhost/phpmyadmin`
   - Buat database baru, misal: `waspas_db`
   - Import file SQL yang ada di folder `database/` pada proyek ini (misal: `database/waspas_db.sql`):
     - Klik database yang baru dibuat
     - Pilih tab **Import**
     - Klik **Choose File** dan pilih file SQL dari folder `database/`
     - Klik **Go** untuk memulai import

4. **Konfigurasi Koneksi Database:**
   - Buka file konfigurasi database (misal: `controller/koneksi.php`)
   - Sesuaikan parameter berikut sesuai pengaturan lokal Anda:
     ```php
     // ...existing code...
     $host = "localhost";
     $user = "root";
     $pass = "";
     $db   = "waspas_db";
     // ...existing code...
     ```

5. **Akses aplikasi melalui browser:**
   ```
   http://localhost/waspas-web/
   ```

## Kebutuhan Sistem

- PHP 7.x atau lebih baru
- MySQL/MariaDB
- XAMPP/LAMP/WAMP

## Lisensi

Proyek ini bersifat open source dan dapat dikembangkan