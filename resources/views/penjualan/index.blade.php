@extends('layouts.app', ['title' => 'Hasil Penjualan', 'subtitle' => 'Catatan pendapatan hasil panen'])

@section('content')
    <div class="card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4">
            <div>
                <h2 class="flex items-center gap-2 text-[15px] font-extrabold tracking-tight">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-100 text-brand-700">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    Data Hasil Penjualan
                </h2>
                <p class="mt-0.5 pl-10 text-[12px] font-semibold text-ink/40">{{ $items->total() }} transaksi penjualan</p>
            </div>
            <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Penjualan
            </a>
        </div>

        <div class="p-5">
            @include('partials.form-errors')

            <form method="GET" action="{{ route('penjualan.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
                <div class="relative min-w-[220px] flex-1">
                    <i class="bi bi-search absolute top-1/2 left-3.5 -translate-y-1/2 text-ink/30"></i>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="input pl-10"
                           placeholder="Cari tanaman / lokasi / pembeli / catatan...">
                </div>
                <select name="tanaman" class="select w-auto">
                    <option value="">Semua Tanaman</option>
                    @foreach ($tanamans as $tanaman)
                        <option value="{{ $tanaman }}" @selected(($filters['tanaman'] ?? '') === $tanaman)>{{ $tanaman }}</option>
                    @endforeach
                </select>
                <select name="bulan" class="select w-auto">
                    <option value="">Semua Bulan</option>
                    @foreach ($bulans as $bulan)
                        <option value="{{ $bulan }}" @selected(($filters['bulan'] ?? '') === $bulan)>
                            {{ \Carbon\Carbon::createFromFormat('!Y-m', $bulan)->translatedFormat('F Y') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark btn-sm"><i class="bi bi-funnel"></i> Filter</button>
                @if (request()->hasAny(['q', 'tanaman', 'bulan']))
                    <a href="{{ route('penjualan.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </form>

            @if ($items->isEmpty())
                <div class="py-14 text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-ink/[.04] text-3xl text-ink/25">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    <p class="mt-4 text-sm font-bold text-ink/45">Belum ada hasil penjualan yang cocok dengan filter.</p>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm mt-4"><i class="bi bi-plus-lg"></i> Tambah Penjualan</a>
                </div>
            @else
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2 rounded-2xl bg-brand-50 px-4 py-3 ring-1 ring-brand-100">
                    <span class="flex items-center gap-2 text-[12.5px] font-bold text-brand-900">
                        <i class="bi bi-cash-stack"></i> Total pendapatan sesuai filter
                    </span>
                    <span class="text-[15px] font-extrabold text-brand-700">
                        Rp {{ number_format((float) $total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-ink/[.06]">
                    <table class="w-full">
                        <thead class="bg-ink/[.02]">
                        <tr>
                            <th class="th rounded-tl-2xl">#</th>
                            <th class="th">Tanggal</th>
                            <th class="th">Tanaman</th>
                            <th class="th">Lokasi</th>
                            <th class="th">Luas</th>
                            <th class="th">Jumlah</th>
                            <th class="th text-right">Harga Satuan</th>
                            <th class="th text-right">Total</th>
                            <th class="th">Pembeli</th>
                            <th class="th rounded-tr-2xl text-right">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $i => $row)
                            <tr class="row-hover">
                                <td class="td text-[12.5px] font-bold text-ink/35">{{ $items->firstItem() + $i }}</td>
                                <td class="td text-[12.5px] font-semibold whitespace-nowrap text-ink/60">{{ $row->tanggal->format('d M Y') }}</td>
                                <td class="td text-[13.5px] font-bold text-ink">{{ $row->nama_tanaman }}</td>
                                <td class="td max-w-[200px] text-[12.5px] font-semibold text-ink/60">
                                    <span class="block truncate" title="{{ $row->lokasi }}">{{ $row->lokasi ?: '-' }}</span>
                                </td>
                                <td class="td whitespace-nowrap text-[12.5px] font-semibold text-ink/60">{{ $row->luasLabel() }}</td>
                                <td class="td whitespace-nowrap">
                                    <span class="badge badge-info">{{ $row->jumlahLabel() }}</span>
                                </td>
                                <td class="td text-right text-[12.5px] font-semibold whitespace-nowrap text-ink/60">
                                    Rp {{ number_format((float) $row->harga_satuan, 0, ',', '.') }}/{{ $row->satuan }}
                                </td>
                                <td class="td text-right text-[13px] font-extrabold whitespace-nowrap text-brand-700">
                                    Rp {{ number_format((float) $row->total, 0, ',', '.') }}
                                </td>
                                <td class="td max-w-[180px] text-[12.5px] font-semibold text-ink/60">
                                    <span class="block truncate" title="{{ $row->pembeli }}">{{ $row->pembeli ?: '-' }}</span>
                                </td>
                                <td class="td text-right whitespace-nowrap">
                                    <a href="{{ route('penjualan.edit', $row) }}" class="icon-btn" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                    <form method="POST" action="{{ route('penjualan.destroy', $row) }}" class="inline"
                                          onsubmit="return confirm('Hapus penjualan {{ $row->nama_tanaman }} tanggal {{ $row->tanggal->format('d M Y') }}?');">
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
