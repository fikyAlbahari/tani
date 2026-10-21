<?php

namespace Database\Seeders;

use App\Models\JadwalTanam;
use App\Models\Pemupukan;
use App\Models\Penyemprotan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tani.test')->first();

        if (JadwalTanam::query()->exists()) {
            return;
        }

        $tanams = [
            [
                'nama_tanaman' => 'Padi IR-64',
                'varietas_benih' => 'IR-64',
                'luas_lahan' => 1.5,
                'tanggal_tanam' => now()->subDays(30)->toDateString(),
                'perkiraan_panen' => now()->addDays(25)->toDateString(),
                'status' => 'Tumbuh',
                'catatan' => 'Lahan sawah blok utara, pengairan lancar.',
            ],
            [
                'nama_tanaman' => 'Jagung Manis',
                'varietas_benih' => 'Hibrida F1',
                'luas_lahan' => 0.8,
                'tanggal_tanam' => now()->subDays(12)->toDateString(),
                'perkiraan_panen' => now()->addDays(55)->toDateString(),
                'status' => 'Ditanam',
                'catatan' => 'Jarak tanam 75 x 25 cm.',
            ],
            [
                'nama_tanaman' => 'Cabai Merah',
                'varietas_benih' => 'CMK',
                'luas_lahan' => 0.4,
                'tanggal_tanam' => now()->addDays(3)->toDateString(),
                'perkiraan_panen' => now()->addDays(80)->toDateString(),
                'status' => 'Direncanakan',
                'catatan' => 'Bibit sudah disemai di rumah kaca.',
            ],
            [
                'nama_tanaman' => 'Tomat Melon',
                'varietas_benih' => 'TM-99',
                'luas_lahan' => 0.6,
                'tanggal_tanam' => now()->subDays(60)->toDateString(),
                'perkiraan_panen' => now()->subDays(2)->toDateString(),
                'status' => 'Panen',
                'catatan' => 'Panen pertama 320 kg.',
            ],
        ];

        foreach ($tanams as $data) {
            JadwalTanam::create($data + ['user_id' => $admin?->id]);
        }

        $pemupukans = [
            [
                'tanggal' => now()->subDays(26)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'jenis_pupuk' => 'NPK Mutiara',
                'dosis' => '150 kg/ha',
                'metode' => 'Tabur',
                'status' => 'Dilakukan',
            ],
            [
                'tanggal' => now()->addDays(4)->toDateString(),
                'nama_tanaman' => 'Cabai Merah',
                'jenis_pupuk' => 'Pupuk Kandang Fermentasi',
                'dosis' => '5 ton/ha',
                'metode' => 'Kocor',
                'status' => 'Terjadwal',
                'catatan' => 'Campur dengan kalsium untuk menguatkan batang.',
            ],
            [
                'tanggal' => now()->addDays(9)->toDateString(),
                'nama_tanaman' => 'Jagung Manis',
                'jenis_pupuk' => 'Urea',
                'dosis' => '200 kg/ha',
                'metode' => 'Tabur',
                'status' => 'Terjadwal',
            ],
        ];

        foreach ($pemupukans as $data) {
            Pemupukan::create($data + ['user_id' => $admin?->id]);
        }

        $penyemprotans = [
            [
                'tanggal' => now()->subDays(8)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'jenis_pestisida' => 'Abamektin',
                'dosis' => '1 mL/L air',
                'target_hama' => 'Tungro & wereng batang cokelat',
                'status' => 'Dilakukan',
            ],
            [
                'tanggal' => now()->addDays(2)->toDateString(),
                'nama_tanaman' => 'Jagung Manis',
                'jenis_pestisida' => 'Pestisida Nabati (Minyak Neem)',
                'dosis' => '3 mL/L air',
                'target_hama' => 'Ulat grayak',
                'status' => 'Terjadwal',
            ],
            [
                'tanggal' => now()->addDays(7)->toDateString(),
                'nama_tanaman' => 'Cabai Merah',
                'jenis_pestisida' => 'Kaptan',
                'dosis' => '2 g/L air',
                'target_hama' => 'Antraknosa (busuk buah)',
                'status' => 'Terjadwal',
            ],
        ];

        foreach ($penyemprotans as $data) {
            Penyemprotan::create($data + ['user_id' => $admin?->id]);
        }
    }
}
