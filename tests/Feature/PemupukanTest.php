<?php

namespace Tests\Feature;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemupukanTest extends TestCase
{
    use RefreshDatabase;

    private function jadwal(User $user, string $nama, string $lokasi = 'Blok Utara'): JadwalTanam
    {
        return JadwalTanam::create([
            'nama_tanaman' => $nama,
            'varietas_benih' => 'IR-64',
            'lokasi' => $lokasi,
            'nama_penggarap' => 'Pak Budi',
            'tanggal_tanam' => now()->toDateString(),
            'status' => 'Ditanam',
            'user_id' => $user->id,
        ]);
    }

    private function catatan(User $user, JadwalTanam $jadwal, string $tanggal, string $jenis = 'NPK', ?string $lokasi = null): Pemupukan
    {
        return Pemupukan::create([
            'jadwal_tanam_id' => $jadwal->id,
            'tanggal' => $tanggal,
            'nama_tanaman' => $jadwal->nama_tanaman,
            'lokasi' => $lokasi ?? $jadwal->lokasi,
            'jenis_pupuk' => $jenis,
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);
    }

    public function test_hanya_pemupukan_pertama_dan_kedua_yang_diberi_tanda(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Cabai Merah');

        foreach ([1, 2, 3] as $hari) {
            $this->catatan($user, $jadwal, now()->addDays($hari)->toDateString());
        }

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('Pemupukan ke-1')
            ->assertSee('Pemupukan ke-2')
            ->assertDontSee('Pemupukan ke-3');
    }

    public function test_urutan_pemupukan_dihitung_per_jadwal(): void
    {
        $user = User::factory()->create();
        $padi = $this->jadwal($user, 'Padi');
        $jagung = $this->jadwal($user, 'Jagung');

        $this->catatan($user, $padi, now()->toDateString(), 'Urea Padi');
        $this->catatan($user, $jagung, now()->toDateString(), 'ZPT Jagung');

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $padi->id]))
            ->assertOk()
            ->assertSee('Urea Padi')
            ->assertDontSee('ZPT Jagung')
            ->assertSee('Pemupukan ke-1');

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $jagung->id]))
            ->assertOk()
            ->assertSee('ZPT Jagung')
            ->assertDontSee('Urea Padi')
            ->assertSee('Pemupukan ke-1');
    }

    public function test_urutan_pemupukan_dihitung_terpisah_per_lokasi(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi', 'Blok Utara');

        foreach ([
            ['lokasi' => 'Blok Utara', 'hari' => 0],
            ['lokasi' => 'Blok Utara', 'hari' => 1],
            ['lokasi' => 'Blok Selatan', 'hari' => 2],
        ] as $data) {
            $this->catatan($user, $jadwal, now()->addDays($data['hari'])->toDateString(), 'Urea', $data['lokasi']);
        }

        $response = $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $jadwal->id]));

        $response->assertOk();
        $this->assertSame(2, substr_count($response->getContent(), 'Pemupukan ke-1'));
        $this->assertSame(1, substr_count($response->getContent(), 'Pemupukan ke-2'));
    }
}
