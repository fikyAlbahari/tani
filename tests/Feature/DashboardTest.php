<?php

namespace Tests\Feature;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private const RSS = <<<'XML'
    <?xml version="1.0" encoding="UTF-8"?>
    <rss version="2.0"><channel>
        <item>
            <title>Harga Padi Naik - Info البلد</title>
            <link>https://example.test/berita-1</link>
            <pubDate>Tue, 21 Oct 2026 08:00:00 GMT</pubDate>
            <source url="https://example.test">Info Blanca</source>
        </item>
    </channel></rss>
    XML;

    private function fakeApis(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current' => [
                    'temperature_2m' => 29.4,
                    'apparent_temperature' => 32.1,
                    'relative_humidity_2m' => 78,
                    'precipitation' => 0.2,
                    'weather_code' => 2,
                    'wind_speed_10m' => 4.2,
                    'is_day' => 1,
                ],
                'daily' => [
                    'time' => ['2026-10-21', '2026-10-22'],
                    'weather_code' => [2, 61],
                    'temperature_2m_max' => [31, 30],
                    'temperature_2m_min' => [24, 23],
                    'precipitation_probability_max' => [20, 70],
                ],
            ]),
            'news.google.com/*' => Http::response(self::RSS),
        ]);
    }

    public function test_dashboard_menampilkan_cuaca_kalender_agenda_dan_berita(): void
    {
        $this->fakeApis();

        $user = User::factory()->create();

        JadwalTanam::create([
            'nama_tanaman' => 'Padi IR-64',
            'varietas_benih' => 'IR-64',
            'luas_lahan' => 1.5,
            'tanggal_tanam' => now()->subDays(30)->toDateString(),
            'perkiraan_panen' => now()->addDays(25)->toDateString(),
            'status' => 'Tumbuh',
            'user_id' => $user->id,
        ]);

        Pemupukan::create([
            'tanggal' => now()->addDays(4)->toDateString(),
            'nama_tanaman' => 'Cabai Merah',
            'jenis_pupuk' => 'NPK Mutiara',
            'dosis' => '150 kg/ha',
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Cuaca Hari Ini');
        $response->assertSee('Kalender Tanam');
        $response->assertSee('Agenda 30 Hari');
        $response->assertSee('Berita Pertanian');
        $response->assertSee('29');
        $response->assertSee('Berawan Sebagian');
        $response->assertSee('Harga Padi Naik');
        $response->assertSee('Padi IR-64');
        $response->assertSee('NPK Mutiara');
    }

    public function test_agenda_menampilkan_tanggal_jadwal_dan_keterangan_waktu(): void
    {
        $this->fakeApis();

        $user = User::factory()->create();

        Pemupukan::create([
            'tanggal' => now()->addDay()->toDateString(),
            'nama_tanaman' => 'Cabai Merah',
            'jenis_pupuk' => 'Urea',
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(now()->addDay()->translatedFormat('l, d F Y'));
        $response->assertSee('Besok');
    }

    public function test_dashboard_tetap_berfungsi_saat_api_cuaca_dan_berita_gagal(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response('', 500),
            'news.google.com/*' => Http::response('', 500),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Data cuaca tidak tersedia');
        $response->assertSee('Kalender Tanam');
        $response->assertSee('Tips Memilih Benih Unggul untuk Musim Tanam');
    }

    public function test_halaman_dashboard_membutuhkan_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
