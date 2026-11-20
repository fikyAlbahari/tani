<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaniKu · Sistem Informasi Pertanian</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23148f53'><rect x='11.2' y='8.2' width='1.6' height='13.6' rx='.8'/><ellipse cx='12' cy='4.3' rx='1.6' ry='2.7'/><ellipse cx='8.85' cy='6.9' rx='1.6' ry='2.7' transform='rotate(-31 8.85 6.9)'/><ellipse cx='15.15' cy='6.9' rx='1.6' ry='2.7' transform='rotate(31 15.15 6.9)'/><ellipse cx='8.55' cy='10.3' rx='1.6' ry='2.7' transform='rotate(-31 8.55 10.3)'/><ellipse cx='15.45' cy='10.3' rx='1.6' ry='2.7' transform='rotate(31 15.45 10.3)'/><path d='M11.4 15.2C8.6 15.2 6.6 13.4 6.3 10.8c2.8 0 4.9 1.8 5.1 4.4Z'/><path d='M12.6 18c2.8 0 4.8-1.8 5.1-4.4-2.8 0-4.9 1.8-5.1 4.4Z'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas">

{{-- Nav --}}
<header class="sticky top-0 z-40 border-b border-ink/[.06] bg-canvas/80 backdrop-blur-xl">
    <div class="mx-auto flex h-[64px] max-w-6xl items-center justify-between px-4 sm:h-[68px] sm:px-5">
        <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#46cb88,#148f53)] text-white shadow-[0_10px_24px_-10px_rgba(20,143,83,.9)] sm:h-10 sm:w-10">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <rect x="11.2" y="8.2" width="1.6" height="13.6" rx=".8"/>
                    <ellipse cx="12" cy="4.3" rx="1.6" ry="2.7"/>
                    <ellipse cx="8.85" cy="6.9" rx="1.6" ry="2.7" transform="rotate(-31 8.85 6.9)"/>
                    <ellipse cx="15.15" cy="6.9" rx="1.6" ry="2.7" transform="rotate(31 15.15 6.9)"/>
                    <ellipse cx="8.55" cy="10.3" rx="1.6" ry="2.7" transform="rotate(-31 8.55 10.3)"/>
                    <ellipse cx="15.45" cy="10.3" rx="1.6" ry="2.7" transform="rotate(31 15.45 10.3)"/>
                    <path d="M11.4 15.2C8.6 15.2 6.6 13.4 6.3 10.8c2.8 0 4.9 1.8 5.1 4.4Z"/>
                    <path d="M12.6 18c2.8 0 4.8-1.8 5.1-4.4-2.8 0-4.9 1.8-5.1 4.4Z"/>
                </svg>
            </span>
            <span class="text-base font-extrabold tracking-tight sm:text-lg">TaniKu</span>
        </a>
        <nav class="flex items-center gap-3.5 sm:gap-6">
            <a href="#info" class="text-[12px] font-bold text-ink/55 transition hover:text-brand-700 sm:text-[13.5px]">Info Lapangan</a>
            <a href="#berita" class="text-[12px] font-bold text-ink/55 transition hover:text-brand-700 sm:text-[13.5px]">Berita</a>
        </nav>
    </div>
</header>

