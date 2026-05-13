# app/controllers

## Fungsi

Controller bertugas menerima request dari router, menyiapkan data yang dibutuhkan, lalu memilih view yang akan ditampilkan. Controller menjadi penghubung antara route, model, dan view.

## Controller yang Ada

- `AuthController.php` : menampilkan halaman login.
- `DashboardController.php` : menampilkan dashboard kasir dan owner.
- `TransactionController.php` : menangani halaman transaksi, keranjang, invoice, kategori, dan form produk.
- `ProductController.php` : menampilkan halaman produk untuk owner.
- `FinanceController.php` : menampilkan halaman keuangan untuk owner.

## Catatan

- Controller harus extends `Controller` dari `app/core/Controller.php`.
- Gunakan `$this->view('nama.view', $data)` untuk menampilkan view.
- Hindari menulis HTML langsung di controller; HTML sebaiknya berada di folder `app/views/`.
