<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use App\Services\CalendarService;
use App\Services\NewsService;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(Request $request, WeatherService $weather, NewsService $news, CalendarService $calendar): View
    {
        $refresh = $request->boolean('refresh');

        $month = min(12, max(1, (int) $request->query('bulan', Carbon::now()->month)));
        $year = min(2100, max(2000, (int) $request->query('tahun', Carbon::now()->year)));
        $start = Carbon::create($year, $month, 1);

        $tanams = JadwalTanam::query()->orderByDesc('tanggal_tanam')->get();
        $pemupukans = Pemupukan::query()->orderByDesc('tanggal')->get();
        $penyemprotans = Penyemprotan::query()->orderByDesc('tanggal')->get();

        return view('landing', [
            'weather' => $weather->current($refresh),
            'jadwalTanams' => $tanams->take(4),
            'pemupukans' => $pemupukans->take(4),
            'penyemprotans' => $penyemprotans->take(4),
            'calendar' => $calendar->build($start, $tanams, $pemupukans, $penyemprotans, [
                'tanam' => route('login'),
                'panen' => route('login'),
                'pupuk' => route('login'),
                'semprot' => route('login'),
            ]),
            'news' => $news->latest(6, $refresh),
        ]);
    }
}
