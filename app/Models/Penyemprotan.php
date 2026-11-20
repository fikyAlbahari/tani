<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penyemprotan extends Model
{
    protected $fillable = [
        'jadwal_tanam_id',
        'tanggal',
        'nama_tanaman',
        'lokasi',
        'jenis_pestisida',
        'dosis',
        'target_hama',
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
}
