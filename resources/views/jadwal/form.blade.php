@extends('layouts.app', [
    'title' => $item->exists ? 'Ubah Jadwal Tanam' : 'Tambah Jadwal Tanam',
    'subtitle' => $item->exists ? 'Perbarui data jadwal tanam' : 'Catat jadwal tanam benih sampai panen',
])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-100 text-lg text-brand-700">
                        <i class="bi bi-calendar2-plus-fill"></i>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-extrabold tracking-tight">
                            {{ $item->exists ? 'Ubah Jadwal Tanam' : 'Tambah Jadwal Tanam' }}
                        </h2>
                        <p class="text-[12px] font-semibold text-ink/40">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi</p>
                    </div>
                </div>
                <a href="{{ route('jadwal.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="{{ $item->exists ? route('jadwal.update', $item) : route('jadwal.store') }}" class="p-5 sm:p-6">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                @include('partials.form-errors')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nama_tanaman" class="label">Nama Tanaman <span class="field-req">*</span></label>
                        <input type="text" id="nama_tanaman" name="nama_tanaman" class="input"
                               value="{{ old('nama_tanaman', $item->nama_tanaman) }}" placeholder="Contoh: Padi, Jagung, Cabai" required>
                    </div>

                    <div>
                        <label for="varietas_benih" class="label">Varietas Benih</label>
                        <input type="text" id="varietas_benih" name="varietas_benih" class="input"
                               value="{{ old('varietas_benih', $item->varietas_benih) }}" placeholder="Contoh: IR-64, Hibrida F1">
                    </div>

                    <div>
                        <label for="luas_lahan" class="label">Luas Lahan (ha)</label>
                        <input type="number" step="0.01" min="0" id="luas_lahan" name="luas_lahan" class="input"
                               value="{{ old('luas_lahan', $item->luas_lahan) }}" placeholder="0.00">
                    </div>

                    <div>
                        <label for="status" class="label">Status <span class="field-req">*</span></label>
                        <select id="status" name="status" class="select" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $item->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_tanam" class="label">Tanggal Tanam <span class="field-req">*</span></label>
                        <input type="date" id="tanggal_tanam" name="tanggal_tanam" class="input"
                               value="{{ old('tanggal_tanam', optional($item->tanggal_tanam)->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="perkiraan_panen" class="label">Perkiraan Tanggal Panen</label>
                        <input type="date" id="perkiraan_panen" name="perkiraan_panen" class="input"
                               value="{{ old('perkiraan_panen', optional($item->perkiraan_panen)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="catatan" class="label">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="textarea"
                              placeholder="Catatan tambahan tentang lahan, air, atau kondisi benih...">{{ old('catatan', $item->catatan) }}</textarea>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-ink/[.06] pt-5">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Simpan</button>
                    <a href="{{ route('jadwal.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
