# app/views/transaction/form

## Fungsi

Folder ini menyimpan view form input pesanan berdasarkan jenis produk. Setiap file mewakili satu jenis produk yang dapat dipilih dari alur transaksi.

## File yang Ada

- `t-shirt.php` : form produk T-Shirt.
- `work-uniform.php` : form produk PDH atau seragam kerja.
- `jersey.php` : form produk jersey.
- `polo-shirt.php` : form produk polo shirt.
- `sports-uniform.php` : form produk seragam olahraga.
- `jacket-hoodie.php` : form produk jaket atau hoodie.

## Catatan

- Route form didefinisikan di `app/config/routes.php`.
- Method form berada di `TransactionController`.
- CSS utama form berada di `public/assets/css/transaction-form.css`.
