<?php
/**
 * ROUTES SIGAP-AIR
 *
 * Panduan untuk semua developer:
 * - Tambahkan route KAMU di blok grup yang sesuai dengan role
 * - Jangan mengubah route milik developer lain
 * - Gunakan resource route jika memungkinkan
 *
 * Legenda PBI per developer:
 *   ARTHUR   → PBI 1, 2, 3  (Admin: master data)
 *   SANITRA  → PBI 4, 5, 6  (Pengaduan + verifikasi + assignment)
 *   FALAH    → PBI 7, 8, 9  (Tracking + profil + SLA)
 *   AMANDA   → PBI 10, 11, 12 (Riwayat + rating + notifikasi)
 *   IMANUEL  → PBI 13, 14, 15 (Filter + laporan + dashboard)
 *   FARISHA  → PBI 16, 17, 18 (User management + petugas + kinerja)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Admin, Supervisor, Petugas, Masyarakat};

// ============================================================
// PUBLIC ROUTES (tanpa login)
// ============================================================
Route::get('/', fn() => redirect()->route('login'));

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --------------------------------------------------------
    // MASYARAKAT / PELAPOR
    // --------------------------------------------------------
    Route::middleware('role:masyarakat')->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/dashboard', [Masyarakat\DashboardController::class, 'index'])->name('dashboard');

        // PBI-04 | SANITRA — Pengajuan pengaduan baru
        Route::get('pengaduan/{pengaduan}/sukses', [Masyarakat\PengaduanController::class, 'sukses'])->name('pengaduan.sukses');
        Route::resource('pengaduan', Masyarakat\PengaduanController::class)->only(['create', 'store']);

        // PBI-10 | AMANDA — Riwayat pengaduan
        Route::resource('riwayat', Masyarakat\RiwayatController::class)->only(['index', 'show']);

        // PBI-11 | AMANDA — Rating kepuasan
        Route::resource('rating', Masyarakat\RatingController::class)->only(['create', 'store']);

        Route::get('/notifikasi', [Masyarakat\NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/baca', [Masyarakat\NotifikasiController::class, 'markRead'])->name('notifikasi.baca');
        Route::patch('/notifikasi/baca-semua', [Masyarakat\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca-semua');
    });

    // --------------------------------------------------------
    // PETUGAS TEKNIS
    // --------------------------------------------------------
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');

        // PBI-07 | FALAH
        Route::resource('tugas', Petugas\PenangananController::class)->only(['index', 'show', 'update']);

        // PBI-08 | FALAH
        Route::get('/profil/edit', [Petugas\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [Petugas\ProfilController::class, 'update'])->name('profil.update');
    });

    // --------------------------------------------------------
    // SUPERVISOR & ADMIN (SHARED)
    // --------------------------------------------------------
    Route::middleware('role:admin,supervisor')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/filter', [Supervisor\FilterPengaduanController::class, 'index'])->name('filter.index');

        Route::get('/laporan', [Supervisor\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [Supervisor\LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        Route::get('/kinerja', [Admin\LaporanKinerjaController::class, 'index'])->name('kinerja.index');
        Route::get('/kinerja/export-excel', [Admin\LaporanKinerjaController::class, 'exportExcel'])->name('kinerja.export-excel');
    });

    // --------------------------------------------------------
    // SUPERVISOR
    // --------------------------------------------------------
    Route::middleware('role:supervisor')->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [Supervisor\DashboardController::class, 'index'])->name('dashboard');

        // PBI-05 | SANITRA
        Route::resource('verifikasi', Supervisor\VerifikasiController::class)->only(['index', 'show', 'update']);

        // PBI-06 | SANITRA
        Route::get('assignment/{pengaduan}/create', [Supervisor\AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('assignment/{pengaduan}', [Supervisor\AssignmentController::class, 'store'])->name('assignment.store');
    });

    // --------------------------------------------------------
    // ADMIN
    // --------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('pelanggan', Admin\PelangganController::class);
        Route::resource('kategori', Admin\KategoriController::class);
        Route::resource('zona', Admin\ZonaController::class);

        Route::resource('sla', Petugas\SlaController::class)->only(['index', 'edit', 'update']);

        Route::resource('user', Admin\UserController::class);
        Route::post('user/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('user.reset-password');

        Route::resource('petugas', Admin\PetugasController::class);
    });

});

require __DIR__.'/auth.php';