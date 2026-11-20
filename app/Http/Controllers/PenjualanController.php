<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Penjualan::query()->orderByDesc('tanggal')->orderByDesc('id');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(fn ($w) => $w->where('nama_tanaman', 'like', "%{$q}%")
                ->orWhere('lokasi', 'like', "%{$q}%")
                ->orWhere('pembeli', 'like', "%{$q}%")
                ->orWhere('catatan', 'like', "%{$q}%"));
        }

        if ($request->filled('tanaman')) {
            $query->where('nama_tanaman', $request->string('tanaman')->toString());
        }

        if ($request->filled('bulan')) {
            $bulan = $request->string('bulan')->toString();

            if (preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
                $query->whereYear('tanggal', (int) $m[1])->whereMonth('tanggal', (int) $m[2]);
            }
        }

        $total = (clone $query)->sum('total');

        return view('penjualan.index', [
            'items' => $query->paginate(10)->withQueryString(),
            'tanamans' => $this->tanamanOptions(),
            'bulans' => $this->bulanOptions(),
            'filters' => $request->only(['q', 'tanaman', 'bulan']),
            'total' => $total,
        ]);
    }

    public function create(): View
    {
        return view('penjualan.form', [
            'item' => new Penjualan(['tanggal' => now()->toDateString(), 'satuan' => 'kg']),
            'satuans' => Penjualan::SATUANS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        Penjualan::create($data);

        return redirect()->route('penjualan.index')->with('success', 'Hasil penjualan berhasil ditambahkan.');
    }

    public function edit(Penjualan $penjualan): View
    {
        return view('penjualan.form', [
            'item' => $penjualan,
            'satuans' => Penjualan::SATUANS,
        ]);
    }

    public function update(Request $request, Penjualan $penjualan): RedirectResponse
    {
        $penjualan->update($this->validated($request));

        return redirect()->route('penjualan.index')->with('success', 'Hasil penjualan berhasil diperbarui.');
    }

    public function destroy(Penjualan $penjualan): RedirectResponse
    {
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Hasil penjualan berhasil dihapus.');
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function tanamanOptions()
    {
        return Penjualan::query()
            ->select('nama_tanaman')
            ->distinct()
            ->orderBy('nama_tanaman')
            ->pluck('nama_tanaman');
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function bulanOptions()
    {
        return Penjualan::query()
            ->orderByDesc('tanggal')
            ->get(['tanggal'])
            ->map(fn (Penjualan $p) => $p->tanggal?->format('Y-m'))
            ->filter()
            ->unique()
            ->values();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'luas' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'jumlah' => ['required', 'numeric', 'gt:0', 'max:9999999999'],
            'satuan' => ['required', 'in:'.implode(',', Penjualan::SATUANS)],
            'harga_satuan' => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'pembeli' => ['nullable', 'string', 'max:150'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal.required' => 'Tanggal penjualan wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama_tanaman.required' => 'Nama tanaman wajib diisi.',
            'luas.numeric' => 'Luas lahan harus berupa angka.',
            'luas.min' => 'Luas lahan tidak boleh negatif.',
            'jumlah.required' => 'Jumlah hasil panen wajib diisi.',
            'jumlah.numeric' => 'Jumlah hasil panen harus berupa angka.',
            'jumlah.gt' => 'Jumlah hasil panen harus lebih dari 0.',
            'satuan.required' => 'Satuan wajib dipilih.',
            'satuan.in' => 'Satuan tidak valid.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
        ]);
    }
}
