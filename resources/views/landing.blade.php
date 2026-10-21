<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaniKu · Sistem Informasi Pertanian</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23148f53'><path d='M12 2c3 3 4.5 6 4.5 9a4.5 4.5 0 1 1-9 0c0-3 1.5-6 4.5-9Z'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas">

{{-- Nav --}}
<header class="sticky top-0 z-40 border-b border-ink/[.06] bg-canvas/80 backdrop-blur-xl">
    <div class="mx-auto flex h-[68px] max-w-6xl items-center justify-between px-5">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#46cb88,#148f53)] text-lg text-white shadow-[0_10px_24px_-10px_rgba(20,143,83,.9)]">
                <i class="bi bi-flower1"></i>
            </span>
            <span class="text-lg font-extrabold tracking-tight">TaniKu</span>
        </a>
        <nav class="flex items-center gap-6">
            <a href="#fitur" class="hidden text-[13.5px] font-bold text-ink/55 transition hover:text-brand-700 sm:block">Fitur</a>
            <a href="#cara-kerja" class="hidden text-[13.5px] font-bold text-ink/55 transition hover:text-brand-700 sm:block">Cara Kerja</a>
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-box-arrow-in-right"></i> Login Admin
            </a>
        </nav>
    </div>
</header>

{{-- Hero --}}
<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute -top-40 -left-32 h-[420px] w-[420px] rounded-full bg-brand-300/25 blur-3xl"></div>
    <div class="pointer-events-none absolute top-24 -right-40 h-[460px] w-[460px] rounded-full bg-sky-300/20 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-14 px-5 py-16 sm:py-24 lg:grid-cols-[1.05fr_.95fr]">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3.5 py-1.5 text-[12px] font-extrabold text-brand-800">
                <i class="bi bi-leaf-fill"></i> Sistem Informasi Pertanian
            </span>

            <h1 class="mt-6 text-[clamp(2.1rem,5.2vw,3.5rem)] font-extrabold leading-[1.05] tracking-[-.03em] text-ink">
                Kelola tanam, pupuk,<br class="hidden sm:block"> dan panen dalam
                <span class="relative whitespace-nowrap text-brand-600">
                    satu tempat
                    <svg class="absolute -bottom-2 left-0 w-full" height="10" viewBox="0 0 200 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 7.5C52 2.5 148 2.5 198 7.5" stroke="#46cb88" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="mt-8 max-w-xl text-[16px] leading-relaxed font-medium text-ink/55">
                TaniKu membantu admin mencatat jadwal tanam <strong class="font-bold text-ink/75">benih sampai panen</strong>,
                jadwal pemupukan dan penyemprotan — lalu memantau semuanya lewat dashboard lengkap dengan cuaca,
                kalender tanam, dan berita pertanian terkini.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary px-6 py-3.5 text-[15px]">
                    <i class="bi bi-grid-1x2"></i> Masuk Dashboard
                </a>
                <a href="#fitur" class="btn btn-ghost px-6 py-3.5 text-[15px]">Lihat Fitur</a>
            </div>

            <div class="mt-12 flex flex-wrap gap-10">
                <div>
                    <p class="text-[26px] font-extrabold tracking-tight text-brand-700">3</p>
                    <p class="text-[12.5px] font-bold text-ink/45">Jadwal Utama</p>
                </div>
                <div>
                    <p class="text-[26px] font-extrabold tracking-tight text-brand-700">24/7</p>
                    <p class="text-[12.5px] font-bold text-ink/45">Data Tersimpan</p>
                </div>
                <div>
                    <p class="text-[26px] font-extrabold tracking-tight text-brand-700">Live</p>
                    <p class="text-[12.5px] font-bold text-ink/45">Cuaca &amp; Berita</p>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="card relative rounded-[28px] p-6 sm:p-7">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-100 text-brand-700">
                            <i class="bi bi-calendar2-week-fill"></i>
                        </span>
                        <div>
                            <p class="text-[14px] font-extrabold tracking-tight">Musim Tanam Ini</p>
                            <p class="text-[11.5px] font-semibold text-ink/40">Rencana hingga panen</p>
                        </div>
                    </div>
                    <span class="badge badge-success"><span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span> Aktif</span>
                </div>

                <ol class="relative mt-7 space-y-6 pl-1">
                    <span class="absolute top-2 bottom-2 left-[21px] w-px bg-ink/10"></span>
                    <li class="relative flex gap-4">
                        <span class="z-10 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-sky-200 bg-sky-50 text-sky-600">
                            <i class="bi bi-seedling"></i>
                        </span>
                        <div class="pt-1">
                            <p class="text-[14px] font-extrabold">Semai Benih</p>
                            <p class="text-[12.5px] font-medium text-ink/50">Persiapan lahan &amp; seleksi bibit unggul</p>
                        </div>
                    </li>
                    <li class="relative flex gap-4">
                        <span class="z-10 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-amber-600">
                            <i class="bi bi-droplet-fill"></i>
                        </span>
                        <div class="pt-1">
                            <p class="text-[14px] font-extrabold">Tanam &amp; Rawat</p>
                            <p class="text-[12.5px] font-medium text-ink/50">Pemupukan serta penyemprotan terjadwal</p>
                        </div>
                    </li>
                    <li class="relative flex gap-4">
                        <span class="z-10 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-brand-200 bg-brand-50 text-brand-700">
                            <i class="bi bi-basket3-fill"></i>
                        </span>
                        <div class="pt-1">
                            <p class="text-[14px] font-extrabold">Panen</p>
                            <p class="text-[12.5px] font-medium text-ink/50">Produksi tercatat rapi hingga distribusi</p>
                        </div>
                    </li>
                </ol>

                <div class="mt-7 flex items-center gap-3 rounded-2xl bg-brand-50 px-4 py-3 text-[12.5px] font-bold text-brand-900 ring-1 ring-brand-100">
                    <i class="bi bi-graph-up-arrow text-lg text-brand-600"></i>
                    Semua inputan admin tampil rapi di dashboard
                </div>
            </div>

            <div class="card absolute -right-3 -bottom-6 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-lg sm:flex">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(150deg,#38bdf8,#2563eb)] text-white">
                    <i class="bi bi-cloud-sun-fill"></i>
                </span>
                <div>
                    <p class="text-[12px] font-bold text-ink/45">Cuaca hari ini</p>
                    <p class="text-[15px] font-extrabold tracking-tight">30°C · Cerah berawan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Fitur --}}
