<?php

namespace Tests\Feature;

use App\Models\LaporanAhp;
use App\Models\User;
use Database\Seeders\DummyDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekomendasiAlternatifTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_populates_six_alternatif_layanan(): void
    {
        $this->seed(DummyDataSeeder::class);

        $this->assertDatabaseCount('alternatifs', 6);

        $expectedAlternatifs = [
            'A1' => 'Layanan Pendidikan Tunarungu',
            'A2' => 'Layanan Pendidikan Tunagrahita',
            'A3' => 'Layanan Pendidikan Autisme',
            'A4' => 'Layanan Pendidikan Tunanetra',
            'A5' => 'Layanan Pendidikan Down Syndrom',
            'A6' => 'Layanan Pendidikan Tunadaksa',
        ];

        foreach ($expectedAlternatifs as $kode => $nama) {
            $this->assertDatabaseHas('alternatifs', [
                'kode' => $kode,
                'nama_layanan' => $nama,
            ]);
        }
    }

    public function test_guru_rekomendasi_page_displays_alternatif_output(): void
    {
        $this->seed(DummyDataSeeder::class);
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->get(route('guru.rekomendasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Rekomendasi Alternatif Layanan');
        $response->assertSee('Layanan Pendidikan Tunarungu');
        $response->assertSee('A1');
    }

    public function test_simpan_dan_cetak_laporan_includes_alternatif_layanan(): void
    {
        $this->seed(DummyDataSeeder::class);
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->post(route('guru.rekomendasi.simpan'), [
            'judul' => 'Laporan Rekomendasi Tes Siswa',
        ]);

        $response->assertRedirect(route('guru.rekomendasi.riwayat'));

        $laporan = LaporanAhp::first();
        $this->assertNotNull($laporan);
        $this->assertArrayHasKey('rekomendasi_layanan', $laporan->data_hasil['results'][0]);

        $cetakResponse = $this->actingAs($guru)->get(route('guru.rekomendasi.cetak', $laporan->id));
        $cetakResponse->assertStatus(200);
        $cetakResponse->assertSee('Rekomendasi Alternatif Layanan');
        $cetakResponse->assertSee($laporan->data_hasil['results'][0]['rekomendasi_layanan']);
    }

    public function test_kepsek_laporan_displays_alternatif_layanan(): void
    {
        $this->seed(DummyDataSeeder::class);
        $guru = User::where('role', 'guru')->first();
        $kepsek = User::where('role', 'kepsek')->first();

        $this->actingAs($guru)->post(route('guru.rekomendasi.simpan'), [
            'judul' => 'Laporan Kepsek Test',
        ]);

        $response = $this->actingAs($kepsek)->get(route('kepsek.laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('Rekomendasi Layanan');
        $response->assertSee('Laporan Kepsek Test');
    }
}
