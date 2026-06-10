# Order App — Laravel

Aplikasi web manajemen pemesanan produk berbasis Laravel. Menyediakan REST API untuk CRUD pesanan dan halaman admin untuk mengelola data pesanan secara real-time tanpa reload halaman.

## Fitur

- CRUD pesanan via REST API (`/api/orders`)
- Halaman admin (`/admin/orders`) dengan tabel pesanan
- UUID v7 sebagai primary key
- AJAX tanpa reload halaman menggunakan Fetch API
- Notifikasi SweetAlert2 untuk konfirmasi dan feedback aksi
- Tampilan Bootstrap 5

## Tech Stack

- **Laravel** 13.15 (PHP 8.3)
- **Database** SQLite (default) / MySQL
- **Frontend** Bootstrap 5, SweetAlert2

## Struktur Tabel `orders`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID | Primary key (auto-generate) |
| `nama_pemesan` | string | Nama pelanggan |
| `nomor_wa` | string | Nomor WhatsApp |
| `email` | string | Email pelanggan |
| `nama_produk` | string | Nama produk yang dipesan |
| `jumlah` | integer | Jumlah produk |
| `status` | enum | `baru` / `diproses` / `selesai` |
| `created_at` | timestamp | Otomatis |
| `updated_at` | timestamp | Otomatis |

## Instalasi

```bash
# Clone & masuk ke direktori
git clone <repo-url>
cd order-app

# Install dependensi
composer install

# Salin konfigurasi
cp .env.example .env
php artisan key:generate

# Jalankan migrasi
php artisan migrate

# (Opsional) Isi data dummy
php artisan db:seed

# Jalankan server
php artisan serve
```

Akses aplikasi di `http://localhost:8000`.

## API Endpoints

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/orders` | Ambil semua pesanan |
| POST | `/api/orders` | Buat pesanan baru |
| GET | `/api/orders/{id}` | Detail pesanan |
| PUT | `/api/orders/{id}` | Update pesanan |
| DELETE | `/api/orders/{id}` | Hapus pesanan |

### Contoh POST `/api/orders`

```json
{
  "nama_pemesan": "Budi Santoso",
  "nomor_wa": "08123456789",
  "email": "budi@example.com",
  "nama_produk": "Kopi Arabica Premium 250gr",
  "jumlah": 2
}
```

### Contoh Response

```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat",
  "order": {
    "id": "019eb0bb-f4c1-7120-b419-6bcbff57f32e",
    "nama_pemesan": "Budi Santoso",
    "status": "baru",
    ...
  }
}
```

## Halaman Admin

Akses di `http://localhost:8000/admin/orders`

- Tambah, edit, dan hapus pesanan langsung dari tabel
- Perubahan status pesanan (baru → diproses → selesai)
- Semua aksi menggunakan AJAX tanpa reload

## Integrasi WordPress

API ini dirancang untuk menerima POST dari form pemesanan di WordPress. Pastikan CORS sudah dikonfigurasi dan WordPress mengirim request ke `http://localhost:8000/api/orders`.

## Lisensi

MIT
