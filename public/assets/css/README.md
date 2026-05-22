# public/assets/css

## Fungsi

Folder ini menyimpan file CSS yang digunakan untuk mengatur tampilan halaman aplikasi.

## Arsitektur

Semua halaman menggunakan dua file dasar di setiap view:

- `theme.css` — design tokens (warna, spacing, radius, typography), reset global, focus state, skip link.
- `components.css` — komponen reusable: sidebar, header banner, button, card, badge, alert, modal, table, toast, empty state, dll.

File khusus halaman hanya berisi style yang spesifik untuk halaman tersebut.

## File yang Ada

- `theme.css` — base/design tokens (wajib di-load lebih dulu).
- `components.css` — komponen UI reusable.
- `auth.css` — halaman login.
- `dashboard.css` — dashboard kasir & owner.
- `transactions.css` — list pesanan, kategori, biodata customer.
- `transaction-cart.css` — halaman keranjang.
- `transaction-form.css` — form input pesanan (T-Shirt, Jersey, PDH, dll.).
- `invoice.css` — halaman invoice & print style.
- `detail-pesanan.css` — halaman detail pesanan.
- `products.css` — manajemen stok produk.
- `finance.css` — laporan penjualan.

## Catatan

- View standar memuat `theme.css` + `components.css` lewat `app/views/partials/head.php`.
  Style spesifik halaman ditambahkan via variabel `$pageStyles` (array nama file).
- Helper `asset()` otomatis menambahkan cache-busting (`?v=<mtime>`).
- Hindari inline style besar di file view; pindahkan ke CSS file untuk caching.
