# Product Requirements Document (PRD)
**Nama Proyek:** LibLoan Admin (Sistem Administrasi Peminjaman Buku)  
**Versi:** 3.0 (Final - Single Role)  
**Status:** Ready for Development  
**Tanggal:** 24 Mei 2024  
**Tipe Aplikasi:** Internal Admin Dashboard  

---

## 1. Ringkasan Eksekutif
**LibLoan Admin** adalah sistem informasi berbasis web yang dirancang khusus untuk internal perpustakaan. Sistem ini berfungsi sebagai pusat kendali bagi **Admin Perpustakaan** untuk mengelola seluruh operasional sirkulasibuku, termasuk data anggota, katalog buku, transaksi peminjaman, pengembalian, dan perhitungan denda.

Tidak ada portal login untuk anggota. Seluruh interaksi transaksi dilakukan oleh Admin atas nama anggota. Aplikasi ini dibangun menggunakan **Laravel 12** dengan **Blade Templates**, didukung oleh **MySQL**, dan mengadopsi desain visual **Shadcn UI** (via Tailwind CSS & Alpine.js) untuk menciptakan dashboard yang bersih, cepat, dan mudah digunakan. Fitur utama meliputi **Pencarian & Penyaringan (Search & Filtering)** yang kuat pada semua modul data.

## 2. Masalah & Tujuan
### 2.1. Masalah
*   Proses peminjaman dan pengembalian buku masih manual, menyebabkan antrean panjang.
*   Sulit melacak siapa yang meminjam buku tertentu atau buku apa yang sedang dipinjam anggota tertentu.
*   Perhitungan denda keterlambatan sering terjadi kesalahan manusia (human error).
*   Tidak adanya fitur pencarian cepat untuk menemukan buku atau anggota saat transaksi berlangsung.

### 2.2. Tujuan
*   Menyediakan dashboard terpusat bagi Admin untuk mengelola seluruh data perpustakaan.
*   Mempercepat proses transaksi (Pinjam/Kembali) dengan fitur pencarian instan.
*   Mengotomatisasi perhitungan denda dan status keterlambatan.
*   Memastikan integritas data stok buku secara real-time.

## 3. Target Pengguna (User Role)
Sistem ini dirancang dengan **Single Role Architecture**.

| Role | Deskripsi | Akses |
| :--- | :--- | :--- |
| **Admin** | Staff Perpustakaan | **Full Access**. Mengelola Buku, Anggota, Transaksi, Laporan, dan Konfigurasi Sistem. |

*Catatan: Anggota perpustakaan tidak memiliki akun login. Data anggota dikelola sepenuhnya oleh Admin.*

## 4. Spesifikasi Teknis (Tech Stack)
| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend** | Laravel 12 | PHP 8.3+, Strict Type, Enum, Latest Features |
| **Frontend** | Blade Templates | Server-Side Rendering (SSR) |
| **Interactivity** | Alpine.js | Dropdown, Modal, Search Autocomplete, Toast |
| **Styling** | Tailwind CSS | Konfigurasi meniru **Shadcn UI** (Radius, Color, Spacing) |
| **Database** | MySQL 8.0+ | Relational DB dengan Indexing untuk Search |
| **Authentication** | Laravel Breeze | Single Guard (Admin Only) |
| **Icons** | Lucide Icons | SVG Icons konsisten |

## 5. Fitur Fungsional (Functional Requirements)

### 5.1. Autentikasi Admin
*   Login halaman khusus Admin (Email & Password).
*   Logout & Session Management.
*   Profil Admin (Ganti Password).

### 5.2. Manajemen Data Anggota (Members)
*   **CRUD Anggota:** Tambah, Edit, Hapus data anggota (Nama, NIM/NIK, Email, No HP).
*   **Status Anggota:** Aktif atau Diblokir (jika memiliki denda belum lunas).
*   **Riwayat Anggota:** Melihat sejarah peminjaman anggota tertentu.

### 5.3. Manajemen Katalog Buku (Books)
*   **CRUD Buku:** Input Judul, Penulis, Penerbit, ISBN, Tahun, Stok, Kategori.
*   **Upload Cover:** Upload gambar sampul buku.
*   **Manajemen Stok:** Stok Total vs Stok Tersedia (otomatis berkurang saat dipinjam).

### 5.4. Transaksi Sirkulasi (Circulation)
*   **Peminjaman (Borrowing):**
    *   Pilih Anggota (Searchable Select).
    *   Pilih Buku (Searchable Select, hanya buku stok > 0).
    *   Tentukan Tanggal Kembali (Default: +7 hari).
    *   Validasi: Cek apakah anggota sedang diblokir atau melebihi kuota pinjam.
*   **Pengembalian (Returning):**
    *   Cari Transaksi Aktif berdasarkan ID atau Anggota.
    *   Sistem otomatis menghitung denda jika terlambat.
    *   Input Pembayaran Denda (Lunas/Belum).
    *   Stok buku otomatis bertambah kembali.

### 5.5. Pencarian & Penyaringan (Search & Filtering) **(Fitur Utama)**
Sistem harus memiliki kemampuan search dan filter yang konsisten di semua tabel data.

