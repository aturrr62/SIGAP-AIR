# SIGAP-AIR

**Sistem Informasi Gerak Cepat Pengaduan Air**  
Aplikasi web berbasis Laravel untuk pengelolaan pengaduan kualitas air bersih PDAM.

---

## 🧰 Teknologi

- PHP 8.1+
- Laravel 10
- Laravel Breeze (Blade) – autentikasi
- MySQL / MariaDB
- Tailwind CSS
- Laravel Dusk – testing otomatis
- Jira – manajemen proyek
- Git & GitHub – version control

---

## 📁 Struktur Folder 
sigap-air/
├── .gitignore
├── .env.example
├── README.md (sudah dibuat sebelumnya)
├── app/
│   ├── Domains/
│   │   ├── Complaint/
│   │   │   ├── Actions/
│   │   │   │   └── GenerateTicketNumberAction.php
│   │   │   ├── DataTransferObjects/
│   │   │   │   └── ComplaintData.php
│   │   │   ├── Models/
│   │   │   │   └── Complaint.php
│   │   │   ├── Repositories/
│   │   │   │   └── ComplaintRepositoryInterface.php
│   │   │   └── Services/
│   │   │       └── ComplaintService.php
│   │   ├── User/
│   │   │   ├── Actions/
│   │   │   │   └── UpdateUserProfileAction.php
│   │   │   ├── DataTransferObjects/
│   │   │   │   └── UserData.php
│   │   │   ├── Models/
│   │   │   │   └── User.php
│   │   │   ├── Repositories/
│   │   │   │   └── UserRepositoryInterface.php
│   │   │   └── Services/
│   │   │       └── UserService.php
│   │   └── MasterData/
│   │       ├── Actions/
│   │       │   └── CreateSlaAction.php
│   │       ├── DataTransferObjects/
│   │       │   └── SlaData.php
│   │       ├── Models/
│   │       │   ├── Category.php
│   │       │   └── Sla.php
│   │       ├── Repositories/
│   │       │   └── MasterDataRepositoryInterface.php
│   │       └── Services/
│   │           └── MasterDataService.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Web/
│   │   │       ├── ComplaintController.php
│   │   │       ├── UserController.php
│   │   │       └── MasterDataController.php
│   │   └── Requests/
│   │       ├── StoreComplaintRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       └── StoreSlaRequest.php
│   ├── Infrastructure/
│   │   ├── Repositories/
│   │   │   ├── EloquentComplaintRepository.php
│   │   │   ├── EloquentUserRepository.php
│   │   │   └── EloquentMasterDataRepository.php
│   │   └── Services/
│   │       └── (kosong untuk integrasi pihak ketiga nanti)
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_complaints_table.php
│   │   ├── 2024_01_01_000002_create_users_table.php (sudah ada dari Laravel, override tidak perlu)
│   │   ├── 2024_01_01_000003_create_categories_table.php
│   │   └── 2024_01_01_000004_create_slas_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── web.php
└── resources/
    └── views/
        ├── complaints/
        │   └── create.blade.php (sudah diberikan)
        ├── profile/
        │   └── edit.blade.php
        └── master/
            └── sla.blade.php



> Setiap anggota hanya perlu fokus pada folder `Domains/[domain masing-masing]`.

---

## 🚀 Setup Proyek (Untuk Semua Anggota)

```bash
# 1. Clone repository
git clone https://github.com/aturrr62/SIGAP-AIR.git
cd SIGAP-AIR

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Atur database di file .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 7. Jalankan migrasi
php artisan migrate

# 8. Build assets
npm run build

# 9. Jalankan server lokal
php artisan serve

