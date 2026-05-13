# app

## Fungsi Folder Ini

Folder `app/` menyimpan seluruh logic dan tampilan internal aplikasi yang tidak boleh diakses langsung dari browser. Request dari `public/index.php` akan diarahkan ke controller, lalu controller akan memanggil model dan view yang ada di folder ini.

## Isi Folder

- `config/` : konfigurasi aplikasi, terutama daftar route URL.
- `controllers/` : class controller yang menangani request dan memilih view.
- `core/` : komponen inti MVC seperti router, base controller, base model, dan helper.
- `models/` : class model untuk menyediakan data aplikasi.
- `views/` : file tampilan PHP yang dirender ke user.

## Catatan

Jangan meletakkan file CSS, gambar, atau asset publik di folder ini. Asset publik diletakkan di `public/assets/` dan dipanggil melalui helper `asset()`.
