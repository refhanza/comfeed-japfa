<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar semua transaksi (untuk index)
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['barang', 'user']);

        // Filter tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter jenis transaksi
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis_transaksi', $request->jenis);
        }

        // Filter barang
        if ($request->has('barang_id') && $request->barang_id != '') {
            $query->where('barang_id', $request->barang_id);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(15);
        $barangs = Barang::active()->orderBy('nama_barang')->get();

        return view('transaksi.index', compact('transaksis', 'barangs'));
    }

    /**
     * Form untuk membuat transaksi baru
     */
    public function create()
    {
        $barangs = Barang::active()->orderBy('nama_barang')->get();
        return view('transaksi.create', compact('barangs'));
    }

    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barangs,id',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'supplier' => 'nullable|string|max:255',
            'customer' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak valid',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih',
            'jenis_transaksi.in' => 'Jenis transaksi tidak valid',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);
            
            // Cek stok untuk transaksi keluar
            if ($request->jenis_transaksi === 'keluar' && $barang->stok < $request->jumlah) {
                return redirect()->back()
                    ->with('error', "Stok tidak mencukupi! Stok tersedia: {$barang->stok} {$barang->satuan}")
                    ->withInput();
            }

            // Buat transaksi baru
            $transaksi = new Transaksi();
            $transaksi->barang_id = $request->barang_id;
            $transaksi->kode_transaksi = Transaksi::generateKodeTransaksi($request->jenis_transaksi);
            $transaksi->jenis_transaksi = $request->jenis_transaksi;
            $transaksi->jumlah = $request->jumlah;
            $transaksi->harga_satuan = $request->harga_satuan;
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->supplier = $request->supplier;
            $transaksi->customer = $request->customer;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->user_id = Auth::id();
            $transaksi->save();

            // Update stok barang
            $barang->updateStok($request->jumlah, $request->jenis_transaksi);

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['barang', 'user']);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Form untuk edit transaksi
     */
    public function edit(Transaksi $transaksi)
    {
        $barangs = Barang::active()->orderBy('nama_barang')->get();
        return view('transaksi.edit', compact('transaksi', 'barangs'));
    }

    /**
     * Update transaksi
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barangs,id',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'supplier' => 'nullable|string|max:255',
            'customer' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak valid',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih',
            'jenis_transaksi.in' => 'Jenis transaksi tidak valid',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $oldBarang = $transaksi->barang;
            $newBarang = Barang::findOrFail($request->barang_id);
            
            // Kembalikan stok lama
            if ($transaksi->jenis_transaksi === 'masuk') {
                $oldBarang->updateStok($transaksi->jumlah, 'keluar');
            } else {
                $oldBarang->updateStok($transaksi->jumlah, 'masuk');
            }

            // Cek stok untuk transaksi keluar baru
            if ($request->jenis_transaksi === 'keluar') {
                $newBarang->refresh(); // Refresh data barang setelah stok dikembalikan
                if ($newBarang->stok < $request->jumlah) {
                    DB::rollback();
                    return redirect()->back()
                        ->with('error', "Stok tidak mencukupi! Stok tersedia: {$newBarang->stok} {$newBarang->satuan}")
                        ->withInput();
                }
            }

            // Update transaksi
            $transaksi->update([
                'barang_id' => $request->barang_id,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'supplier' => $request->supplier,
                'customer' => $request->customer,
                'keterangan' => $request->keterangan,
            ]);

            // Update stok dengan data baru
            $newBarang->updateStok($request->jumlah, $request->jenis_transaksi);

            DB::commit();

            return redirect()->route('transaksi.show', $transaksi)
                ->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilan untuk barang masuk
     */
    public function barangMasuk(Request $request)
    {
        $query = Transaksi::with(['barang', 'user'])->masuk();

        // Filter tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter barang
        if ($request->has('barang_id') && $request->barang_id != '') {
            $query->where('barang_id', $request->barang_id);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(10);
        $barangs = Barang::active()->orderBy('nama_barang')->get();

        return view('transaksi.barang-masuk', compact('transaksis', 'barangs'));
    }

    /**
     * Form untuk tambah barang masuk
     */
    public function createBarangMasuk()
    {
        $barangs = Barang::active()->orderBy('nama_barang')->get();
        return view('transaksi.create-barang-masuk', compact('barangs'));
    }

    /**
     * Simpan data barang masuk
     */
    public function storeBarangMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'supplier' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak valid',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);

            // Buat transaksi baru
            $transaksi = new Transaksi();
            $transaksi->barang_id = $request->barang_id;
            $transaksi->kode_transaksi = Transaksi::generateKodeTransaksi('masuk');
            $transaksi->jenis_transaksi = 'masuk';
            $transaksi->jumlah = $request->jumlah;
            $transaksi->harga_satuan = $request->harga_satuan;
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->supplier = $request->supplier;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->user_id = Auth::id();
            $transaksi->save();

            // Update stok barang
            $barang->updateStok($request->jumlah, 'masuk');

            DB::commit();

            return redirect()->route('transaksi.barang-masuk')
                ->with('success', 'Barang masuk berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilan untuk barang keluar dengan Export functionality (HANYA PDF)
     */
    public function barangKeluar(Request $request)
    {
        $query = Transaksi::with(['barang', 'user'])->keluar();

        // Filter tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter barang
        if ($request->has('barang_id') && $request->barang_id != '') {
            $query->where('barang_id', $request->barang_id);
        }

        // Handle Export PDF ONLY
        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportBarangKeluarPDF($request, $query);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(10);
        $barangs = Barang::active()->orderBy('nama_barang')->get();

        return view('transaksi.barang-keluar', compact('transaksis', 'barangs'));
    }

    /**
     * Export Barang Keluar PDF ONLY
     */
    private function exportBarangKeluarPDF(Request $request, $query)
    {
        try {
            $transaksis = $query->get();
            
            // Validasi apakah ada data untuk diekspor
            if ($transaksis->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data untuk diekspor dengan filter yang dipilih.');
            }
            
            // Generate filename with date range
            $dateRange = '';
            if ($request->tanggal_dari && $request->tanggal_sampai) {
                $dateRange = '_' . $request->tanggal_dari . '_to_' . $request->tanggal_sampai;
            } elseif ($request->tanggal_dari) {
                $dateRange = '_from_' . $request->tanggal_dari;
            } elseif ($request->tanggal_sampai) {
                $dateRange = '_until_' . $request->tanggal_sampai;
            }
            
            $filename = 'barang_keluar' . $dateRange . '_' . date('Y-m-d_H-i-s') . '.pdf';

            // Cek apakah view untuk PDF sudah ada
            if (!view()->exists('exports.barang-keluar-pdf')) {
                throw new \Exception('Template PDF tidak ditemukan. Pastikan file barang-keluar-pdf.blade.php sudah dibuat di resources/views/exports/');
            }
            
            // Prepare data untuk PDF dengan validasi lengkap
            $filters = [
                'tanggal_dari' => $request->tanggal_dari ?? null,
                'tanggal_sampai' => $request->tanggal_sampai ?? null,
                'barang_id' => $request->barang_id ?? null,
            ];
            
            $barangs = Barang::active()->orderBy('nama_barang')->get();
            
            // Create PDF dengan data yang aman
            $pdf = PDF::loadView('exports.barang-keluar-pdf', [
                'transaksis' => $transaksis,
                'filters' => $filters,
                'barangs' => $barangs
            ]);
            
            // Set paper dan orientasi
            $pdf->setPaper('A4', 'landscape');
            
            // Set options untuk PDF
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Form untuk tambah barang keluar
     */
    public function createBarangKeluar()
    {
        $barangs = Barang::active()->where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('transaksi.create-barang-keluar', compact('barangs'));
    }

    /**
     * Simpan data barang keluar
     */
    public function storeBarangKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'customer' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak valid',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);
            
            // Cek stok mencukupi
            if ($barang->stok < $request->jumlah) {
                return redirect()->back()
                    ->with('error', "Stok tidak mencukupi! Stok tersedia: {$barang->stok} {$barang->satuan}")
                    ->withInput();
            }

            // Buat transaksi baru
            $transaksi = new Transaksi();
            $transaksi->barang_id = $request->barang_id;
            $transaksi->kode_transaksi = Transaksi::generateKodeTransaksi('keluar');
            $transaksi->jenis_transaksi = 'keluar';
            $transaksi->jumlah = $request->jumlah;
            $transaksi->harga_satuan = $request->harga_satuan;
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->customer = $request->customer;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->user_id = Auth::id();
            $transaksi->save();

            // Update stok barang
            $result = $barang->updateStok($request->jumlah, 'keluar');
            
            if (!$result) {
                throw new \Exception('Gagal memperbarui stok barang');
            }

            DB::commit();

            return redirect()->route('transaksi.barang-keluar')
                ->with('success', 'Barang keluar berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * API untuk mendapatkan detail barang
     */
    public function getBarangDetail($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'harga' => $barang->harga,
                    'stok' => $barang->stok,
                    'satuan' => $barang->satuan,
                    'formatted_harga' => $barang->formatted_harga ?? 'Rp ' . number_format($barang->harga, 0, ',', '.')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Hapus transaksi (dengan pengembalian stok)
     */
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            $barang = $transaksi->barang;
            
            // Kembalikan stok
            if ($transaksi->jenis_transaksi === 'masuk') {
                // Jika transaksi masuk dihapus, kurangi stok
                $barang->updateStok($transaksi->jumlah, 'keluar');
            } else {
                // Jika transaksi keluar dihapus, tambah stok
                $barang->updateStok($transaksi->jumlah, 'masuk');
            }

            $transaksi->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan transaksi
     */
    public function laporan(Request $request)
    {
        $query = Transaksi::with(['barang', 'user']);

        // Filter tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter jenis transaksi
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis_transaksi', $request->jenis);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(15);
        
        // Summary dengan filter yang sama
        $summaryQuery = function($jenisTransaksi) use ($request) {
            $query = Transaksi::where('jenis_transaksi', $jenisTransaksi);
            
            if ($request->tanggal_dari) {
                $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
            }
            
            if ($request->tanggal_sampai) {
                $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
            }
            
            return $query->sum('total_harga');
        };

        $totalMasuk = $summaryQuery('masuk');
        $totalKeluar = $summaryQuery('keluar');

        return view('transaksi.laporan', compact('transaksis', 'totalMasuk', 'totalKeluar'));
    }
}