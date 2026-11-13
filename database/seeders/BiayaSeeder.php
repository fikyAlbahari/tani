<?php

namespace Database\Seeders;

use App\Models\Biaya;
use App\Models\User;
use Illuminate\Database\Seeder;

class BiayaSeeder extends Seeder
{
    public function run(): void
    {
        if (Biaya::query()->exists()) {
            return;
        }

        $admin = User::where('email', 'admin@tani.test')->first();

        $biayas = [
            [
                'tanggal' => now()->subDays(30)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'bulan_tanam' => now()->format('Y-m'),
                'kategori' => 'Bibit',
                'uraian' => 'Beli benih padi IR-64 25 kg',
                'jumlah' => 375000,
                'catatan' => 'Toko tani Kecamatan Tanjung.',
            ],
            [
                'tanggal' => now()->subDays(27)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'bulan_tanam' => now()->format('Y-m'),
                'kategori' => 'Tenaga Kerja',
                'uraian' => 'Olah lahan dan tanam 1,5 ha',
                'jumlah' => 1200000,
            ],
            [
                'tanggal' => now()->subDays(18)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'bulan_tanam' => now()->format('Y-m'),
                'kategori' => 'Pupuk',
                'uraian' => 'Pupuk NPK Mutiara 150 kg',
                'jumlah' => 1350000,
            ],
            [
                'tanggal' => now()->subDays(12)->toDateString(),
                'nama_tanaman' => 'Jagung Manis',
                'bulan_tanam' => now()->subMonth()->format('Y-m'),
                'kategori' => 'Bibit',
                'uraian' => 'Benih jagung hibrida F1 5 kg',
                'jumlah' => 450000,
            ],
            [
                'tanggal' => now()->subDays(8)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'bulan_tanam' => now()->format('Y-m'),
                'kategori' => 'Pestisida',
                'uraian' => 'Abamektin 2 liter',
                'jumlah' => 240000,
                'catatan' => 'Untuk pengendalian wereng.',
            ],
        ];

        foreach ($biayas as $data) {
            Biaya::create($data + ['user_id' => $admin?->id]);
        }
    }
}
