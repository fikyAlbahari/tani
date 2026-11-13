<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use App\Models\Biaya;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use App\Services\CalendarService;
use App\Services\NewsService;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, WeatherService $weather, NewsService $news, CalendarService $calendar): View
    {
        $month = (int) $request->query('bulan', Carbon::now()->month);
        $year = (int) $request->query('tahun', Carbon::now()->year);
        $refresh = $request->boolean('refresh');

        $month = min(12, max(1, $month));
        $year = min(2100, max(2000, $year));

        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $tanams = JadwalTanam::orderBy('tanggal_tanam')->get();
        $pemupukans = Pemupukan::orderBy('tanggal')->get();
        $penyemprotans = Penyemprotan::orderBy('tanggal')->get();
        $biayas = Biaya::orderBy('tanggal')->get();

        return view('dashboard', [
            'stats' => $this->stats($tanams, $pemupukans, $penyemprotans, $biayas, $start, $end),
            'weather' => $weather->current($refresh),
            'news' => $news->latest(6, $refresh),
            'calendar' => $calendar->build($start, $tanams, $pemupukans, $penyemprotans),
            'upcoming' => $this->upcoming($tanams, $pemupukans, $penyemprotans),
            'recentTanam' => $tanams->sortByDesc('created_at')->take(5)->values(),
            'recentPemupukan' => $pemupukans->sortByDesc('created_at')->take(5)->values(),
            'recentPenyemprotan' => $penyemprotans->sortByDesc('created_at')->take(5)->values(),
            'biayaPerBulanTanam' => $this->biayaPerBulanTanam($biayas),
        ]);
    }

    private function stats(Collection $tanams, Collection $pemupukans, Collection $penyemprotans, Collection $biayas, Carbon $start, Carbon $end): array
    {
        $inMonth = fn (Carbon $date) => $date->between($start, $end);

        return [
            'total_tanam' => $tanams->count(),
            'aktif' => $tanams->where('status', '!=', 'Panen')->count(),
            'panen_bulan_ini' => $tanams->filter(fn ($t) => $t->perkiraan_panen && $inMonth($t->perkiraan_panen))->count(),
            'pemupukan_bulan_ini' => $pemupukans->filter(fn ($p) => $inMonth($p->tanggal))->count(),
            'penyemprotan_bulan_ini' => $penyemprotans->filter(fn ($p) => $inMonth($p->tanggal))->count(),
            'biaya_bulan_ini' => $biayas->filter(fn ($b) => $inMonth($b->tanggal))->sum(fn ($b) => (float) $b->jumlah),
        ];
    }

    /**
     * @return Collection<int, array{bulan:?string,label:string,periode:string,total:float,jumlah_catatan:int,tanaman:string,tanggal:string,latest:int}>
     */
    private function biayaPerBulanTanam(Collection $biayas): Collection
    {
        return $biayas
            ->groupBy(fn (Biaya $b) => (string) $b->bulan_tanam)
            ->map(function (Collection $rows, string $bulan) {
                $latest = $rows->sortByDesc(fn (Biaya $b) => $b->tanggal?->timestamp ?? 0)->first()?->tanggal;
                $bulan = $bulan !== '' ? $bulan : null;

                return [
                    'bulan' => $bulan,
                    'label' => Biaya::bulanLabelFor($bulan),
                    'periode' => Biaya::periodeLabelFor($bulan),
                    'total' => (float) $rows->sum(fn (Biaya $b) => (float) $b->jumlah),
                    'jumlah_catatan' => $rows->count(),
                    'tanaman' => $rows->pluck('nama_tanaman')->filter()->unique()->implode(', '),
                    'tanggal' => $latest?->translatedFormat('d M Y') ?? '-',
                    'latest' => $latest?->timestamp ?? 0,
                ];
            })
            ->sortByDesc(fn (array $item) => $item['bulan'] ?? '')
            ->values();
    }

    /**
     * @return array<int, array{date:string,kind:string,label:string,detail:string,status:string,url:string,date_label:string,relative:string}>
     */
    private function upcoming(Collection $tanams, Collection $pemupukans, Collection $penyemprotans): array
    {
        $today = Carbon::today();
        $limit = $today->copy()->addDays(30);

        $items = [];

        foreach ($tanams as $t) {
            if ($t->tanggal_tanam && $t->tanggal_tanam->between($today, $limit)) {
                $items[] = [
                    'date' => $t->tanggal_tanam->format('Y-m-d'),
                    'kind' => 'tanam',
                    'label' => 'Tanam '.$t->nama_tanaman,
                    'detail' => 'Benih: '.($t->varietas_benih ?? '-'),
                    'status' => $t->status,
                    'url' => route('jadwal.index'),
                ];
            }
            if ($t->perkiraan_panen && $t->perkiraan_panen->between($today, $limit)) {
                $items[] = [
                    'date' => $t->perkiraan_panen->format('Y-m-d'),
                    'kind' => 'panen',
                    'label' => 'Panen '.$t->nama_tanaman,
                    'detail' => 'Luas: '.($t->luas_lahan ? $t->luas_lahan.' ha' : '-'),
                    'status' => $t->status,
                    'url' => route('jadwal.index'),
                ];
            }
        }

        foreach ($pemupukans as $p) {
            if ($p->tanggal->between($today, $limit)) {
                $items[] = [
                    'date' => $p->tanggal->format('Y-m-d'),
                    'kind' => 'pupuk',
                    'label' => 'Pemupukan '.$p->nama_tanaman,
                    'detail' => $p->jenis_pupuk.($p->dosis ? ' · '.$p->dosis : ''),
                    'status' => $p->status,
                    'url' => route('pemupukan.index'),
                ];
            }
        }

        foreach ($penyemprotans as $p) {
            if ($p->tanggal->between($today, $limit)) {
                $items[] = [
                    'date' => $p->tanggal->format('Y-m-d'),
                    'kind' => 'semprot',
                    'label' => 'Penyemprotan '.$p->nama_tanaman,
                    'detail' => $p->jenis_pestisida.($p->target_hama ? ' · '.$p->target_hama : ''),
                    'status' => $p->status,
                    'url' => route('penyemprotan.index'),
                ];
            }
        }

        usort($items, fn ($a, $b) => strcmp($a['date'], $b['date']));

        foreach ($items as &$item) {
            $date = Carbon::parse($item['date']);
            $item['date_label'] = $date->translatedFormat('l, d F Y');
            $item['relative'] = $this->relativeLabel($date, $today);
        }
        unset($item);

        return array_slice($items, 0, 8);
    }

    private function relativeLabel(Carbon $date, Carbon $today): string
    {
        $diff = (int) $today->copy()->startOfDay()->diffInDays($date->copy()->startOfDay(), false);

        if ($diff === 0) {
            return 'Hari ini';
        }

        if ($diff === 1) {
            return 'Besok';
        }

        if ($diff < 0) {
            return 'Terlambat';
        }

        return $diff.' hari lagi';
    }
}
