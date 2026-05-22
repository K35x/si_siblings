# app/views/partials

## Fungsi

Folder ini berisi komponen kecil (partial) yang digunakan di banyak halaman aplikasi. Partial membantu menghindari duplikasi kode HTML umum.

## File yang Ada

- `head.php` : tag `<head>` HTML (meta, CSS, title).
- `sidebar-toggle.php` : tombol toggle sidebar untuk mobile.

## Catatan

- Partial di folder ini bersifat global dan dapat digunakan di view manapun.
- Jangan menaruh CSS atau gambar di folder ini; gunakan `public/assets/`.
