<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd rsbadak-laravel-test
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuai konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsbadak-laravel-test
DB_USERNAME=root
DB_PASSWORD=root

# Atau gunakan SQLite untuk development cepat:
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite
```

### 4. Migrate & Seed Database

```bash
php artisan migrate --seed
```

Perintah ini akan membuat seluruh tabel dan mengisi data awal (users, poli, dokter, jadwal, pasien, dan pendaftaran demo).

### 5. Build Frontend

```bash
npm run build
```

### 6. Jalankan Aplikasi

```bash
# Cara 1: Menggunakan artisan serve
php artisan serve

# Cara 2: Menggunakan composer dev (menjalankan server, queue, logs, dan vite secara bersamaan)
composer dev
```

Aplikasi akan berjalan di **http://localhost:8000**.

---

## 🔐 Akun Demo

Setelah menjalankan seeder, gunakan akun berikut untuk login:

| Peran | Email | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin@rs.test` | `password` | Akses penuh ke semua fitur termasuk manajemen user |
| **Petugas** | `petugas@rs.test` | `password` | Akses pendaftaran, pasien |

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
