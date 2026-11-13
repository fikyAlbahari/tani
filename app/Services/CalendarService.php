<?php

namespace App\Services;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    /**
     * Bangun data kalender bulanan berisi event tanam, panen, pupuk, dan semprot.
     *
     * @param  Collection<int, JadwalTanam>  $tanams
     * @param  Collection<int, Pemupukan>  $pemupukans
     * @param  Collection<int, Penyemprotan>  $penyemprotans
     * @param  array<string, string>  $urls  URL tujuan per jenis event (tanam, panen, pupuk, semprot).
     * @return array{title:string,rows:array<int,array<int,array<string,mixed>|null>>,prev:array{bulan:int,tahun:int},next:array{bulan:int,tahun:int}}
     */
    public function build(Carbon $start, Collection $tanams, Collection $pemupukans, Collection $penyemprotans, array $urls = []): array
    {
        $urls += [
            'tanam' => route('jadwal.index'),
            'panen' => route('jadwal.index'),
            'pupuk' => route('pemupukan.index'),
            'semprot' => route('penyemprotan.index'),
        ];

        $events = [];

        foreach ($tanams as $t) {
            if ($t->tanggal_tanam) {
                $events[$t->tanggal_tanam->format('Y-m-d')][] = [
                    'type' => 'tanam',
                    'label' => 'Tanam '.$t->nama_tanaman,
                    'url' => $urls['tanam'],
                ];
            }
            if ($t->perkiraan_panen) {
                $events[$t->perkiraan_panen->format('Y-m-d')][] = [
                    'type' => 'panen',
                    'label' => 'Panen '.$t->nama_tanaman,
                    'url' => $urls['panen'],
                ];
            }
        }

        foreach ($pemupukans as $p) {
            $events[$p->tanggal->format('Y-m-d')][] = [
                'type' => 'pupuk',
                'label' => 'Pupuk '.$p->nama_tanaman,
                'url' => $urls['pupuk'],
            ];
        }

        foreach ($penyemprotans as $p) {
            $events[$p->tanggal->format('Y-m-d')][] = [
                'type' => 'semprot',
                'label' => 'Semprot '.$p->nama_tanaman,
                'url' => $urls['semprot'],
            ];
        }

        $daysInMonth = $start->daysInMonth;
        $leading = ($start->dayOfWeekIso - 1);

        $cells = [];
        for ($i = 0; $i < $leading; $i++) {
            $cells[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $start->copy()->addDays($day - 1);
            $key = $date->format('Y-m-d');

            $cells[] = [
                'day' => $day,
                'date' => $key,
                'is_today' => $date->isToday(),
                'is_weekend' => $date->dayOfWeek === 0 || $date->dayOfWeek === 6,
                'events' => $events[$key] ?? [],
            ];
        }

        while (count($cells) % 7 !== 0) {
            $cells[] = null;
        }

        $prev = $start->copy()->subMonth();
        $next = $start->copy()->addMonth();

        return [
            'title' => $start->translatedFormat('F Y'),
            'rows' => array_chunk($cells, 7),
            'prev' => ['bulan' => $prev->month, 'tahun' => $prev->year],
            'next' => ['bulan' => $next->month, 'tahun' => $next->year],
        ];
    }
}
