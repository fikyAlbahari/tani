@extends('layouts.app', ['title' => 'Biaya Tanam', 'subtitle' => 'Catatan biaya per jadwal tanam'])

@section('content')
    <div class="card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4">
            <div>
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <i class="bi bi-cash-coin"></i>
                    </span>
                    Daftar Jadwal Tanam
                </h2>
                <p class="mt-0.5 pl-10 text-[12px] font-semibold text-ink/40">
                    Pilih jadwal lalu klik Kelola untuk mengisi catatan biaya tanam
                </p>
            </div>
            <a href="{{ route('jadwal.index') }}" class="btn btn-ghost btn-sm">
                <i class="bi bi-calendar3"></i> Atur Jadwal
            </a>
        </div>

        <div class="p-5">
            @if ($jadwals->isEmpty())
                <div class="py-14 text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-ink/[.04] text-3xl text-ink/25">
                        <i class="bi bi-calendar2-x"></i>
                    </span>
                    <p class="mt-4 text-sm font-bold text-ink/45">Belum ada jadwal tanam.</p>
                    <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm mt-4">
                        <i class="bi bi-plus-lg"></i> Tambah Jadwal
                    </a>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-ink/[.06]">
                    <table class="w-full">
                        <thead class="bg-ink/[.02]">
                        <tr>
                            <th class="th rounded-tl-2xl">#</th>
                            <th class="th">Tanaman</th>
                            <th class="th">Lokasi</th>
                            <th class="th">Tanggal Tanam</th>
                            <th class="th">Status</th>
                            <th class="th">Catatan</th>
                            <th class="th text-right">Total Biaya</th>
                            <th class="th rounded-tr-2xl text-right">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($jadwals as $i => $row)
                            <tr class="row-hover">
                                <td class="td text-[12.5px] font-bold text-ink/35">{{ $i + 1 }}</td>
                                <td class="td">
                                    <span class="block text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</span>
                                    <span class="block text-[11.5px] font-semibold text-ink/45">{{ $row->varietas_benih ?: 'Varietas -' }}</span>
                                </td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->lokasi ?: '-' }}</td>
                                <td class="td text-[12.5px] font-semibold whitespace-nowrap text-ink/60">
                                    {{ $row->tanggal_tanam?->format('d M Y') }}
                                </td>
                                <td class="td">
                                    <span class="badge badge-{{ $row->statusClass() }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $row->status }}
                                    </span>
                                </td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">
                                    <i class="bi bi-receipt mr-1 text-emerald-600"></i>{{ $row->biayas_count }} catatan
                                </td>
                                <td class="td text-right text-[13px] font-extrabold whitespace-nowrap text-ink">
                                    Rp {{ number_format((float) $row->biayas_sum_jumlah, 0, ',', '.') }}
                                </td>
                                <td class="td text-right whitespace-nowrap">
                                    <a href="{{ route('biaya.kelola', ['jadwal' => $row->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-sliders2"></i> Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
