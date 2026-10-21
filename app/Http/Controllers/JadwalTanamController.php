<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalTanamController extends Controller
{
    public function index(Request $request): View
    {
        $query = JadwalTanam::query()->orderByDesc('tanggal_tanam');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(fn ($w) => $w->where('nama_tanaman', 'like', "%{$q}%")
                ->orWhere('varietas_benih', 'like', "%{$q}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('jadwal.index', [
            'items' => $query->paginate(10)->withQueryString(),
            'statuses' => JadwalTanam::STATUSES,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('jadwal.form', [
            'item' => new JadwalTanam(['status' => 'Direncanakan', 'tanggal_tanam' => now()->toDateString()]),
            'statuses' => JadwalTanam::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        JadwalTanam::create($data);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal tanam berhasil ditambahkan.');
    }

    public function edit(JadwalTanam $jadwal): View
    {
        return view('jadwal.form', [
            'item' => $jadwal,
            'statuses' => JadwalTanam::STATUSES,
        ]);
    }

    public function update(Request $request, JadwalTanam $jadwal): RedirectResponse
    {
        $jadwal->update($this->validated($request, $jadwal));

        return redirect()->route('jadwal.index')->with('success', 'Jadwal tanam berhasil diperbarui.');
    }

    public function destroy(JadwalTanam $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal tanam dihapus.');
    }

    private function validated(Request $request, ?JadwalTanam $item = null): array
    {
        return $request->validate([
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'varietas_benih' => ['nullable', 'string', 'max:100'],
            'luas_lahan' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'tanggal_tanam' => ['required', 'date'],
            'perkiraan_panen' => ['nullable', 'date', 'after_or_equal:tanggal_tanam'],
            'status' => ['required', 'in:'.implode(',', JadwalTanam::STATUSES)],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_tanaman.required' => 'Nama tanaman wajib diisi.',
            'tanggal_tanam.required' => 'Tanggal tanam wajib diisi.',
            'tanggal_tanam.date' => 'Tanggal tanam tidak valid.',
            'perkiraan_panen.after_or_equal' => 'Perkiraan panen tidak boleh sebelum tanggal tanam.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'luas_lahan.numeric' => 'Luas lahan harus berupa angka.',
        ]);
    }
}
