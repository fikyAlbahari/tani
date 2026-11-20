<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

class Biaya extends Model
{
    protected $fillable = [
        'jadwal_tanam_id',
        'tanggal',
        'nama_tanaman',
        'lokasi',
        'bulan_tanam',
        'kategori',
        'uraian',
        'jumlah',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public const KATEGORIS = [
        'Bibit',
        'Pupuk',
        'Pestisida',
        'Tenaga Kerja',
        'Sewa Lahan',
        'Irigasi',
        'Transportasi',
        'Pemanenan',
        'Lainnya',
    ];

    public const TANPA_BULAN = 'Tanpa Bulan Tanam';

    /** Masa tanam sampai panen dihitung 4 bulan. */
    public const DURASI_BULAN = 4;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalTanam(): BelongsTo
    {
        return $this->belongsTo(JadwalTanam::class);
    }

    public function bulanLabel(): string
    {
        return self::bulanLabelFor($this->bulan_tanam);
    }

    public function periodeLabel(): string
    {
        return self::periodeLabelFor($this->bulan_tanam);
    }

    public static function bulanLabelFor(?string $bulan): string
    {
        $start = self::parseBulan($bulan);

        return $start?->translatedFormat('F Y') ?? self::TANPA_BULAN;
    }

    public static function periodeLabelFor(?string $bulan): string
    {
        $start = self::parseBulan($bulan);

        if (! $start) {
            return '-';
        }

        $end = $start->copy()->addMonths(self::DURASI_BULAN - 1);

        return $start->translatedFormat('M Y').' – '.$end->translatedFormat('M Y');
    }

    private static function parseBulan(?string $bulan): ?Carbon
    {
        if (! $bulan) {
            return null;
        }

        try {
            return Carbon::createFromFormat('!Y-m', $bulan)->startOfMonth();
        } catch (Throwable) {
            return null;
        }
    }

    public function kategoriClass(): string
    {
        return match ($this->kategori) {
            'Bibit' => 'info',
            'Pupuk' => 'success',
            'Pestisida' => 'warning',
            'Tenaga Kerja' => 'muted',
            default => 'warning',
        };
    }
}
