# app/core

## Fungsi

Folder ini berisi class dan helper dasar yang dipakai oleh seluruh aplikasi. Komponen di folder ini mengatur routing, rendering view, base model, dan pembuatan URL.

## File Penting

- `App.php` : router sederhana yang membaca path request, mencocokkannya dengan route, lalu menjalankan controller.
- `Controller.php` : base controller dengan method `view()` untuk memuat file view.
- `Model.php` : base class untuk model aplikasi.
- `helpers.php` : helper global seperti `app_base_url()`, `url()`, dan `asset()`.

## Catatan

- Ubah file di folder ini dengan hati-hati karena berdampak ke seluruh aplikasi.
- Gunakan `url()` untuk link halaman dan `asset()` untuk file CSS/gambar agar path tetap konsisten.
