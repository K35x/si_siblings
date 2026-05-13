# app/models

## Fungsi

Model menyediakan dan mengelola data yang dibutuhkan controller. Data dapat berasal dari array sementara, database, API, atau sumber lain.

## File yang Ada

- `TransactionModel.php` : menyediakan data transaksi untuk halaman transaksi.

## Catatan

- Model harus fokus pada data dan aturan bisnis, bukan tampilan.
- Controller memanggil model untuk mengambil data, lalu mengirim data tersebut ke view.
- Jika nanti menggunakan database, query dan logic pengambilan data sebaiknya ditempatkan di model.
