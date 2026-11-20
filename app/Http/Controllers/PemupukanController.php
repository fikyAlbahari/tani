<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemupukanController extends Controller
{
    public function index(): View
    {
        $jadwals = JadwalTanam::query()
            ->withCount('pemupukans')
            ->orderByDesc('tanggal_tanam')
            ->get();

        return view('pemupukan.index', [
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
            ? Pemupukan::where('jadwal_tanam_id', $jadwal->id)->orderByDesc('tanggal')->orderByDesc('id')->get()
            : collect();

        return view('pemupukan.kelola', [
            'jadwals' => $jadwals,
            'jadwal' => $jadwal,
            'items' => $items,
            'urutan' => Pemupukan::urutanKoleksi($items),
            'statuses' => Pemupukan::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        $jadwal = $this->jadwalDari($request);

        return view('pemupukan.form', [
            'item' => new Pemupukan([
                'status' => 'Terjadwal',
                'tanggal' => now()->toDateString(),
                'jadwal_tanam_id' => $jadwal?->id,
                'nama_tanaman' => $jadwal?->nama_tanaman,
                'lokasi' => $jadwal?->lokasi,
            ]),
            'statuses' => Pemupukan::STATUSES,
            'jadwal' => $jadwal,
            'dariJadwal' => $jadwal !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        $pemupukan = Pemupukan::create($data);

        return $this->kembali($request, $pemupukan->jadwal_tanam_id)
            ->with('success', 'Jadwal pemupukan berhasil ditambahkan.');
    }

    public function edit(Request $request, Pemupukan $pemupukan): View
    {
        return view('pemupukan.form', [
            'item' => $pemupukan,
            'statuses' => Pemupukan::STATUSES,
            'jadwal' => $pemupukan->jadwalTanam,
            'dariJadwal' => $request->query('asal') === 'kelola',
        ]);
    }

    public function update(Request $request, Pemupukan $pemupukan): RedirectResponse
    {
        $pemupukan->update($this->validated($request));

        return $this->kembali($request, $pemupukan->jadwal_tanam_id)
            ->with('success', 'Jadwal pemupukan berhasil diperbarui.');
    }

    public function destroy(Request $request, Pemupukan $pemupukan): RedirectResponse
    {
        $jadwalId = $pemupukan->jadwal_tanam_id ?? ($request->integer('jadwal_tanam_id') ?: null);

        $pemupukan->delete();

        return $this->kembali($request, $jadwalId)->with('success', 'Jadwal pemupukan dihapus.');
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
            ? redirect()->route('pemupukan.kelola', ['jadwal' => $jadwalId])
            : redirect()->route('pemupukan.index');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'jadwal_tanam_id' => ['nullable', 'exists:jadwal_tanams,id'],
            'tanggal' => ['required', 'date'],
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'jenis_pupuk' => ['required', 'string', 'max:100'],
            'dosis' => ['nullable', 'string', 'max:50'],
            'metode' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:'.implode(',', Pemupukan::STATUSES)],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal.required' => 'Tanggal pemupukan wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama_tanaman.required' => 'Nama tanaman wajib diisi.',
            'jenis_pupuk.required' => 'Jenis pupuk wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);
    }
}
