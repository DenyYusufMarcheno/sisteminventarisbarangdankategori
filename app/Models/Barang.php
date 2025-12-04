<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'kode_barang',
        'nama_barang',
        'stok',
        'harga',
        'satuan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    /**
     * Relasi Many-to-One dengan Kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi One-to-Many dengan Transaksi
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'barang_id');
    }

    /**
     * Format harga ke Rupiah
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Status stok (rendah jika < 10)
     */
    public function getStatusStokAttribute()
    {
        if ($this->stok == 0) {
            return 'habis';
        } elseif ($this->stok < 10) {
            return 'rendah';
        }
        return 'normal';
    }
}