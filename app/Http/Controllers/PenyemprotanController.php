<?php

namespace App\Http\Controllers;

use App\Models\Penyemprotan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenyemprotanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Penyemprotan::query()->orderByDesc('tanggal');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(fn ($w) => $w->where('nama_tanaman', 'like', "%{$q}%")
                ->orWhere('jenis_pestisida', 'like', "%{$q}%")
                ->orWhere('target_hama', 'like', "%{$q}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('penyemprotan.index', [
            'items' => $query->paginate(10)->withQueryString(),
            'statuses' => Penyemprotan::STATUSES,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('penyemprotan.form', [
            'item' => new Penyemprotan(['status' => 'Terjadwal', 'tanggal' => now()->toDateString()]),
            'statuses' => Penyemprotan::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        Penyemprotan::create($data);

        return redirect()->route('penyemprotan.index')->with('success', 'Jadwal penyemprotan berhasil ditambahkan.');
    }

    public function edit(Penyemprotan $penyemprotan): View
    {
        return view('penyemprotan.form', [
            'item' => $penyemprotan,
            'statuses' => Penyemprotan::STATUSES,
        ]);
    }

    public function update(Request $request, Penyemprotan $penyemprotan): RedirectResponse
    {
        $penyemprotan->update($this->validated($request));

        return redirect()->route('penyemprotan.index')->with('success', 'Jadwal penyemprotan berhasil diperbarui.');
    }

    public function destroy(Penyemprotan $penyemprotan): RedirectResponse
    {
        $penyemprotan->delete();

        return redirect()->route('penyemprotan.index')->with('success', 'Jadwal penyemprotan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_tanaman' => ['required', 'string', 'max:100'],
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
