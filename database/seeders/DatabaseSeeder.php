<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seeder Zona Wilayah (Bandung — 4 wilayah utama)
        $zonaBdgUtara = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Bandung Utara',
            'kode_zona' => 'BDG-U01',
            'deskripsi' => 'Wilayah pelayanan Bandung Utara meliputi Kec. Cidadap, Coblong, Bandung Wetan, Cibeunying Kaler, Cibeunying Kidul, Sukasari',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $zonaBdgSelatan = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Bandung Selatan',
            'kode_zona' => 'BDG-S02',
            'deskripsi' => 'Wilayah pelayanan Bandung Selatan meliputi Kec. Lengkong, Regol, Bandung Kidul, Buahbatu, Kiaracondong',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $zonaBdgBarat = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Bandung Barat',
            'kode_zona' => 'BDG-B03',
            'deskripsi' => 'Wilayah pelayanan Bandung Barat meliputi Kec. Andir, Cicendo, Sukajadi, Babakan Ciparay, Bojongloa Kaler, Bojongloa Kidul',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $zonaBdgTimur = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Bandung Timur',
            'kode_zona' => 'BDG-T04',
            'deskripsi' => 'Wilayah pelayanan Bandung Timur meliputi Kec. Arcamanik, Antapani, Mandalajati, Cibiru, Ujungberung',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seeder Kategori Pengaduan
        DB::table('kategori_pengaduan')->insert([
            [
                'nama_kategori' => 'Air Keruh',
                'kode_kategori' => 'AK-01',
                'deskripsi' => 'Laporan terkait air berwarna keruh atau kotor',
                'sla_jam' => 24,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Air Macet',
                'kode_kategori' => 'AM-02',
                'deskripsi' => 'Laporan tidak ada aliran air sama sekali',
                'sla_jam' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Air Berbau',
                'kode_kategori' => 'AB-03',
                'deskripsi' => 'Laporan air berbau tidak sedap atau berbau kimia',
                'sla_jam' => 48,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Seeder Akun Users
        $password = Hash::make('password');

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sigapair.test',
            'password' => $password,
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $supervisorId = DB::table('users')->insertGetId([
            'name' => 'Supervisor',
            'username' => 'supervisor',
            'email' => 'supervisor@sigapair.test',
            'password' => $password,
            'role' => 'supervisor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $petugasId = DB::table('users')->insertGetId([
            'name' => 'Petugas Lapangan',
            'username' => 'petugas',
            'email' => 'petugas@sigapair.test',
            'password' => $password,
            'role' => 'petugas',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $masyarakatId = DB::table('users')->insertGetId([
            'name' => 'Masyarakat Umum',
            'username' => 'masyarakat',
            'email' => 'masyarakat@sigapair.test',
            'password' => $password,
            'role' => 'masyarakat',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Register Petugas -> Zona Pertama
        DB::table('petugas')->insert([
            'user_id' => $petugasId,
            'zona_id' => $zonaBdgUtara,
            'nip' => 'PEG-1001',
            'status_tersedia' => 'tersedia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
