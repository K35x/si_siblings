# app/views/layouts

## Fungsi

Folder ini berisi layout halaman besar dan komponen bersama yang digunakan oleh beberapa halaman aplikasi.

## File yang Ada

- `auth.php` : halaman login.
- `cashier.php` : dashboard kasir.
- `owner.php` : dashboard owner.
- `sidebar.php` : sidebar reusable berbasis role kasir/owner.

## Catatan

- Menu sidebar diatur di `sidebar.php`.
- Untuk menandai menu aktif, controller dapat mengirim variabel `$activeMenu`.
- Untuk menentukan role sidebar, controller dapat mengirim variabel `$sidebarRole`.
- CSS dashboard dan auth berada di `public/assets/css/`.
