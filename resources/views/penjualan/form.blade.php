@extends('layouts.app', [
    'title' => $item->exists ? 'Ubah Hasil Penjualan' : 'Tambah Hasil Penjualan',
    'subtitle' => $item->exists ? 'Perbarui catatan hasil penjualan panen' : 'Catat pendapatan dari hasil panen',
])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="flex items-center justify-between gap-3 border-b border-ink/[.07] px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-100 text-lg text-brand-700">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-extrabold tracking-tight">
                            {{ $item->exists ? 'Ubah Hasil Penjualan' : 'Tambah Hasil Penjualan' }}
                        </h2>
                        <p class="text-[12px] font-semibold text-ink/40">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi</p>
                    </div>
                </div>
                <a href="{{ route('penjualan.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="{{ $item->exists ? route('penjualan.update', $item) : route('penjualan.store') }}" class="p-5 sm:p-6">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                @include('partials.form-errors')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tanggal" class="label">Tanggal Penjualan <span class="field-req">*</span></label>
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
                        <label for="luas" class="label">Luas Lahan (ha)</label>
                        <input type="number" id="luas" name="luas" class="input" min="0" step="0.01" inputmode="decimal"
                               value="{{ old('luas', $item->luas !== null ? (float) $item->luas : '') }}" placeholder="Contoh: 0.5">
                    </div>

                    <div>
                        <label for="pembeli" class="label">Pembeli</label>
                        <input type="text" id="pembeli" name="pembeli" class="input"
                               value="{{ old('pembeli', $item->pembeli) }}" placeholder="Contoh: Koperasi Tani Makmur">
                    </div>

                    <div>
                        <label for="jumlah" class="label">Jumlah Hasil Panen <span class="field-req">*</span></label>
                        <input type="number" id="jumlah" name="jumlah" class="input" min="0.01" step="0.01" inputmode="decimal"
                               value="{{ old('jumlah', $item->jumlah !== null ? (float) $item->jumlah : '') }}" placeholder="Contoh: 1200" required>
                    </div>

                    <div>
                        <label for="satuan" class="label">Satuan <span class="field-req">*</span></label>
                        <select id="satuan" name="satuan" class="select" required>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan }}" @selected(old('satuan', $item->satuan ?: 'kg') === $satuan)>{{ $satuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="harga_satuan" class="label">Harga per Satuan (Rp) <span class="field-req">*</span></label>
                        <input type="number" id="harga_satuan" name="harga_satuan" class="input" min="0" step="100" inputmode="numeric"
                               value="{{ old('harga_satuan', $item->harga_satuan !== null ? (float) $item->harga_satuan : '') }}" placeholder="Contoh: 6500" required>
                    </div>

                    <div>
                        <label class="label">Total Pendapatan</label>
                        <div id="totalPreview"
                             class="flex h-[46px] items-center rounded-xl border border-brand-200 bg-brand-50 px-3.5 text-[15px] font-extrabold text-brand-700">
                            Rp 0
                        </div>
                        <p class="mt-1 text-[11px] font-semibold text-ink/40">Dihitung otomatis dari jumlah × harga satuan.</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="catatan" class="label">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="textarea"
                              placeholder="Kualitas hasil panen, nomor nota, atau keterangan tambahan...">{{ old('catatan', $item->catatan) }}</textarea>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-ink/[.06] pt-5">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Simpan</button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const jumlah = document.getElementById('jumlah');
            const harga = document.getElementById('harga_satuan');
            const preview = document.getElementById('totalPreview');
            const rupiah = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });

            function updateTotal() {
                const total = (parseFloat(jumlah?.value) || 0) * (parseFloat(harga?.value) || 0);
                preview.textContent = 'Rp ' + rupiah.format(total);
            }

            jumlah?.addEventListener('input', updateTotal);
            harga?.addEventListener('input', updateTotal);
            updateTotal();
        })();
    </script>
@endpush
