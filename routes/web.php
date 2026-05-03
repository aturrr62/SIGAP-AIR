<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SlaController as AdminSlaController;
use App\Http\Controllers\Supervisor\MonitorSlaController;
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\NotifikasiController;
use App\Http\Controllers\Masyarakat\PengaduanController;
use App\Http\Controllers\Masyarakat\RiwayatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role: Masyarakat
    Route::middleware(['role:masyarakat'])->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/dashboard', [MasyarakatDashboardController::class, 'index'])->name('dashboard');

        // PBI-04 Pengajuan Pengaduan Digital
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}/sukses', [PengaduanController::class, 'sukses'])->name('pengaduan.sukses');

        // PBI-10 Riwayat Pengaduan
        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{pengaduan}', [RiwayatController::class, 'show'])->name('riwayat.show');

        // PBI-12 Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    });

    // Role: Petugas
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

        // PBI-07 — Penanganan Tugas
        Route::get('/tugas', [\App\Http\Controllers\Petugas\PenangananController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'show'])->name('tugas.show');
        Route::patch('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'update'])->name('tugas.update');
        Route::get('/riwayat', [\App\Http\Controllers\Petugas\PenangananController::class, 'riwayat'])->name('riwayat');
    });

    // Role: Supervisor
    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'index'])->name('dashboard');

        // PBI-05: Verifikasi
        Route::get('/verifikasi', [\App\Http\Controllers\Supervisor\VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{pengaduan}', [\App\Http\Controllers\Supervisor\VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{pengaduan}', [\App\Http\Controllers\Supervisor\VerifikasiController::class, 'update'])->name('verifikasi.update');

        // PBI-06: Assignment
        Route::get('/assignment/create/{pengaduan}', [\App\Http\Controllers\Supervisor\AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('/assignment', [\App\Http\Controllers\Supervisor\AssignmentController::class, 'store'])->name('assignment.store');

        // PBI-09: Monitor SLA & Alert Overdue
        Route::get('/monitor-sla', [MonitorSlaController::class, 'index'])->name('monitor-sla.index');

        // PBI-13,14,15,18 routes here
    });

    // Role: Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // PBI-02: Kategori
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);

        // PBI-09: Konfigurasi SLA per Kategori
        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');
        Route::get('/sla/{sla}/edit', [AdminSlaController::class, 'edit'])->name('sla.edit');
        Route::patch('/sla/{sla}', [AdminSlaController::class, 'update'])->name('sla.update');

        // PBI-01: Pelanggan
        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);

        // PBI-16,17 routes here
    });

    // Shared: Admin & Supervisor
    Route::middleware(['role:admin,supervisor'])->group(function () {
        // Shared routes here
    });
});

require __DIR__.'/auth.php';
