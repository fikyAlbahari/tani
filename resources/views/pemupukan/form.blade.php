@extends('layouts.app', [
    'title' => $item->exists ? 'Ubah Pemupukan' : 'Tambah Pemupukan',
    'subtitle' => $item->exists ? 'Perbarui jadwal pemupukan' : 'Catat jadwal pemupukan tanaman',
])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-lg text-amber-600">
                        <i class="bi bi-droplet-fill"></i>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-extrabold tracking-tight">
                            {{ $item->exists ? 'Ubah Pemupukan' : 'Tambah Pemupukan' }}
                        </h2>
                        <p class="text-[12px] font-semibold text-ink/40">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi</p>
                    </div>
                </div>
                <a href="{{ route('pemupukan.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="{{ $item->exists ? route('pemupukan.update', $item) : route('pemupukan.store') }}" class="p-5 sm:p-6">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                @include('partials.form-errors')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tanggal" class="label">Tanggal Pemupukan <span class="field-req">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="input"
                               value="{{ old('tanggal', optional($item->tanggal)->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="nama_tanaman" class="label">Nama Tanaman <span class="field-req">*</span></label>
                        <input type="text" id="nama_tanaman" name="nama_tanaman" class="input"
                               value="{{ old('nama_tanaman', $item->nama_tanaman) }}" placeholder="Contoh: Padi, Tomat" required>
                    </div>

                    <div>
                        <label for="lokasi" class="label">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="input"
                               value="{{ old('lokasi', $item->lokasi) }}" placeholder="Contoh: Blok Utara, Desa Sukamaju">
                    </div>

                    <div>
                        <label for="jenis_pupuk" class="label">Jenis Pupuk <span class="field-req">*</span></label>
                        <input type="text" id="jenis_pupuk" name="jenis_pupuk" class="input"
                               value="{{ old('jenis_pupuk', $item->jenis_pupuk) }}" placeholder="Contoh: NPK, Urea, Kandang" required>
                    </div>

                    <div>
                        <label for="dosis" class="label">Dosis</label>
                        <input type="text" id="dosis" name="dosis" class="input"
                               value="{{ old('dosis', $item->dosis) }}" placeholder="Contoh: 250 kg/ha">
                    </div>

                    <div>
                        <label for="metode" class="label">Metode Aplikasi</label>
                        <input type="text" id="metode" name="metode" class="input"
                               value="{{ old('metode', $item->metode) }}" placeholder="Contoh: Tabur, Kocor, Foliar">
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
                              placeholder="Kondisi lahan, cuaca, atau hasil pemupukan...">{{ old('catatan', $item->catatan) }}</textarea>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-ink/[.06] pt-5">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Simpan</button>
                    <a href="{{ route('pemupukan.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
