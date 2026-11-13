<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin · TaniKu</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23148f53'><rect x='11.2' y='8.2' width='1.6' height='13.6' rx='.8'/><ellipse cx='12' cy='4.3' rx='1.6' ry='2.7'/><ellipse cx='8.85' cy='6.9' rx='1.6' ry='2.7' transform='rotate(-31 8.85 6.9)'/><ellipse cx='15.15' cy='6.9' rx='1.6' ry='2.7' transform='rotate(31 15.15 6.9)'/><ellipse cx='8.55' cy='10.3' rx='1.6' ry='2.7' transform='rotate(-31 8.55 10.3)'/><ellipse cx='15.45' cy='10.3' rx='1.6' ry='2.7' transform='rotate(31 15.45 10.3)'/><path d='M11.4 15.2C8.6 15.2 6.6 13.4 6.3 10.8c2.8 0 4.9 1.8 5.1 4.4Z'/><path d='M12.6 18c2.8 0 4.8-1.8 5.1-4.4-2.8 0-4.9 1.8-5.1 4.4Z'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas">

<div class="grid min-h-screen lg:grid-cols-2">

    {{-- Panel kiri --}}
    <div class="relative hidden overflow-hidden bg-[linear-gradient(160deg,#148f53_0%,#0f5f3a_45%,#0a2c1e_100%)] p-12 text-white lg:flex lg:flex-col lg:justify-between">
        <div class="pointer-events-none absolute -top-24 -left-20 h-96 w-96 rounded-full bg-brand-300/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-10 h-96 w-96 rounded-full bg-sky-300/10 blur-3xl"></div>

        <a href="{{ route('home') }}" class="relative flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 text-white ring-1 ring-white/20 backdrop-blur">
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
            <span>
                <span class="block text-[18px] font-extrabold tracking-tight">TaniKu</span>
                <span class="block text-[11.5px] font-semibold text-white/50">Sistem Informasi Pertanian</span>
            </span>
        </a>

        <div class="relative">
            <h2 class="max-w-md text-[34px] leading-[1.15] font-extrabold tracking-tight">
                Selamat datang kembali, Admin
            </h2>
            <p class="mt-4 max-w-md text-[15px] leading-relaxed font-medium text-white/65">
                Kelola jadwal tanam, pemupukan, penyemprotan, dan pantau pertanian lewat satu dashboard.
            </p>

            <ul class="mt-9 space-y-3.5">
                @foreach ([
                    'Input jadwal tanam benih sampai panen',
                    'Jadwal pemupukan &amp; penyemprotan',
                    'Cuaca, kalender, dan berita pertanian',
                ] as $feature)
                    <li class="flex items-center gap-3 text-[14.5px] font-semibold text-white/85">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/15 text-[12px] ring-1 ring-white/20">
                            <i class="bi bi-check-lg"></i>
                        </span>
                        <span>{!! $feature !!}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="relative text-[12.5px] font-semibold text-white/40">&copy; {{ date('Y') }} TaniKu</p>
    </div>

    {{-- Panel kanan --}}
    <div class="flex items-center justify-center px-5 py-12 sm:px-10">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 text-[13px] font-bold text-ink/45 transition hover:text-brand-700 lg:hidden">
                <i class="bi bi-arrow-left"></i> Kembali ke beranda
            </a>

            <div class="card p-7 sm:p-9">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-100 text-xl text-brand-700">
                    <i class="bi bi-person-badge-fill"></i>
                </span>
                <h1 class="mt-5 text-[24px] font-extrabold tracking-tight">Login Admin</h1>
                <p class="mt-1 text-[13.5px] font-medium text-ink/45">Gunakan akun admin Anda untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-7">
                    @csrf

                    @include('partials.form-errors')

                    <div>
                        <label for="email" class="label">Email</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute top-1/2 left-3.5 -translate-y-1/2 text-ink/30"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="input pl-10" placeholder="admin@tani.test" required autofocus>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <i class="bi bi-lock absolute top-1/2 left-3.5 -translate-y-1/2 text-ink/30"></i>
                            <input type="password" id="password" name="password" class="input pr-12 pl-10" placeholder="••••••••" required>
                            <button type="button" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false"
                                    class="absolute top-1/2 right-2 flex h-8 w-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-lg text-ink/35 transition hover:bg-brand-50 hover:text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/40">
                                <i id="togglePasswordIcon" class="bi bi-eye text-[15px]"></i>
                            </button>
                        </div>
                    </div>

                    <label class="mt-4 flex cursor-pointer items-center gap-2.5 text-[13px] font-bold text-ink/55">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-ink/20 accent-brand-600">
                        Ingat saya
                    </label>

                    <button type="submit" class="btn btn-primary mt-5 w-full py-3.5 text-[15px]">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </form>
            </div>

            <a href="{{ route('home') }}" class="mt-6 hidden items-center justify-center gap-2 text-[13px] font-bold text-ink/45 transition hover:text-brand-700 lg:flex">
                <i class="bi bi-arrow-left"></i> Kembali ke beranda
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('password');
        const button = document.getElementById('togglePassword');
        const icon = document.getElementById('togglePasswordIcon');

        if (!input || !button || !icon) return;

        button.addEventListener('click', function () {
            const revealed = input.type === 'password';

            input.type = revealed ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !revealed);
            icon.classList.toggle('bi-eye-slash', revealed);
            button.setAttribute('aria-label', revealed ? 'Sembunyikan password' : 'Tampilkan password');
            button.setAttribute('aria-pressed', revealed ? 'true' : 'false');
            input.focus();
        });
    });
</script>

</body>
</html>
