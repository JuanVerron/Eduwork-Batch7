# TUGAS_SESI9

Project mini e-commerce berbasis PHP native dan MySQL untuk tugas Bootcamp Eduwork Sesi 9.

## Fitur

- Login sederhana menggunakan email
- Registrasi user otomatis jika email belum terdaftar
- Halaman home berisi daftar produk
- Filter produk berdasarkan kategori
- Sorting harga `Termurah` dan `Termahal`
- Search produk secara otomatis tanpa perlu tekan Enter
- Keranjang belanja
- Checkout transaksi
- Status transaksi: `menunggu pembayaran`, `lunas`, `gagal`
- Lanjutkan transaksi yang belum selesai dari halaman home
- Halaman admin untuk kelola produk (CRUD)
- Proteksi halaman admin hanya untuk akun `admin@admin.com`

## Role

### User

- Melihat daftar produk
- Mencari dan memfilter produk
- Menambahkan produk ke cart
- Checkout
- Melanjutkan pembayaran transaksi yang masih pending

### Admin

- Login dengan email `admin@admin.com`
- Langsung diarahkan ke halaman admin setelah login
- Menambah produk
- Mengedit produk
- Menghapus produk
- Bisa melihat produk seperti user biasa

## Teknologi

- PHP Native
- MySQL
- Bootstrap 5
- Bootstrap Icons
- jQuery DataTables untuk tabel admin

## Struktur Folder

```bash
TUGAS_SESI9/
|-- admin/
|   |-- auth.php
|   |-- index.php
|   |-- create.php
|   |-- edit.php
|   |-- delete.php
|-- components/
|   |-- navbar.php
|   |-- template.php
|-- image/
|-- cart.php
|-- checkout.php
|-- hapus_cart.php
|-- home.php
|-- koneksi.php
|-- login.php
|-- logout.php
|-- tambah_cart.php
|-- transaction_status.php
```

## Konfigurasi Database

File koneksi ada di [koneksi.php]

Gunakan konfigurasi berikut:

```php
$host     = "localhost";
$username = "root";
$password = "";
$database = "sesi9_bootcampeduwork";
```

### Tabel yang digunakan

#### `user`

- `id`
- `name`
- `email`
- `phone`
- `address`
- `created_at`

#### `product`

- `id`
- `nama`
- `deskripsi`
- `price`
- `category`
- `stock`
- `image`
- `created_at`

#### `transaction`

- `id`
- `user_id`
- `total`
- `status`
- `created_at`
- `updated_at`

#### `transaction_product`

- `id`
- `transaction_id`
- `product_id`
- `total_product`
- `total_price`

## Alur Penggunaan

### User

1. Login dengan email tanpa password untuk memudahkan login
2. Jika email belum terdaftar, isi kelengkapan profil
3. Pilih produk di halaman home
4. Tambahkan ke cart
5. Checkout
6. Setelah checkout, transaksi masuk ke status `menunggu pembayaran`
7. User bisa:
   - bayar sekarang
   - batalkan transaksi
   - kembali ke home
8. Jika kembali ke home, transaksi pending tetap bisa dibuka lagi lewat section `Transaksi Belum Selesai`

### Admin

1. Login menggunakan email `admin@admin.com`
2. Setelah login langsung masuk ke halaman admin
3. Admin dapat mengelola data produk

## Catatan Penting

- Tombol menu Admin tidak ditampilkan untuk user biasa
- Jika user biasa mencoba membuka URL admin secara langsung, user akan diarahkan ke `home.php`
- Gambar produk disimpan di folder `image/`
- Saat checkout, stock produk akan berkurang
- Jika transaksi dibatalkan, stock akan dikembalikan

## Akun Admin

Gunakan email berikut untuk akses admin:

```text
admin@admin.com
```
