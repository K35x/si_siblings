# public/assets

## Fungsi

Folder ini menyimpan file statis yang boleh diakses langsung oleh browser, seperti CSS dan gambar.

## Isi Folder

- `css/` : stylesheet halaman aplikasi.
- `img/` : gambar yang digunakan oleh halaman aplikasi.

## Catatan

- Panggil asset dari view menggunakan helper `asset()`, contoh: `asset('css/auth.css')`.
- Jangan menyimpan file PHP atau logic aplikasi di folder ini.
- File di folder ini bersifat publik, jadi jangan menyimpan data rahasia.
