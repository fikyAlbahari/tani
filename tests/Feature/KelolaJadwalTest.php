<?php

namespace Tests\Feature;

use App\Models\Biaya;
use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelolaJadwalTest extends TestCase
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

    private function pemupukan(User $user, JadwalTanam $jadwal, string $jenis): Pemupukan
    {
        return Pemupukan::create([
            'jadwal_tanam_id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal_tanam->toDateString(),
            'nama_tanaman' => $jadwal->nama_tanaman,
            'lokasi' => $jadwal->lokasi,
            'jenis_pupuk' => $jenis,
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);
    }

    private function penyemprotan(User $user, JadwalTanam $jadwal, string $pestisida): Penyemprotan
    {
        return Penyemprotan::create([
            'jadwal_tanam_id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal_tanam->toDateString(),
            'nama_tanaman' => $jadwal->nama_tanaman,
            'lokasi' => $jadwal->lokasi,
            'jenis_pestisida' => $pestisida,
            'status' => 'Terjadwal',
            'user_id' => $user->id,
        ]);
    }

    private function biaya(User $user, JadwalTanam $jadwal, int $jumlah, string $uraian): Biaya
    {
        return Biaya::create([
            'jadwal_tanam_id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal_tanam->toDateString(),
            'nama_tanaman' => $jadwal->nama_tanaman,
            'lokasi' => $jadwal->lokasi,
            'bulan_tanam' => $jadwal->tanggal_tanam->format('Y-m'),
            'kategori' => 'Bibit',
            'uraian' => $uraian,
            'jumlah' => $jumlah,
            'user_id' => $user->id,
        ]);
    }

    public function test_kelola_pemupukan_hanya_menampilkan_catatan_jadwal_terpilih(): void
    {
        $user = User::factory()->create();
        $padi = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $jagung = $this->jadwal($user, 'Jagung Manis', now()->subDay()->toDateString());

        $this->pemupukan($user, $padi, 'NPK Padi');
        $this->pemupukan($user, $jagung, 'Urea Jagung');

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $padi->id]))
            ->assertOk()
            ->assertSee('Kelola Pemupukan per Jadwal')
            ->assertSee('NPK Padi')
            ->assertDontSee('Urea Jagung');

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $jagung->id]))
            ->assertOk()
            ->assertSee('Urea Jagung')
            ->assertDontSee('NPK Padi');
    }

    public function test_kelola_penyemprotan_hanya_menampilkan_catatan_jadwal_terpilih(): void
    {
        $user = User::factory()->create();
        $padi = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $jagung = $this->jadwal($user, 'Jagung Manis', now()->subDay()->toDateString());

        $this->penyemprotan($user, $padi, 'Imidakloprid Padi');
        $this->penyemprotan($user, $jagung, 'Nabati Jagung');

        $this->actingAs($user)
            ->get(route('penyemprotan.kelola', ['jadwal' => $padi->id]))
            ->assertOk()
            ->assertSee('Kelola Penyemprotan per Jadwal')
            ->assertSee('Imidakloprid Padi')
            ->assertDontSee('Nabati Jagung');

        $this->actingAs($user)
            ->get(route('penyemprotan.kelola', ['jadwal' => $jagung->id]))
            ->assertOk()
            ->assertSee('Nabati Jagung')
            ->assertDontSee('Imidakloprid Padi');
    }

    public function test_kelola_biaya_hanya_menampilkan_catatan_dan_total_jadwal_terpilih(): void
    {
        $user = User::factory()->create();
        $padi = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $jagung = $this->jadwal($user, 'Jagung Manis', now()->subDay()->toDateString());

        $this->biaya($user, $padi, 250000, 'Benih padi');
        $this->biaya($user, $padi, 50000, 'Irigasi padi');
        $this->biaya($user, $jagung, 900000, 'Sewa traktor jagung');

        $this->actingAs($user)
            ->get(route('biaya.kelola', ['jadwal' => $padi->id]))
            ->assertOk()
            ->assertSee('Kelola Biaya Tanam per Jadwal')
            ->assertSee('Rp 300.000')
            ->assertDontSee('Sewa traktor jagung')
            ->assertDontSee('Rp 900.000');

        $this->actingAs($user)
            ->get(route('biaya.kelola', ['jadwal' => $jagung->id]))
            ->assertOk()
            ->assertSee('Rp 900.000')
            ->assertDontSee('Rp 300.000');
    }

    public function test_halaman_jadwal_tidak_memiliki_tombol_kelola(): void
    {
        $user = User::factory()->create();
        $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)
            ->get(route('jadwal.index'))
            ->assertOk()
            ->assertSee('Jadwal Tanam')
            ->assertDontSee('Kelola');
    }

    public function test_menu_modul_menampilkan_daftar_jadwal_bukan_catatan_yang_numpuk(): void
    {
        $user = User::factory()->create();
        $padi = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $jagung = $this->jadwal($user, 'Jagung Manis', now()->subDay()->toDateString());

        $this->pemupukan($user, $padi, 'NPK Padi');
        $this->penyemprotan($user, $jagung, 'Nabati Jagung');
        $this->biaya($user, $padi, 250000, 'Benih padi');

        $this->actingAs($user)
            ->get(route('pemupukan.index'))
            ->assertOk()
            ->assertSee('Daftar Jadwal Tanam')
            ->assertSee('Padi IR-64')
            ->assertSee('Jagung Manis')
            ->assertSee(route('pemupukan.kelola', ['jadwal' => $padi->id]), false)
            ->assertDontSee('NPK Padi');

        $this->actingAs($user)
            ->get(route('penyemprotan.index'))
            ->assertOk()
            ->assertSee('Daftar Jadwal Tanam')
            ->assertSee(route('penyemprotan.kelola', ['jadwal' => $jagung->id]), false)
            ->assertDontSee('Nabati Jagung');

        $this->actingAs($user)
            ->get(route('biaya.index'))
            ->assertOk()
            ->assertSee('Daftar Jadwal Tanam')
            ->assertSee('Rp 250.000')
            ->assertSee(route('biaya.kelola', ['jadwal' => $padi->id]), false)
            ->assertDontSee('Benih padi');
    }

    public function test_ubah_dan_hapus_pemupukan_dari_kelola_kembali_ke_kelola(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $pemupukan = $this->pemupukan($user, $jadwal, 'NPK');
        $kembali = route('pemupukan.kelola', ['jadwal' => $jadwal->id]);

        $this->actingAs($user)
            ->put(route('pemupukan.update', $pemupukan), [
                'asal' => 'kelola',
                'jadwal_tanam_id' => $jadwal->id,
                'tanggal' => now()->toDateString(),
                'nama_tanaman' => $jadwal->nama_tanaman,
                'lokasi' => $jadwal->lokasi,
                'jenis_pupuk' => 'Urea',
                'status' => 'Dilakukan',
            ])
            ->assertRedirect($kembali);

        $this->assertDatabaseHas('pemupukans', [
            'id' => $pemupukan->id,
            'jenis_pupuk' => 'Urea',
        ]);

        $this->actingAs($user)
            ->delete(route('pemupukan.destroy', $pemupukan), [
                'asal' => 'kelola',
                'jadwal_tanam_id' => $jadwal->id,
            ])
            ->assertRedirect($kembali);

        $this->assertDatabaseMissing('pemupukans', ['id' => $pemupukan->id]);
    }

    public function test_pemupukan_dari_kelola_terhubung_ke_jadwal(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)
            ->post(route('pemupukan.store'), [
                'jadwal_tanam_id' => $jadwal->id,
                'asal' => 'kelola',
                'tanggal' => now()->toDateString(),
                'nama_tanaman' => $jadwal->nama_tanaman,
                'lokasi' => $jadwal->lokasi,
                'jenis_pupuk' => 'Urea',
                'status' => 'Terjadwal',
            ])
            ->assertRedirect(route('pemupukan.kelola', ['jadwal' => $jadwal->id]));

        $this->assertDatabaseHas('pemupukans', [
            'jadwal_tanam_id' => $jadwal->id,
            'jenis_pupuk' => 'Urea',
        ]);
    }

    public function test_penyemprotan_dari_kelola_terhubung_ke_jadwal(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)
            ->post(route('penyemprotan.store'), [
                'jadwal_tanam_id' => $jadwal->id,
                'asal' => 'kelola',
                'tanggal' => now()->toDateString(),
                'nama_tanaman' => $jadwal->nama_tanaman,
                'lokasi' => $jadwal->lokasi,
                'jenis_pestisida' => 'Nabati',
                'status' => 'Terjadwal',
            ])
            ->assertRedirect(route('penyemprotan.kelola', ['jadwal' => $jadwal->id]));

        $this->assertDatabaseHas('penyemprotans', [
            'jadwal_tanam_id' => $jadwal->id,
            'jenis_pestisida' => 'Nabati',
        ]);
    }

    public function test_biaya_dari_kelola_terhubung_ke_jadwal(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)
            ->post(route('biaya.store'), [
                'jadwal_tanam_id' => $jadwal->id,
                'asal' => 'kelola',
                'tanggal' => now()->toDateString(),
                'nama_tanaman' => $jadwal->nama_tanaman,
                'lokasi' => $jadwal->lokasi,
                'bulan_tanam' => now()->format('Y-m'),
                'kategori' => 'Bibit',
                'uraian' => 'Beli benih',
                'jumlah' => 150000,
            ])
            ->assertRedirect(route('biaya.kelola', ['jadwal' => $jadwal->id]));

        $this->assertDatabaseHas('biayas', [
            'jadwal_tanam_id' => $jadwal->id,
            'uraian' => 'Beli benih',
        ]);
    }

    public function test_tombol_tambah_di_kelola_mengarah_ke_halaman_form_terpisah(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());

        $this->actingAs($user)
            ->get(route('pemupukan.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('Tambah Pemupukan')
            ->assertSee(route('pemupukan.create', ['jadwal' => $jadwal->id, 'asal' => 'kelola']));

        $this->actingAs($user)
            ->get(route('penyemprotan.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('Tambah Penyemprotan')
            ->assertSee(route('penyemprotan.create', ['jadwal' => $jadwal->id, 'asal' => 'kelola']));

        $this->actingAs($user)
            ->get(route('biaya.kelola', ['jadwal' => $jadwal->id]))
            ->assertOk()
            ->assertSee('Tambah Biaya')
            ->assertSee(route('biaya.create', ['jadwal' => $jadwal->id, 'asal' => 'kelola']));

        $this->actingAs($user)
            ->get(route('biaya.create', ['jadwal' => $jadwal->id, 'asal' => 'kelola']))
            ->assertOk()
            ->assertSee('name="asal" value="kelola"', false)
            ->assertSee('name="jadwal_tanam_id" value="'.$jadwal->id.'"', false);
    }

    public function test_tambah_data_biasa_tetap_kembali_ke_daftar_masing_masing(): void
    {
        $user = User::factory()->create();
        $jadwal = $this->jadwal($user, 'Padi IR-64', now()->toDateString());
        $data = [
            'jadwal_tanam_id' => $jadwal->id,
            'tanggal' => now()->toDateString(),
            'nama_tanaman' => $jadwal->nama_tanaman,
            'lokasi' => $jadwal->lokasi,
            'status' => 'Terjadwal',
        ];

        $this->actingAs($user)
            ->post(route('pemupukan.store'), $data + ['jenis_pupuk' => 'NPK'])
            ->assertRedirect(route('pemupukan.index'));

        $this->actingAs($user)
            ->post(route('penyemprotan.store'), $data + ['jenis_pestisida' => 'Nabati'])
            ->assertRedirect(route('penyemprotan.index'));

        $this->actingAs($user)
            ->post(route('biaya.store'), $data + [
                'kategori' => 'Bibit',
                'uraian' => 'Beli benih',
                'jumlah' => 100000,
            ])
            ->assertRedirect(route('biaya.index'));
    }
}
