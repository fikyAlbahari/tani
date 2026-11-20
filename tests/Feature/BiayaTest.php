<?php

namespace Tests\Feature;

use App\Models\Biaya;
use App\Models\JadwalTanam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BiayaTest extends TestCase
{
    use RefreshDatabase;

    private function jadwal(User $user, string $nama, string $tanggal): JadwalTanam
    {
        return JadwalTanam::create([
            'nama_tanaman' => $nama,
            'varietas_benih' => 'IR-64',
            'lokasi' => 'Blok Utara',
            'nama_penggarap' => 'Pak Budi',
            'tanggal_tanam' => $tanggal,
            'status' => 'Ditanam',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_bisa_menambah_biaya_dan_melihat_totalnya(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)->post(route('biaya.store'), [
            'jadwal_tanam_id' => $jadwal->id,
            'asal' => 'kelola',
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Padi IR-64',
            'bulan_tanam' => now()->format('Y-m'),
            'kategori' => 'Bibit',
            'uraian' => 'Beli benih padi 10 kg',
            'jumlah' => 250000,
        ])->assertRedirect(route('biaya.kelola', ['jadwal' => $jadwal->id]));

        $this->assertDatabaseHas('biayas', [
            'jadwal_tanam_id' => $jadwal->id,
            'nama_tanaman' => 'Padi IR-64',
            'kategori' => 'Bibit',
            'jumlah' => 250000,
        ]);

        $this->actingAs($user)
            ->get(route('biaya.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('Beli benih padi 10 kg')
            ->assertSee('Rp 250.000');
    }

    public function test_biaya_bisa_dikelompokkan_per_bulan_tanam(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', '2026-11-01');

        $this->actingAs($user)->post(route('biaya.store'), [
            'jadwal_tanam_id' => $jadwal->id,
            'asal' => 'kelola',
            'tanggal' => '2026-11-01',
            'nama_tanaman' => 'Padi IR-64',
            'bulan_tanam' => '2026-11',
            'kategori' => 'Bibit',
            'uraian' => 'Beli benih padi 10 kg',
            'jumlah' => 250000,
        ])->assertRedirect(route('biaya.kelola', ['jadwal' => $jadwal->id]));

        $this->assertDatabaseHas('biayas', [
            'jadwal_tanam_id' => $jadwal->id,
            'nama_tanaman' => 'Padi IR-64',
            'bulan_tanam' => '2026-11',
        ]);

        $this->actingAs($user)
            ->get(route('biaya.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('November 2026')
            ->assertSee('Rp 250.000');
    }

    public function test_dashboard_menampilkan_total_biaya_per_bulan_tanam(): void
    {
        Http::fake();

        $user = User::factory()->create();

        $base = [
            'tanggal' => now()->toDateString(),
            'kategori' => 'Bibit',
            'uraian' => 'Pembelian benih',
        ];

        Biaya::create($base + ['nama_tanaman' => 'Padi IR-64', 'bulan_tanam' => '2026-11', 'jumlah' => 250000]);
        Biaya::create($base + ['nama_tanaman' => 'Padi IR-64', 'bulan_tanam' => '2026-11', 'jumlah' => 150000]);
        Biaya::create($base + ['nama_tanaman' => 'Jagung Manis', 'bulan_tanam' => '2026-10', 'jumlah' => 400000]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Biaya per Sekali Tanam')
            ->assertSee('November 2026')
            ->assertSee('Nov 2026')
            ->assertSee('Feb 2027')
            ->assertSee('Rp 400.000')
            ->assertSee('2 catatan')
            ->assertSee('4 bulan');
    }

    public function test_kategori_biaya_harus_valid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('biaya.store'), [
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Padi IR-64',
            'kategori' => 'Kategori Tidak Ada',
            'uraian' => 'Beli benih',
            'jumlah' => 100000,
        ])->assertSessionHasErrors('kategori');

        $this->assertSame(0, Biaya::count());
    }
}
