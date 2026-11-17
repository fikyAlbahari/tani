<?php

namespace App\Http\Controllers;

use App\Models\Pemupukan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemupukanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pemupukan::query()->orderByDesc('tanggal');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(fn ($w) => $w->where('nama_tanaman', 'like', "%{$q}%")
                ->orWhere('lokasi', 'like', "%{$q}%")
                ->orWhere('jenis_pupuk', 'like', "%{$q}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('pemupukan.index', [
            'items' => $query->paginate(10)->withQueryString(),
            'statuses' => Pemupukan::STATUSES,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('pemupukan.form', [
            'item' => new Pemupukan(['status' => 'Terjadwal', 'tanggal' => now()->toDateString()]),
            'statuses' => Pemupukan::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        Pemupukan::create($data);

        return redirect()->route('pemupukan.index')->with('success', 'Jadwal pemupukan berhasil ditambahkan.');
    }

    public function edit(Pemupukan $pemupukan): View
    {
        return view('pemupukan.form', [
            'item' => $pemupukan,
            'statuses' => Pemupukan::STATUSES,
        ]);
    }

    public function update(Request $request, Pemupukan $pemupukan): RedirectResponse
    {
        $pemupukan->update($this->validated($request));

        return redirect()->route('pemupukan.index')->with('success', 'Jadwal pemupukan berhasil diperbarui.');
    }

    public function destroy(Pemupukan $pemupukan): RedirectResponse
    {
        $pemupukan->delete();

        return redirect()->route('pemupukan.index')->with('success', 'Jadwal pemupukan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
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
