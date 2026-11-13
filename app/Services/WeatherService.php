<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherService
{
    private const CACHE_KEY = 'weather.current';

    private const FRESH_MINUTES = 30;

    private const KEEP_MINUTES = 360;

    private const CODES = [
        0 => ['Cerah', 'bi-sun'],
        1 => ['Cerah Berawan', 'bi-cloud-sun'],
        2 => ['Berawan Sebagian', 'bi-cloud-sun'],
        3 => ['Berawan Tebal', 'bi-cloud'],
        45 => ['Berkabut', 'bi-cloud-fog'],
        48 => ['Berkabut Embun Beku', 'bi-cloud-fog'],
        51 => ['Gerimis Ringan', 'bi-cloud-drizzle'],
        53 => ['Gerimis Sedang', 'bi-cloud-drizzle'],
        55 => ['Gerimis Lebat', 'bi-cloud-drizzle'],
        56 => ['Gerimis Beku', 'bi-cloud-drizzle'],
        57 => ['Gerimis Beku Lebat', 'bi-cloud-drizzle'],
        61 => ['Hujan Ringan', 'bi-cloud-rain'],
        63 => ['Hujan Sedang', 'bi-cloud-rain'],
        65 => ['Hujan Lebat', 'bi-cloud-rain-heavy'],
        66 => ['Hujan Beku', 'bi-cloud-rain'],
        67 => ['Hujan Beku Lebat', 'bi-cloud-rain-heavy'],
        71 => ['Salju Ringan', 'bi-cloud-snow'],
        73 => ['Salju Sedang', 'bi-cloud-snow'],
        75 => ['Salju Lebat', 'bi-cloud-snow'],
        77 => ['Butiran Salju', 'bi-cloud-snow'],
        80 => ['Hujan Petir Ringan', 'bi-cloud-lightning'],
        81 => ['Hujan Petir Sedang', 'bi-cloud-lightning'],
        82 => ['Hujan Petir Lebat', 'bi-cloud-lightning'],
        85 => ['Hujan Salju', 'bi-cloud-snow'],
        86 => ['Hujan Salju Lebat', 'bi-cloud-snow'],
        95 => ['Badai Petir', 'bi-cloud-lightning'],
        96 => ['Badai Petir & Hujan Es', 'bi-cloud-lightning'],
        99 => ['Badai Petir Lebat', 'bi-cloud-lightning'],
    ];

    public function current(bool $forceRefresh = false): array
    {
        $cached = Cache::get(self::CACHE_KEY);
        $cached = is_array($cached) ? $cached : null;

        if (! $forceRefresh && $cached !== null && $this->isFresh($cached)) {
            return $cached;
        }

        $fresh = $this->fetch();

        if ($fresh !== null) {
            Cache::put(self::CACHE_KEY, $fresh, now()->addMinutes(self::KEEP_MINUTES));

            return $fresh;
        }

        if ($cached !== null) {
            $cached['stale'] = true;

            return $cached;
        }

        return [
            'available' => false,
            'place' => config('weather.place'),
            'fetched_at' => null,
            'current' => null,
            'days' => [],
            'stale' => false,
        ];
    }

    private function isFresh(array $payload): bool
    {
        $fetchedAt = $payload['fetched_at'] ?? null;

        if (! is_string($fetchedAt) || $fetchedAt === '') {
            return false;
        }

        return Carbon::parse($fetchedAt)->greaterThan(now()->subMinutes(self::FRESH_MINUTES));
    }

    private function fetch(): ?array
    {
        $lat = config('weather.lat');
        $lon = config('weather.lon');
        $place = config('weather.place');

        $url = sprintf(
            'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s'.
            '&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m,wind_direction_10m,is_day'.
            '&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max'.
            '&timezone=Asia%%2FMakassar&forecast_days=4',
            $lat,
            $lon
        );

        try {
            $response = Http::timeout(6)->retry(2, 300, null, false)->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        if (! is_array($data) || ! isset($data['current'], $data['daily'])) {
            return null;
        }

        $current = $data['current'];
        $code = (int) ($current['weather_code'] ?? 0);

        $days = [];
        foreach (array_slice($data['daily']['time'] ?? [], 0, 4) as $i => $date) {
            $dCode = (int) ($data['daily']['weather_code'][$i] ?? 0);
            $days[] = [
                'date' => $date,
                'label' => $this->label($dCode),
                'icon' => $this->icon($dCode),
                'max' => round($data['daily']['temperature_2m_max'][$i] ?? 0),
                'min' => round($data['daily']['temperature_2m_min'][$i] ?? 0),
                'rain_prob' => (int) ($data['daily']['precipitation_probability_max'][$i] ?? 0),
            ];
        }

        return [
            'available' => true,
            'place' => $place,
            'fetched_at' => now()->toDateTimeString(),
            'stale' => false,
            'current' => [
                'temp' => round($current['temperature_2m'] ?? 0),
                'feels' => round($current['apparent_temperature'] ?? 0),
                'humidity' => (int) ($current['relative_humidity_2m'] ?? 0),
                'wind' => round($current['wind_speed_10m'] ?? 0, 1),
                'precipitation' => round($current['precipitation'] ?? 0, 1),
                'is_day' => (int) ($current['is_day'] ?? 1),
                'code' => $code,
                'label' => $this->label($code),
                'icon' => $this->icon($code),
            ],
            'days' => $days,
        ];
    }

    public function label(int $code): string
    {
        return self::CODES[$code][0] ?? 'Tidak Diketahui';
    }

    public function icon(int $code): string
    {
        return self::CODES[$code][1] ?? 'bi-cloud';
    }

    public static function dayName(string $date): string
    {
        $names = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return $names[(int) date('w', strtotime($date))];
    }
}
