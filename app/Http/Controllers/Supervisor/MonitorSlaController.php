<?php
/**
 * PBI-09 — Monitor SLA & Alert Overdue Otomatis (Supervisor)
 * TANGGUNG JAWAB: Falah Adhi Chandra
 *
 * Fitur:
 * - Daftar semua pengaduan beserta status SLA-nya
 * - Filter berdasarkan status SLA (berjalan, overdue, terpenuhi)
 * - Badge visual overdue yang mencolok
 * - Statistik ringkas SLA
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Sla, Kategori};
use Illuminate\Http\Request;

class MonitorSlaController extends Controller
{
    public function index(Request $request)
    {
        $filterSla     = $request->get('status_sla', 'semua');
        $filterKategori = $request->get('kategori_id');
        $search         = $request->get('search');

        $query = Pengaduan::with(['kategori', 'zona', 'pelapor', 'sla', 'assignment.petugasRelasi'])
            ->whereHas('sla'); // Hanya yang punya record SLA

        // Filter berdasarkan status SLA
        if ($filterSla && $filterSla !== 'semua') {
            $query->whereHas('sla', fn($q) => $q->where('status_sla', $filterSla));
        }

        // Filter berdasarkan kategori
        if ($filterKategori) {
            $query->where('kategori_id', $filterKategori);
        }

        // Search by nomor tiket
        if ($search) {
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        $pengaduans = $query->latest()->paginate(15)->appends($request->query());

        // Statistik SLA
        $stats = [
            'total_berjalan'  => Sla::where('status_sla', 'berjalan')->count(),
            'total_overdue'   => Sla::where('status_sla', 'overdue')->count(),
            'total_terpenuhi' => Sla::where('status_sla', 'terpenuhi')->count(),
            'flagged'         => Sla::where('is_flagged', true)->where('status_sla', 'overdue')->count(),
        ];

        $kategoris = Kategori::where('is_active', true)->orderBy('nama_kategori')->get();

        return view('supervisor.monitor-sla.index', compact(
            'pengaduans', 'stats', 'filterSla', 'filterKategori', 'search', 'kategoris'
        ));
    }
}
