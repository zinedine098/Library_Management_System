# LibLoan Admin - Sistem Administrasi Peminjaman Buku Perpustakaan

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Sistem informasi berbasis web untuk mengelola operasional perpustakaan, termasuk manajemen anggota, katalog buku, transaksi peminjaman, pengembalian, dan perhitungan denda.

![Dashboard Preview](https://via.placeholder.com/1200x600.png?text=LibLoan+Admin+Dashboard)

## 📋 Fitur Utama

### 📚 Manajemen Katalog Buku
- CRUD buku dengan upload cover image
- Kategorisasi buku
- Manajemen stok otomatis
- Pencarian dan filter (judul, penulis, ISBN, kategori, ketersediaan)

### 👥 Manajemen Anggota
- CRUD anggota perpustakaan
- Status keanggotaan (Active/Blocked)
- Riwayat peminjaman per anggota
- Pencarian dan filter anggota

### 📖 Transaksi Sirkulasi
- **Peminjaman**: Pilih anggota & buku, tentukan tanggal kembali
- **Pengembalian**: Hitung denda otomatis (Rp 1.000/hari)
- Validasi stok dan status anggota
- Export laporan ke CSV

### 📊 Dashboard & Laporan
- Statistik real-time (total buku, anggota, pinjaman, terlambat)
- Daftar buku terlambat
- Export laporan transaksi bulanan

### 🔐 Authentication & Profile
- Login admin dengan session
- Manage profile (nama, email)
- Change password

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 |
| Frontend | Blade Templates + Tailwind CSS |
| Interactivity | Alpine.js |
| Database | MySQL 8.0+ |
| Icons | Lucide Icons (SVG) |

## 📋 Prerequisites

Pastikan server lokal Anda telah menginstall:

- **PHP** >= 8.2
- **Composer** (latest version)
- **Node.js** >= 18.x
- **MySQL** >= 8.0 atau **MariaDB** >= 10.3
- **PHP Extensions**: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/your-username/libloan-admin.git
cd libloan-admin
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpus_akreditasi
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
# Run migrations and seed initial data
php artisan migrate:fresh --seed
```

### 6. Create Storage Link

```bash
# Create symbolic link for book cover images
php artisan storage:link
```

### 7. Build Frontend Assets

```bash
# Development build (with hot reload)
npm run dev

# Or production build
npm run build
```

### 8. Start Development Server

```bash
# Start Laravel development server
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

## 👤 Default Login Credentials

Setelah menjalankan seeder, gunakan kredensial berikut untuk login:

| Field | Value |
|-------|-------|
| **Email** | `admin@libloan.com` |
| **Password** | `password` |

## 📁 Project Structure

```
libloan-admin/
├── app/
│   ├── Http/Controllers/     # Controllers (Auth, Books, Members, Borrowings)
│   ├── Models/               # Eloquent Models (Book, Member, Borrowing, Category)
│   └── Providers/            # Service Providers
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── css/                  # Tailwind CSS
│   ├── js/                   # Alpine.js
│   └── views/                # Blade templates
│       ├── components/       # Reusable Blade components
│       ├── layouts/          # Main layout
│       ├── books/            # Book views
│       ├── members/          # Member views
│       ├── borrowings/       # Borrowing views
│       └── profile/          # Profile views
├── routes/
│   └── web.php               # Web routes
└── storage/
    └── app/public/           # Uploaded files (book covers)
```

## 🎨 UI Components

Sistem menggunakan komponen Blade yang reusable dengan desain Shadcn-inspired:

- `<x-layouts.app>` - Main layout dengan sidebar
- `<x-card>` - Card container
- `<x-table>` - Styled table
- `<x-input>` - Input field
- `<x-select>` - Dropdown select
- `<x-button>` - Button dengan variants
- `<x-badge>` - Status badge
- `<x-modal>` - Modal dialog

## 📖 API Endpoints (Optional)

Jika ingin mengembangkan API di masa depan:

```bash
# Install API support
php artisan install:api
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

## 📦 Deployment

### Production Checklist

1. Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build` untuk production assets
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`
7. Setup proper file permissions untuk `storage/` dan `bootstrap/cache/`

### Recommended Server Requirements

- PHP 8.2+ dengan OPcache enabled
- MySQL 8.0+ dengan indexing pada kolom search
- SSL/HTTPS untuk production
- Queue worker untuk background jobs (optional)

## 🔒 Security Features

- CSRF Protection pada semua form
- Password hashing dengan bcrypt
- SQL Injection protection via Eloquent ORM
- XSS protection via Blade templating
- Session-based authentication

## 📝 License

Project ini dilisensikan di bawah [MIT License](LICENSE).

## 🤝 Contributing

1. Fork repository ini
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📧 Contact

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.

---

**Dibuat dengan ❤️ menggunakan Laravel**
