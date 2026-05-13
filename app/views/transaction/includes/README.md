# app/views/transaction/includes

## Fungsi

Folder ini berisi file include yang digunakan oleh view transaksi. Include membantu menghindari duplikasi komponen seperti header atau sidebar pada beberapa halaman transaksi.

## File yang Ada

- `header.php` : header khusus halaman transaksi.
- `sidebar.php` : wrapper/sidebar include yang memuat sidebar reusable dari `app/views/layouts/sidebar.php`.

## Catatan

- Jika membutuhkan sidebar utama, gunakan include ini agar role dan menu aktif tetap konsisten.
- Jangan menaruh CSS atau gambar di folder ini; gunakan `public/assets/`.
