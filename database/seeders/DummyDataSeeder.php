<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\KriteriaComparison;
use App\Models\Penilaian;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Users
        foreach(['admin', 'guru', 'kepsek'] as $role) {
            User::firstOrCreate(
                ['username' => $role],
                ['name' => ucfirst($role) . ' SPK', 'email' => $role . '@example.com', 'password' => Hash::make('password'), 'role' => $role]
            );
        }
        $kepsek = User::where('role', 'kepsek')->first();

        // 2. Kriteria
        $kriteriasData = [
            ['kode' => 'C1', 'nama' => 'Jenis kebutuhan khusus siswa'],
            ['kode' => 'C2', 'nama' => 'Kemampuan akademik'],
            ['kode' => 'C3', 'nama' => 'Kemampuan komunikasi'],
            ['kode' => 'C4', 'nama' => 'Kemandirian siswa'],
            ['kode' => 'C5', 'nama' => 'Kemampuan sosial']
        ];
        foreach ($kriteriasData as $k) {
            Kriteria::firstOrCreate(['kode' => $k['kode']], $k);
        }

        $kriterias = Kriteria::orderBy('id', 'asc')->get();

        // 3. Subkriteria
        $subs = [
            'C1' => [
                ['nama' => 'Sangat Kurang', 'nilai' => 1],
                ['nama' => 'Kurang', 'nilai' => 2],
                ['nama' => 'Cukup', 'nilai' => 3],
                ['nama' => 'Baik', 'nilai' => 4],
                ['nama' => 'Sangat Baik', 'nilai' => 5],
            ]
        ];
        foreach ($kriterias as $k) {
            if (isset($subs[$k->kode])) {
                foreach ($subs[$k->kode] as $s) {
                    Subkriteria::firstOrCreate(['kriteria_id' => $k->id, 'nama' => $s['nama']], $s);
                }
            }
        }

        // 4. Kriteria Comparisons (Pairwise Matrix Generation)
        $n = count($kriterias);
        for ($i=0; $i<$n; $i++) {
            for ($j=$i+1; $j<$n; $j++) {
                $val = rand(2, 5); // random importance
                if ($i == 0) $val = 3;
                if ($i == 1) $val = 2;
                KriteriaComparison::updateOrCreate(
                    ['kriteria1_id' => $kriterias[$i]->id, 'kriteria2_id' => $kriterias[$j]->id],
                    ['nilai' => $val]
                );
            }
        }

        // 5. Siswa Data
        $siswasData = [
            ['nisn' => '1001', 'nama' => 'Ahmad Reza', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunarungu', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2015-05-10', 'alamat' => 'Jl. Merdeka 1'],
            ['nisn' => '1002', 'nama' => 'Budi Santoso', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunanetra', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2014-11-20', 'alamat' => 'Jl. Pahlawan 2'],
            ['nisn' => '1003', 'nama' => 'Citra Lestari', 'jenis_kelamin' => 'Perempuan', 'jenis_kebutuhan_khusus' => 'Autisme', 'tempat_lahir' => 'Surabaya', 'tanggal_lahir' => '2016-01-15', 'alamat' => 'Jl. Sudirman 3'],
            ['nisn' => '1004', 'nama' => 'Deni Darmawan', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunadaksa', 'tempat_lahir' => 'Medan', 'tanggal_lahir' => '2015-08-30', 'alamat' => 'Jl. Thamrin 4'],
            ['nisn' => '1005', 'nama' => 'Eka Putri', 'jenis_kelamin' => 'Perempuan', 'jenis_kebutuhan_khusus' => 'Kesulitan Belajar', 'tempat_lahir' => 'Semarang', 'tanggal_lahir' => '2014-04-22', 'alamat' => 'Jl. Gatot Subroto 5'],
        ];

        foreach ($siswasData as $s) {
            Siswa::firstOrCreate(['nisn' => $s['nisn']], $s);
        }
        $siswas = Siswa::all();

        // 6. Penilaian
        foreach ($siswas as $siswa) {
            foreach ($kriterias as $k) {
                Penilaian::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'kriteria_id' => $k->id],
                    ['nilai' => rand(65, 95)]
                );
            }
        }

        // 7. Pengumuman
        if (Pengumuman::count() == 0) {
            Pengumuman::create([
                'judul' => 'Instruksi Pengisian AHP Semester Ganjil',
                'isi' => 'Harap kepada seluruh guru Wali Kelas agar segera menuntaskan borang penilaian matriks kriteria paling lambat minggu depan.',
                'status_aktif' => true,
                'created_by' => $kepsek->id
            ]);
            Pengumuman::create([
                'judul' => 'Sistem Rekomendasi Terintegrasi Baru',
                'isi' => 'Dashboard grafik statistik telah aktif dan siap digunakan secara penuh, pastikan input data valid.',
                'status_aktif' => true,
                'created_by' => $kepsek->id
            ]);
        }
    }
}
