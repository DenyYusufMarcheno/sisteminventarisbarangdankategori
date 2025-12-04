<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi One-to-Many dengan Barang
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    /**
     * Hitung jumlah barang per kategori
     */
    public function getTotalBarangAttribute()
    {
        return $this->barangs()->count();
    }
}