@extends('layouts.app', ['title' => 'Dashboard', 'subtitle' => 'Ringkasan kegiatan pertanian hari ini'])

@section('content')
    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="card group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-[0_20px_50px_-24px_rgba(16,35,26,.35)]">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-xl text-sky-600 transition group-hover:scale-105">
                <i class="bi bi-calendar2-week-fill"></i>
            </span>
            <div>
                <strong class="block text-[26px] font-extrabold leading-none tracking-tight text-ink">{{ $stats['total_tanam'] }}</strong>
                <span class="mt-1 block text-[12.5px] font-semibold text-ink/45">Total Jadwal Tanam</span>
            </div>
        </div>

        <div class="card group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-[0_20px_50px_-24px_rgba(16,35,26,.35)]">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-100 text-xl text-brand-700 transition group-hover:scale-105">
                <i class="bi bi-flower2"></i>
            </span>
            <div>
                <strong class="block text-[26px] font-extrabold leading-none tracking-tight text-ink">{{ $stats['aktif'] }}</strong>
                <span class="mt-1 block text-[12.5px] font-semibold text-ink/45">Tanaman Aktif</span>
            </div>
        </div>

        <div class="card group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-[0_20px_50px_-24px_rgba(16,35,26,.35)]">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-xl text-emerald-600 transition group-hover:scale-105">
                <i class="bi bi-basket3-fill"></i>
            </span>
            <div>
                <strong class="block text-[26px] font-extrabold leading-none tracking-tight text-ink">{{ $stats['panen_bulan_ini'] }}</strong>
                <span class="mt-1 block text-[12.5px] font-semibold text-ink/45">Panen Bulan Ini</span>
            </div>
        </div>

        <div class="card group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-[0_20px_50px_-24px_rgba(16,35,26,.35)]">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-600 transition group-hover:scale-105">
                <i class="bi bi-droplet-fill"></i>
            </span>
            <div>
                <strong class="block text-[26px] font-extrabold leading-none tracking-tight text-ink">{{ $stats['pemupukan_bulan_ini'] }}</strong>
                <span class="mt-1 block text-[12.5px] font-semibold text-ink/45">Pemupukan Bulan Ini</span>
            </div>
        </div>

        <div class="card group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-[0_20px_50px_-24px_rgba(16,35,26,.35)]">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-xl text-rose-500 transition group-hover:scale-105">
                <i class="bi bi-bug-fill"></i>
            </span>
            <div>
                <strong class="block text-[26px] font-extrabold leading-none tracking-tight text-ink">{{ $stats['penyemprotan_bulan_ini'] }}</strong>
                <span class="mt-1 block text-[12.5px] font-semibold text-ink/45">Penyemprotan Bulan Ini</span>
            </div>
        </div>
    </div>

    {{-- Cuaca + Kalender --}}
    <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-5">
        <section class="xl:col-span-2">
            <div class="relative h-full overflow-hidden rounded-3xl bg-[linear-gradient(150deg,#38bdf8_0%,#2563eb_48%,#4338ca_100%)] p-6 text-white shadow-[0_28px_60px_-30px_rgba(37,99,235,.9)]">
                <div class="pointer-events-none absolute -top-24 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-28 -left-16 h-64 w-64 rounded-full bg-white/5 blur-2xl"></div>

                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[12.5px] font-bold tracking-wide text-white/70 uppercase">Cuaca Hari Ini</p>
                        <p class="mt-0.5 flex items-center gap-1.5 text-[15px] font-extrabold">
                            <i class="bi bi-geo-alt-fill text-white/80"></i> {{ $weather['place'] ?? 'Lokasi' }}
                        </p>
                        @if (!empty($weather['fetched_at']))
                            <p class="mt-0.5 text-[11.5px] font-semibold text-white/60">
                                Diperbarui {{ \Carbon\Carbon::parse($weather['fetched_at'])->format('H:i') }} WIB
                            </p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <span class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold backdrop-blur">
                            Live · Open-Meteo
                        </span>
                        <a href="{{ request()->fullUrlWithQuery(['refresh' => 1]) }}"
                           class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold backdrop-blur transition hover:bg-white/30"
                           title="Perbarui data cuaca">
                            <i class="bi bi-arrow-clockwise"></i> Perbarui
                        </a>
                    </div>
                </div>

                @if (!($weather['available'] ?? false))
                    <div class="relative mt-10 text-center">
                        <i class="bi bi-cloud-slash text-5xl text-white/70"></i>
                        <p class="mt-3 text-sm font-semibold text-white/80">Data cuaca tidak tersedia saat ini.</p>
                        <p class="text-[12.5px] text-white/60">Server cuaca tidak terjangkau — coba muat ulang halaman.</p>
                    </div>
                @else
                    @if (!empty($weather['stale']))
                        <div class="relative mt-4 flex items-center gap-2 rounded-2xl bg-amber-400/20 px-3 py-2 text-[12px] font-bold ring-1 ring-amber-200/40">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Server cuaca sedang tidak terjangkau — menampilkan data tersimpan terakhir.
                        </div>
                    @endif

                    <div class="relative mt-6 flex items-center gap-5">
                        <i class="bi {{ $weather['current']['icon'] }} text-[76px] leading-none text-white drop-shadow-[0_8px_20px_rgba(255,255,255,.45)]"></i>
                        <div>
                            <p class="text-[56px] font-light leading-none tracking-tight">
                                {{ $weather['current']['temp'] }}<span class="align-top text-2xl font-medium text-white/70">°C</span>
                            </p>
                            <p class="mt-1 text-[15px] font-bold">{{ $weather['current']['label'] }}</p>
                            <p class="text-[12.5px] text-white/70">Terasa seperti {{ $weather['current']['feels'] }}°C</p>
                        </div>
                    </div>

                    <div class="relative mt-6 grid grid-cols-3 gap-2.5">
                        <div class="rounded-2xl bg-white/12 p-3 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-droplet-fill text-white/80"></i>
                            <p class="mt-1 text-lg font-extrabold">{{ $weather['current']['humidity'] }}%</p>
                            <p class="text-[11px] font-semibold text-white/60">Kelembapan</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-3 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-wind text-white/80"></i>
                            <p class="mt-1 text-lg font-extrabold">{{ $weather['current']['wind'] }}</p>
                            <p class="text-[11px] font-semibold text-white/60">km/jam</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-3 text-center ring-1 ring-white/15 backdrop-blur">
                            <i class="bi bi-cloud-rain-heavy text-white/80"></i>
                            <p class="mt-1 text-lg font-extrabold">{{ $weather['current']['precipitation'] }}</p>
                            <p class="text-[11px] font-semibold text-white/60">mm hujan</p>
                        </div>
                    </div>

                    <div class="relative mt-3 grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                        @foreach ($weather['days'] as $i => $day)
                            <div class="rounded-2xl bg-white/10 p-3 text-center ring-1 ring-white/10 backdrop-blur transition hover:bg-white/15">
                                <p class="text-[11px] font-extrabold tracking-wide text-white/65 uppercase">
                                    {{ $i === 0 ? 'Hari Ini' : \App\Services\WeatherService::dayName($day['date']) }}
                                </p>
                                <i class="bi {{ $day['icon'] }} my-1.5 block text-xl text-white"></i>
                                <p class="text-[12px] font-extrabold">{{ $day['max'] }}° / {{ $day['min'] }}°</p>
                                <p class="mt-0.5 text-[10.5px] font-semibold text-white/60"><i class="bi bi-umbrella"></i> {{ $day['rain_prob'] }}%</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="card overflow-hidden xl:col-span-3">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4">
                <div>
                    <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                        <i class="bi bi-calendar3-fill text-brand-600"></i> Kalender Tanam
                    </h2>
                    <p class="text-[12px] font-semibold text-ink/40">Klik event untuk membuka data terkait</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['bulan' => $calendar['prev']['bulan'], 'tahun' => $calendar['prev']['tahun']]) }}"
                       class="icon-btn" aria-label="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></a>
                    <span class="min-w-[126px] text-center text-[14px] font-extrabold capitalize">{{ $calendar['title'] }}</span>
                    <a href="{{ request()->fullUrlWithQuery(['bulan' => $calendar['next']['bulan'], 'tahun' => $calendar['next']['tahun']]) }}"
                       class="icon-btn" aria-label="Bulan berikutnya"><i class="bi bi-chevron-right"></i></a>
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-7 gap-1.5 pb-2">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                        <span class="text-center text-[10.5px] font-extrabold tracking-[.08em] text-ink/35 uppercase">{{ $weekday }}</span>
                    @endforeach
                </div>

                <div class="space-y-1.5">
                    @foreach ($calendar['rows'] as $row)
                        <div class="grid grid-cols-7 gap-1.5">
                            @foreach ($row as $cell)
                                @if ($cell === null)
                                    <div class="min-h-[78px] rounded-xl"></div>
                                @else
                                    <div class="min-h-[78px] rounded-xl border p-1.5 transition
                                        {{ $cell['is_today']
                                            ? 'border-brand-500 bg-brand-50/70 ring-2 ring-brand-500/40'
                                            : ($cell['is_weekend'] ? 'border-ink/[.04] bg-ink/[.015]' : 'border-ink/[.06] bg-white') }}
                                        hover:border-brand-300">
                                        <span class="block text-[11px] font-extrabold {{ $cell['is_today'] ? 'text-brand-700' : 'text-ink/40' }}">
                                            {{ $cell['day'] }}
                                        </span>

                                        @foreach (array_slice($cell['events'], 0, 2) as $event)
                                            <a href="{{ $event['url'] }}" class="event-chip event-{{ $event['type'] }} mt-1" title="{{ $event['label'] }}">
                                                {{ $event['label'] }}
                                            </a>
                                        @endforeach

                                        @if (count($cell['events']) > 2)
                                            <span class="mt-1 block text-[10px] font-bold text-ink/40">+{{ count($cell['events']) - 2 }} lagi</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 border-t border-ink/[.06] pt-4 text-[12px] font-bold text-ink/50">
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-sky-500"></i> Tanam</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-brand-500"></i> Panen</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-amber-400"></i> Pupuk</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-rose-400"></i> Semprot</span>
                </div>
            </div>
        </section>
    </div>

    {{-- Agenda + Berita --}}
    <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-5">
        <section class="card xl:col-span-2">
            <div class="flex items-center justify-between border-b border-ink/[.07] px-5 py-4">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-clock-history text-brand-600"></i> Agenda 30 Hari
                </h2>
                <span class="badge badge-muted">{{ count($upcoming) }} agenda</span>
            </div>

            <div class="p-5">
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
                                <a href="{{ $item['url'] }}" class="group flex items-center gap-3.5 rounded-2xl border border-transparent p-2.5 transition hover:border-ink/[.06] hover:bg-brand-50/50">
                                    <span class="event-{{ $item['kind'] }} flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl border-l-[3px] bg-ink/[.03] leading-none">
                                        <span class="text-[13px] font-extrabold text-ink">{{ \Carbon\Carbon::parse($item['date'])->format('d') }}</span>
                                        <span class="mt-0.5 text-[9.5px] font-bold text-ink/45 uppercase">{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('M') }}</span>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-[13.5px] font-bold text-ink group-hover:text-brand-800">{{ $item['label'] }}</span>
                                        <span class="block truncate text-[12px] font-medium text-ink/45">{{ $item['detail'] }}</span>
                                        <span class="mt-1 flex flex-wrap items-center gap-x-1.5 text-[11.5px] font-bold text-brand-700">
                                            <i class="bi bi-calendar2-event"></i>
                                            {{ $item['date_label'] }}
                                            <span class="font-semibold text-ink/40">· {{ $item['relative'] }}</span>
                                        </span>
                                    </span>
                                    <span class="badge badge-{{ \App\Models\JadwalTanam::badgeFor($item['status']) }}">{{ $item['status'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        <section class="card xl:col-span-3">
            <div class="flex items-center justify-between border-b border-ink/[.07] px-5 py-4">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-newspaper text-brand-600"></i> Berita Pertanian
                </h2>
                <div class="flex items-center gap-2">
                    <span class="badge badge-muted"><i class="bi bi-arrow-clockwise"></i> otomatis</span>
                    <a href="{{ request()->fullUrlWithQuery(['refresh' => 1]) }}"
                       class="text-[12.5px] font-extrabold text-brand-700 hover:underline"
                       title="Perbarui berita">Perbarui</a>
                </div>
            </div>

            <ul class="divide-y divide-ink/[.06] px-5">
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

    {{-- Inputan terbaru --}}
    <div class="mt-5 grid grid-cols-1 gap-5 2xl:grid-cols-3">
        <section class="card">
            <div class="flex items-center justify-between border-b border-ink/[.07] px-5 py-4">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-calendar2-week-fill text-sky-500"></i> Jadwal Tanam Terbaru
                </h2>
                <a href="{{ route('jadwal.index') }}" class="text-[12.5px] font-extrabold text-brand-700 hover:underline">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                @if ($recentTanam->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-sm font-semibold text-ink/45">Belum ada jadwal tanam.</p>
                        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </div>
                @else
                    <table class="w-full">
                        <tbody>
                        @foreach ($recentTanam as $row)
                            <tr class="row-hover">
                                <td class="td">
                                    <span class="block text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</span>
                                    <span class="block text-[11.5px] font-semibold text-ink/45">{{ $row->varietas_benih ?: 'Benih -' }}</span>
                                </td>
                                <td class="td text-right text-[12.5px] font-semibold text-ink/60">{{ $row->tanggal_tanam?->format('d M Y') }}</td>
                                <td class="td text-right"><span class="badge badge-{{ $row->statusClass() }}">{{ $row->status }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="flex items-center justify-between border-b border-ink/[.07] px-5 py-4">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-droplet-fill text-amber-500"></i> Pemupukan Terbaru
                </h2>
                <a href="{{ route('pemupukan.index') }}" class="text-[12.5px] font-extrabold text-brand-700 hover:underline">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                @if ($recentPemupukan->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-sm font-semibold text-ink/45">Belum ada jadwal pemupukan.</p>
                        <a href="{{ route('pemupukan.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </div>
                @else
                    <table class="w-full">
                        <tbody>
                        @foreach ($recentPemupukan as $row)
                            <tr class="row-hover">
                                <td class="td">
                                    <span class="block text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</span>
                                    <span class="block text-[11.5px] font-semibold text-ink/45">{{ $row->jenis_pupuk }}{{ $row->dosis ? ' · '.$row->dosis : '' }}</span>
                                </td>
                                <td class="td text-right text-[12.5px] font-semibold text-ink/60">{{ $row->tanggal->format('d M Y') }}</td>
                                <td class="td text-right"><span class="badge badge-{{ $row->statusClass() }}">{{ $row->status }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="flex items-center justify-between border-b border-ink/[.07] px-5 py-4">
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <i class="bi bi-bug-fill text-rose-500"></i> Penyemprotan Terbaru
                </h2>
                <a href="{{ route('penyemprotan.index') }}" class="text-[12.5px] font-extrabold text-brand-700 hover:underline">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                @if ($recentPenyemprotan->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-sm font-semibold text-ink/45">Belum ada jadwal penyemprotan.</p>
                        <a href="{{ route('penyemprotan.create') }}" class="btn btn-primary btn-sm mt-3"><i class="bi bi-plus-lg"></i> Tambah</a>
                    </div>
                @else
                    <table class="w-full">
                        <tbody>
                        @foreach ($recentPenyemprotan as $row)
                            <tr class="row-hover">
                                <td class="td">
                                    <span class="block text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</span>
                                    <span class="block text-[11.5px] font-semibold text-ink/45">{{ $row->jenis_pestisida }}{{ $row->target_hama ? ' · '.$row->target_hama : '' }}</span>
                                </td>
                                <td class="td text-right text-[12.5px] font-semibold text-ink/60">{{ $row->tanggal->format('d M Y') }}</td>
                                <td class="td text-right"><span class="badge badge-{{ $row->statusClass() }}">{{ $row->status }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>
    </div>
@endsection
