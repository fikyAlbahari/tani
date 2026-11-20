<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel yang dicatat per jadwal tanam.
     *
     * @var array<int, string>
     */
    private array $tables = ['pemupukans', 'penyemprotans', 'biayas'];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->foreignId('jadwal_tanam_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('jadwal_tanams')
                    ->nullOnDelete();
            });
        }

        $this->backfill();
    }

    public function down(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropConstrainedForeignId('jadwal_tanam_id');
            });
        }
    }

    /**
     * Hubungkan catatan lama ke jadwal tanam yang paling mendekati.
     */
    private function backfill(): void
    {
        $jadwals = DB::table('jadwal_tanams')
            ->get(['id', 'nama_tanaman', 'lokasi', 'tanggal_tanam'])
            ->map(fn ($row) => (array) $row);

        if ($jadwals->isEmpty()) {
            return;
        }

        foreach ($this->tables as $name) {
            $rows = DB::table($name)->whereNull('jadwal_tanam_id')->get(['id', 'nama_tanaman', 'lokasi', 'tanggal']);

            foreach ($rows as $row) {
                $candidates = $jadwals->filter(
                    fn (array $jadwal) => strcasecmp(trim($jadwal['nama_tanaman']), trim($row->nama_tanaman)) === 0
                        && strcasecmp(trim((string) $jadwal['lokasi']), trim((string) $row->lokasi)) === 0
                );

                if ($candidates->isEmpty()) {
                    continue;
                }

                $match = $candidates
                    ->sortBy(fn (array $jadwal) => abs(strtotime($jadwal['tanggal_tanam']) - strtotime($row->tanggal)))
                    ->first();

                DB::table($name)->where('id', $row->id)->update(['jadwal_tanam_id' => $match['id']]);
            }
        }
    }
};
