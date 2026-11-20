@extends('layouts.app', ['title' => 'Pemupukan', 'subtitle' => 'Jadwal dan pelaksanaan pemupukan'])

@section('content')
    <div class="card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4">
            <div>
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i class="bi bi-droplet-fill"></i>
                    </span>
                    Data Pemupukan
                </h2>
                <p class="mt-0.5 pl-10 text-[12px] font-semibold text-ink/40">{{ $items->total() }} jadwal tercatat</p>
            </div>
            <a href="{{ route('pemupukan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Pemupukan
            </a>
        </div>

        <div class="p-5">
            @include('partials.form-errors')

            <form method="GET" action="{{ route('pemupukan.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
                <div class="relative min-w-[220px] flex-1">
                    <i class="bi bi-search absolute top-1/2 left-3.5 -translate-y-1/2 text-ink/30"></i>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="input pl-10"
                           placeholder="Cari tanaman / lokasi / jenis pupuk...">
                </div>
                <select name="status" class="select w-auto">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark btn-sm"><i class="bi bi-funnel"></i> Filter</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('pemupukan.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </form>

            @if ($items->isEmpty())
                <div class="py-14 text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-ink/[.04] text-3xl text-ink/25">
                        <i class="bi bi-droplet-half"></i>
                    </span>
                    <p class="mt-4 text-sm font-bold text-ink/45">Belum ada jadwal pemupukan yang cocok dengan filter.</p>
                    <a href="{{ route('pemupukan.create') }}" class="btn btn-primary btn-sm mt-4"><i class="bi bi-plus-lg"></i> Tambah Pemupukan</a>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-ink/[.06]">
                    <table class="w-full">
                        <thead class="bg-ink/[.02]">
                        <tr>
                            <th class="th rounded-tl-2xl">#</th>
                            <th class="th">Tanggal</th>
                            <th class="th">Tanaman</th>
                            <th class="th">Lokasi</th>
                            <th class="th">Jenis Pupuk</th>
                            <th class="th">Dosis</th>
                            <th class="th">Metode</th>
                            <th class="th">Status</th>
                            <th class="th rounded-tr-2xl text-right">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $i => $row)
                            <tr class="row-hover">
                                <td class="td text-[12.5px] font-bold text-ink/35">{{ $items->firstItem() + $i }}</td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->tanggal->format('d M Y') }}</td>
                                <td class="td">
                                    <span class="block text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</span>
                                    @if (in_array($urutan[$row->id] ?? null, [1, 2], true))
                                        <span class="badge badge-{{ ($urutan[$row->id] ?? null) === 1 ? 'info' : 'warning' }} mt-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>Pemupukan ke-{{ $urutan[$row->id] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->lokasi ?: '-' }}</td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->jenis_pupuk }}</td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->dosis ?: '-' }}</td>
                                <td class="td text-[12.5px] font-semibold text-ink/60">{{ $row->metode ?: '-' }}</td>
                                <td class="td">
                                    <span class="badge badge-{{ $row->statusClass() }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $row->status }}
                                    </span>
                                </td>
                                <td class="td text-right whitespace-nowrap">
                                    <a href="{{ route('pemupukan.edit', $row) }}" class="icon-btn" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                    <form method="POST" action="{{ route('pemupukan.destroy', $row) }}" class="inline"
                                          onsubmit="return confirm('Hapus jadwal pemupukan {{ $row->nama_tanaman }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn icon-btn-danger" title="Hapus"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @include('partials.pagination', ['items' => $items])
            @endif
        </div>
    </div>
@endsection
