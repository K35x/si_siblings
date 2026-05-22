# app/views/transaction/includes

## Fungsi

Folder ini berisi file partial yang digunakan oleh view form transaksi. Partial membantu menghindari duplikasi komponen seperti layout wrapper dan validasi.

## File yang Ada

- `form-layout-start.php` : pembuka layout form (HTML head, sidebar, main content wrapper).
- `form-layout-end.php` : penutup layout form (closing tags, script).
- `validation-errors.php` : tampilan pesan error validasi form.
- `size-table.php` : tabel referensi ukuran produk.
- `upload-section.php` : section upload desain/logo.

## Catatan

- Partial di folder ini khusus untuk halaman transaksi/form.
- Jangan menaruh CSS atau gambar di folder ini; gunakan `public/assets/`.