*   **A. Search Buku (Katalog)**
    *   **Keyword:** Judul, Penulis, ISBN, Penerbit.
    *   **Filter:** Kategori, Tahun Terbit, Status Stok (Tersedia/Habis).
    *   **Sort:** Terbaru, Judul A-Z, Paling Sering Dipinjam.
*   **B. Search Anggota**
    *   **Keyword:** Nama, NIM/NIK, Email.
    *   **Filter:** Status (Aktif/Blokir), Jenis Keanggotaan.
*   **C. Search Transaksi (Riwayat)**
    *   **Keyword:** Nama Anggota, Judul Buku, ID Transaksi.
    *   **Filter:** Status (Dipinjam/Kembali), Status Denda (Lunas/Belum), Tanggal Pinjam (Range).
    *   **URL Persistence:** Parameter filter tersimpan di URL (query string) agar link bisa dibagikan.
    *   **Active Filters Tag:** Menampilkan chip filter aktif yang bisa dihapus satu per satu.

### 5.6. Dashboard & Laporan
*   **Statistik Utama:** Total Buku, Total Anggota, Sedang Dipinjam, Terlambat, Denda Bulan Ini.
*   **Tabel Buku Terlambat:** Daftar transaksi yang belum kembali melewati jatuh tempo.
*   **Export Data:** Export laporan transaksi bulanan ke CSV/Excel.

## 6. Desain Database (Schema Design)

### 6.1. Tabel Utama & Indeks
Desain difokuskan untuk kecepatan query pada fitur search dan filter.

```sql
-- 1. Users (Admin)
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    INDEX idx_email (email)
);

-- 2. Members (Anggota)
CREATE TABLE members (
    id BIGINT UNSIGNED PRIMARY KEY,
    membership_number VARCHAR(50) UNIQUE, -- NIM/NIK
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20),
    status ENUM('active', 'blocked') DEFAULT 'active',
    INDEX idx_membership_number (membership_number),
    INDEX idx_name (name),
    INDEX idx_status (status)
);

-- 3. Categories
CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    INDEX idx_slug (slug)
);

-- 4. Books
CREATE TABLE books (
    id BIGINT UNSIGNED PRIMARY KEY,
    category_id BIGINT UNSIGNED,
    title VARCHAR(255),
    author VARCHAR(255),
    publisher VARCHAR(255),
    isbn VARCHAR(50),
    published_year YEAR,
    stock_total INT DEFAULT 1,
    stock_available INT DEFAULT 1,
    cover_image VARCHAR(255),
    INDEX idx_title (title),
    INDEX idx_author (author),
    INDEX idx_isbn (isbn),
    INDEX idx_category (category_id),
    INDEX idx_stock (stock_available)
);

-- 5. Borrowings (Transaksi)
CREATE TABLE borrowings (
    id BIGINT UNSIGNED PRIMARY KEY,
    member_id BIGINT UNSIGNED,
    book_id BIGINT UNSIGNED,
    admin_id BIGINT UNSIGNED, -- Siapa admin yang memproses
    borrow_date DATE,
    due_date DATE,
    return_date DATE NULL,
    fine_amount DECIMAL(10,2) DEFAULT 0,
    fine_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
    INDEX idx_member (member_id),
    INDEX idx_book (book_id),
    INDEX idx_status (status),
    INDEX idx_due_date (due_date),
    FOREIGN KEY (member_id) REFERENCES members(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);
```

## 7. UI/UX Guidelines (Shadcn Style on Blade)

### 7.1. Design System
*   **Font:** Inter (Google Fonts).
*   **Radius:** `rounded-md` (0.375rem) untuk input/button, `rounded-lg` untuk card.
*   **Border:** `border-slate-200` (subtle).
*   **Shadow:** `shadow-sm` untuk card, `shadow-md` untuk dropdown/modal.
*   **Color Palette:**
    *   Primary: `bg-slate-900 text-white`
    *   Destructive: `bg-red-500 text-white`
    *   Muted: `bg-slate-100 text-slate-500`

### 7.2. Komponen Blade (Reusable)
1.  **`x-layout.app`**: Wrapper utama dengan Sidebar & Navbar.
2.  **`x-card`**: Container putih dengan border halus.
3.  **`x-table`**: Tabel dengan header sticky dan row hover.
4.  **`x-badge`**: Indikator status (Success, Warning, Danger).
5.  **`x-input`**: Input field dengan focus ring `ring-slate-900`.
6.  **`x-select`**: Dropdown styling konsisten.
7.  **`x-modal`**: Dialog popup menggunakan Alpine.js `x-dialog`.

### 7.3. Implementasi Search & Filter UI
*   **Toolbar:** Terletak di atas setiap tabel data.
*   **Search Input:** Lebar responsif, ikon search di kanan.
*   **Filter Button:** Membuka popover (Alpine.js) berisi opsi filter.
*   **Active Tags:** Row di bawah toolbar menampilkan filter aktif (contoh: `[Status: Dipinjam (x)]`).
*   **Pagination:** Link pagination harus mempertahankan query string (`$data->withQueryString()`).

