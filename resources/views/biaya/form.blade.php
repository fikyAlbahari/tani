@extends('layouts.app', [
    'title' => $item->exists ? 'Ubah Biaya Tanam' : 'Tambah Biaya Tanam',
    'subtitle' => $item->exists ? 'Perbarui catatan biaya tanam' : 'Catat pengeluaran selama masa tanam',
])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-lg text-emerald-600">
                        <i class="bi bi-cash-coin"></i>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-extrabold tracking-tight">
                            {{ $item->exists ? 'Ubah Biaya Tanam' : 'Tambah Biaya Tanam' }}
                        </h2>
                        <p class="text-[12px] font-semibold text-ink/40">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi</p>
                    </div>
                </div>
                <a href="{{ route('biaya.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="{{ $item->exists ? route('biaya.update', $item) : route('biaya.store') }}" class="p-5 sm:p-6">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                @include('partials.form-errors')

                <div class="grid gap-4 sm:grid-cols-2">
                    
                    <div>
                        <label for="tanggal" class="label">Tanggal Biaya <span class="field-req">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="input"
                               value="{{ old('tanggal', optional($item->tanggal)->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="nama_tanaman" class="label">Nama Tanaman <span class="field-req">*</span></label>
                        <input type="text" id="nama_tanaman" name="nama_tanaman" class="input"
                               value="{{ old('nama_tanaman', $item->nama_tanaman) }}" placeholder="Contoh: Padi IR-64, Jagung" required>
                    </div>

                    <div>
                        <label for="lokasi" class="label">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="input"
                               value="{{ old('lokasi', $item->lokasi) }}" placeholder="Contoh: Blok Utara, Desa Sukamaju">
                    </div>

                    <div>
                        <label for="bulan_tanam" class="label">Bulan Tanam</label>
                        <input type="month" id="bulan_tanam" name="bulan_tanam" class="input"
                               value="{{ old('bulan_tanam', $item->bulan_tanam) }}">
                        <p class="mt-1 text-[11px] font-semibold text-ink/40">Masa tanam sampai panen dihitung 4 bulan, dipakai untuk total biaya per siklus.</p>
                    </div>

                    <div>
                        <label for="kategori" class="label">Kategori Biaya <span class="field-req">*</span></label>
                        <select id="kategori" name="kategori" class="select" required>
                            <option value="" disabled @selected(! old('kategori', $item->kategori))>Pilih kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori', $item->kategori) === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jumlah" class="label">Jumlah Biaya (Rp) <span class="field-req">*</span></label>
                        <input type="number" id="jumlah" name="jumlah" class="input" min="0" step="100" inputmode="numeric"
                               value="{{ old('jumlah', $item->jumlah !== null ? (float) $item->jumlah : '') }}" placeholder="Contoh: 250000" required>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="uraian" class="label">Uraian Biaya <span class="field-req">*</span></label>
                    <input type="text" id="uraian" name="uraian" class="input"
                           value="{{ old('uraian', $item->uraian) }}" placeholder="Contoh: Beli benih padi 10 kg" required>
                </div>

                <div class="mt-4">
                    <label for="catatan" class="label">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="textarea"
                              placeholder="Toko, nomor nota, atau keterangan tambahan...">{{ old('catatan', $item->catatan) }}</textarea>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-ink/[.06] pt-5">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Simpan</button>
                    <a href="{{ route('biaya.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
