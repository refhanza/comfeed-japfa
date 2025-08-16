<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'deskripsi',
        'kategori',
        'satuan',
        'harga',
        'stok',
        'is_active'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'is_active' => 'boolean'
    ];

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk status stok
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'Habis';
        } elseif ($this->stok <= 10) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }

    // Scope untuk barang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk stok menipis
    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<=', 10);
    }

    // Relasi dengan transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Method untuk update stok
    public function updateStok($jumlah, $jenis)
    {
        try {
            if ($jenis === 'masuk') {
                $this->increment('stok', $jumlah);
            } elseif ($jenis === 'keluar') {
                if ($this->stok >= $jumlah) {
                    $this->decrement('stok', $jumlah);
                } else {
                    return false; // Stok tidak mencukupi
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Method untuk mendapatkan total nilai barang
    public function getTotalNilaiAttribute()
    {
        return $this->stok * $this->harga;
    }
}