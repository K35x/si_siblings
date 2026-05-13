# si_siblings

## Struktur Utama

- `app/` : kode aplikasi utama: konfigurasi route, controller, model, core framework sederhana, dan view.
- `public/` : document root web server; berisi entry point `index.php` dan asset publik.

## Catatan Pengembangan

- Akses aplikasi sebaiknya diarahkan ke folder `public/`.
- Semua request web masuk melalui `public/index.php` lalu diproses oleh router di `app/core/App.php`.
- Gunakan helper `url()` dan `asset()` untuk membuat URL agar tetap aman saat base path berubah.
