# app/views

## Fungsi

Folder ini berisi file PHP yang bertugas menampilkan HTML ke user. View menerima data dari controller dan tidak berisi logic yang kompleks.

## Isi Folder

- `finance/` : view halaman keuangan owner.
- `layouts/` : layout dan komponen umum seperti sidebar, dashboard kasir, dashboard owner, dan login.
- `partials/` : komponen kecil yang digunakan di banyak halaman (head HTML, sidebar toggle).
- `product/` : view halaman produk owner.
- `transaction/` : view halaman transaksi kasir dan form pesanan.

## Catatan

- Jangan menyimpan CSS atau gambar di folder ini.
- CSS dan gambar harus berada di `public/assets/`.
- Gunakan helper `asset()` untuk memanggil CSS/gambar dan `url()` untuk link internal.
- Gunakan **kebab-case** untuk penamaan file (contoh: `create-order.php`, bukan `create_order.php`).
