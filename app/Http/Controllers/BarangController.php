<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BarangController extends Controller
{
   /**
     * Handle export functionality in index method
     * Add this to your existing BarangController index method
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->has('status')) {
            if ($request->status == 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status == 'nonaktif') {
                $query->where('is_active', false);
            } elseif ($request->status == 'stok_menipis') {
                $query->stokMenipis();
            }
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'nama_desc':
                    $query->orderBy('nama_barang', 'desc');
                    break;
                case 'stok_asc':
                    $query->orderBy('stok', 'asc');
                    break;
                case 'stok_desc':
                    $query->orderBy('stok', 'desc');
                    break;
                default:
                    $query->orderBy('nama_barang', 'asc');
            }
        } else {
            $query->orderBy('nama_barang');
        }

        // Handle Export
        if ($request->has('export')) {
            return $this->exportBarang($query, $request->export);
        }

        $barangs = $query->paginate(10);
        $kategoris = Barang::distinct()->pluck('kategori')->filter();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    /**
     * Export barang data
     */
    private function exportBarang($query, $format)
    {
        $barangs = $query->get();
        $filename = 'barang_' . date('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ];

            $callback = function() use ($barangs) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 
                    'Satuan', 'Harga', 'Status', 'Dibuat'
                ]);

                // Data
                foreach ($barangs as $barang) {
                    fputcsv($file, [
                        $barang->kode_barang,
                        $barang->nama_barang,
                        $barang->kategori,
                        $barang->stok,
                        $barang->satuan,
                        $barang->harga,
                        $barang->is_active ? 'Aktif' : 'Non-aktif',
                        $barang->created_at->format('d/m/Y')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // For other formats, you can add Excel/PDF export here
        return redirect()->back()->with('error', 'Format export tidak didukung');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Barang::distinct()->pluck('kategori')->filter();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kategori' => 'required|string|max:100',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'stok_awal.required' => 'Stok awal wajib diisi',
            'stok_awal.integer' => 'Stok awal harus berupa angka',
            'satuan.required' => 'Satuan wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $barang = new Barang();
            $barang->kode_barang = $this->generateKodeBarang();
            $barang->nama_barang = $request->nama_barang;
            $barang->deskripsi = $request->deskripsi;
            $barang->harga = $request->harga;
            $barang->stok = $request->stok_awal;
            $barang->satuan = $request->satuan;
            $barang->kategori = $request->kategori;
            $barang->is_active = true;
            $barang->save();

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        $barang->load(['transaksi' => function ($query) {
            $query->orderBy('tanggal_transaksi', 'desc');
        }]);

        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $kategoris = Barang::distinct()->pluck('kategori')->filter();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'kategori' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'satuan' => $request->satuan,
                'kategori' => $request->kategori,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            // Cek apakah barang memiliki transaksi
            if ($barang->transaksi()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi. Gunakan fitur non-aktifkan barang.');
            }

            $barang->delete();

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate kode barang otomatis
     */
    private function generateKodeBarang()
    {
        $prefix = 'BRG';
        $date = date('Ymd');
        
        // Ambil nomor urut terakhir hari ini
        $lastBarang = Barang::where('kode_barang', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        $urutan = 1;
        if ($lastBarang) {
            $lastKode = $lastBarang->kode_barang;
            $lastUrutan = (int) substr($lastKode, -3);
            $urutan = $lastUrutan + 1;
        }

        return $prefix . $date . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Toggle status aktif barang
     */
    public function toggleStatus(Barang $barang)
    {
        try {
            $barang->update(['is_active' => !$barang->is_active]);
            
            $status = $barang->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "Barang berhasil {$status}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}