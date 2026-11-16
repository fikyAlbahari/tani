<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalTanam extends Model
{
    protected $fillable = [
        'nama_tanaman',
        'varietas_benih',
        'luas_lahan',
        'lokasi',
        'nama_penggarap',
        'tanggal_tanam',
        'perkiraan_panen',
        'status',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'perkiraan_panen' => 'date',
        'luas_lahan' => 'decimal:2',
    ];

    public const STATUSES = ['Direncanakan', 'Disemai', 'Ditanam', 'Tumbuh', 'Panen'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusClass(): string
    {
        return self::badgeFor((string) $this->status);
    }

    public static function badgeFor(string $status): string
    {
        return match ($status) {
            'Panen', 'Dilakukan' => 'success',
            'Direncanakan', 'Terjadwal', 'Dilewati' => 'muted',
            'Disemai', 'Ditanam' => 'info',
            default => 'warning',
        };
    }
}
