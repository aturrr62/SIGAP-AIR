<?php

namespace Tests\Browser;

use App\Models\Assignment;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI18_LaporanKinerjaPetugasTest extends DuskTestCase
{
    private function buatUser($name, $username, $email, $role)
    {
        $id = DB::table('users')->insertGetId([
            'name' => $name,
            'username' => $username . '_' . uniqid(),
            'email' => $email . '_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => $role,
            'no_telepon' => '08123456789',
            'is_active' => 1,
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find($id);
    }

    private function buatDataKinerja()
    {
        $supervisor = $this->buatUser(
            'Dewi Supervisor',
            'supervisor_pbi18',
            'supervisor_pbi18',
            'supervisor'
        );

        $masyarakat = $this->buatUser(
            'Budi Masyarakat',
            'masyarakat_pbi18',
            'masyarakat_pbi18',
            'masyarakat'
        );

        $userPetugas = $this->buatUser(
            'Roni Petugas',
            'petugas_pbi18',
            'petugas_pbi18',
            'petugas'
        );

        $zonaId = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Zona Utara PBI18',
            'kode_zona' => 'ZU' . uniqid(),
            'deskripsi' => 'Zona untuk testing PBI 18',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kategori = KategoriPengaduan::create([
            'nama_kategori' => 'Kebocoran Pipa PBI18 ' . uniqid(),
            'kode_kategori' => 'KB' . uniqid(),
            'deskripsi' => 'Kategori testing PBI 18',
            'sla_jam' => 24,
            'is_active' => true,
        ]);

        $petugas = Petugas::create([
            'user_id' => $userPetugas->id,
            'zona_id' => $zonaId,
            'nip' => 'PTG-PBI18-' . uniqid(),
            'status_tersedia' => 'tersedia',
        ]);

        $pengaduan = Pengaduan::create([
            'nomor_tiket' => 'TKT-PBI18-' . uniqid(),
            'user_id' => $masyarakat->id,
            'kategori_id' => $kategori->id,
            'zona_id' => $zonaId,
            'lokasi' => 'Jl. Testing PBI 18',
            'deskripsi' => 'Air tidak mengalir',
            'status' => 'selesai',
            'tanggal_pengajuan' => now()->subDays(2),
        ]);

        DB::table('assignment')->insert([
            'pengaduan_id' => $pengaduan->id,
            'petugas_id' => $petugas->id,
            'supervisor_id' => $supervisor->id,
            'jadwal_penanganan' => now()->subDay(),
            'status_assignment' => 'selesai',
            'tanggal_selesai' => now(),
            'created_at' => now()->subDays(2),
            'updated_at' => now(),
        ]);

        Rating::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => $masyarakat->id,
            'rating' => 5,
            'komentar' => 'Pelayanan sangat baik',
        ]);

        return [$supervisor, $zonaId];
    }

    /** @test */
    public function supervisor_dapat_melihat_laporan_kinerja_petugas()
    {
        [$supervisor] = $this->buatDataKinerja();

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                ->visit('/supervisor/kinerja')
                ->assertPathIs('/supervisor/kinerja')
                ->assertSee('Laporan Kinerja Petugas')
                ->assertSee('Export CSV')
                ->assertSee('Nama Petugas')
                ->assertSee('No. Pegawai')
                ->assertSee('Total Tugas')
                ->assertSee('Selesai')
                ->assertSee('Roni Petugas')
                ->assertSee('1');
        });
    }

    /** @test */
    public function supervisor_dapat_melakukan_filter_laporan_kinerja_petugas()
    {
        [$supervisor, $zonaId] = $this->buatDataKinerja();

        $this->browse(function (Browser $browser) use ($supervisor, $zonaId) {
            $browser->loginAs($supervisor)
                ->visit('/supervisor/kinerja')
                ->select('zona_id', $zonaId)
                ->type('dari', now()->subDays(7)->format('Y-m-d'))
                ->type('sampai', now()->format('Y-m-d'))
                ->press('Filter')
                ->assertPathIs('/supervisor/kinerja')
                ->assertQueryStringHas('zona_id', (string) $zonaId)
                ->assertSee('Roni Petugas');
        });
    }

    /** @test */
    public function masyarakat_tidak_bisa_mengakses_laporan_kinerja_petugas()
    {
        $masyarakat = $this->buatUser(
            'Masyarakat Tidak Boleh Akses',
            'masyarakat_noakses_pbi18',
            'masyarakat_noakses_pbi18',
            'masyarakat'
        );

        $this->browse(function (Browser $browser) use ($masyarakat) {
            $browser->loginAs($masyarakat)
                ->visit('/supervisor/kinerja')
                ->assertDontSee('Laporan Kinerja Petugas');
        });
    }
}