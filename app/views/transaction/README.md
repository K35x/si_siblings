# app/views/transaction

## Fungsi

Folder ini berisi tampilan untuk alur transaksi kasir, mulai dari daftar pesanan, membuat pesanan, memilih kategori produk, keranjang, invoice, sampai form input produk.

## File dan Folder Penting

- `index.php` : halaman daftar/status transaksi.
- `create-order.php` : halaman awal pembuatan pesanan.
- `select-category.php` : halaman pemilihan kategori produk.
- `cart.php` : halaman keranjang transaksi.
- `invoice.php` : halaman invoice transaksi.
- `form/` : kumpulan form input berdasarkan jenis produk.
- `includes/` : komponen kecil khusus halaman transaksi seperti header dan sidebar wrapper.

## Catatan

- Controller utama folder ini adalah `TransactionController`.
- CSS transaksi berada di `public/assets/css/transactions.css`, `transaction-cart.css`, dan `transaction-form.css`.
