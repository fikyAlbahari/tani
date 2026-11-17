<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
