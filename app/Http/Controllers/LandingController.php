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

        // Hanya tampilkan jadwal dalam 4 bulan terakhir (termasuk jadwal yang akan datang).
        $sejak = Carbon::now()->subMonthsNoOverflow(4)->startOfDay();

        $tanams = JadwalTanam::query()->orderByDesc('tanggal_tanam')->get();
        $pemupukans = Pemupukan::query()->orderByDesc('tanggal')->get();
        $penyemprotans = Penyemprotan::query()->orderByDesc('tanggal')->get();

        $jadwalTanams = $tanams
            ->filter(fn (JadwalTanam $row) => $row->tanggal_tanam && $row->tanggal_tanam->greaterThanOrEqualTo($sejak))
            ->values();

        $pemupukanTerbaru = $pemupukans
            ->filter(fn (Pemupukan $row) => $row->tanggal && $row->tanggal->greaterThanOrEqualTo($sejak))
            ->values();

        $penyemprotanTerbaru = $penyemprotans
            ->filter(fn (Penyemprotan $row) => $row->tanggal && $row->tanggal->greaterThanOrEqualTo($sejak))
            ->values();

        return view('landing', [
            'weather' => $weather->current($refresh),
            'jadwalTanams' => $jadwalTanams,
            'pemupukans' => $pemupukanTerbaru,
            'urutanPemupukan' => Pemupukan::urutanUntuk($pemupukans),
            'penyemprotans' => $penyemprotanTerbaru,
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