<section id="fitur" class="py-20">
    <div class="mx-auto max-w-6xl px-5">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-[12.5px] font-extrabold tracking-[.14em] text-brand-600 uppercase">Fitur Utama</p>
            <h2 class="mt-3 text-[clamp(1.7rem,3.6vw,2.4rem)] font-extrabold tracking-tight">Semua kebutuhan tani dalam satu aplikasi</h2>
            <p class="mt-3 text-[15px] font-medium text-ink/50">Dari pencatatan tanam hingga pemantauan cuaca dan berita.</p>
        </div>

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['icon' => 'bi-calendar2-week-fill', 'tone' => 'bg-sky-100 text-sky-600', 'title' => 'Jadwal Tanam', 'desc' => 'Catat nama tanaman, varietas benih, luas lahan, tanggal tanam, sampai perkiraan panen lengkap dengan status.'],
                ['icon' => 'bi-droplet-fill', 'tone' => 'bg-amber-100 text-amber-600', 'title' => 'Pemupukan', 'desc' => 'Atur jadwal pemupukan: jenis pupuk, dosis, metode aplikasi, dan status pelaksanaannya.'],
                ['icon' => 'bi-bug-fill', 'tone' => 'bg-rose-100 text-rose-500', 'title' => 'Penyemprotan', 'desc' => 'Jadwalkan pestisida beserta dosis dan target hama untuk menjaga kesehatan tanaman.'],
                ['icon' => 'bi-grid-1x2-fill', 'tone' => 'bg-brand-100 text-brand-700', 'title' => 'Dashboard Lengkap', 'desc' => 'Ringkasan statistik, data inputan terbaru, cuaca, kalender tanam, dan berita dalam satu layar.'],
                ['icon' => 'bi-cloud-sun-fill', 'tone' => 'bg-sky-100 text-sky-600', 'title' => 'Cuaca Hari Ini', 'desc' => 'Pantau suhu, kelembapan, angin, dan prakiraan hujan untuk menentukan waktu kerja di lahan.'],
                ['icon' => 'bi-newspaper', 'tone' => 'bg-brand-100 text-brand-700', 'title' => 'Berita Pertanian', 'desc' => 'Kabar terbaru seputar pertanian, harga komoditas, dan tips budidaya otomatis di dashboard.'],
            ] as $feature)
                <div class="card group p-6 transition hover:-translate-y-1 hover:shadow-[0_28px_60px_-30px_rgba(16,35,26,.4)]">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl transition group-hover:scale-110 {{ $feature['tone'] }}">
                        <i class="bi {{ $feature['icon'] }}"></i>
                    </span>
                    <h3 class="mt-5 text-[16px] font-extrabold tracking-tight">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-[13.5px] leading-relaxed font-medium text-ink/50">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Cara kerja --}}
<section id="cara-kerja" class="border-y border-ink/[.06] bg-white/60 py-20">
    <div class="mx-auto max-w-6xl px-5">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-[12.5px] font-extrabold tracking-[.14em] text-brand-600 uppercase">Cara Kerja</p>
            <h2 class="mt-3 text-[clamp(1.7rem,3.6vw,2.4rem)] font-extrabold tracking-tight">Tiga langkah sederhana</h2>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
            @foreach ([
                ['n' => '01', 'title' => 'Login Admin', 'desc' => 'Masuk menggunakan akun admin untuk membuka halaman pengelolaan data.'],
                ['n' => '02', 'title' => 'Input Kegiatan', 'desc' => 'Tambah jadwal tanam benih sampai panen, pemupukan, dan penyemprotan.'],
                ['n' => '03', 'title' => 'Pantau Dashboard', 'desc' => 'Lihat seluruh inputan, cuaca, kalender, dan berita dalam satu layar.'],
            ] as $step)
                <div class="card relative overflow-hidden p-7">
                    <span class="absolute -top-6 -right-3 text-[86px] leading-none font-extrabold text-brand-600/[.07]">{{ $step['n'] }}</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-[13px] font-extrabold text-white shadow-[0_10px_22px_-10px_rgba(20,143,83,.9)]">
                        {{ $step['n'] }}
                    </span>
                    <h3 class="mt-5 text-[16px] font-extrabold tracking-tight">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-[13.5px] leading-relaxed font-medium text-ink/50">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer class="mt-16 bg-[linear-gradient(165deg,#123f2a,#0a2c1e_60%,#071f15)] py-9 text-white/60">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-5 text-[13px] font-semibold">
        <span class="flex items-center gap-2">
            <i class="bi bi-flower1 text-brand-400"></i>
            TaniKu · Sistem Informasi Pertanian
        </span>
        <span>&copy; {{ date('Y') }}</span>
    </div>
</footer>

</body>
</html>
