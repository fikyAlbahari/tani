<?php

namespace Tests\Feature;

use App\Models\Pemupukan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemupukanTest extends TestCase
{
    use RefreshDatabase;

    public function test_hanya_pemupukan_pertama_dan_kedua_yang_diberi_tanda(): void
    {
        $user = User::factory()->create();

        foreach ([1, 2, 3] as $hari) {
            Pemupukan::create([
                'tanggal' => now()->addDays($hari)->toDateString(),
                'nama_tanaman' => 'Cabai Merah',
                'jenis_pupuk' => 'NPK',
                'status' => 'Terjadwal',
                'user_id' => $user->id,
            ]);
        }

        $this->actingAs($user)
            ->get(route('pemupukan.index'))
            ->assertOk()
            ->assertSee('Pemupukan ke-1')
            ->assertSee('Pemupukan ke-2')
            ->assertDontSee('Pemupukan ke-3');
    }

    public function test_urutan_pemupukan_dihitung_per_tanaman(): void
    {
        $user = User::factory()->create();

        Pemupukan::create([
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => 'Padi',
            'jenis_pupuk' => 'Urea',
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);

        Pemupukan::create([
            'tanggal' => now()->addDay()->toDateString(),
            'nama_tanaman' => 'Jagung',
            'jenis_pupuk' => 'NPK',
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('pemupukan.index'));

        $response->assertOk();
        $this->assertSame(2, substr_count($response->getContent(), 'Pemupukan ke-1'));
    }

    public function test_urutan_pemupukan_dihitung_terpisah_per_lokasi(): void
    {
        $user = User::factory()->create();

        foreach ([
            ['lokasi' => 'Blok Utara', 'hari' => 0],
            ['lokasi' => 'Blok Utara', 'hari' => 1],
            ['lokasi' => 'Blok Selatan', 'hari' => 2],
        ] as $data) {
            Pemupukan::create([
                'tanggal' => now()->addDays($data['hari'])->toDateString(),
                'nama_tanaman' => 'Padi',
                'lokasi' => $data['lokasi'],
                'jenis_pupuk' => 'Urea',
                'status' => 'Terjadwal',
                'user_id' => $user->id,
            ]);
        }

        $response = $this->actingAs($user)->get(route('pemupukan.index'));

        $response->assertOk();
        $this->assertSame(2, substr_count($response->getContent(), 'Pemupukan ke-1'));
        $this->assertSame(1, substr_count($response->getContent(), 'Pemupukan ke-2'));
    }
}