{{-- Hero --}}
<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[420px] w-[520px] -translate-x-1/2 rounded-full bg-brand-300/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -right-32 h-[380px] w-[380px] rounded-full bg-sky-300/20 blur-3xl"></div>

    <div class="relative mx-auto max-w-4xl px-4 py-14 text-center sm:px-5 sm:py-28">
        <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3.5 py-1.5 text-[12px] font-extrabold text-brand-800">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <rect x="11.2" y="8.2" width="1.6" height="13.6" rx=".8"/>
                <ellipse cx="12" cy="4.3" rx="1.6" ry="2.7"/>
                <ellipse cx="8.85" cy="6.9" rx="1.6" ry="2.7" transform="rotate(-31 8.85 6.9)"/>
                <ellipse cx="15.15" cy="6.9" rx="1.6" ry="2.7" transform="rotate(31 15.15 6.9)"/>
                <ellipse cx="8.55" cy="10.3" rx="1.6" ry="2.7" transform="rotate(-31 8.55 10.3)"/>
                <ellipse cx="15.45" cy="10.3" rx="1.6" ry="2.7" transform="rotate(31 15.45 10.3)"/>
                <path d="M11.4 15.2C8.6 15.2 6.6 13.4 6.3 10.8c2.8 0 4.9 1.8 5.1 4.4Z"/>
                <path d="M12.6 18c2.8 0 4.8-1.8 5.1-4.4-2.8 0-4.9 1.8-5.1 4.4Z"/>
            </svg> Sistem Informasi Pertanian
        </span>

        <h1 class="mx-auto mt-5 max-w-3xl text-[clamp(2rem,5.2vw,3.5rem)] font-extrabold leading-[1.05] tracking-[-.03em] text-ink sm:mt-6">
            Kelola tanam, pupuk,
            <span class="relative whitespace-nowrap text-brand-600">
                dan panen
                <svg class="absolute -bottom-1.5 left-0 w-full" height="10" viewBox="0 0 200 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 7.5C52 2.5 148 2.5 198 7.5" stroke="#46cb88" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </span>
            dalam satu tempat
        </h1>

        <p class="mx-auto mt-6 max-w-2xl text-[15px] leading-relaxed font-medium text-ink/55 sm:mt-8 sm:text-[16px]">
            TaniKu membantu admin mencatat jadwal tanam <strong class="font-bold text-ink/75">benih sampai panen</strong>,
            jadwal pemupukan dan penyemprotan — lengkap dengan cuaca hari ini dan berita pertanian terkini.
        </p>

        <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-9 sm:flex-row sm:items-center">
            <a href="{{ route('login') }}" class="btn btn-primary w-full px-6 py-3.5 text-[15px] sm:w-auto">
                <i class="bi bi-grid-1x2"></i> Masuk Dashboard
            </a>
            <a href="#info" class="btn btn-ghost w-full px-6 py-3.5 text-[15px] sm:w-auto">
                <i class="bi bi-activity"></i> Info Lapangan
            </a>
        </div>
    </div>
</section>

