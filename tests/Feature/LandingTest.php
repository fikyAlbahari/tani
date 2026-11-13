<?php

namespace Tests\Feature;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_depan_menampilkan_cuaca_dan_jadwal_kegiatan(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current' => [
                    'temperature_2m' => 30.2,
                    'apparent_temperature' => 33.1,
                    'relative_humidity_2m' => 76,
                    'precipitation' => 0.1,
                    'weather_code' => 1,
                    'wind_speed_10m' => 5.4,
                    'is_day' => 1,
                ],
                'daily' => [
                    'time' => ['2026-11-12', '2026-11-13'],
                    'weather_code' => [1, 61],
                    'temperature_2m_max' => [32, 30],
                    'temperature_2m_min' => [24, 23],
                    'precipitation_probability_max' => [20, 70],
                ],
            ]),
            'news.google.com/*' => Http::response(<<<'XML'
                <?xml version="1.0" encoding="UTF-8"?>
                <rss version="2.0">
                    <channel>
                        <title>Pertanian</title>
                        <item>
                            <title>Harga Gabah Naik di Lombok Utara - Antara News</title>
                            <link>https://example.com/berita-gabah</link>
                            <pubDate>Wed, 12 Nov 2026 07:00:00 +0800</pubDate>
                            <source url="https://example.com">Antara News</source>
                        </item>
                    </channel>
                </rss>
                XML),
        ]);

        JadwalTanam::create([
            'nama_tanaman' => 'Padi IR-64',
            'varietas_benih' => 'IR-64',
            'luas_lahan' => 1.5,
            'tanggal_tanam' => now()->subDays(10)->toDateString(),
            'status' => 'Tumbuh',
        ]);

        Pemupukan::create([
            'tanggal' => now()->addDays(2)->toDateString(),
            'nama_tanaman' => 'Cabai Merah',
            'jenis_pupuk' => 'NPK Mutiara',
            'status' => 'Terjadwal',
        ]);

        Penyemprotan::create([
            'tanggal' => now()->addDays(3)->toDateString(),
            'nama_tanaman' => 'Jagung Manis',
            'jenis_pestisida' => 'Abamektin',
            'status' => 'Terjadwal',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Info Lapangan');
        $response->assertSee('Cuaca Hari Ini');
        $response->assertSee('Kabupaten Lombok Utara');
        $response->assertSee('Kalender Tanam');
        $response->assertSee(now()->translatedFormat('F Y'));
        $response->assertDontSee('Kelola di dashboard admin');
        $response->assertSee('Padi IR-64');
        $response->assertSee('NPK Mutiara');
        $response->assertSee('Abamektin');
        $response->assertSee('Berita Pertanian');
        $response->assertSee('Harga Gabah Naik di Lombok Utara');
        $response->assertSee('Antara News');
    }
}
