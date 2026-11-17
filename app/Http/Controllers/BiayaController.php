<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BiayaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Biaya::query()->orderByDesc('tanggal');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(fn ($w) => $w->where('nama_tanaman', 'like', "%{$q}%")
                ->orWhere('lokasi', 'like', "%{$q}%")
                ->orWhere('uraian', 'like', "%{$q}%")
                ->orWhere('bulan_tanam', 'like', "%{$q}%"));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->string('kategori')->toString());
        }

        if ($request->filled('bulan')) {
            $query->where('bulan_tanam', $request->string('bulan')->toString());
        }

        $total = (clone $query)->sum('jumlah');

        return view('biaya.index', [
            'items' => $query->paginate(10)->withQueryString(),
            'kategoris' => Biaya::KATEGORIS,
            'bulans' => $this->bulanOptions(),
            'filters' => $request->only(['q', 'kategori', 'bulan']),
            'total' => $total,
        ]);
    }

    public function create(): View
    {
        return view('biaya.form', [
            'item' => new Biaya(['tanggal' => now()->toDateString()]),
            'kategoris' => Biaya::KATEGORIS,
            'bulans' => $this->bulanOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['bulan_tanam'] = ($data['bulan_tanam'] ?? null) ?: null;
        $data['user_id'] = $request->user()->id;

        Biaya::create($data);

        return redirect()->route('biaya.index')->with('success', 'Biaya tanam berhasil ditambahkan.');
    }

    public function edit(Biaya $biaya): View
    {
        return view('biaya.form', [
            'item' => $biaya,
            'kategoris' => Biaya::KATEGORIS,
            'bulans' => $this->bulanOptions(),
        ]);
    }

    public function update(Request $request, Biaya $biaya): RedirectResponse
    {
        $data = $this->validated($request);
        $data['bulan_tanam'] = ($data['bulan_tanam'] ?? null) ?: null;

        $biaya->update($data);

        return redirect()->route('biaya.index')->with('success', 'Biaya tanam berhasil diperbarui.');
    }

    public function destroy(Biaya $biaya): RedirectResponse
    {
        $biaya->delete();

        return redirect()->route('biaya.index')->with('success', 'Biaya tanam berhasil dihapus.');
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function bulanOptions()
    {
        return Biaya::query()
            ->whereNotNull('bulan_tanam')
            ->where('bulan_tanam', '<>', '')
            ->distinct()
            ->orderByDesc('bulan_tanam')
            ->pluck('bulan_tanam');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'bulan_tanam' => ['nullable', 'date_format:Y-m'],
            'kategori' => ['required', 'in:'.implode(',', Biaya::KATEGORIS)],
            'uraian' => ['required', 'string', 'max:150'],
            'jumlah' => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal.required' => 'Tanggal biaya wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama_tanaman.required' => 'Nama tanaman wajib diisi.',
            'bulan_tanam.date_format' => 'Format bulan tanam tidak valid.',
            'kategori.required' => 'Kategori biaya wajib dipilih.',
            'kategori.in' => 'Kategori biaya tidak valid.',
            'uraian.required' => 'Uraian biaya wajib diisi.',
            'jumlah.required' => 'Jumlah biaya wajib diisi.',
            'jumlah.numeric' => 'Jumlah biaya harus berupa angka.',
            'jumlah.min' => 'Jumlah biaya tidak boleh negatif.',
        ]);
    }
}
