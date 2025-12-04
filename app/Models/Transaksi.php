<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'user_id',
        'tipe',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
    ];

    /**
     * Relasi Many-to-One dengan Barang
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi Many-to-One dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk transaksi masuk
     */
    public function scopeMasuk($query)
    {
        return $query->where('tipe', 'masuk');
    }

    /**
     * Scope untuk transaksi keluar
     */
    public function scopeKeluar($query)
    {
        return $query->where('tipe', 'keluar');
    }
}