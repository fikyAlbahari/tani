@extends('layouts.app', ['title' => 'Dashboard', 'subtitle' => 'Ringkasan kegiatan pertanian hari ini'])

@section('content')
    {{-- Statistik --}}
    @php
        $statCards = [
            ['icon' => 'bi-calendar2-week-fill', 'from' => '#38bdf8', 'to' => '#0284c7', 'value' => $stats['total_tanam'], 'label' => 'Total Jadwal Tanam'],
            ['icon' => 'bi-flower2', 'from' => '#46cb88', 'to' => '#148f53', 'value' => $stats['aktif'], 'label' => 'Tanaman Aktif'],
            ['icon' => 'bi-basket3-fill', 'from' => '#34d399', 'to' => '#059669', 'value' => $stats['panen_bulan_ini'], 'label' => 'Panen Bulan Ini'],
            ['icon' => 'bi-droplet-fill', 'from' => '#fbbf24', 'to' => '#f97316', 'value' => $stats['pemupukan_bulan_ini'], 'label' => 'Pemupukan Bulan Ini'],
            ['icon' => 'bi-shield-fill-check', 'from' => '#fb7185', 'to' => '#e11d48', 'value' => $stats['penyemprotan_bulan_ini'], 'label' => 'Penyemprotan Bulan Ini', 'wide' => true],
            ['icon' => 'bi-wallet2', 'from' => '#2dd4bf', 'to' => '#0d9488', 'value' => 'Rp '.number_format((float) $stats['biaya_bulan_ini'], 0, ',', '.'), 'label' => 'Biaya Bulan Ini', 'money' => true],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3 2xl:grid-cols-6">
        @foreach ($statCards as $card)
            @php($isMoney = $card['money'] ?? false)
            @php($isWide = ($card['wide'] ?? false) || $isMoney)
            <div class="card group flex min-w-0 gap-2.5 p-3.5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_22px_46px_-26px_rgba(16,35,26,.45)] sm:flex-row sm:items-center sm:gap-3.5 sm:p-4 {{ $isWide ? 'col-span-2 flex-row items-center sm:col-span-1' : 'flex-col' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-[15px] text-white ring-1 ring-white/30 transition duration-300 group-hover:scale-105 sm:h-11 sm:w-11 sm:rounded-2xl sm:text-[17px]"
                      style="background-image: linear-gradient(135deg, {{ $card['from'] }}, {{ $card['to'] }}); box-shadow: 0 12px 22px -14px {{ $card['to'] }};">
                    <i class="bi {{ $card['icon'] }}"></i>
                </span>
                <div class="min-w-0">
                    <strong class="block truncate font-extrabold leading-none tracking-tight text-ink {{ $isMoney ? 'text-[17px] sm:text-[18px]' : 'text-[20px] sm:text-[24px]' }}">
                        {{ $card['value'] }}
                    </strong>
                    <span class="mt-1 block text-[10.5px] font-semibold leading-tight text-ink/45 sm:mt-1.5 sm:text-[12px]">{{ $card['label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Cuaca + Kalender --}}
    <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-5">
        <div class="flex flex-col gap-5 xl:col-span-2">
            <section class="relative overflow-hidden rounded-3xl bg-[linear-gradient(150deg,#38bdf8_0%,#2563eb_48%,#4338ca_100%)] p-5 text-white shadow-[0_28px_60px_-30px_rgba(37,99,235,.9)] sm:p-6">
                <div class="pointer-events-none absolute -top-24 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-28 -left-16 h-64 w-64 rounded-full bg-white/5 blur-2xl"></div>

                <div class="relative flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold tracking-[.12em] text-white/70 uppercase">Cuaca Hari Ini</p>
                        <p class="mt-1 flex items-center gap-1.5 text-[14.5px] font-extrabold">
                            <i class="bi bi-geo-alt-fill shrink-0 text-white/80"></i>
                            <span class="line-clamp-2 leading-snug">{{ $weather['place'] ?? 'Lokasi' }}</span>
                        </p>
                        @if (!empty($weather['fetched_at']))
                            <p class="mt-0.5 text-[11px] font-semibold text-white/55">
                                Diperbarui {{ \Carbon\Carbon::parse($weather['fetched_at'])->format('H:i') }} WITA
                            </p>
                        @endif
                    </div>
                    <div class="flex shrink-0 flex-col items-end gap-1.5">
                        <span class="whitespace-nowrap rounded-full bg-white/15 px-2.5 py-1 text-[10.5px] font-bold backdrop-blur">Live · Open-Meteo</span>
                        <a href="{{ request()->fullUrlWithQuery(['refresh' => 1]) }}"
                           class="whitespace-nowrap rounded-full bg-white/15 px-2.5 py-1 text-[10.5px] font-bold backdrop-blur transition hover:bg-white/30"
                           title="Perbarui data cuaca">
                            <i class="bi bi-arrow-clockwise"></i> Perbarui
                        </a>
                    </div>
                </div>

                @if (!($weather['available'] ?? false))
                    <div class="relative mt-8 mb-2 text-center">
                        <i class="bi bi-cloud-slash text-5xl text-white/70"></i>
                        <p class="mt-3 text-sm font-semibold text-white/80">Data cuaca tidak tersedia saat ini.</p>
                        <p class="text-[12px] text-white/60">Server cuaca tidak terjangkau — coba muat ulang halaman.</p>
                    </div>
                @else
                    @if (!empty($weather['stale']))
                        <div class="relative mt-4 flex items-center gap-2 rounded-2xl bg-amber-400/20 px-3 py-2 text-[12px] font-bold ring-1 ring-amber-200/40">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Data tersimpan terakhir — server cuaca tidak terjangkau.
                        </div>
                    @endif

                    <div class="relative mt-5 flex items-center gap-3 sm:mt-6 sm:gap-4">
                        <i class="bi {{ $weather['current']['icon'] }} shrink-0 text-[48px] leading-none text-white drop-shadow-[0_8px_20px_rgba(255,255,255,.45)] sm:text-[64px]"></i>
                        <div class="min-w-0">
                            <p class="text-[38px] font-light leading-none tracking-tight sm:text-[48px]">
                                {{ $weather['current']['temp'] }}<span class="align-top text-lg font-medium text-white/70 sm:text-xl">°C</span>
                            </p>
                            <p class="mt-1.5 truncate text-[14px] font-bold">{{ $weather['current']['label'] }}</p>
                            <p class="text-[12px] text-white/65">Terasa seperti {{ $weather['current']['feels'] }}°C</p>
                        </div>
                    </div>

                    <div class="relative mt-5 grid grid-cols-3 gap-2.5">
                        <div class="rounded-2xl bg-white/12 px-2 py-2.5 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-droplet-fill text-[13px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['humidity'] }}%</p>
                            <p class="text-[10px] font-semibold text-white/60">Kelembapan</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 px-2 py-2.5 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-wind text-[13px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['wind'] }}</p>
                            <p class="text-[10px] font-semibold text-white/60">km/jam</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 px-2 py-2.5 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-cloud-rain-heavy text-[13px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['precipitation'] }}</p>
                            <p class="text-[10px] font-semibold text-white/60">mm hujan</p>
                        </div>
                    </div>
                @endif
            </section>

            <section class="card flex flex-1 flex-col p-4 sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="flex items-center gap-2 text-[13.5px] font-extrabold tracking-tight">
                        <i class="bi bi-cloud-sun text-sky-500"></i> Prakiraan 4 Hari
                    </h2>
                    <span class="text-[10.5px] font-bold tracking-wide text-ink/35 uppercase">Open-Meteo</span>
                </div>

                @if (!empty($weather['days']))
                    <div class="mt-4 grid flex-1 grid-cols-2 content-center gap-2 sm:grid-cols-4">
                        @foreach ($weather['days'] as $i => $day)
                            <div class="rounded-2xl border border-ink/[.06] bg-canvas/70 p-3 text-center transition hover:border-sky-200 hover:bg-sky-50/60">
                                <p class="truncate text-[10px] font-extrabold tracking-wide text-ink/40 uppercase">
                                    {{ $i === 0 ? 'Kini' : \App\Services\WeatherService::dayName($day['date']) }}
                                </p>
                                <i class="bi {{ $day['icon'] }} my-2 block text-2xl text-sky-600"></i>
                                <p class="text-[13px] font-extrabold text-ink">
                                    {{ $day['max'] }}°<span class="font-semibold text-ink/35">/{{ $day['min'] }}°</span>
                                </p>
                                <p class="mt-0.5 text-[10px] font-semibold text-ink/40">
                                    <i class="bi bi-umbrella"></i> {{ $day['rain_prob'] }}%
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-1 items-center justify-center py-6 text-center">
                        <p class="text-[12.5px] font-semibold text-ink/40">Prakiraan belum tersedia.</p>
                    </div>
                @endif
            </section>
        </div>

        <section class="card overflow-hidden xl:col-span-3">
            <div class="flex flex-col gap-3 border-b border-ink/[.07] px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div>
                    <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                        <i class="bi bi-calendar3-fill text-brand-600"></i> Kalender Tanam
                    </h2>
                    <p class="text-[12px] font-semibold text-ink/40">Klik event untuk membuka data terkait<span class="sm:hidden"> · geser ke samping</span></p>
                </div>
                <div class="flex items-center justify-between gap-2 sm:justify-end">
                    <a href="{{ request()->fullUrlWithQuery(['bulan' => $calendar['prev']['bulan'], 'tahun' => $calendar['prev']['tahun']]) }}"
                       class="icon-btn" aria-label="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></a>
                    <span class="min-w-[126px] text-center text-[14px] font-extrabold capitalize">{{ $calendar['title'] }}</span>
                    <a href="{{ request()->fullUrlWithQuery(['bulan' => $calendar['next']['bulan'], 'tahun' => $calendar['next']['tahun']]) }}"
                       class="icon-btn" aria-label="Bulan berikutnya"><i class="bi bi-chevron-right"></i></a>
                </div>
            </div>

            <div class="p-4 sm:p-5">
                <div class="-mx-1 overflow-x-auto px-1 pb-1">
                    <div class="min-w-[520px]">
                        <div class="grid grid-cols-7 gap-1.5 pb-2">
                            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                                <span class="text-center text-[10px] font-extrabold tracking-[.06em] text-ink/35 uppercase">{{ $weekday }}</span>
                            @endforeach
                        </div>

                        <div class="space-y-1.5">
                            @foreach ($calendar['rows'] as $row)
                                <div class="grid grid-cols-7 gap-1.5">
                                    @foreach ($row as $cell)
                                        @if ($cell === null)
                                            <div class="min-h-[66px] rounded-xl"></div>
                                        @else
                                            <div class="min-h-[66px] rounded-xl border p-1.5 transition
                                                {{ $cell['is_today']
                                                    ? 'border-brand-500 bg-brand-50/70 ring-2 ring-brand-500/40'
                                                    : ($cell['is_weekend'] ? 'border-ink/[.04] bg-ink/[.015]' : 'border-ink/[.06] bg-white') }}
                                                hover:border-brand-300">
                                                <span class="block text-[11px] font-extrabold {{ $cell['is_today'] ? 'text-brand-700' : 'text-ink/40' }}">
                                                    {{ $cell['day'] }}
                                                </span>

                                                @foreach (array_slice($cell['events'], 0, 2) as $event)
                                                    <a href="{{ $event['url'] }}" class="event-chip event-{{ $event['type'] }} mt-1 block truncate !border-l-2 !px-1 !text-[9px]" title="{{ $event['label'] }}">
                                                        {{ explode(' ', $event['label'])[0] }}
                                                    </a>
                                                @endforeach

                                                @if (count($cell['events']) > 2)
                                                    <span class="mt-1 block text-[9.5px] font-bold text-ink/40">+{{ count($cell['events']) - 2 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 border-t border-ink/[.06] pt-3.5 text-[11.5px] font-bold text-ink/50">
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-sky-500"></i> Tanam</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-brand-500"></i> Panen</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-amber-400"></i> Pupuk</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-rose-400"></i> Semprot</span>
                </div>
            </div>
        </section>
    </div>

    {{-- Jadwal Tanam, Pemupukan & Penyemprotan --}}
    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        <section class="card flex flex-col overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-4 py-3.5">
                <div class="flex min-w-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white" style="background-image: linear-gradient(135deg,#38bdf8,#0284c7); box-shadow: 0 10px 18px -12px #0284c7;">
                        <i class="bi bi-calendar2-week-fill text-[15px]"></i>
                    </span>
                    <div class="min-w-0">
                        <h2 class="truncate text-[14px] font-extrabold tracking-tight">Jadwal Tanam</h2>
                        <p class="truncate text-[11px] font-semibold text-ink/40">{{ $recentTanam->count() }} data terbaru</p>
                    </div>
                </div>
                <a href="{{ route('jadwal.index') }}" class="shrink-0 text-[12px] font-extrabold text-brand-700 hover:underline">Semua →</a>
            </div>

            <ul class="flex-1 divide-y divide-ink/[.06]">
                @forelse ($recentTanam as $row)
                    <li class="flex items-center gap-3 px-4 py-3 transition hover:bg-sky-50/50">
                        <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100">
                            <span class="text-[13px] font-extrabold leading-none">{{ $row->tanggal_tanam?->format('d') ?? '-' }}</span>
                            <span class="mt-0.5 text-[8.5px] font-extrabold uppercase">{{ $row->tanggal_tanam?->translatedFormat('M') ?? '' }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-extrabold text-ink">{{ $row->nama_tanaman }}</p>
                            <p class="truncate text-[11px] font-semibold text-ink/45">{{ $row->varietas_benih ?: 'Benih -' }}</p>
                        </div>
                        <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                    </li>
                @empty
                    <li class="px-4 py-9 text-center">
                        <p class="text-[12.5px] font-semibold text-ink/45">Belum ada jadwal tanam.</p>
                        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </li>
                @endforelse
            </ul>

            <a href="{{ route('jadwal.index') }}" class="mt-auto flex items-center justify-between border-t border-ink/[.06] bg-ink/[.015] px-4 py-2.5 text-[11.5px] font-extrabold text-ink/45 transition hover:bg-sky-50 hover:text-sky-700">Kelola jadwal tanam <i class="bi bi-arrow-right"></i></a>
        </section>

        <section class="card flex flex-col overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-4 py-3.5">
                <div class="flex min-w-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white" style="background-image: linear-gradient(135deg,#fbbf24,#f97316); box-shadow: 0 10px 18px -12px #f97316;">
                        <i class="bi bi-droplet-fill text-[15px]"></i>
                    </span>
                    <div class="min-w-0">
                        <h2 class="truncate text-[14px] font-extrabold tracking-tight">Pemupukan</h2>
                        <p class="truncate text-[11px] font-semibold text-ink/40">{{ $recentPemupukan->count() }} data terbaru</p>
                    </div>
                </div>
                <a href="{{ route('pemupukan.index') }}" class="shrink-0 text-[12px] font-extrabold text-brand-700 hover:underline">Semua →</a>
            </div>

            <ul class="flex-1 divide-y divide-ink/[.06]">
                @forelse ($recentPemupukan as $row)
                    <li class="flex items-center gap-3 px-4 py-3 transition hover:bg-amber-50/50">
                        <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                            <span class="text-[13px] font-extrabold leading-none">{{ $row->tanggal->format('d') }}</span>
                            <span class="mt-0.5 text-[8.5px] font-extrabold uppercase">{{ $row->tanggal->translatedFormat('M') }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-extrabold text-ink">{{ $row->nama_tanaman }}</p>
                            <p class="truncate text-[11px] font-semibold text-ink/45">{{ $row->jenis_pupuk }}{{ $row->dosis ? ' · '.$row->dosis : '' }}</p>
                        </div>
                        <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                    </li>
                @empty
                    <li class="px-4 py-9 text-center">
                        <p class="text-[12.5px] font-semibold text-ink/45">Belum ada jadwal pemupukan.</p>
                        <a href="{{ route('pemupukan.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </li>
                @endforelse
            </ul>

            <a href="{{ route('pemupukan.index') }}" class="mt-auto flex items-center justify-between border-t border-ink/[.06] bg-ink/[.015] px-4 py-2.5 text-[11.5px] font-extrabold text-ink/45 transition hover:bg-amber-50 hover:text-amber-700">Kelola pemupukan <i class="bi bi-arrow-right"></i></a>
        </section>

        <section class="card flex flex-col overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-4 py-3.5">
                <div class="flex min-w-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white" style="background-image: linear-gradient(135deg,#fb7185,#e11d48); box-shadow: 0 10px 18px -12px #e11d48;">
                        <i class="bi bi-shield-fill-check text-[15px]"></i>
                    </span>
                    <div class="min-w-0">
                        <h2 class="truncate text-[14px] font-extrabold tracking-tight">Penyemprotan</h2>
                        <p class="truncate text-[11px] font-semibold text-ink/40">{{ $recentPenyemprotan->count() }} data terbaru</p>
                    </div>
                </div>
                <a href="{{ route('penyemprotan.index') }}" class="shrink-0 text-[12px] font-extrabold text-brand-700 hover:underline">Semua →</a>
            </div>

            <ul class="flex-1 divide-y divide-ink/[.06]">
                @forelse ($recentPenyemprotan as $row)
                    <li class="flex items-center gap-3 px-4 py-3 transition hover:bg-rose-50/50">
                        <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100">
                            <span class="text-[13px] font-extrabold leading-none">{{ $row->tanggal->format('d') }}</span>
                            <span class="mt-0.5 text-[8.5px] font-extrabold uppercase">{{ $row->tanggal->translatedFormat('M') }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-extrabold text-ink">{{ $row->nama_tanaman }}</p>
                            <p class="truncate text-[11px] font-semibold text-ink/45">{{ $row->jenis_pestisida }}{{ $row->target_hama ? ' · '.$row->target_hama : '' }}</p>
                        </div>
                        <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                    </li>
                @empty
                    <li class="px-4 py-9 text-center">
                        <p class="text-[12.5px] font-semibold text-ink/45">Belum ada jadwal penyemprotan.</p>
                        <a href="{{ route('penyemprotan.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </li>
                @endforelse
            </ul>

            <a href="{{ route('penyemprotan.index') }}" class="mt-auto flex items-center justify-between border-t border-ink/[.06] bg-ink/[.015] px-4 py-2.5 text-[11.5px] font-extrabold text-ink/45 transition hover:bg-rose-50 hover:text-rose-600">Kelola penyemprotan <i class="bi bi-arrow-right"></i></a>
        </section>
    </div>

    {{-- Biaya per Sekali Tanam --}}
    <section class="card mt-5 overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-ink/[.07] px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white" style="background-image: linear-gradient(135deg,#2dd4bf,#0d9488); box-shadow: 0 10px 18px -12px #0d9488;">
                    <i class="bi bi-wallet2 text-[15px]"></i>
                </span>
                <div>
                    <h2 class="text-[15px] font-extrabold tracking-tight">Biaya per Sekali Tanam</h2>
                    <p class="text-[12px] font-semibold text-ink/40">Total pengeluaran tiap bulan tanam (masa tanam 4 bulan)</p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:shrink-0">
                <span class="badge badge-muted">{{ $biayaPerBulanTanam->count() }} siklus</span>
                <a href="{{ route('biaya.index') }}" class="text-[12px] font-extrabold text-brand-700 hover:underline">Kelola →</a>
            </div>
        </div>

        @if ($biayaPerBulanTanam->isEmpty())
            <div class="px-5 py-10 text-center">
                <i class="bi bi-wallet2 text-4xl text-ink/20"></i>
                <p class="mt-2 text-sm font-semibold text-ink/45">Belum ada catatan biaya tanam.</p>
                <a href="{{ route('biaya.create') }}" class="btn btn-primary btn-sm mt-4"><i class="bi bi-plus-lg"></i> Tambah Biaya</a>
            </div>
        @else
            <div class="grid gap-3 p-4 sm:grid-cols-2 sm:p-5 xl:grid-cols-3">
                @foreach ($biayaPerBulanTanam as $siklus)
                    <div class="rounded-2xl border border-ink/[.06] bg-canvas/70 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                        <div class="flex items-start justify-between gap-2">
                            <p class="truncate text-[13px] font-extrabold text-ink" title="{{ $siklus['label'] }}">{{ $siklus['label'] }}</p>
                            <span class="badge badge-muted shrink-0">{{ \App\Models\Biaya::DURASI_BULAN }} bulan</span>
                        </div>
                        <p class="mt-2 text-[20px] font-extrabold tracking-tight text-emerald-700">
                            Rp {{ number_format($siklus['total'], 0, ',', '.') }}
                        </p>
                        <p class="mt-1 flex items-center gap-1.5 truncate text-[11px] font-semibold text-ink/40">
                            <i class="bi bi-calendar-range"></i>
                            <span class="truncate">{{ $siklus['periode'] }}</span>
                        </p>
                        <p class="mt-0.5 flex items-center gap-1.5 truncate text-[11px] font-semibold text-ink/40">
                            <i class="bi bi-flower2"></i>
                            <span class="truncate">{{ $siklus['tanaman'] ?: '-' }}</span>
                        </p>
                        <p class="mt-0.5 flex items-center gap-1.5 text-[11px] font-semibold text-ink/35">
                            <i class="bi bi-clock-history"></i> {{ $siklus['jumlah_catatan'] }} catatan · terakhir {{ $siklus['tanggal'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Agenda + Berita --}}
    <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-5">
        <section class="card xl:col-span-2">
            <div class="flex items-center justify-between gap-2 border-b border-ink/[.07] px-4 py-4 sm:px-5">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-clock-history text-brand-600"></i> Agenda 30 Hari
                </h2>
                <span class="badge badge-muted shrink-0">{{ count($upcoming) }} agenda</span>
            </div>

            <div class="p-4 sm:p-5">
                @if (empty($upcoming))
                    <div class="py-8 text-center">
                        <i class="bi bi-calendar2-check text-4xl text-ink/20"></i>
                        <p class="mt-2 text-sm font-semibold text-ink/45">Belum ada agenda pada 30 hari ke depan.</p>
                        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm mt-4"><i class="bi bi-plus-lg"></i> Tambah Jadwal</a>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach ($upcoming as $item)
                            <li>
                                <a href="{{ $item['url'] }}" class="group flex items-center gap-3 rounded-2xl border border-transparent p-2.5 transition hover:border-ink/[.06] hover:bg-brand-50/50 sm:gap-3.5">
                                    <span class="event-{{ $item['kind'] }} flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl border-l-[3px] bg-ink/[.03] leading-none">
                                        <span class="text-[13px] font-extrabold text-ink">{{ \Carbon\Carbon::parse($item['date'])->format('d') }}</span>
                                        <span class="mt-0.5 text-[9.5px] font-bold text-ink/45 uppercase">{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('M') }}</span>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-[13.5px] font-bold text-ink group-hover:text-brand-800">{{ $item['label'] }}</span>
                                        <span class="block truncate text-[12px] font-medium text-ink/45">{{ $item['detail'] }}</span>
                                        <span class="mt-1 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-[11.5px] font-bold text-brand-700">
                                            <i class="bi bi-calendar2-event"></i>
                                            {{ $item['date_label'] }}
                                            <span class="font-semibold text-ink/40">· {{ $item['relative'] }}</span>
                                            <span class="badge badge-{{ \App\Models\JadwalTanam::badgeFor($item['status']) }} sm:hidden">{{ $item['status'] }}</span>
                                        </span>
                                    </span>
                                    <span class="badge badge-{{ \App\Models\JadwalTanam::badgeFor($item['status']) }} hidden shrink-0 sm:inline-flex">{{ $item['status'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        <section class="card xl:col-span-3">
            <div class="flex items-center justify-between gap-2 border-b border-ink/[.07] px-4 py-4 sm:px-5">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-newspaper text-brand-600"></i> Berita Pertanian
                </h2>
                <div class="flex items-center gap-2">
                    <span class="badge badge-muted hidden sm:inline-flex"><i class="bi bi-arrow-clockwise"></i> otomatis</span>
                    <a href="{{ request()->fullUrlWithQuery(['refresh' => 1]) }}"
                       class="text-[12.5px] font-extrabold text-brand-700 hover:underline"
                       title="Perbarui berita">Perbarui</a>
                </div>
            </div>

            <ul class="divide-y divide-ink/[.06] px-4 sm:px-5">
                @forelse ($news as $item)
                    <li>
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="group flex items-start gap-3.5 py-3.5 transition">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                                <i class="bi bi-megaphone-fill"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[13.8px] font-bold leading-snug text-ink transition group-hover:text-brand-700">
                                    {{ $item['title'] }}
                                </span>
                                <span class="mt-1 block text-[11.5px] font-semibold text-ink/40">
                                    {{ $item['source'] }} · {{ $item['published_at'] }}
                                </span>
                            </span>
                            <i class="bi bi-arrow-up-right mt-1 shrink-0 text-ink/25 transition group-hover:text-brand-600"></i>
                        </a>
                    </li>
                @empty
                    <li class="py-10 text-center">
                        <i class="bi bi-newspaper text-4xl text-ink/20"></i>
                        <p class="mt-2 text-sm font-semibold text-ink/45">Berita belum tersedia.</p>
                    </li>
                @endforelse
            </ul>
        </section>
    </div>

@endsection
