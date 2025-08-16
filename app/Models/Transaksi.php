<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'barang_id',
        'user_id',
        'jenis_transaksi',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tanggal_transaksi',
        'supplier',
        'customer',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'jumlah' => 'integer'
    ];

    // Accessor untuk format tanggal
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_transaksi->format('d/m/Y');
    }

    // Accessor untuk format harga
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    // Scope untuk transaksi masuk
    public function scopeMasuk($query)
    {
        return $query->where('jenis_transaksi', 'masuk');
    }

    // Scope untuk transaksi keluar
    public function scopeKeluar($query)
    {
        return $query->where('jenis_transaksi', 'keluar');
    }

    // Scope untuk hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_transaksi', Carbon::today());
    }

    // Scope untuk bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_transaksi', Carbon::now()->month)
                    ->whereYear('tanggal_transaksi', Carbon::now()->year);
    }

    // Relasi dengan barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static method untuk generate kode transaksi
    public static function generateKodeTransaksi($jenis)
    {
        $prefix = strtoupper($jenis) === 'MASUK' ? 'MSK' : 'KLR';
        $date = date('Ymd');
        
        // Ambil nomor urut terakhir hari ini
        $lastTransaksi = self::where('kode_transaksi', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        $urutan = 1;
        if ($lastTransaksi) {
            $lastKode = $lastTransaksi->kode_transaksi;
            $lastUrutan = (int) substr($lastKode, -3);
            $urutan = $lastUrutan + 1;
        }

        return $prefix . $date . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    // Boot method untuk auto generate kode dan total harga
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = self::generateKodeTransaksi($transaksi->jenis_transaksi);
            }
            
            if (empty($transaksi->total_harga)) {
                $transaksi->total_harga = $transaksi->jumlah * $transaksi->harga_satuan;
            }
        });

        static::updating(function ($transaksi) {
            $transaksi->total_harga = $transaksi->jumlah * $transaksi->harga_satuan;
        });
    }
}