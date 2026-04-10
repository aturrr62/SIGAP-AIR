# 💧 SIGAP-AIR

**Sistem Informasi Gerak Cepat Pengaduan Air**
Aplikasi web berbasis Laravel untuk pengelolaan pengaduan kualitas air bersih PDAM.

---

## 🧾 Deskripsi

SIGAP-AIR adalah sistem berbasis web yang dirancang untuk mempermudah masyarakat dalam melaporkan permasalahan kualitas air, serta membantu pihak PDAM dalam mengelola, memproses, dan memonitor pengaduan secara efisien.

---

## 🧰 Teknologi yang Digunakan

* PHP 8.1+
* Laravel 10
* Laravel Breeze (Blade) – Authentication
* MySQL / MariaDB
* Tailwind CSS
* Laravel Dusk – Automated Testing
* Jira – Project Management
* Git & GitHub – Version Control

---

## 📁 Struktur Folder

```bash
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Supervisor/
│   │   ├── Petugas/
│   │   ├── Masyarakat/
│   │   └── Auth/
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│
├── Services/
│
resources/
├── views/
│   ├── admin/
│   ├── supervisor/
│   ├── petugas/
│   ├── masyarakat/
│   ├── layouts/
│   └── components/
│
routes/
├── web.php
├── admin.php
├── supervisor.php
├── petugas.php
└── masyarakat.php
│
database/
├── migrations/
└── seeders/
│
tests/
├── Feature/
└── Browser/
```

> 📌 Setiap anggota hanya perlu fokus pada folder:

```bash
app/Domains/[domain masing-masing]
```

---
Flow Sistem (End-to-End)
```bash
Masyarakat mengajukan pengaduan
Supervisor memverifikasi
Supervisor melakukan assignment petugas
Petugas melakukan update status & upload dokumentasi
Sistem mengirim notifikasi
Masyarakat memberikan rating

---



## ⚙️ Setup Proyek

Ikuti langkah berikut untuk menjalankan project secara lokal:

```bash
# 1. Clone repository
git clone https://github.com/aturrr62/SIGAP-AIR.git

# 2. Masuk ke folder project
cd SIGAP-AIR

# 3. Install dependency PHP
composer install

# 4. Install dependency frontend
npm install

# 5. Copy file environment
cp .env.example .env

# 6. Generate application key
php artisan key:generate

# 7. Konfigurasi database di file .env
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=

# 8. Jalankan migrasi database
php artisan migrate

# 9. Build assets frontend
npm run build

# 10. Jalankan server
php artisan serve
```

---

## 🧪 Testing

Untuk menjalankan automated testing menggunakan Laravel Dusk:

```bash
php artisan dusk
```

---

## 👥 Kontribusi

1. Buat branch baru dari `main`
2. Kerjakan fitur sesuai domain masing-masing
3. Commit perubahan dengan pesan yang jelas
4. Push ke branch
5. Buat Pull Request ke `main`

---

## 🔐 Git Workflow (Disarankan)

* ❌ Tidak diperbolehkan push langsung ke `main`
* ✅ Gunakan Pull Request
* ✅ Minimal 1 approval sebelum merge

---

## 📌 Catatan

* Pastikan `.env` tidak di-commit
* Gunakan struktur domain yang sudah ditentukan
* Ikuti standar penamaan file & class Laravel

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik dan pengembangan internal.
