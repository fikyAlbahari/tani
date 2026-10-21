<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · TaniKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23148f53'><path d='M12 2c3 3 4.5 6 4.5 9a4.5 4.5 0 1 1-9 0c0-3 1.5-6 4.5-9Z'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed inset-y-3 left-3 z-50 flex w-[264px] -translate-x-[calc(100%+1.5rem)] flex-col rounded-3xl bg-[linear-gradient(165deg,#123f2a_0%,#0a2c1e_55%,#071f15_100%)] p-4 shadow-[0_24px_60px_-24px_rgba(10,44,30,.8)] transition-transform duration-300 ease-out lg:translate-x-0">

        <a href="{{ route('home') }}" class="flex items-center gap-3 px-2 pb-6 pt-1">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#46cb88,#148f53)] text-xl text-white shadow-[0_10px_24px_-8px_rgba(70,203,136,.9)]">
                <i class="bi bi-flower1"></i>
            </span>
            <span>
                <span class="block text-[17px] font-extrabold tracking-tight text-white">TaniKu</span>
                <span class="block text-[11px] font-semibold text-white/40">Sistem Informasi Pertanian</span>
            </span>
        </a>

        <nav class="flex-1 space-y-1 overflow-y-auto px-1">
            <p class="px-3 pb-2 text-[10.5px] font-extrabold tracking-[.16em] text-white/30 uppercase">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-base {{ request()->routeIs('dashboard') ? 'text-brand-300' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                </span>
                Dashboard
            </a>

            <a href="{{ route('jadwal.index') }}"
               class="sidebar-link {{ request()->routeIs('jadwal.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-base {{ request()->routeIs('jadwal.*') ? 'text-brand-300' : '' }}">
                    <i class="bi bi-calendar2-week-fill"></i>
                </span>
                Jadwal Tanam
            </a>

            <a href="{{ route('pemupukan.index') }}"
               class="sidebar-link {{ request()->routeIs('pemupukan.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-base {{ request()->routeIs('pemupukan.*') ? 'text-brand-300' : '' }}">
                    <i class="bi bi-droplet-fill"></i>
                </span>
                Pemupukan
            </a>

            <a href="{{ route('penyemprotan.index') }}"
               class="sidebar-link {{ request()->routeIs('penyemprotan.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-base {{ request()->routeIs('penyemprotan.*') ? 'text-brand-300' : '' }}">
                    <i class="bi bi-bug-fill"></i>
                </span>
                Penyemprotan
            </a>
        </nav>

        <div class="mt-4 rounded-2xl bg-white/[.06] p-3 ring-1 ring-white/10">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[linear-gradient(135deg,#46cb88,#127345)] text-sm font-extrabold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-[13px] font-bold text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="block truncate text-[11px] font-medium text-white/40">{{ auth()->user()->email ?? '' }}</span>
                </span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-white/5 py-2 text-[12.5px] font-bold text-white/60 transition hover:bg-rose-500 hover:text-white">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-ink/45 backdrop-blur-sm lg:hidden"></div>

    {{-- Konten --}}
    <div class="min-w-0 flex-1 lg:pl-[280px]">
        <header class="sticky top-0 z-30 border-b border-ink/[.06] bg-canvas/85 backdrop-blur-xl">
            <div class="mx-auto flex max-w-[1440px] items-center gap-4 px-5 py-4 sm:px-7">
                <button type="button" id="btnMenu" aria-label="Buka menu"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink/10 bg-white text-lg text-ink/70 shadow-sm transition hover:border-brand-300 hover:text-brand-700 lg:hidden">
                    <i class="bi bi-list"></i>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-[20px] font-extrabold tracking-tight text-ink">@yield('title', 'Dashboard')</h1>
                    <p class="truncate text-[13px] font-medium text-ink/45">@yield('subtitle', '')</p>
                </div>

                <div class="hidden items-center gap-2 rounded-2xl border border-ink/[.07] bg-white px-4 py-2.5 shadow-sm md:flex">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-100 text-brand-700">
                        <i class="bi bi-calendar3"></i>
                    </span>
                    <span class="text-[13px] font-bold text-ink/70">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1440px] px-5 pb-14 pt-6 sm:px-7">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
