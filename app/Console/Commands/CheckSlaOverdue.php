<?php
/**
 * PBI-09 — Artisan Command: Cek SLA Overdue Otomatis
 * TANGGUNG JAWAB: Falah Adhi Chandra
 *
 * Command ini berjalan secara terjadwal (setiap 15 menit) untuk:
 * 1. Scan semua SLA yang masih 'berjalan' dan sudah melewati batas_waktu
 * 2. Update status_sla menjadi 'overdue' + set is_flagged = true
 * 3. Kirim notifikasi otomatis ke semua supervisor aktif
 */
namespace App\Console\Commands;

use App\Models\{Sla, User, Notifikasi};
use Illuminate\Console\Command;

class CheckSlaOverdue extends Command
{
    protected $signature   = 'sla:check-overdue';
    protected $description = 'Cek dan tandai pengaduan yang melewati batas SLA, kirim alert ke supervisor.';

    public function handle(): int
    {
        // Ambil semua SLA yang masih berjalan tapi sudah melewati batas waktu
        $overdues = Sla::where('status_sla', 'berjalan')
            ->where('batas_waktu', '<', now())
            ->with('pengaduan.kategori', 'pengaduan.zona')
            ->get();

        if ($overdues->isEmpty()) {
            $this->info('✅ Tidak ada SLA yang overdue.');
            return self::SUCCESS;
        }

        // Ambil semua supervisor aktif (sekali saja)
        $supervisors = User::where('role', 'supervisor')
            ->where('is_active', true)
            ->get();

        $count = 0;

        foreach ($overdues as $sla) {
            $sla->update([
                'status_sla' => 'overdue',
                'is_flagged' => true,
            ]);

            // Kirim notifikasi ke semua supervisor
            foreach ($supervisors as $supervisor) {
                Notifikasi::create([
                    'user_id'      => $supervisor->id,
                    'pengaduan_id' => $sla->pengaduan_id,
                    'judul'        => '⚠️ SLA Terlampaui',
                    'pesan'        => "Pengaduan #{$sla->pengaduan->nomor_tiket} "
                        . "({$sla->pengaduan->kategori->nama_kategori}) "
                        . "di {$sla->pengaduan->zona->nama_zona} telah melewati batas SLA "
                        . "{$sla->pengaduan->kategori->sla_jam} jam. Segera tindak lanjuti!",
                    'is_read'      => false,
                ]);
            }

            $count++;
        }

        $this->warn("🚨 {$count} pengaduan telah ditandai overdue. Notifikasi dikirim ke {$supervisors->count()} supervisor.");

        return self::SUCCESS;
    }
}
