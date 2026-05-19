# Sistem PMB - Penerimaan Mahasiswa Baru

Sistem PMB sederhana berbasis **PHP Native** dan **MySQL** untuk mengelola proses penerimaan mahasiswa baru tanpa alur ujian.

Alur utama sistem:

1. Pendaftar melakukan registrasi akun.
2. Pendaftar login dan mengisi biodata.
3. Pendaftar upload dokumen.
4. Admin melakukan verifikasi dokumen.
5. Pendaftar yang diterima melakukan pembayaran daftar ulang.
6. Admin melakukan verifikasi pembayaran.
7. Admin mengatur data OSPEK untuk pendaftar yang sudah lunas.

## Teknologi

- PHP Native
- MySQL
- Laragon / XAMPP
- Bootstrap 5
- phpMyAdmin

## Fitur

### Pendaftar

- Register akun
- Login
- Dashboard pendaftar
- Isi dan update biodata
- Upload dokumen pendaftaran
- Melihat status pendaftaran
- Upload bukti pembayaran jika diterima
- Melihat status pembayaran
- Melihat informasi OSPEK jika pembayaran sudah lunas

### Admin

- Login admin
- Dashboard admin
- Melihat data pendaftar
- Melihat detail biodata pendaftar
- Verifikasi dokumen pendaftar
- Menentukan hasil pendaftaran: menunggu, diterima, ditolak
- Verifikasi pembayaran: menunggu, lunas, ditolak
- Mengatur jadwal OSPEK: tanggal, lokasi, kelompok, mentor

## Struktur Folder

```text
QuisWeb/
├── admin/
├── assets/
│   └── css/
├── auth/
├── config/
├── database/
├── layouts/
├── pendaftar/
├── uploads/
│   ├── dokumen/
│   └── pembayaran/
├── index.php
└── README.md
```

## Cara Install

1. Pindahkan folder `QuisWeb` ke folder web server:

   ```text
   D:/laragon/www/QuisWeb
   ```

   atau jika memakai XAMPP:

   ```text
   C:/xampp/htdocs/QuisWeb
   ```

2. Jalankan Laragon atau XAMPP.

3. Buka phpMyAdmin.

4. Buat database:

   ```sql
   CREATE DATABASE db_pmb;
   ```

5. Import database project atau jalankan file SQL yang tersedia di folder:

   ```text
   database/
   ```

6. Sesuaikan koneksi database di:

   ```text
   config/koneksi.php
   ```

   Default koneksi lokal:

   ```php
   $conn = mysqli_connect(
       "localhost",
       "root",
       "",
       "db_pmb"
   );
   ```

7. Buka aplikasi di browser:

   ```text
   http://localhost/QuisWeb
   ```

## Akun Admin

Contoh akun admin lokal:

```text
Email    : admin@gmail.com
Password : admin123
```

Jika password admin belum sesuai, buat ulang hash password menggunakan `password_hash()` di PHP.

## Status Pendaftaran

Status pendaftaran digunakan untuk menentukan tahap pendaftar:

```text
menunggu
diterima
ditolak
```

Pendaftar hanya dapat melanjutkan ke pembayaran jika status pendaftaran adalah:

```text
diterima
```

## Status Pembayaran

Status pembayaran:

```text
belum_bayar
menunggu
lunas
ditolak
```

Pendaftar hanya dapat melihat informasi OSPEK jika status pembayaran adalah:

```text
lunas
```

## Catatan Upload File

Folder upload digunakan untuk menyimpan dokumen pendaftar dan bukti pembayaran:

```text
uploads/dokumen/
uploads/pembayaran/
```

Format file yang diperbolehkan:

```text
PDF, JPG, JPEG, PNG
```

Ukuran maksimal file:

```text
2 MB
```

## Catatan GitHub

Jangan upload file pribadi pendaftar ke GitHub, seperti KTP, ijazah, kartu keluarga, foto, atau bukti pembayaran.

Gunakan `.gitignore` seperti ini:

```gitignore
uploads/dokumen/*
uploads/pembayaran/*

!uploads/dokumen/.htaccess
!uploads/pembayaran/.htaccess

*.zip
.env
```

## Alur Sistem

```text
Register/Login
      ↓
Isi Biodata
      ↓
Upload Dokumen
      ↓
Verifikasi Dokumen oleh Admin
      ↓
Pengumuman Hasil
      ↓
Pembayaran Daftar Ulang
      ↓
Verifikasi Pembayaran oleh Admin
      ↓
Informasi OSPEK
```
