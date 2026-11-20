<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_penjualan_membutuhkan_login(): void
    {
        $this->get('/penjualan')->assertRedirect('/login');
    }

    public function test_daftar_penjualan_menampilkan_data_dan_total_pendapatan(): void
    {
        $user = User::factory()->create();

        Penjualan::create([
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Padi IR-64',
            'lokasi' => 'Blok Utara',
            'jumlah' => 100,
            'satuan' => 'kg',
            'harga_satuan' => 7000,
            'pembeli' => 'Koperasi Tani Makmur',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/penjualan');

        $response->assertStatus(200);
        $response->assertSee('Data Hasil Penjualan');
        $response->assertSee('Padi IR-64');
        $response->assertSee('Koperasi Tani Makmur');
        $response->assertSee('Rp 700.000');
    }

    public function test_penjualan_baru_menghitung_total_otomatis(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/penjualan', [
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Cabai Merah',
            'lokasi' => 'Blok Selatan',
            'jumlah' => 250,
            'satuan' => 'kg',
            'harga_satuan' => 8000,
            'pembeli' => 'Pasar Induk',
        ]);

        $response->assertRedirect('/penjualan');

        $penjualan = Penjualan::firstOrFail();

        $this->assertSame('Cabai Merah', $penjualan->nama_tanaman);
        $this->assertEquals(2000000, (float) $penjualan->total);
        $this->assertSame($user->id, $penjualan->user_id);
    }

    public function test_penjualan_dapat_diperbarui_dan_dihapus(): void
    {
        $user = User::factory()->create();

        $penjualan = Penjualan::create([
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Jagung Manis',
            'jumlah' => 100,
            'satuan' => 'kg',
            'harga_satuan' => 5000,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->put("/penjualan/{$penjualan->id}", [
                'tanggal' => now()->toDateString(),
                'nama_tanaman' => 'Jagung Manis',
                'jumlah' => 120,
                'satuan' => 'kg',
                'harga_satuan' => 6000,
            ])
            ->assertRedirect('/penjualan');

        $this->assertEquals(720000, (float) $penjualan->fresh()->total);

        $this->actingAs($user)
            ->delete("/penjualan/{$penjualan->id}")
            ->assertRedirect('/penjualan');

        $this->assertDatabaseMissing('penjualans', ['id' => $penjualan->id]);
    }

    public function test_dashboard_menampilkan_ringkasan_hasil_penjualan(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response('', 500),
            'news.google.com/*' => Http::response('', 500),
        ]);

        $user = User::factory()->create();

        Penjualan::create([
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Tomat Melon',
            'jumlah' => 100,
            'satuan' => 'kg',
            'harga_satuan' => 7500,
            'pembeli' => 'Pasar Desa',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Hasil Penjualan');
        $response->assertSee('Pendapatan Bulan Ini');
        $response->assertSee('Laba Bulan Ini');
        $response->assertSee('Rp 750.000');
        $response->assertSee('Pasar Desa');
    }
}
