# Jobsheet 9 — CRUD Penuh

Sub-CPMK: Membangun fitur CRUD pada proyek.

## Perubahan dari Jobsheet 8
- Tambah `books/edit.php` + `books/process_edit.php`, `members/edit.php` + `members/process_edit.php` — melengkapi Create+Read (Jobsheet 8) dengan **Update**.
- Tambah `books/delete.php`, `members/delete.php` — **Delete**, hanya menerima `POST` (bukan GET) agar tidak terpicu tidak sengaja lewat link/crawler.
- Tombol Hapus di `list.php` sekarang berupa `<form class="form-delete" method="post">` sungguhan (bukan lagi tombol `<button>` polos) — `app.js` (`initDeleteConfirm`) diubah untuk konfirmasi di event `submit` (bisa `preventDefault()`), bukan `click`.
- `books/list.php` & `members/list.php`: tambah **pagination** (`LIMIT`/`OFFSET`, 5 baris/halaman) dan **pencarian server-side** (`WHERE judul/nama ILIKE :kw`) — form GET, menggantikan kolom cari client-side murni dari Jobsheet 5/6.

## Cara menjalankan
**Persiapan database**: pastikan database `simpus_mini` sudah dibuat dan pengaturan di `includes/connection.php` sesuai dengan PostgreSQL lokal. Dari folder `jobsheet-09/`, impor skema berikut sebelum menjalankan aplikasi:
```bash
psql -h localhost -p 5432 -U postgres -d simpus_mini -f sql/01_books_members.sql
```
Jika menggunakan pgAdmin, buka Query Tool pada database `simpus_mini`, lalu jalankan isi `sql/01_books_members.sql`. Langkah ini membuat tabel `buku` dan `anggota`; lewati jika tabel dari Jobsheet 8 sudah tersedia. Error `relation "buku" does not exist` berarti tabel belum tersedia pada database/schema yang digunakan koneksi.

**Opsi 1 — PHP built-in server**:
```bash
php -S localhost:8000
```
Buka `http://localhost:8000/index.php`, uji siklus lengkap: tambah → tampil → ubah (Edit) → tampil berubah → hapus → hilang dari list.

**Opsi 2 — Laragon (Apache)**: lewat virtual host langsung ke folder `jobsheet-09/` (mis. `http://jobsheet09.test/`), atau bersarang di bawah domain proyek (mis. `http://dp2026.test/kode-praktikum/jobsheet-09/`) — path CSS/JS/link sudah relatif otomatis (lihat `includes/header.php`), jadi keduanya jalan.

## Catatan
- Kolom pencarian (`#search-input`) di halaman ini melayani dua peran: filter instan client-side (JS, dari Jobsheet 5) untuk baris yang sedang tampil di halaman saat ini, dan pencarian penuh lintas-halaman lewat tombol "Cari" (server-side).
- Nilai `q` dari pencarian belum di-escape saat ditampilkan kembali ke `value` input — ini **sengaja belum diperbaiki** di sini; audit dan perbaikan XSS dilakukan menyeluruh di Jobsheet 11.