{{-- Info Lapangan --}}
<section id="info" class="relative scroll-mt-20 overflow-hidden border-y border-ink/[.06] bg-[linear-gradient(180deg,#f8fbf9_0%,#edf7f1_100%)] py-14 sm:py-24">
    <div class="pointer-events-none absolute -top-24 right-0 h-72 w-72 rounded-full bg-sky-300/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 left-0 h-72 w-72 rounded-full bg-brand-300/20 blur-3xl"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-5">
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between sm:gap-5">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white/80 px-3.5 py-1.5 text-[11px] font-extrabold tracking-[.12em] text-brand-700 uppercase backdrop-blur">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span>
                    </span>
                    Info Lapangan · Live
                </span>
                <h2 class="mt-4 text-[clamp(1.6rem,4.6vw,2.4rem)] font-extrabold tracking-tight">Cuaca hari ini &amp; kegiatan terbaru</h2>
                <p class="mt-3 max-w-xl text-[13.5px] leading-relaxed font-medium text-ink/50 sm:text-[14.5px]">
                    Ringkasan cuaca Kabupaten Lombok Utara serta jadwal tanam, pemupukan, dan penyemprotan terbaru dari data admin.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-xl border border-ink/[.08] bg-white/80 px-3.5 py-2.5 text-[12.5px] font-bold text-ink/55 backdrop-blur">
                    <i class="bi bi-calendar3 text-brand-600"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <!-- <a href="{{ route('home', ['refresh' => 1]) }}#info" class="btn btn-ghost btn-sm bg-white/80 backdrop-blur">
                    <i class="bi bi-arrow-clockwise"></i> Perbarui
                </a> -->
            </div>
        </div>

        <div class="mt-8 grid gap-4 sm:mt-10 sm:gap-5 lg:grid-cols-2">

            {{-- Cuaca hari ini --}}
            <div class="group relative overflow-hidden rounded-[28px] bg-[linear-gradient(145deg,#0ea5e9_0%,#2563eb_46%,#4338ca_100%)] p-5 text-white shadow-[0_30px_70px_-32px_rgba(37,99,235,.75)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_36px_80px_-32px_rgba(37,99,235,.9)] sm:p-7">
                <div class="pointer-events-none absolute -top-20 -right-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-16 h-56 w-56 rounded-full bg-white/5 blur-2xl"></div>

                <div class="relative flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-extrabold tracking-[.14em] text-white/65 uppercase">Cuaca Hari Ini</p>
                        <p class="mt-1 flex items-center gap-1.5 truncate text-[15px] font-extrabold">
                            <i class="bi bi-geo-alt-fill shrink-0 text-white/80"></i> {{ $weather['place'] ?? 'Lokasi' }}
                        </p>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-[10.5px] font-extrabold backdrop-blur ring-1 ring-white/15">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> LIVE
                    </span>
                </div>

                @if (!($weather['available'] ?? false))
                    <div class="relative mt-12 mb-4 text-center">
                        <i class="bi bi-cloud-slash text-5xl text-white/60"></i>
                        <p class="mt-3 text-[13px] font-semibold text-white/75">Data cuaca belum tersedia.</p>
                        <p class="text-[11.5px] text-white/50">Coba muat ulang beberapa saat lagi.</p>
                    </div>
                @else
                    <div class="relative mt-6 flex items-end justify-between gap-3 sm:mt-7 sm:gap-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <i class="bi {{ $weather['current']['icon'] }} shrink-0 text-[52px] leading-none text-white drop-shadow-[0_10px_24px_rgba(255,255,255,.45)] sm:text-[64px]"></i>
                            <div>
                                <p class="text-[44px] font-light leading-none tracking-tight sm:text-[54px]">
                                    {{ $weather['current']['temp'] }}<span class="align-top text-lg font-medium text-white/70 sm:text-xl">°C</span>
                                </p>
                                <p class="mt-1.5 text-[14px] font-extrabold">{{ $weather['current']['label'] }}</p>
                                <p class="text-[11.5px] font-medium text-white/65">Terasa seperti {{ $weather['current']['feels'] }}°C</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-5 grid grid-cols-3 gap-2 sm:mt-6 sm:gap-2.5">
                        <div class="rounded-2xl bg-white/12 p-2.5 text-center ring-1 ring-white/15 backdrop-blur transition hover:bg-white/[.18] sm:p-3">
                            <i class="bi bi-droplet-fill text-[14px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['humidity'] }}%</p>
                            <p class="text-[10px] font-semibold text-white/60">Kelembapan</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-2.5 text-center ring-1 ring-white/15 backdrop-blur transition hover:bg-white/[.18] sm:p-3">
                            <i class="bi bi-wind text-[14px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['wind'] }}</p>
                            <p class="text-[10px] font-semibold text-white/60">km/jam</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-2.5 text-center ring-1 ring-white/15 backdrop-blur transition hover:bg-white/[.18] sm:p-3">
                            <i class="bi bi-cloud-rain-heavy text-[14px] text-white/80"></i>
                            <p class="mt-1 text-[15px] font-extrabold">{{ $weather['current']['precipitation'] }}</p>
                            <p class="text-[10px] font-semibold text-white/60">mm hujan</p>
                        </div>
                    </div>

                    @if (!empty($weather['days']))
                        <div class="relative mt-4 rounded-2xl bg-white/10 p-2.5 ring-1 ring-white/10 backdrop-blur sm:p-3">
                            <div class="flex items-center justify-between px-0.5 pb-2">
                                <p class="text-[10px] font-extrabold tracking-[.12em] text-white/55 uppercase">Prakiraan 4 Hari</p>
                                <i class="bi bi-cloud-sun text-[12px] text-white/50"></i>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5">
                                @foreach ($weather['days'] as $i => $day)
                                    <div class="rounded-xl bg-white/[.07] px-1 py-2 text-center ring-1 ring-white/10">
                                        <p class="text-[9.5px] font-extrabold tracking-wide text-white/60 uppercase">{{ $i === 0 ? 'Kini' : \App\Services\WeatherService::dayName($day['date']) }}</p>
                                        <i class="bi {{ $day['icon'] }} my-1 block text-[15px] text-white"></i>
                                        <p class="text-[11px] font-extrabold">{{ $day['max'] }}°</p>
                                        <p class="text-[9.5px] font-semibold text-white/55">{{ $day['min'] }}° · {{ $day['rain_prob'] }}%</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- <div class="relative mt-3 flex items-center justify-between text-[10.5px] font-semibold text-white/55">
                        <span>
                            @if (!empty($weather['fetched_at']))
                                Diperbarui pukul {{ \Carbon\Carbon::parse($weather['fetched_at'])->format('H:i') }}
                            @endif
                        </span>
                        <span>Open-Meteo</span>
                    </div> -->
                @endif
            </div>

            {{-- Jadwal tanam --}}
            <div class="card group flex h-full flex-col overflow-hidden p-0 transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_60px_-32px_rgba(16,35,26,.4)]">
                <span class="h-1 w-full bg-gradient-to-r from-sky-400 to-cyan-300"></span>

                <div class="flex items-center justify-between gap-3 px-4 pt-4 pb-3 sm:px-5 sm:pt-5 sm:pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#38bdf8,#0284c7)] text-xl text-white shadow-[0_12px_24px_-12px_rgba(2,132,199,.95)] ring-1 ring-white/40 transition duration-300 group-hover:scale-105 group-hover:-rotate-3">
                            <i class="bi bi-calendar2-range-fill drop-shadow-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-[15px] font-extrabold tracking-tight">Jadwal Tanam</h3>
                            <p class="text-[11.5px] font-semibold text-ink/40">Rencana tanam 4 bulan terakhir</p>
                        </div>
                    </div>
                    <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-sky-50 px-2 text-[11.5px] font-extrabold text-sky-700 ring-1 ring-sky-100">
                        {{ $jadwalTanams->count() }}
                    </span>
                </div>

                <ul class="flex-1 space-y-1 px-2.5 pb-2.5 sm:px-3 sm:pb-3">
                    @forelse ($jadwalTanams as $row)
                        <li class="flex items-center gap-2.5 rounded-xl px-2 py-2.5 transition hover:bg-sky-50/70 sm:gap-3 sm:px-2.5">
                            <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-sky-50 leading-none text-sky-700 ring-1 ring-sky-100">
                                <span class="text-[13px] font-extrabold">{{ $row->tanggal_tanam?->format('d') ?? '-' }}</span>
                                <span class="mt-0.5 text-[8.5px] font-extrabold tracking-wide uppercase">{{ $row->tanggal_tanam?->translatedFormat('M') ?? '-' }}</span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[13px] font-extrabold text-ink">{{ $row->nama_tanaman }}</p>
                                <p class="mt-0.5 truncate text-[11px] font-semibold text-ink/45">
                                    {{ $row->varietas_benih ?: 'Benih belum dicatat' }} · {{ $row->luas_lahan ? $row->luas_lahan.' ha' : 'Luas -' }}
                                </p>
                                <p class="mt-0.5 truncate text-[11px] font-semibold text-ink/45">
                                    <i class="bi bi-geo-alt"></i> {{ $row->lokasi ?: 'Lokasi -' }}
                                    <span class="mx-1 text-ink/20">·</span>
                                    <i class="bi bi-person"></i> {{ $row->nama_penggarap ?: 'Penggarap -' }}
                                </p>
                            </div>
                            <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                        </li>
                    @empty
                        <li class="rounded-xl bg-ink/[.02] px-3 py-8 text-center text-[12.5px] font-semibold text-ink/45">Belum ada jadwal tanam.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Pemupukan --}}
            <div class="card group flex h-full flex-col overflow-hidden p-0 transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_60px_-32px_rgba(16,35,26,.4)]">
                <span class="h-1 w-full bg-gradient-to-r from-amber-400 to-orange-300"></span>

                <div class="flex items-center justify-between gap-3 px-4 pt-4 pb-3 sm:px-5 sm:pt-5 sm:pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#fbbf24,#f97316)] text-xl text-white shadow-[0_12px_24px_-12px_rgba(249,115,22,.95)] ring-1 ring-white/40 transition duration-300 group-hover:scale-105 group-hover:-rotate-3">
                            <i class="bi bi-moisture drop-shadow-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-[15px] font-extrabold tracking-tight">Pemupukan</h3>
                            <p class="text-[11.5px] font-semibold text-ink/40">Jadwal pemupukan 4 bulan terakhir</p>
                        </div>
                    </div>
                    <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-amber-50 px-2 text-[11.5px] font-extrabold text-amber-700 ring-1 ring-amber-100">
                        {{ $pemupukans->count() }}
                    </span>
                </div>

                <ul class="flex-1 space-y-1 px-2.5 pb-2.5 sm:px-3 sm:pb-3">
                    @forelse ($pemupukans as $row)
                        <li class="flex items-center gap-2.5 rounded-xl px-2 py-2.5 transition hover:bg-amber-50/70 sm:gap-3 sm:px-2.5">
                            <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-amber-50 leading-none text-amber-700 ring-1 ring-amber-100">
                                <span class="text-[13px] font-extrabold">{{ $row->tanggal->format('d') }}</span>
                                <span class="mt-0.5 text-[8.5px] font-extrabold tracking-wide uppercase">{{ $row->tanggal->translatedFormat('M') }}</span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="flex items-center gap-1.5 text-[13px] font-extrabold text-ink">
                                    <span class="truncate">{{ $row->nama_tanaman }}</span>
                                    @if (in_array($urutanPemupukan[$row->id] ?? null, [1, 2], true))
                                        <span class="badge badge-{{ ($urutanPemupukan[$row->id] ?? null) === 1 ? 'info' : 'warning' }} shrink-0 !px-1.5 !py-0.5 !text-[9px]">
                                            <span class="h-1 w-1 rounded-full bg-current"></span>Pemupukan {{ $urutanPemupukan[$row->id] }}
                                        </span>
                                    @endif
                                </p>
                                <p class="mt-0.5 truncate text-[11px] font-semibold text-ink/45"
                                   title="{{ $row->jenis_pupuk }}{{ $row->dosis ? ' · '.$row->dosis : '' }}">
                                    {{ $row->jenis_pupuk }}{{ $row->dosis ? ' · '.$row->dosis : '' }}
                                </p>
                            </div>
                            <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                        </li>
                    @empty
                        <li class="rounded-xl bg-ink/[.02] px-3 py-8 text-center text-[12.5px] font-semibold text-ink/45">Belum ada jadwal pemupukan.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Penyemprotan --}}
            <div class="card group flex h-full flex-col overflow-hidden p-0 transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_60px_-32px_rgba(16,35,26,.4)]">
                <span class="h-1 w-full bg-gradient-to-r from-rose-400 to-pink-300"></span>

                <div class="flex items-center justify-between gap-3 px-4 pt-4 pb-3 sm:px-5 sm:pt-5 sm:pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#fb7185,#e11d48)] text-xl text-white shadow-[0_12px_24px_-12px_rgba(225,29,72,.95)] ring-1 ring-white/40 transition duration-300 group-hover:scale-105 group-hover:-rotate-3">
                            <i class="bi bi-shield-fill-check drop-shadow-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-[15px] font-extrabold tracking-tight">Penyemprotan</h3>
                            <p class="text-[11.5px] font-semibold text-ink/40">Jadwal penyemprotan 4 bulan terakhir</p>
                        </div>
                    </div>
                    <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-rose-50 px-2 text-[11.5px] font-extrabold text-rose-600 ring-1 ring-rose-100">
                        {{ $penyemprotans->count() }}
                    </span>
                </div>

                <ul class="flex-1 space-y-1 px-2.5 pb-2.5 sm:px-3 sm:pb-3">
                    @forelse ($penyemprotans as $row)
                        <li class="flex items-center gap-2.5 rounded-xl px-2 py-2.5 transition hover:bg-rose-50/70 sm:gap-3 sm:px-2.5">
                            <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-rose-50 leading-none text-rose-600 ring-1 ring-rose-100">
                                <span class="text-[13px] font-extrabold">{{ $row->tanggal->format('d') }}</span>
                                <span class="mt-0.5 text-[8.5px] font-extrabold tracking-wide uppercase">{{ $row->tanggal->translatedFormat('M') }}</span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[13px] font-extrabold text-ink">{{ $row->nama_tanaman }}</p>
                                <p class="mt-0.5 truncate text-[11px] font-semibold text-ink/45"
                                   title="{{ $row->jenis_pestisida }}{{ $row->target_hama ? ' · '.$row->target_hama : '' }}">
                                    {{ $row->jenis_pestisida }}{{ $row->target_hama ? ' · '.$row->target_hama : '' }}
                                </p>
                                <p class="mt-0.5 truncate text-[11px] font-semibold text-ink/45">
                                    <i class="bi bi-geo-alt"></i> {{ $row->lokasi ?: 'Lokasi -' }}
                                </p>
                            </div>
                            <span class="badge badge-{{ $row->statusClass() }} shrink-0">{{ $row->status }}</span>
                        </li>
                    @empty
                        <li class="rounded-xl bg-ink/[.02] px-3 py-8 text-center text-[12.5px] font-semibold text-ink/45">Belum ada jadwal penyemprotan.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Kalender Tanam --}}
        <div class="card mt-5 overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-ink/[.07] px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#38bdf8,#0284c7)] text-xl text-white shadow-[0_12px_24px_-12px_rgba(2,132,199,.95)] ring-1 ring-white/40">
                        <i class="bi bi-calendar3"></i>
                    </span>
                    <div>
                        <h3 class="text-[15px] font-extrabold tracking-tight">Kalender Tanam</h3>
                        <p class="text-[11.5px] font-semibold text-ink/40">Tanam, panen, pemupukan, dan penyemprotan</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2 sm:justify-end">
                    <a href="{{ route('home', ['bulan' => $calendar['prev']['bulan'], 'tahun' => $calendar['prev']['tahun']]) }}#info"
                       class="icon-btn" aria-label="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></a>
                    <span class="min-w-[126px] text-center text-[14px] font-extrabold capitalize">{{ $calendar['title'] }}</span>
                    <a href="{{ route('home', ['bulan' => $calendar['next']['bulan'], 'tahun' => $calendar['next']['tahun']]) }}#info"
                       class="icon-btn" aria-label="Bulan berikutnya"><i class="bi bi-chevron-right"></i></a>
                </div>
            </div>

            <div class="p-3 sm:p-5">
                <div class="grid grid-cols-7 gap-1 pb-2 sm:gap-1.5">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                        <span class="text-center text-[9px] font-extrabold tracking-[.04em] text-ink/35 uppercase sm:text-[10px] sm:tracking-[.06em]">{{ $weekday }}</span>
                    @endforeach
                </div>

                <div class="space-y-1 sm:space-y-1.5">
                    @foreach ($calendar['rows'] as $row)
                        <div class="grid grid-cols-7 gap-1 sm:gap-1.5">
                            @foreach ($row as $cell)
                                @if ($cell === null)
                                    <div class="min-h-[54px] rounded-lg sm:min-h-[66px] sm:rounded-xl"></div>
                                @else
                                    <div class="min-h-[54px] rounded-lg border p-1 transition sm:min-h-[66px] sm:rounded-xl sm:p-1.5
                                        {{ $cell['is_today']
                                            ? 'border-brand-500 bg-brand-50/70 ring-2 ring-brand-500/40'
                                            : ($cell['is_weekend'] ? 'border-ink/[.04] bg-ink/[.015]' : 'border-ink/[.06] bg-white') }}
                                        hover:border-brand-300">
                                        <span class="block text-[10px] font-extrabold {{ $cell['is_today'] ? 'text-brand-700' : 'text-ink/40' }} sm:text-[11px]">{{ $cell['day'] }}</span>

                                        {{-- Mobile: titik warna per event --}}
                                        @if ($cell['events'] !== [])
                                            <div class="mt-1 flex flex-wrap items-center gap-0.5 sm:hidden">
                                                @foreach (array_slice($cell['events'], 0, 4) as $event)
                                                    @php($dotColor = match ($event['type']) {
                                                        'tanam' => 'bg-sky-500',
                                                        'panen' => 'bg-brand-500',
                                                        'pupuk' => 'bg-amber-400',
                                                        'semprot' => 'bg-rose-400',
                                                        default => 'bg-ink/30',
                                                    })
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
                                                @endforeach
                                                @if (count($cell['events']) > 4)
                                                    <span class="text-[8px] font-bold text-ink/40">+{{ count($cell['events']) - 4 }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Desktop: label event --}}
                                        <div class="hidden sm:block">
                                            @foreach (array_slice($cell['events'], 0, 2) as $event)
                                                <a href="{{ $event['url'] }}" class="event-chip event-{{ $event['type'] }} mt-1 block truncate !border-l-2 !px-1 !text-[9px]" title="{{ $event['label'] }}">
                                                    {{ explode(' ', $event['label'])[0] }}
                                                </a>
                                            @endforeach

                                            @if (count($cell['events']) > 2)
                                                <span class="mt-1 block text-[9.5px] font-bold text-ink/40">+{{ count($cell['events']) - 2 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1.5 border-t border-ink/[.06] pt-3 text-[10.5px] font-bold text-ink/50 sm:mt-4 sm:gap-x-4 sm:gap-y-2 sm:pt-3.5 sm:text-[11.5px]">
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-sky-500"></i> Tanam</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-brand-500"></i> Panen</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-amber-400"></i> Pupuk</span>
                    <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-rose-400"></i> Semprot</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Berita Pertanian --}}
<section id="berita" class="scroll-mt-20 border-t border-ink/[.06] bg-white/60 py-14 sm:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-5">
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between sm:gap-5">
            <div class="max-w-2xl">
                <p class="text-[12.5px] font-extrabold tracking-[.14em] text-brand-600 uppercase">Berita Pertanian</p>
                <h2 class="mt-3 text-[clamp(1.6rem,4.6vw,2.4rem)] font-extrabold tracking-tight">Kabar terbaru untuk petani</h2>
                <p class="mt-3 max-w-xl text-[13.5px] leading-relaxed font-medium text-ink/50 sm:text-[14.5px]">
                    Harga komoditas, cuaca tanam, dan tips budidaya terbaru — diperbarui otomatis dari sumber berita pertanian.
                </p>
            </div>

            <!-- <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-xl border border-ink/[.08] bg-white/80 px-3.5 py-2.5 text-[12px] font-bold text-ink/50 backdrop-blur">
                    <i class="bi bi-arrow-repeat text-brand-600"></i> Otomatis
                </span>
                <a href="{{ route('home', ['refresh' => 1]) }}#berita" class="btn btn-ghost btn-sm bg-white/80 backdrop-blur">
                    <i class="bi bi-arrow-clockwise"></i> Perbarui
                </a>
            </div> -->
        </div>

        <div class="mt-8 grid gap-4 sm:mt-10 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($news as $item)
                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-ink/[.07] bg-white p-4 shadow-[0_18px_40px_-32px_rgba(16,35,26,.5)] transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-[0_28px_54px_-28px_rgba(20,143,83,.45)] sm:p-5">
                    <span class="pointer-events-none absolute -top-16 -right-12 h-32 w-32 rounded-full bg-brand-100/50 blur-2xl transition duration-300 group-hover:bg-brand-200/60"></span>

                    <div class="relative flex items-start justify-between gap-3">
                        <span class="inline-flex min-w-0 items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 text-[10.5px] font-extrabold tracking-wide text-brand-700 uppercase ring-1 ring-brand-100">
                            <i class="bi bi-newspaper shrink-0"></i>
                            <span class="truncate">{{ $item['source'] }}</span>
                        </span>
                        <i class="bi bi-arrow-up-right mt-0.5 shrink-0 text-[13px] text-ink/25 transition group-hover:text-brand-600"></i>
                    </div>

                    <p class="relative mt-3 line-clamp-3 flex-1 text-[14px] font-extrabold leading-snug text-ink transition group-hover:text-brand-800">
                        {{ $item['title'] }}
                    </p>

                    <span class="relative mt-4 flex items-center gap-1.5 border-t border-ink/[.06] pt-3 text-[11px] font-bold text-ink/40">
                        <i class="bi bi-clock"></i> {{ $item['published_at'] ?: 'Baru saja' }}
                    </span>
                </a>
            @empty
                <div class="rounded-3xl border border-dashed border-ink/10 bg-white/70 px-5 py-12 text-center sm:col-span-2 lg:col-span-3">
                    <i class="bi bi-newspaper text-4xl text-ink/20"></i>
                    <p class="mt-2 text-sm font-semibold text-ink/45">Berita belum tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<footer class="bg-[linear-gradient(165deg,#123f2a,#0a2c1e_60%,#071f15)] py-9 text-white/60">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 text-[13px] font-semibold sm:px-5">
        <span class="flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <rect x="11.2" y="8.2" width="1.6" height="13.6" rx=".8"/>
                <ellipse cx="12" cy="4.3" rx="1.6" ry="2.7"/>
                <ellipse cx="8.85" cy="6.9" rx="1.6" ry="2.7" transform="rotate(-31 8.85 6.9)"/>
                <ellipse cx="15.15" cy="6.9" rx="1.6" ry="2.7" transform="rotate(31 15.15 6.9)"/>
                <ellipse cx="8.55" cy="10.3" rx="1.6" ry="2.7" transform="rotate(-31 8.55 10.3)"/>
                <ellipse cx="15.45" cy="10.3" rx="1.6" ry="2.7" transform="rotate(31 15.45 10.3)"/>
                <path d="M11.4 15.2C8.6 15.2 6.6 13.4 6.3 10.8c2.8 0 4.9 1.8 5.1 4.4Z"/>
                <path d="M12.6 18c2.8 0 4.8-1.8 5.1-4.4-2.8 0-4.9 1.8-5.1 4.4Z"/>
            </svg>
            TaniKu · Sistem Informasi Pertanian
        </span>
        <span>&copy; {{ date('Y') }}</span>
    </div>
</footer>

</body>
</html>
