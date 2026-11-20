@extends('layouts.app', ['title' => 'Kelola Penyemprotan', 'subtitle' => 'Catatan penyemprotan sesuai jadwal tanam'])

@section('content')
    <div class="space-y-5">
        <div class="card flex flex-wrap items-center justify-between gap-3 px-5 py-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-lg text-rose-500">
                    <i class="bi bi-bug-fill"></i>
                </span>
                <div>
                    <h2 class="text-[15px] font-extrabold tracking-tight">Kelola Penyemprotan per Jadwal</h2>
                    <p class="text-[12px] font-semibold text-ink/40">Pilih jadwal tanam agar catatan tidak menumpuk</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($jadwal)
                    <a href="{{ route('penyemprotan.create', ['jadwal' => $jadwal->id, 'asal' => 'kelola']) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Tambah Penyemprotan
                    </a>
                @endif
                <a href="{{ route('penyemprotan.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Daftar Jadwal</a>
            </div>
        </div>

        <!-- <div class="card p-4 sm:p-5">
            <form method="GET" action="{{ route('penyemprotan.kelola') }}" class="flex flex-wrap items-end gap-2">
                <div class="min-w-[240px] flex-1">
                    <label for="jadwal" class="label">Jadwal Tanam</label>
                    <select id="jadwal" name="jadwal" class="select" onchange="this.form.submit()">
                        <option value="">Pilih jadwal tanam</option>
                        @foreach ($jadwals as $pilihan)
                            <option value="{{ $pilihan->id }}" @selected($jadwal?->id === $pilihan->id)>
                                {{ $pilihan->nama_tanaman }} · {{ $pilihan->tanggal_tanam?->format('d M Y') }}
                                {{ $pilihan->lokasi ? '· '.$pilihan->lokasi : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-sm"><i class="bi bi-funnel"></i> Tampilkan</button>
            </form>
        </div> -->

        @if (! $jadwal)
            <div class="card px-5 py-14 text-center">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-ink/[.04] text-3xl text-ink/25">
                    <i class="bi bi-calendar2-x"></i>
                </span>
                <p class="mt-4 text-sm font-bold text-ink/45">Belum ada jadwal tanam yang bisa dipilih.</p>
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm mt-4"><i class="bi bi-plus-lg"></i> Tambah Jadwal</a>
            </div>
        @else
            <div class="card flex flex-wrap items-center justify-between gap-3 border-l-4 border-l-rose-400 px-5 py-4">
                <div class="min-w-0">
                    <h3 class="truncate text-[14px] font-extrabold text-ink">{{ $jadwal->nama_tanaman }}</h3>
                    <p class="truncate text-[12px] font-semibold text-ink/45">
                        {{ $jadwal->varietas_benih ?: 'Varietas -' }}
                        &middot; {{ $jadwal->lokasi ?: 'Lokasi -' }}
                        &middot; {{ $jadwal->nama_penggarap ?: 'Penggarap -' }}
                        &middot; Tanam {{ $jadwal->tanggal_tanam?->format('d M Y') }}
                    </p>
                </div>
                <span class="badge badge-{{ $jadwal->statusClass() }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $jadwal->status }}
                </span>
            </div>

            <div class="card p-4 sm:p-5">
                <div class="mt-4">
                    @if ($items->isEmpty())
                        <div class="py-12 text-center">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-ink/[.04] text-2xl text-ink/25">
                                <i class="bi bi-bug"></i>
                            </span>
                            <p class="mt-3 text-sm font-bold text-ink/45">Belum ada penyemprotan untuk jadwal ini.</p>
                        </div>
                    @else
                        <div class="mb-3">
                            <p class="text-[12.5px] font-extrabold text-ink/50">{{ $items->count() }} catatan penyemprotan</p>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-ink/[.06]">
                            <table class="w-full">
                                <thead class="bg-ink/[.02]">
                                <tr>
                                    <th class="th">Tanggal</th>
                                    <th class="th">Jenis Pestisida</th>
                                    <th class="th">Dosis</th>
                                    <th class="th">Target Hama</th>
                                    <th class="th">Status</th>
                                    <th class="th text-right">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $row)
                                    <tr class="row-hover">
                                        <td class="td text-[12.5px] font-semibold whitespace-nowrap text-ink/60">{{ $row->tanggal->format('d M Y') }}</td>
                                        <td class="td text-[13px] font-bold text-ink">{{ $row->jenis_pestisida }}</td>
                                        <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->dosis ?: '-' }}</td>
                                        <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->target_hama ?: '-' }}</td>
                                        <td class="td">
                                            <span class="badge badge-{{ $row->statusClass() }}">
                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $row->status }}
                                            </span>
                                        </td>
                                        <td class="td text-right whitespace-nowrap">
                                            <a href="{{ route('penyemprotan.edit', ['penyemprotan' => $row, 'asal' => 'kelola']) }}" class="icon-btn" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                            <form method="POST" action="{{ route('penyemprotan.destroy', $row) }}" class="inline"
                                                  onsubmit="return confirm('Hapus penyemprotan {{ $row->jenis_pestisida }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="asal" value="kelola">
                                                <input type="hidden" name="jadwal_tanam_id" value="{{ $jadwal->id }}">
                                                <button type="submit" class="icon-btn icon-btn-danger" title="Hapus"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
