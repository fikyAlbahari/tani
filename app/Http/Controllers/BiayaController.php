<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\JadwalTanam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BiayaController extends Controller
{
    public function index(): View
    {
        $jadwals = JadwalTanam::query()
            ->withCount('biayas')
            ->withSum('biayas', 'jumlah')
            ->orderByDesc('tanggal_tanam')
            ->get();

        return view('biaya.index', [
            'jadwals' => $jadwals,
        ]);
    }

    public function kelola(Request $request): View
    {
        $jadwals = JadwalTanam::query()->orderByDesc('tanggal_tanam')->get();

        $jadwal = $request->filled('jadwal')
            ? JadwalTanam::find($request->integer('jadwal'))
            : $jadwals->first();

        $items = $jadwal
            ? Biaya::where('jadwal_tanam_id', $jadwal->id)->orderByDesc('tanggal')->orderByDesc('id')->get()
            : collect();

        return view('biaya.kelola', [
            'jadwals' => $jadwals,
            'jadwal' => $jadwal,
            'items' => $items,
            'kategoris' => Biaya::KATEGORIS,
            'total' => (float) $items->sum(fn (Biaya $biaya) => (float) $biaya->jumlah),
        ]);
    }

    public function create(Request $request): View
    {
        $jadwal = $this->jadwalDari($request);

        return view('biaya.form', [
            'item' => new Biaya([
                'tanggal' => now()->toDateString(),
                'jadwal_tanam_id' => $jadwal?->id,
                'nama_tanaman' => $jadwal?->nama_tanaman,
                'lokasi' => $jadwal?->lokasi,
                'bulan_tanam' => $jadwal?->tanggal_tanam?->format('Y-m'),
            ]),
            'kategoris' => Biaya::KATEGORIS,
            'bulans' => $this->bulanOptions(),
            'jadwal' => $jadwal,
            'dariJadwal' => $jadwal !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['bulan_tanam'] = ($data['bulan_tanam'] ?? null) ?: null;
        $data['user_id'] = $request->user()->id;

        $biaya = Biaya::create($data);

        return $this->kembali($request, $biaya->jadwal_tanam_id)
            ->with('success', 'Biaya tanam berhasil ditambahkan.');
    }

    public function edit(Request $request, Biaya $biaya): View
    {
        return view('biaya.form', [
            'item' => $biaya,
            'kategoris' => Biaya::KATEGORIS,
            'bulans' => $this->bulanOptions(),
            'jadwal' => $biaya->jadwalTanam,
            'dariJadwal' => $request->query('asal') === 'kelola',
        ]);
    }

    public function update(Request $request, Biaya $biaya): RedirectResponse
    {
        $data = $this->validated($request);
        $data['bulan_tanam'] = ($data['bulan_tanam'] ?? null) ?: null;

        $biaya->update($data);

        return $this->kembali($request, $biaya->jadwal_tanam_id)
            ->with('success', 'Biaya tanam berhasil diperbarui.');
    }

    public function destroy(Request $request, Biaya $biaya): RedirectResponse
    {
        $jadwalId = $biaya->jadwal_tanam_id ?? ($request->integer('jadwal_tanam_id') ?: null);

        $biaya->delete();

        return $this->kembali($request, $jadwalId)->with('success', 'Biaya tanam berhasil dihapus.');
    }

    private function jadwalDari(Request $request): ?JadwalTanam
    {
        return $request->filled('jadwal')
            ? JadwalTanam::find($request->integer('jadwal'))
            : null;
    }

    private function kembali(Request $request, ?int $jadwalId): RedirectResponse
    {
        return $jadwalId && $request->input('asal') === 'kelola'
            ? redirect()->route('biaya.kelola', ['jadwal' => $jadwalId])
            : redirect()->route('biaya.index');
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
            'jadwal_tanam_id' => ['nullable', 'exists:jadwal_tanams,id'],
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
