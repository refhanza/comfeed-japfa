<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BarangKeluarExport implements FromCollection, WithHeadings
{
    protected $transaksis;
    protected $request;

    public function __construct($transaksis, $request)
    {
        $this->transaksis = $transaksis;
        $this->request = $request;
    }

    public function collection()
    {
        $data = new Collection();
        
        // Add header info
        $data->push([
            'COMFEED JAPFA - LAPORAN BARANG KELUAR',
            '', '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        $periode = 'Semua Data';
        if ($this->request->tanggal_dari && $this->request->tanggal_sampai) {
            $periode = \Carbon\Carbon::parse($this->request->tanggal_dari)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($this->request->tanggal_sampai)->format('d/m/Y');
        } elseif ($this->request->tanggal_dari) {
            $periode = 'Dari ' . \Carbon\Carbon::parse($this->request->tanggal_dari)->format('d/m/Y');
        } elseif ($this->request->tanggal_sampai) {
            $periode = 'Sampai ' . \Carbon\Carbon::parse($this->request->tanggal_sampai)->format('d/m/Y');
        }
        
        $data->push([
            'Periode: ' . $periode,
            '', '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        $data->push([
            'Dicetak pada: ' . now()->format('d/m/Y H:i:s'),
            '', '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        // Empty row
        $data->push(['', '', '', '', '', '', '', '', '', '', '', '', '']);
        
        // Add data rows
        foreach ($this->transaksis as $index => $transaksi) {
            $data->push([
                $index + 1,
                $transaksi->kode_transaksi,
                \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y'),
                $transaksi->barang->kode_barang,
                $transaksi->barang->nama_barang,
                $transaksi->barang->kategori,
                $transaksi->jumlah,
                $transaksi->barang->satuan,
                $transaksi->harga_satuan,
                $transaksi->total_harga,
                $transaksi->customer ?? '-',
                $transaksi->user->name ?? '-',
                $transaksi->keterangan ?? '-',
            ]);
        }
        
        // Add summary
        $data->push(['', '', '', '', '', '', '', '', '', '', '', '', '']);
        $data->push([
            'RINGKASAN',
            '', '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        $data->push([
            'Total Transaksi:',
            $this->transaksis->count(),
            '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        $data->push([
            'Total Nilai Keluar:',
            'Rp ' . number_format($this->transaksis->sum('total_harga'), 0, ',', '.'),
            '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        $data->push([
            'Rata-rata per Transaksi:',
            'Rp ' . number_format($this->transaksis->avg('total_harga'), 0, ',', '.'),
            '', '', '', '', '', '', '', '', '', '', ''
        ]);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Jumlah',
            'Satuan',
            'Harga Satuan',
            'Total Harga',
            'Customer',
            'User',
            'Keterangan',
        ];
    }
}