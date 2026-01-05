# lab13Web

| Nama                     | Kelas    | NIM       | Mata Kuliah           | Tugas |
|---------------------------|----------|------------|------------------------|--------|
| Friska Pebriana Lestari   | TI.24.A1 | 312410160  | Pemrograman Web 1      | Pertemuan 15    |

## Struktur Folder
```
lab13/
│
├── index.php
├── tambah_barang.php
├── edit.php
├── hapus.php
├── koneksi.php
│
└── picts/
    ├── sneakers_running.png
    ├── tas_ransel.png
    ├── topi.png
    └── (gambar barang lainnya)
```

## Penjelasan Tiap File
```
koneksi.php
```
File ini berfungsi untuk koneksi ke database MySQL.
Biasanya berisi:

- hostname
- username database
- password
- nama database

File ini dipanggil (include / require) oleh file lain agar bisa mengakses database.

```
index.php
```
Halaman utama aplikasi yang menampilkan:

- Daftar data barang dari database
- Gambar barang
- Tombol Edit dan Hapus
- Tombol untuk Tambah Barang

File ini menjalankan fungsi READ pada CRUD.

```
tambah_barang.php
```
Digunakan untuk:
- Menampilkan form input barang baru
- Menyimpan data barang ke database
- Termasuk fungsi CREATE pada CRUD.

Biasanya berisi:

- Form HTML
- Proses INSERT INTO MySQL
- Upload gambar ke folder picts

```
edit.php
```
Digunakan untuk:
- Mengubah data barang yang sudah ada
- Menampilkan data lama ke dalam form
- Menyimpan hasil perubahan ke database
  
Merupakan fungsi UPDATE pada CRUD.

```
hapus.php
```
Digunakan untuk:
- Menghapus data barang berdasarkan id
- Biasanya dipanggil lewat parameter URL (GET)
  
Merupakan fungsi DELETE pada CRUD.

```
Folder picts/
```
Folder ini berfungsi untuk:
- Menyimpan gambar barang
- Menampilkan gambar pada halaman index.php

```
Teknologi yang Digunakan
```
- PHP (Native)
- MySQL
- HTML
- CSS 
- XAMPP / Laragon sebagai server lokal
