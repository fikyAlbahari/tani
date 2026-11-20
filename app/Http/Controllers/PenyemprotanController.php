<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use App\Models\Penyemprotan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenyemprotanController extends Controller
{
    public function index(): View
    {
        $jadwals = JadwalTanam::query()
            ->withCount('penyemprotans')
            ->orderByDesc('tanggal_tanam')
            ->get();

        return view('penyemprotan.index', [
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
            ? Penyemprotan::where('jadwal_tanam_id', $jadwal->id)->orderByDesc('tanggal')->orderByDesc('id')->get()
            : collect();

        return view('penyemprotan.kelola', [
            'jadwals' => $jadwals,
            'jadwal' => $jadwal,
            'items' => $items,
            'statuses' => Penyemprotan::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        $jadwal = $this->jadwalDari($request);

        return view('penyemprotan.form', [
            'item' => new Penyemprotan([
                'status' => 'Terjadwal',
                'tanggal' => now()->toDateString(),
                'jadwal_tanam_id' => $jadwal?->id,
                'nama_tanaman' => $jadwal?->nama_tanaman,
                'lokasi' => $jadwal?->lokasi,
            ]),
            'statuses' => Penyemprotan::STATUSES,
            'jadwal' => $jadwal,
            'dariJadwal' => $jadwal !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        $penyemprotan = Penyemprotan::create($data);

        return $this->kembali($request, $penyemprotan->jadwal_tanam_id)
            ->with('success', 'Jadwal penyemprotan berhasil ditambahkan.');
    }

    public function edit(Request $request, Penyemprotan $penyemprotan): View
    {
        return view('penyemprotan.form', [
            'item' => $penyemprotan,
            'statuses' => Penyemprotan::STATUSES,
            'jadwal' => $penyemprotan->jadwalTanam,
            'dariJadwal' => $request->query('asal') === 'kelola',
        ]);
    }

    public function update(Request $request, Penyemprotan $penyemprotan): RedirectResponse
    {
        $penyemprotan->update($this->validated($request));

        return $this->kembali($request, $penyemprotan->jadwal_tanam_id)
            ->with('success', 'Jadwal penyemprotan berhasil diperbarui.');
    }

    public function destroy(Request $request, Penyemprotan $penyemprotan): RedirectResponse
    {
        $jadwalId = $penyemprotan->jadwal_tanam_id ?? ($request->integer('jadwal_tanam_id') ?: null);

        $penyemprotan->delete();

        return $this->kembali($request, $jadwalId)->with('success', 'Jadwal penyemprotan dihapus.');
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
            ? redirect()->route('penyemprotan.kelola', ['jadwal' => $jadwalId])
            : redirect()->route('penyemprotan.index');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'jadwal_tanam_id' => ['nullable', 'exists:jadwal_tanams,id'],
            'tanggal' => ['required', 'date'],
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'jenis_pestisida' => ['required', 'string', 'max:100'],
            'dosis' => ['nullable', 'string', 'max:50'],
            'target_hama' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:'.implode(',', Penyemprotan::STATUSES)],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal.required' => 'Tanggal penyemprotan wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama_tanaman.required' => 'Nama tanaman wajib diisi.',
            'jenis_pestisida.required' => 'Jenis pestisida wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);
    }
}
