<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_tanaman',
        'lokasi',
        'luas',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total',
        'pembeli',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'luas' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public const SATUANS = ['kg', 'ton', 'kuintal', 'ikat', 'karung'];

    /**
     * Total pendapatan selalu dihitung dari jumlah x harga satuan.
     */
    protected static function booted(): void
    {
        static::saving(function (Penjualan $penjualan) {
            $penjualan->total = round((float) $penjualan->jumlah * (float) $penjualan->harga_satuan, 2);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function luasLabel(): string
    {
        if ($this->luas === null) {
            return '-';
        }

        return rtrim(rtrim(number_format((float) $this->luas, 2, ',', '.'), '0'), ',').' ha';
    }

    public function jumlahLabel(): string
    {
        return rtrim(rtrim(number_format((float) $this->jumlah, 2, ',', '.'), '0'), ',').' '.$this->satuan;
    }

    public function bulanLabel(): string
    {
        return $this->tanggal?->translatedFormat('F Y') ?? '-';
    }
}
