<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class NewsService
{
    private const CACHE_KEY = 'news.latest';

    private const FRESH_MINUTES = 15;

    private const KEEP_MINUTES = 360;

    private const FALLBACK = [
        [
            'title' => 'Tips Memilih Benih Unggul untuk Musim Tanam',
            'url' => '#',
            'source' => 'Panduan Tani',
            'published_at' => 'Hari ini',
        ],
        [
            'title' => 'Jadwal Pemupukan NPK agar Hasil Panen Maksimal',
            'url' => '#',
            'source' => 'Panduan Tani',
            'published_at' => 'Kemarin',
        ],
        [
            'title' => 'Pengendalian Hama Ulat Grayak pada Tanaman Sayur',
            'url' => '#',
            'source' => 'Panduan Tani',
            'published_at' => '2 hari lalu',
        ],
    ];

    /**
     * @return array<int, array{title:string,url:string,source:string,published_at:string}>
     */
    public function latest(int $limit = 6, bool $forceRefresh = false): array
    {
        $cached = Cache::get(self::CACHE_KEY);
        $cached = is_array($cached) ? $cached : null;

        if (! $forceRefresh && $cached !== null && $this->isFresh($cached)) {
            return array_slice($cached, 0, $limit);
        }

        $fresh = $this->fetch();

        if ($fresh !== null) {
            Cache::put(self::CACHE_KEY, $fresh, now()->addMinutes(self::KEEP_MINUTES));

            return array_slice($fresh, 0, $limit);
        }

        if ($cached !== null) {
            return array_slice($cached, 0, $limit);
        }

        return array_slice(self::FALLBACK, 0, $limit);
    }

    /**
     * @param  array<int, array<string,mixed>>  $payload
     */
    private function isFresh(array $payload): bool
    {
        $fetchedAt = $payload[0]['fetched_at_iso'] ?? null;

        if (! is_string($fetchedAt) || $fetchedAt === '') {
            return false;
        }

        return Carbon::parse($fetchedAt)->greaterThan(now()->subMinutes(self::FRESH_MINUTES));
    }

    /**
     * @return array<int, array{title:string,url:string,source:string,published_at:string,published_at_iso:string}>|null
     */
    private function fetch(): ?array
    {
        $queries = [
            config('weather.news_query'),
            'harga komoditas pertanian Indonesia',
        ];

        foreach ($queries as $query) {
            $url = 'https://news.google.com/rss/search?q='.rawurlencode($query).'&hl=id&gl=ID&ceid=ID:id';

            try {
                $response = Http::timeout(6)->retry(2, 300, null, false)->get($url);
            } catch (Throwable) {
                continue;
            }

            if (! $response->successful()) {
                continue;
            }

            $xml = @simplexml_load_string($response->body());

            if ($xml === false || ! isset($xml->channel->item)) {
                continue;
            }

            $items = [];
            foreach ($xml->channel->item as $item) {
                $source = (string) ($item->source ?? 'Berita');
                $title = trim((string) $item->title);

                if (str_ends_with($title, ' - '.$source)) {
                    $title = trim(substr($title, 0, -strlen(' - '.$source)));
                }

                $items[] = [
                    'title' => $title,
                    'url' => (string) $item->link,
                    'source' => $source,
                    'published_at' => $this->humanDate((string) $item->pubDate),
                    'published_at_iso' => (string) ($item->pubDate ?? ''),
                ];
            }

            if ($items !== []) {
                return $items;
            }
        }

        return null;
    }

    private function humanDate(string $rssDate): string
    {
        $ts = strtotime($rssDate);

        if ($ts === false) {
            return '';
        }

        $diff = time() - $ts;

        if ($diff < 3600) {
            return max(1, (int) floor($diff / 60)).' menit lalu';
        }
        if ($diff < 86400) {
            return (int) floor($diff / 3600).' jam lalu';
        }
        if ($diff < 604800) {
            return (int) floor($diff / 86400).' hari lalu';
        }

        return date('d M Y', $ts);
    }
}
