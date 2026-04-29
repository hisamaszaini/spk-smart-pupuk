# SMART Pupuk - Sistem Pakar Distribusi Pupuk

**SMART Pupuk** adalah Sistem Pendukung Keputusan (SPK) berbasis web yang dirancang untuk membantu proses distribusi pupuk secara adil dan transparan. Sistem ini menggunakan metode **SMART** (*Simple Multi-Attribute Rating Technique*) untuk menentukan urutan prioritas penerima alokasi pupuk berdasarkan kriteria yang telah ditentukan.

## 🚀 Fitur Utama

- **Dashboard Informatif**: Ringkasan data petani, kriteria, dan total pupuk terdistribusi.
- **Manajemen Data Master**:
    - **Data Petani**: Pengelolaan informasi petani dan luas lahan.
    - **Data Periode**: Pengaturan periode distribusi pupuk.
    - **Data Kriteria**: Fleksibilitas dalam menambah atau mengubah kriteria penilaian.
- **Bobot Kriteria**: Penentuan nilai bobot untuk setiap kriteria sesuai tingkat kepentingan.
- **Proses SPK SMART**:
    - Perhitungan otomatis mulai dari Matriks Keputusan hingga Nilai Utility.
    - Transparansi perhitungan langkah-demi-langkah.
- **Hasil Perangkingan & Alokasi**: Rekomendasi alokasi pupuk dalam satuan Kilogram (Kg) secara proporsional.
- **Manajemen Profil**: Pengaturan akun pengguna dan keamanan.

## 🛠️ Teknologi yang Digunakan

- **Framework**: [Laravel 13](https://laravel.com)
- **Frontend**: [Livewire Volt](https://livewire.laravel.com/docs/volt) (TALL Stack variation)
- **Styling**: [Tailwind CSS 4](https://tailwindcss.com)
- **Build Tool**: [Vite](https://vitejs.dev)
- **Database**: MySQL / SQLite

## 📂 Struktur Folder Penting

```text
spk-smart-pupuk/
├── app/
│   ├── Models/             # Model Eloquent (Petani, Kriteria, Alokasi, dll)
│   ├── Providers/          # Konfigurasi Volt, Fortify, & App Providers
│   └── Services/           # SmartService.php (Inti Perhitungan SMART)
├── database/
│   ├── migrations/         # Skema Database (tabel_petani, tabel_alokasi, dll)
│   └── seeders/            # Seeder untuk User Admin & data awal
├── resources/
│   ├── css/                # app.css (Tailwind CSS 4)
│   └── views/
│       ├── layouts/        # Layout App & Auth
│       │   ├── app.blade.php
│       │   └── app/        # Sidebar & Header components
│       ├── livewire/       # Komponen Volt (Single File Components)
│       │   ├── admin/      # Halaman utama aplikasi (Dashboard, Manager, SPK)
│       │   ├── auth/       # Halaman Login & Registrasi
│       │   └── settings/   # Halaman Profil & Keamanan
│       ├── components/     # Komponen Blade reusable
│       └── flux/           # Komponen UI dari Flux
└── routes/
    └── web.php             # Definisi rute aplikasi
```

## ⚙️ Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal Anda:

### 1. Persyaratan Sistem
- PHP >= 8.3
- Composer
- Node.js & NPM
- Database (MySQL/SQLite)

### 2. Mendapatkan Source Code

**Opsi A: Menggunakan Git Clone**
```bash
git clone https://github.com/username/spk-smart-pupuk.git
cd spk-smart-pupuk
```

**Opsi B: Download ZIP**
1. Download file ZIP dari repositori GitHub ini.
2. Ekstrak file ZIP ke folder lokal Anda.
3. Buka terminal/command prompt di dalam folder tersebut.

### 3. Instalasi Dependency
Instal library PHP dan JavaScript:
```bash
composer install
npm install
```

### 4. Konfigurasi Lingkungan
Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

**Contoh Konfigurasi Database (.env):**

Untuk **MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_pupuk
DB_USERNAME=root
DB_PASSWORD=
```

Untuk **SQLite** (opsional):
```env
DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite (otomatis jika tidak diisi)
```

### 5. Migrasi & Seeding Database
Buat tabel dan masukkan data awal (admin):
```bash
php artisan migrate --seed
```

### 6. Menjalankan Aplikasi

**Mode Pengembangan (Development):**
```bash
npm run dev
```

**Mode Produksi (Production):**
Jika ingin menjalankan aplikasi dengan performa maksimal (lebih cepat):
1. Compile aset JavaScript & CSS:
   ```bash
   npm run build
   ```
2. Optimasi Laravel:
   ```bash
   php artisan optimize
   ```
3. Jalankan server:
   ```bash
   php artisan serve --port=8000
   ```

**Penting untuk Produksi:**
Pastikan file `.env` Anda memiliki pengaturan berikut untuk keamanan dan performa:
```env
APP_ENV=production
APP_DEBUG=false
```

## 🔑 Akses Login

Setelah aplikasi berjalan, buka browser dan akses `http://localhost:8000`. Gunakan akun default berikut untuk masuk:

- **Email**: `admin@example.com`
- **Password**: `admin123`

---