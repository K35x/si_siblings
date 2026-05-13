# app/config

## Fungsi

Folder ini menyimpan pengaturan yang menghubungkan URL dengan controller dan method yang harus dijalankan.

## File Penting

- `routes.php` : daftar route aplikasi. Setiap key adalah path URL, sedangkan value berisi pasangan controller dan method.

## Contoh Alur

Saat user mengakses `/transactions`, router akan membaca `routes.php`, menemukan controller yang sesuai, lalu menjalankan method pada controller tersebut.

## Catatan

- Tambahkan route baru di `routes.php` jika membuat halaman baru.
- Pastikan controller yang dipakai sudah di-require di `public/index.php`.
- Root route `/` saat ini diarahkan ke halaman login.
