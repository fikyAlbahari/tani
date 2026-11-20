<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Pemupukan extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_tanaman',
        'lokasi',
        'jenis_pupuk',
        'dosis',
        'metode',
        'status',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public const STATUSES = ['Terjadwal', 'Dilakukan', 'Dilewati'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusClass(): string
    {
        return JadwalTanam::badgeFor((string) $this->status);
    }

    /**
     * Nomor urut pemupukan tiap tanaman berdasarkan tanggal pelaksanaan.
     *
     * @param  Collection<int, self>  $items
     * @return array<int, int>
     */
    public static function urutanUntuk(Collection $items): array
    {
        if ($items->isEmpty()) {
            return [];
        }

        $urutan = [];

        static::query()
            ->whereIn('nama_tanaman', $items->pluck('nama_tanaman')->unique()->all())
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get(['id', 'nama_tanaman'])
            ->groupBy('nama_tanaman')
            ->each(function ($rows) use (&$urutan) {
                $rows->values()->each(function ($row, $index) use (&$urutan) {
                    $urutan[$row->id] = $index + 1;
                });
            });

        return $urutan;
    }
}
