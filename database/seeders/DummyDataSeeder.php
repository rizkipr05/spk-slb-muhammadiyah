<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\KriteriaComparison;
use App\Models\Pengumuman;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\Subkriteria;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Users
        foreach (['admin', 'guru', 'kepsek'] as $role) {
            User::firstOrCreate(
                ['username' => $role],
                ['name' => ucfirst($role).' SPK', 'email' => $role.'@example.com', 'password' => Hash::make('password'), 'role' => $role]
            );
        }
        $kepsek = User::where('role', 'kepsek')->first();
        $guru = User::where('role', 'guru')->first();

        // 2. Alternatif Layanan Pendidikan (Sesuai Proposal & Output Rekomendasi SPK)
        $alternatifsData = [
            [
                'kode' => 'A1',
                'nama_layanan' => 'Layanan Pendidikan Tunarungu',
                'deskripsi' => 'Layanan pendidikan yang dirancang untuk mendukung kebutuhan belajar siswa dengan hambatan pendengaran melalui stimulasi visual, bahasa isyarat, dan pemanfaatan alat bantu pendengaran.',
            ],
            [
                'kode' => 'A2',
                'nama_layanan' => 'Layanan Pendidikan Tunagrahita',
                'deskripsi' => 'Layanan pendidikan yang disesuaikan untuk siswa dengan hambatan intelektual dan perkembangan kognitif guna melatih kemampuan adaptif serta bina diri mandiri.',
            ],
            [
                'kode' => 'A3',
                'nama_layanan' => 'Layanan Pendidikan Autisme',
                'deskripsi' => 'Layanan pendidikan yang disesuaikan untuk siswa spektrum autisme guna mendukung konsentrasi belajar, pengembangan interaksi sosial, dan komunikasi adaptif.',
            ],
            [
                'kode' => 'A4',
                'nama_layanan' => 'Layanan Pendidikan Tunanetra',
                'deskripsi' => 'Layanan pendidikan khusus peserta didik tunanetra yang berfokus pada orientasi mobilitas, penguasaan huruf Braille, dan pemanfaatan sarana sensori taktil.',
            ],
            [
                'kode' => 'A5',
                'nama_layanan' => 'Layanan Pendidikan Down Syndrom',
                'deskripsi' => 'Layanan pendidikan yang disesuaikan untuk siswa Down Syndrome guna mengoptimalkan potensi motorik, stimulasi kognitif, kemampuan bersosialisasi, dan keterampilan hidup harian.',
            ],
            [
                'kode' => 'A6',
                'nama_layanan' => 'Layanan Pendidikan Tunadaksa',
                'deskripsi' => 'Layanan pendidikan yang disesuaikan untuk siswa dengan hambatan fisik atau gerak melalui penyediaan aksesibilitas lingkungan dan latihan fungsional tubuh.',
            ],
        ];

        foreach ($alternatifsData as $alt) {
            Alternatif::updateOrCreate(['kode' => $alt['kode']], $alt);
        }

        // 3. Kriteria (K1 s/d K5 - Standar Kriteria Kuesioner Pakar)
        $kriteriasData = [
            ['kode' => 'K1', 'nama' => 'Jenis Kebutuhan Khusus'],
            ['kode' => 'K2', 'nama' => 'Kemampuan Akademik'],
            ['kode' => 'K3', 'nama' => 'Kemampuan Komunikasi'],
            ['kode' => 'K4', 'nama' => 'Kemandirian Siswa'],
            ['kode' => 'K5', 'nama' => 'Kemampuan Sosial'],
        ];

        // Jika terdapat data kriteria lama berkode C1-C5, ubah menjadi K1-K5
        $codeMap = ['C1' => 'K1', 'C2' => 'K2', 'C3' => 'K3', 'C4' => 'K4', 'C5' => 'K5'];
        foreach ($codeMap as $oldCode => $newCode) {
            $oldK = Kriteria::where('kode', $oldCode)->first();
            if ($oldK && ! Kriteria::where('kode', $newCode)->exists()) {
                $oldK->update(['kode' => $newCode]);
            }
        }

        foreach ($kriteriasData as $k) {
            Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }

        $kriterias = Kriteria::whereIn('kode', ['K1', 'K2', 'K3', 'K4', 'K5'])->orderBy('kode', 'asc')->get();

        // 4. Subkriteria Lengkap untuk seluruh Kriteria
        $subs = [
            'K1' => [
                ['nama' => 'Tunanetra', 'nilai' => 5],
                ['nama' => 'Tunarungu', 'nilai' => 5],
                ['nama' => 'Tunagrahita', 'nilai' => 4],
                ['nama' => 'Autisme', 'nilai' => 4],
                ['nama' => 'Down Syndrom', 'nilai' => 3],
                ['nama' => 'Tunadaksa', 'nilai' => 3],
                ['nama' => 'Kesulitan Belajar', 'nilai' => 2],
            ],
            'default' => [
                ['nama' => 'Sangat Kurang', 'nilai' => 1],
                ['nama' => 'Kurang', 'nilai' => 2],
                ['nama' => 'Cukup', 'nilai' => 3],
                ['nama' => 'Baik', 'nilai' => 4],
                ['nama' => 'Sangat Baik', 'nilai' => 5],
            ],
        ];

        foreach ($kriterias as $k) {
            $template = $subs[$k->kode] ?? $subs['default'];
            foreach ($template as $s) {
                Subkriteria::firstOrCreate(
                    ['kriteria_id' => $k->id, 'nama' => $s['nama']],
                    ['nilai' => $s['nilai']]
                );
            }
        }

        // 5. Kriteria Comparisons (Pairwise Matrix dari Hasil Kuesioner Pakar)
        // K1: JKK, K2: KA, K3: KK, K4: KS, K5: KSO
        $comparisonValues = [
            'K1' => ['K2' => 1.8378, 'K3' => 2.4166, 'K4' => 4.3734, 'K5' => 5.5106],
            'K2' => ['K3' => 1.3819, 'K4' => 2.4166, 'K5' => 3.5652],
            'K3' => ['K4' => 1.5874, 'K5' => 2.4166],
            'K4' => ['K5' => 1.4471],
        ];

        $kriteriaByKode = $kriterias->keyBy('kode');
        foreach ($comparisonValues as $kode1 => $targets) {
            foreach ($targets as $kode2 => $val) {
                if (isset($kriteriaByKode[$kode1]) && isset($kriteriaByKode[$kode2])) {
                    KriteriaComparison::updateOrCreate(
                        ['kriteria1_id' => $kriteriaByKode[$kode1]->id, 'kriteria2_id' => $kriteriaByKode[$kode2]->id],
                        ['nilai' => $val]
                    );
                }
            }
        }

        // 6. Siswa Data (Dihubungkan ke Guru agar tampil di role Guru)
        $siswasData = [
            ['nisn' => '1001', 'nama' => 'Ahmad Reza', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunarungu', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2015-05-10', 'alamat' => 'Jl. Merdeka 1', 'guru_id' => $guru ? $guru->id : null],
            ['nisn' => '1002', 'nama' => 'Budi Santoso', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunanetra', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2014-11-20', 'alamat' => 'Jl. Pahlawan 2', 'guru_id' => $guru ? $guru->id : null],
            ['nisn' => '1003', 'nama' => 'Citra Lestari', 'jenis_kelamin' => 'Perempuan', 'jenis_kebutuhan_khusus' => 'Autisme', 'tempat_lahir' => 'Surabaya', 'tanggal_lahir' => '2016-01-15', 'alamat' => 'Jl. Sudirman 3', 'guru_id' => $guru ? $guru->id : null],
            ['nisn' => '1004', 'nama' => 'Deni Darmawan', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Tunadaksa', 'tempat_lahir' => 'Medan', 'tanggal_lahir' => '2015-08-30', 'alamat' => 'Jl. Thamrin 4', 'guru_id' => $guru ? $guru->id : null],
            ['nisn' => '1005', 'nama' => 'Eka Putri', 'jenis_kelamin' => 'Perempuan', 'jenis_kebutuhan_khusus' => 'Tunagrahita', 'tempat_lahir' => 'Semarang', 'tanggal_lahir' => '2014-04-22', 'alamat' => 'Jl. Gatot Subroto 5', 'guru_id' => $guru ? $guru->id : null],
            ['nisn' => '1006', 'nama' => 'Fajar Pratama', 'jenis_kelamin' => 'Laki-laki', 'jenis_kebutuhan_khusus' => 'Down Syndrom', 'tempat_lahir' => 'Palu', 'tanggal_lahir' => '2015-02-18', 'alamat' => 'Jl. Tadulako 6', 'guru_id' => $guru ? $guru->id : null],
        ];

        foreach ($siswasData as $s) {
            Siswa::updateOrCreate(['nisn' => $s['nisn']], $s);
        }
        $siswas = Siswa::all();

        // Pastikan siswa yang sudah ada memiliki guru_id jika kosong
        if ($guru) {
            Siswa::whereNull('guru_id')->update(['guru_id' => $guru->id]);
        }

        // 6. Penilaian
        foreach ($siswas as $siswa) {
            foreach ($kriterias as $k) {
                Penilaian::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'kriteria_id' => $k->id],
                    ['nilai' => rand(70, 95)]
                );
            }
        }

        // 7. Pengumuman
        if (Pengumuman::count() == 0) {
            Pengumuman::create([
                'judul' => 'Instruksi Pengisian AHP Semester Ganjil',
                'isi' => 'Harap kepada seluruh guru Wali Kelas agar segera menuntaskan borang penilaian matriks kriteria paling lambat minggu depan.',
                'status_aktif' => true,
                'created_by' => $kepsek->id,
            ]);
            Pengumuman::create([
                'judul' => 'Sistem Rekomendasi Terintegrasi Baru',
                'isi' => 'Dashboard grafik statistik telah aktif dan siap digunakan secara penuh, pastikan input data valid.',
                'status_aktif' => true,
                'created_by' => $kepsek->id,
            ]);
        }
    }
}
