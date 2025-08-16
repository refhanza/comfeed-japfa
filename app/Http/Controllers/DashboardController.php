<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total barang
        $totalBarang = Barang::count();
        
        // Barang masuk hari ini
        $barangMasukHariIni = Transaksi::masuk()->hariIni()->sum('jumlah');
        
        // Barang keluar hari ini
        $barangKeluarHariIni = Transaksi::keluar()->hariIni()->sum('jumlah');
        
        // Stok menipis (stok <= 10)
        $stokMenipis = Barang::stokMenipis()->count();
        
        // Transaksi terbaru (5 transaksi terakhir)
        $transaksiTerbaru = Transaksi::with(['barang', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Barang dengan stok menipis
        $barangStokMenipis = Barang::stokMenipis()
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get();
        
        // Data chart transaksi 7 hari terakhir
        $chartData = $this->getChartData();
        
        // Top 5 barang paling sering keluar bulan ini
        $topBarangKeluar = Transaksi::select('barang_id', DB::raw('SUM(jumlah) as total_keluar'))
            ->with('barang')
            ->keluar()
            ->bulanIni()
            ->groupBy('barang_id')
            ->orderBy('total_keluar', 'desc')
            ->limit(5)
            ->get();
        
        // Summary nilai inventory
        $nilaiInventory = Barang::selectRaw('SUM(stok * harga) as total_nilai')
            ->where('is_active', true)
            ->first()
            ->total_nilai ?? 0;
        
        // Total transaksi bulan ini
        $transaksiMasukBulanIni = Transaksi::masuk()->bulanIni()->sum('total_harga');
        $transaksiKeluarBulanIni = Transaksi::keluar()->bulanIni()->sum('total_harga');
        
        return view('dashboard', compact(
            'totalBarang',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'stokMenipis',
            'transaksiTerbaru',
            'barangStokMenipis',
            'chartData',
            'topBarangKeluar',
            'nilaiInventory',
            'transaksiMasukBulanIni',
            'transaksiKeluarBulanIni'
        ));
    }

    /**
     * Get data untuk chart transaksi 7 hari terakhir
     */
    private function getChartData()
    {
        $dates = collect();
        $masukData = collect();
        $keluarData = collect();

        // Generate 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('d/m');
            
            $dates->push($formattedDate);
            
            // Data barang masuk
            $masuk = Transaksi::masuk()
                ->whereDate('tanggal_transaksi', $date)
                ->sum('jumlah');
            $masukData->push($masuk);
            
            // Data barang keluar
            $keluar = Transaksi::keluar()
                ->whereDate('tanggal_transaksi', $date)
                ->sum('jumlah');
            $keluarData->push($keluar);
        }

        return [
            'dates' => $dates->toArray(),
            'masuk' => $masukData->toArray(),
            'keluar' => $keluarData->toArray(),
        ];
    }

    /**
     * API endpoint untuk refresh dashboard cards
     */
    public function refreshCards()
    {
        $data = [
            'totalBarang' => Barang::count(),
            'barangMasukHariIni' => Transaksi::masuk()->hariIni()->sum('jumlah'),
            'barangKeluarHariIni' => Transaksi::keluar()->hariIni()->sum('jumlah'),
            'stokMenipis' => Barang::stokMenipis()->count(),
        ];

        return response()->json($data);
    }

    /**
     * Get statistik per kategori
     */
    public function statistikKategori()
    {
        $kategoris = Barang::select('kategori', DB::raw('COUNT(*) as total'), DB::raw('SUM(stok) as total_stok'))
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->get();

        return response()->json($kategoris);
    }

    /**
     * Get data untuk export
     */
    public function exportData(Request $request)
    {
        $type = $request->get('type', 'barang'); // barang, transaksi, stok_menipis

        switch ($type) {
            case 'barang':
                $data = Barang::select('kode_barang', 'nama_barang', 'kategori', 'stok', 'satuan', 'harga')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'Kode Barang' => $item->kode_barang,
                            'Nama Barang' => $item->nama_barang,
                            'Kategori' => $item->kategori,
                            'Stok' => $item->stok,
                            'Satuan' => $item->satuan,
                            'Harga' => $item->harga,
                            'Nilai Total' => $item->stok * $item->harga
                        ];
                    });
                break;

            case 'stok_menipis':
                $data = Barang::stokMenipis()
                    ->select('kode_barang', 'nama_barang', 'stok', 'satuan')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'Kode Barang' => $item->kode_barang,
                            'Nama Barang' => $item->nama_barang,
                            'Stok Tersisa' => $item->stok,
                            'Satuan' => $item->satuan,
                            'Status' => $item->status_stok
                        ];
                    });
                break;

            case 'transaksi':
                $data = Transaksi::with(['barang', 'user'])
                    ->when($request->tanggal_dari, function($query) use ($request) {
                        return $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
                    })
                    ->when($request->tanggal_sampai, function($query) use ($request) {
                        return $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
                    })
                    ->orderBy('tanggal_transaksi', 'desc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'Kode Transaksi' => $item->kode_transaksi,
                            'Tanggal' => $item->formatted_tanggal,
                            'Jenis' => ucfirst($item->jenis_transaksi),
                            'Barang' => $item->barang->nama_barang,
                            'Jumlah' => $item->jumlah,
                            'Harga Satuan' => $item->harga_satuan,
                            'Total Harga' => $item->total_harga,
                            'User' => $item->user->name
                        ];
                    });
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json([
            'data' => $data,
            'filename' => $type . '_' . date('Y-m-d') . '.csv'
        ]);
    }
}