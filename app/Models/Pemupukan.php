<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Pemupukan extends Model
{
    protected $fillable = [
        'jadwal_tanam_id',
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

    public function jadwalTanam(): BelongsTo
    {
        return $this->belongsTo(JadwalTanam::class);
    }

    public function statusClass(): string
    {
        return JadwalTanam::badgeFor((string) $this->status);
    }

    /**
     * Kunci pengelompokan urutan pemupukan.
     *
     * Pemupukan dihitung per lokasi agar tiap lahan punya urutan pertama/kedua
     * sendiri. Bila lokasi belum diisi, urutan dihitung per nama tanaman.
     */
    public static function kunciUrutan(self $item): string
    {
        $lokasi = mb_strtolower(trim((string) $item->lokasi));
        $tanaman = mb_strtolower(trim((string) $item->nama_tanaman));

        return $lokasi !== ''
            ? 'lokasi:'.$lokasi.'|tanaman:'.$tanaman
            : 'tanaman:'.$tanaman;
    }

    /**
     * Nomor urut pemupukan dari koleksi yang diberikan.
     *
     * @param  Collection<int, self>  $items
     * @return array<int, int>
     */
    public static function urutanKoleksi(Collection $items): array
    {
        $urutan = [];

        $items
            ->sortBy([['tanggal', 'asc'], ['id', 'asc']])
            ->groupBy(fn (self $row) => static::kunciUrutan($row))
            ->each(function (Collection $rows) use (&$urutan) {
                $rows->values()->each(function (self $row, int $index) use (&$urutan) {
                    $urutan[$row->id] = $index + 1;
                });
            });

        return $urutan;
    }

    /**
     * Nomor urut pemupukan berdasarkan lokasi lahan dan tanggal pelaksanaan.
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
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get(['id', 'nama_tanaman', 'lokasi'])
            ->groupBy(fn (self $row) => static::kunciUrutan($row))
            ->each(function (Collection $rows) use (&$urutan) {
                $rows->values()->each(function (self $row, int $index) use (&$urutan) {
                    $urutan[$row->id] = $index + 1;
                });
            });

        return $urutan;
    }
}
