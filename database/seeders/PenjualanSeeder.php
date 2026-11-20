<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        if (Penjualan::query()->exists()) {
            return;
        }

        $admin = User::where('email', 'admin@tani.test')->first();

        $penjualans = [
            [
                'tanggal' => now()->subDays(2)->toDateString(),
                'nama_tanaman' => 'Tomat Melon',
                'lokasi' => 'Blok Timur, Desa Sukamaju',
                'luas' => 0.4,
                'jumlah' => 320,
                'satuan' => 'kg',
                'harga_satuan' => 9000,
                'pembeli' => 'Pasar Induk Tanjung',
                'catatan' => 'Panen pertama, kualitas grade A.',
            ],
            [
                'tanggal' => now()->subDays(5)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'lokasi' => 'Blok Utara, Desa Sukamaju',
                'luas' => 1.5,
                'jumlah' => 1200,
                'satuan' => 'kg',
                'harga_satuan' => 6500,
                'pembeli' => 'Koperasi Tani Makmur',
                'catatan' => 'Gabah kering panen.',
            ],
            [
                'tanggal' => now()->subDays(9)->toDateString(),
                'nama_tanaman' => 'Padi IR-64',
                'lokasi' => 'Blok Utara, Desa Sukamaju',
                'luas' => 1.0,
                'jumlah' => 850,
                'satuan' => 'kg',
                'harga_satuan' => 6800,
                'pembeli' => 'Tengkulak Pak Hasan',
            ],
            [
                'tanggal' => now()->subMonth()->toDateString(),
                'nama_tanaman' => 'Jagung Manis',
                'lokasi' => 'Blok Tengah, Desa Sukamaju',
                'luas' => 0.6,
                'jumlah' => 500,
                'satuan' => 'kg',
                'harga_satuan' => 5500,
                'pembeli' => 'Pasar Desa',
                'catatan' => 'Penjualan sisa panen bulan lalu.',
            ],
        ];

        foreach ($penjualans as $data) {
            Penjualan::create($data + ['user_id' => $admin?->id]);
        }
    }
}
