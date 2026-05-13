# public

## Fungsi

Folder `public/` adalah satu-satunya folder yang sebaiknya diakses langsung oleh web server/browser. Semua request aplikasi masuk melalui `index.php`, lalu diteruskan ke router MVC.

## Isi Folder

- `index.php` : entry point aplikasi; memuat core, controller, model, route, lalu menjalankan router.
- `assets/` : asset publik seperti CSS dan gambar.

## Catatan

- Arahkan document root web server ke folder ini jika memungkinkan.
- File internal aplikasi berada di `app/` dan tidak perlu diakses langsung dari browser.
- Asset dipanggil dari view menggunakan helper `asset()`.