## 8. Implementasi Teknis (Backend Logic)

### 8.1. Eloquent Scopes (Search & Filter)
Menggunakan Scope pada Model untuk menjaga controller tetap bersih.

**File:** `app/Models/Book.php`
```php
public function scopeSearch($query, $search)
{
    return $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('author', 'like', "%{$search}%")
          ->orWhere('isbn', 'like', "%{$search}%");
    });
}

public function scopeFilter($query, array $filters)
{
    return $query->when($filters['category'] ?? null, fn($q, $c) => $q->where('category_id', $c))
        ->when($filters['availability'] ?? null, function ($q, $avail) {
            if ($avail === 'available') return $q->where('stock_available', '>', 0);
            if ($avail === 'out') return $q->where('stock_available', 0);
        })
        ->when($filters['sort'] ?? 'title', function ($q, $sort) {
            return $q->orderBy($sort, $filters['direction'] ?? 'asc');
        });
}
```

**File:** `app/Models/Borrowing.php`
```php
public function scopeStatus($query, $status)
{
    return $query->where('status', $status);
}

public function scopeOverdue($query)
{
    return $query->where('status', 'borrowed')
                 ->where('due_date', '<', now()->toDateString());
}
```

### 8.2. Controller Pattern
**File:** `app/Http/Controllers/BorrowingController.php`
```php
public function index(Request $request)
{
    $filters = $request->only(['status', 'member_id', 'date_from', 'date_to']);
    $search = $request->get('search');

    $borrowings = Borrowing::with(['member', 'book', 'admin'])
        ->search($search)
        ->when($filters['status'] ?? null, fn($q, $s) => $q->status($s))
        ->when($filters['member_id'] ?? null, fn($q, $m) => $q->where('member_id', $m))
        ->when($filters['date_from'] ?? null, fn($q, $d) => $q->whereDate('borrow_date', '>=', $d))
        ->orderBy('created_at', 'desc')
        ->paginate(15)
        ->withQueryString(); // PENTING: Menjaga filter saat paging

    $members = Member::select('id', 'name')->get(); // Untuk dropdown filter

    return view('borrowings.index', compact('borrowings', 'filters', 'search', 'members'));
}
```

## 9. Roadmap Pengembangan

| Fase | Durasi | Fokus | Deliverables |
| :--- | :--- | :--- | :--- |
| **1. Setup & Auth** | Minggu 1 | Instalasi Laravel 12, DB, Auth Admin, UI Kit | Project Structure, Login, Blade Components |
| **2. Master Data** | Minggu 2 | CRUD Buku, Kategori, Anggota | Manajemen Data Dasar, Upload Gambar |
| **3. Transaksi** | Minggu 3 | Logika Pinjam, Kembali, Stok, Denda | Sirkulasi Buku, Kalkulasi Otomatis |
| **4. Search & Filter** | Minggu 4 | **Implementasi Logic Search, Filter, Sort** | Query Scopes, UI Toolbar, Pagination |
| **5. Report & Polish** | Minggu 5 | Dashboard, Export, Testing, Deployment | Laporan, Bug Fix, Live Server |

## 10. Kriteria Penerimaan (Acceptance Criteria)

### 10.1. Fungsional
*   [ ] Admin dapat login dan mengakses dashboard.
*   [ ] Admin dapat menambah buku dan anggota baru.
*   [ ] **Transaksi Pinjam:** Stok buku berkurang otomatis, tanggal kembali terhitung.
*   [ ] **Transaksi Kembali:** Stok bertambah, denda dihitung jika terlambat.
*   [ ] **Search:** Mencari buku berdasarkan judul/isbn menampilkan hasil yang relevan.
*   [ ] **Filter:** Memfilter transaksi "Belum Kembali" hanya menampilkan data yang sesuai.
*   [ ] **Pagination:** Halaman 2 transaksi tetap membawa filter "Belum Kembali".
*   [ ] **Blokir:** Anggota dengan denda unpaid tidak bisa melakukan peminjaman baru.

### 10.2. Non-Fungsional
*   [ ] **Kecepatan:** Query pencarian buku < 500ms (dengan Index).
*   [ ] **UI:** Tampilan konsisten dengan gaya Shadcn (bersih, minimalis).
*   [ ] **Responsif:** Dashboard dapat diakses via Tablet/IPad Admin.
*   [ ] **Keamanan:** Route Admin dilindungi Middleware, input sanitized.

## 11. Risiko & Mitigasi
| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Data Stok Negatif** | Stok buku menjadi minus saat transaksi | Validasi backend ketat (`stock_available > 0`) sebelum save |
| **Query Lambat** | Search transaksi lama menjadi lambat | Indexing pada kolom `borrow_date`, `status`, `member_id` |
| **Human Error Admin** | Salah input tanggal kembali | Set default tanggal kembali otomatis (misal +7 hari) |

---

**Disetujui Oleh:**  
_________________________  
(Kepala Perpustakaan)

**Dibuat Oleh:**  
_________________________  
(Team Lead Developer)