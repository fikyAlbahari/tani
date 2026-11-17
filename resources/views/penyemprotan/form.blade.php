@extends('layouts.app', [
    'title' => $item->exists ? 'Ubah Penyemprotan' : 'Tambah Penyemprotan',
    'subtitle' => $item->exists ? 'Perbarui jadwal penyemprotan' : 'Catat jadwal penyemprotan & pengendalian hama',
])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-lg text-rose-500">
                        <i class="bi bi-bug-fill"></i>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-extrabold tracking-tight">
                            {{ $item->exists ? 'Ubah Penyemprotan' : 'Tambah Penyemprotan' }}
                        </h2>
                        <p class="text-[12px] font-semibold text-ink/40">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi</p>
                    </div>
                </div>
                <a href="{{ route('penyemprotan.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="{{ $item->exists ? route('penyemprotan.update', $item) : route('penyemprotan.store') }}" class="p-5 sm:p-6">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                @include('partials.form-errors')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tanggal" class="label">Tanggal Penyemprotan <span class="field-req">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="input"
                               value="{{ old('tanggal', optional($item->tanggal)->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="nama_tanaman" class="label">Nama Tanaman <span class="field-req">*</span></label>
                        <input type="text" id="nama_tanaman" name="nama_tanaman" class="input"
                               value="{{ old('nama_tanaman', $item->nama_tanaman) }}" placeholder="Contoh: Padi, Cabai" required>
                    </div>

                    <div>
                        <label for="lokasi" class="label">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="input"
                               value="{{ old('lokasi', $item->lokasi) }}" placeholder="Contoh: Blok Utara, Desa Sukamaju">
                    </div>

                    <div>
                        <label for="jenis_pestisida" class="label">Jenis Pestisida / Obat <span class="field-req">*</span></label>
                        <input type="text" id="jenis_pestisida" name="jenis_pestisida" class="input"
                               value="{{ old('jenis_pestisida', $item->jenis_pestisida) }}" placeholder="Contoh: Imidakloprid, Nabati" required>
                    </div>

                    <div>
                        <label for="dosis" class="label">Dosis</label>
                        <input type="text" id="dosis" name="dosis" class="input"
                               value="{{ old('dosis', $item->dosis) }}" placeholder="Contoh: 2 mL/L air">
                    </div>

                    <div>
                        <label for="target_hama" class="label">Target Hama / Penyakit</label>
                        <input type="text" id="target_hama" name="target_hama" class="input"
                               value="{{ old('target_hama', $item->target_hama) }}" placeholder="Contoh: Ulat grayak, wereng">
                    </div>

                    <div>
                        <label for="status" class="label">Status <span class="field-req">*</span></label>
                        <select id="status" name="status" class="select" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $item->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="catatan" class="label">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="textarea"
                              placeholder="Cuaca saat semprot, kondisi hama, atau hasil pengamatan...">{{ old('catatan', $item->catatan) }}</textarea>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-ink/[.06] pt-5">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Simpan</button>
                    <a href="{{ route('penyemprotan.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
