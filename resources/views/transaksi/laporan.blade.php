@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Print Header -->
    <div class="hidden print:block print-header">
        <div class="print-header-content">
            <div class="header-left">
                <div class="company-logo">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="company-info">
                    <h1>COMFEED JAPFA</h1>
                    <h2>LAPORAN TRANSAKSI INVENTORY</h2>
                    <p>Sistem Manajemen Inventory Terintegrasi</p>
                </div>
            </div>
            <div class="header-right">
                <p><strong>Tanggal Cetak:</strong></p>
                <p>{{ now()->format('d F Y, H:i') }} WIB</p>
                <p><strong>Dicetak oleh:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Screen Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-700 rounded-xl shadow-lg p-6 text-white print:hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold">📊 Laporan Transaksi</h1>
                    <p class="text-blue-100">Dashboard Analytics & Reporting</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <button id="print-btn" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded-lg backdrop-blur-sm transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-print text-sm"></i>
                    <span>Cetak Laporan</span>
                </button>
                <a href="{{ route('dashboard') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded-lg backdrop-blur-sm transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 print:hidden">
        <a href="{{ route('transaksi.create-barang-masuk') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-lg p-4 text-white transition-all duration-200 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">📈 Barang Masuk</h3>
                    <p class="text-green-100 text-sm">Tambah transaksi masuk</p>
                </div>
                <i class="fas fa-plus text-xl opacity-80"></i>
            </div>
        </a>
        
        <a href="{{ route('transaksi.create-barang-keluar') }}" class="bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 rounded-lg p-4 text-white transition-all duration-200 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">📉 Barang Keluar</h3>
                    <p class="text-red-100 text-sm">Tambah transaksi keluar</p>
                </div>
                <i class="fas fa-minus text-xl opacity-80"></i>
            </div>
        </a>
        
        <a href="{{ route('barang.index') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-lg p-4 text-white transition-all duration-200 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">📦 Kelola Barang</h3>
                    <p class="text-blue-100 text-sm">Manajemen inventory</p>
                </div>
                <i class="fas fa-boxes text-xl opacity-80"></i>
            </div>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 print:hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 rounded-t-xl">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-filter text-blue-500 mr-3"></i>
                Filter & Pencarian
            </h2>
        </div>
        
        <div class="p-6">
            <form method="GET" action="{{ route('transaksi.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📅 Tanggal Mulai</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📅 Tanggal Akhir</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📋 Jenis Transaksi</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Semua Transaksi</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>📈 Barang Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>📉 Barang Keluar</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">⚡ Aksi</label>
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-lg transition-all duration-200 flex items-center justify-center space-x-1">
                            <i class="fas fa-search text-sm"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('transaksi.laporan') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded-lg transition-all duration-200 flex items-center justify-center space-x-1">
                            <i class="fas fa-redo text-sm"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Print Filter Info -->
    @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis']))
    <div class="print-filter-info">
        <div class="filter-container">
            <h3><i class="fas fa-filter"></i> FILTER LAPORAN</h3>
            <div class="filter-grid">
                @if(request('tanggal_dari'))
                    <div><strong>Periode Mulai:</strong> {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d F Y') }}</div>
                @endif
                @if(request('tanggal_sampai'))
                    <div><strong>Periode Akhir:</strong> {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d F Y') }}</div>
                @endif
                @if(request('jenis'))
                    <div><strong>Jenis Transaksi:</strong> {{ ucfirst(request('jenis')) }}</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="summary-section">
        @php
            $totalMasukValue = $totalMasuk ?? 0;
            $totalKeluarValue = $totalKeluar ?? 0;
            $netBalance = $totalMasukValue - $totalKeluarValue;
        @endphp
        
        <!-- Screen Version -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 print:hidden">
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-up text-lg"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-80">Growth</div>
                        <div class="text-sm font-bold">+12.5%</div>
                    </div>
                </div>
                <h3 class="font-semibold mb-1">💰 Total Barang Masuk</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalMasukValue, 0, ',', '.') }}</p>
                <p class="text-green-100 text-sm mt-1">Pemasukan Inventory</p>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-down text-lg"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-80">Usage</div>
                        <div class="text-sm font-bold">-8.3%</div>
                    </div>
                </div>
                <h3 class="font-semibold mb-1">💸 Total Barang Keluar</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalKeluarValue, 0, ',', '.') }}</p>
                <p class="text-red-100 text-sm mt-1">Pengeluaran Inventory</p>
            </div>

            <div class="bg-gradient-to-br {{ $netBalance >= 0 ? 'from-blue-500 to-indigo-600' : 'from-orange-500 to-amber-600' }} rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-balance-scale text-lg"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-80">Balance</div>
                        <div class="text-sm font-bold">{{ $netBalance >= 0 ? '+' : '-' }}{{ $totalMasukValue > 0 ? number_format(abs($netBalance / $totalMasukValue * 100), 1) : '0' }}%</div>
                    </div>
                </div>
                <h3 class="font-semibold mb-1">⚖️ Net Balance</h3>
                <p class="text-2xl font-bold">
                    {{ $netBalance >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netBalance), 0, ',', '.') }}
                </p>
                <p class="text-blue-100 text-sm mt-1">{{ $netBalance >= 0 ? '✅ Surplus' : '⚠️ Deficit' }}</p>
            </div>
        </div>

        <!-- Print Version -->
        <div class="hidden print:block print-summary">
            <div class="summary-box">
                <h3>RINGKASAN TRANSAKSI</h3>
                <div class="summary-flex">
                    <div class="summary-col">
                        <div class="summary-label">TOTAL BARANG MASUK</div>
                        <div class="summary-amount green">Rp {{ number_format($totalMasukValue, 0, ',', '.') }}</div>
                        <div class="summary-note">Pemasukan Inventory</div>
                    </div>
                    <div class="summary-col">
                        <div class="summary-label">TOTAL BARANG KELUAR</div>
                        <div class="summary-amount red">Rp {{ number_format($totalKeluarValue, 0, ',', '.') }}</div>
                        <div class="summary-note">Pengeluaran Inventory</div>
                    </div>
                    <div class="summary-col">
                        <div class="summary-label">NET BALANCE</div>
                        <div class="summary-amount {{ $netBalance >= 0 ? 'blue' : 'orange' }}">
                            {{ $netBalance >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netBalance), 0, ',', '.') }}
                        </div>
                        <div class="summary-note">{{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-section">
        <div class="table-container">
            <div class="table-header">
                <h2><i class="fas fa-table"></i> DETAIL TRANSAKSI</h2>
                <div class="record-count">{{ isset($transaksis) ? $transaksis->total() : 0 }} Records</div>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-kode">Kode</th>
                            <th class="col-tanggal">Tanggal</th>
                            <th class="col-barang">Barang</th>
                            <th class="col-jenis">Jenis</th>
                            <th class="col-jumlah">Jumlah</th>
                            <th class="col-harga">Harga</th>
                            <th class="col-total">Total</th>
                            <th class="col-partner">Partner</th>
                            <th class="col-user print:hidden">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($transaksis) && $transaksis->count() > 0)
                            @foreach($transaksis as $transaksi)
                                <tr>
                                    <td class="col-no">{{ $loop->iteration + (($transaksis->currentPage() - 1) * $transaksis->perPage()) }}</td>
                                    <td class="col-kode">
                                        <div class="code-badge">{{ $transaksi->kode_transaksi }}</div>
                                    </td>
                                    <td class="col-tanggal">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                                    <td class="col-barang">
                                        <div class="barang-name">{{ $transaksi->barang->nama_barang }}</div>
                                        <div class="barang-code">{{ $transaksi->barang->kode_barang }}</div>
                                    </td>
                                    <td class="col-jenis">
                                        <div class="jenis-badge {{ $transaksi->jenis_transaksi == 'masuk' ? 'masuk' : 'keluar' }}">
                                            {{ $transaksi->jenis_transaksi == 'masuk' ? 'MASUK' : 'KELUAR' }}
                                        </div>
                                    </td>
                                    <td class="col-jumlah">
                                        <div class="qty-wrapper">
                                            <span class="qty {{ $transaksi->jenis_transaksi == 'masuk' ? 'positive' : 'negative' }}">
                                                {{ $transaksi->jenis_transaksi == 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                            </span>
                                            <span class="unit">{{ $transaksi->barang->satuan }}</span>
                                        </div>
                                    </td>
                                    <td class="col-harga">Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="col-total">
                                        <div class="total-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="col-partner">{{ $transaksi->supplier ?? $transaksi->customer ?? '-' }}</td>
                                    <td class="col-user print:hidden">{{ $transaksi->user->name }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="no-data">
                                    <div class="no-data-content">
                                        <i class="fas fa-inbox"></i>
                                        <h3>Belum Ada Data Transaksi</h3>
                                        <p>Mulai dengan menambahkan transaksi pertama untuk melihat laporan yang komprehensif.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    
                    @if(isset($transaksis) && $transaksis->count() > 0)
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="7" class="total-label">GRAND TOTAL:</td>
                            <td class="grand-total">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="hidden print:block print-footer">
        <div class="footer-content">
            <div class="footer-left">
                <p><strong>COMFEED JAPFA</strong> - Sistem Manajemen Inventory</p>
                <p>Alamat: Jl. Industri No. 123, Jakarta | Telp: (021) 123-4567 | Email: info@comfeed-japfa.com</p>
            </div>
            <div class="footer-right">
                <p>Halaman 1 dari 1</p>
                <p>Dokumen ini dicetak pada {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($transaksis) && $transaksis->hasPages())
    <div class="print:hidden">
        <div class="bg-white rounded-lg shadow p-4">
            {{ $transaksis->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

<style>
/* ========== PRINT STYLES ========== */
@media print {
    @page {
        size: A4 landscape;
        margin: 0.4in 0.6in;
        background: white;
    }
    
    * {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        text-shadow: none !important;
        transform: none !important;
        animation: none !important;
        transition: none !important;
    }
    
    body {
        font-family: Arial, sans-serif !important;
        font-size: 9pt !important;
        line-height: 1.2 !important;
        color: black !important;
        background: white !important;
    }
    
    .print\:hidden { display: none !important; }
    .print\:block { display: block !important; }
    
    /* Header */
    .print-header {
        margin-bottom: 15px !important;
        border-bottom: 2px solid #333 !important;
        padding-bottom: 10px !important;
    }
    
    .print-header-content {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
    }
    
    .header-left {
        display: flex !important;
        align-items: center !important;
        gap: 15px !important;
    }
    
    .company-logo {
        width: 50px !important;
        height: 50px !important;
        background: #2563EB !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 20pt !important;
    }
    
    .company-info h1 {
        font-size: 18pt !important;
        font-weight: bold !important;
        margin: 0 !important;
        color: #1F2937 !important;
    }
    
    .company-info h2 {
        font-size: 12pt !important;
        font-weight: 600 !important;
        margin: 2px 0 !important;
        color: #2563EB !important;
    }
    
    .company-info p {
        font-size: 8pt !important;
        margin: 0 !important;
        color: #6B7280 !important;
    }
    
    .header-right {
        text-align: right !important;
        font-size: 8pt !important;
        color: #4B5563 !important;
    }
    
    .header-right p {
        margin: 1px 0 !important;
    }
    
    /* Filter Info */
    .print-filter-info {
        margin-bottom: 12px !important;
    }
    
    .filter-container {
        background: #F8F9FA !important;
        border: 1px solid #E5E7EB !important;
        padding: 8px !important;
    }
    
    .filter-container h3 {
        font-size: 9pt !important;
        font-weight: bold !important;
        margin-bottom: 6px !important;
        color: #1F2937 !important;
    }
    
    .filter-grid {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        font-size: 8pt !important;
    }
    
    /* Summary */
    .print-summary {
        margin-bottom: 15px !important;
    }
    
    .summary-box {
        background: #F8F9FA !important;
        border: 1px solid #6B7280 !important;
        padding: 10px !important;
    }
    
    .summary-box h3 {
        font-size: 11pt !important;
        font-weight: bold !important;
        text-align: center !important;
        margin-bottom: 10px !important;
        padding-bottom: 5px !important;
        border-bottom: 1px solid #6B7280 !important;
        color: #1F2937 !important;
    }
    
    .summary-flex {
        display: grid !important;
        grid-template-columns: 1fr 1fr 1fr !important;
        gap: 15px !important;
        text-align: center !important;
    }
    
    .summary-col:not(:last-child) {
        border-right: 1px solid #6B7280 !important;
        padding-right: 10px !important;
    }
    
    .summary-label {
        font-size: 8pt !important;
        font-weight: 600 !important;
        color: #6B7280 !important;
        margin-bottom: 3px !important;
    }
    
    .summary-amount {
        font-size: 12pt !important;
        font-weight: bold !important;
        margin-bottom: 2px !important;
    }
    
    .summary-amount.green { color: #15803D !important; }
    .summary-amount.red { color: #B91C1C !important; }
    .summary-amount.blue { color: #1D4ED8 !important; }
    .summary-amount.orange { color: #EA580C !important; }
    
    .summary-note {
        font-size: 7pt !important;
        color: #6B7280 !important;
    }
    
    /* Table Container */
    .table-container {
        border: 1px solid #6B7280 !important;
        overflow: visible !important;
    }
    
    .table-header {
        background: #F3F4F6 !important;
        padding: 8px !important;
        border-bottom: 1px solid #6B7280 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    
    .table-header h2 {
        font-size: 10pt !important;
        font-weight: bold !important;
        margin: 0 !important;
        color: #1F2937 !important;
    }
    
    .record-count {
        font-size: 8pt !important;
        color: #6B7280 !important;
        background: #E5E7EB !important;
        padding: 2px 6px !important;
    }
    
    /* Data Table - Fixed Width Columns */
    .data-table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 7pt !important;
        table-layout: fixed !important;
    }
    
    /* Column Width Definitions */
    .col-no { width: 4% !important; }
    .col-kode { width: 12% !important; }
    .col-tanggal { width: 8% !important; }
    .col-barang { width: 20% !important; }
    .col-jenis { width: 8% !important; }
    .col-jumlah { width: 10% !important; }
    .col-harga { width: 12% !important; }
    .col-total { width: 14% !important; }
    .col-partner { width: 12% !important; }
    .col-user { width: 0% !important; } /* Hidden on print */
    
    .data-table th {
        background: #F3F4F6 !important;
        color: #1F2937 !important;
        font-weight: bold !important;
        font-size: 7pt !important;
        padding: 4px 2px !important;
        text-align: center !important;
        border: 1px solid #6B7280 !important;
        vertical-align: middle !important;
        word-wrap: break-word !important;
    }
    
    .data-table td {
        padding: 3px 2px !important;
        border: 1px solid #9CA3AF !important;
        font-size: 7pt !important;
        color: #1F2937 !important;
        vertical-align: top !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .data-table tbody tr:nth-child(even) {
        background: #FAFAFA !important;
    }
    
    /* Cell Content Styling */
    .code-badge {
        font-family: monospace !important;
        font-size: 6pt !important;
        background: #E5E7EB !important;
        padding: 1px 3px !important;
        border: 1px solid #9CA3AF !important;
        display: block !important;
        text-align: center !important;
        word-break: break-all !important;
    }
    
    .barang-name {
        font-weight: 600 !important;
        font-size: 7pt !important;
        line-height: 1.1 !important;
        margin-bottom: 1px !important;
        word-break: break-word !important;
    }
    
    .barang-code {
        font-size: 6pt !important;
        color: #6B7280 !important;
        font-style: italic !important;
        word-break: break-all !important;
    }
    
    .jenis-badge {
        font-size: 6pt !important;
        font-weight: bold !important;
        padding: 2px 4px !important;
        border: 1px solid !important;
        text-align: center !important;
        display: block !important;
        border-radius: 0 !important;
    }
    
    .jenis-badge.masuk {
        background: #DCFCE7 !important;
        color: #15803D !important;
        border-color: #22C55E !important;
    }
    
    .jenis-badge.keluar {
        background: #FEE2E2 !important;
        color: #DC2626 !important;
        border-color: #EF4444 !important;
    }
    
    .qty-wrapper {
        text-align: center !important;
    }
    
    .qty {
        font-weight: bold !important;
        font-size: 7pt !important;
        display: block !important;
        margin-bottom: 1px !important;
    }
    
    .qty.positive { color: #16A34A !important; }
    .qty.negative { color: #DC2626 !important; }
    
    .unit { 
        font-size: 6pt !important; 
        color: #6B7280 !important;
        font-style: italic !important;
    }
    
    .total-value {
        font-weight: bold !important;
        color: #1F2937 !important;
        text-align: right !important;
        word-break: break-all !important;
    }
    
    /* Text Alignment for Columns */
    .col-no { text-align: center !important; }
    .col-kode { text-align: center !important; }
    .col-tanggal { text-align: center !important; }
    .col-barang { text-align: left !important; }
    .col-jenis { text-align: center !important; }
    .col-jumlah { text-align: center !important; }
    .col-harga { text-align: right !important; }
    .col-total { text-align: right !important; }
    .col-partner { text-align: left !important; }
    .col-user { text-align: left !important; }
    
    /* Footer */
    .data-table tfoot {
        background: #F3F4F6 !important;
        border-top: 2px solid #6B7280 !important;
    }
    
    .total-row td {
        font-weight: bold !important;
        font-size: 8pt !important;
        padding: 6px 2px !important;
    }
    
    .total-label {
        text-align: right !important;
        color: #1F2937 !important;
    }
    
    .grand-total {
        color: #2563EB !important;
        font-size: 9pt !important;
        text-align: right !important;
    }
    
    /* No Data */
    .no-data {
        text-align: center !important;
        padding: 20px !important;
    }
    
    .no-data-content i {
        font-size: 16pt !important;
        color: #9CA3AF !important;
        margin-bottom: 8px !important;
        display: block !important;
    }
    
    .no-data-content h3 {
        font-size: 10pt !important;
        color: #4B5563 !important;
        margin-bottom: 4px !important;
    }
    
    .no-data-content p {
        font-size: 8pt !important;
        color: #6B7280 !important;
    }
    
    /* Print Footer */
    .print-footer {
        margin-top: 15px !important;
        padding-top: 8px !important;
        border-top: 1px solid #6B7280 !important;
    }
    
    .footer-content {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        font-size: 7pt !important;
        color: #6B7280 !important;
    }
    
    .footer-content p {
        margin: 1px 0 !important;
    }
    
    .footer-right {
        text-align: right !important;
    }
    
    /* Hide loading and dynamic elements */
    .animate-spin,
    .loading,
    [class*="loading"],
    .fa-spinner,
    .spinner,
    [id*="loading"],
    .animate-pulse,
    .animate-bounce,
    .notification-enter,
    .notification-exit,
    [role="alert"],
    .alert {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }
    
    /* Remove icon content that might show as text */
    .fas:before,
    .fa:before {
        content: "" !important;
    }
    
    /* Ensure clean spacing */
    .space-y-6 > * + * {
        margin-top: 10px !important;
    }
    
    /* Override any positioning */
    .fixed,
    .absolute,
    .relative {
        position: static !important;
    }
    
    /* Remove any remaining backgrounds */
    .bg-gradient-to-r,
    .bg-gradient-to-br,
    .rounded-xl,
    .rounded-lg,
    .shadow-lg,
    .shadow-xl {
        background: white !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }
}

/* ========== SCREEN STYLES ========== */
@media screen {
    .print-header,
    .print-summary,
    .print-footer {
        display: none !important;
    }
    
    .print-filter-info {
        display: block;
    }
    
    .print-filter-info .bg-blue-50 {
        background: rgb(239 246 255) !important;
        border: 1px solid rgb(191 219 254) !important;
        border-radius: 0.5rem !important;
        padding: 1rem !important;
        margin-bottom: 1.5rem !important;
    }
    
    /* Screen Table Styling */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    
    .data-table th {
        background: #f8fafc;
        color: #374151;
        font-weight: 600;
        padding: 12px 8px;
        text-align: left;
        border-bottom: 2px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
    }
    
    .data-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        vertical-align: top;
    }
    
    .data-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .code-badge {
        background: #f3f4f6;
        color: #374151;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #d1d5db;
        display: inline-block;
    }
    
    .barang-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 2px;
    }
    
    .barang-code {
        font-size: 12px;
        color: #6b7280;
        font-style: italic;
    }
    
    .jenis-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 9999px;
        display: inline-block;
        text-align: center;
        min-width: 60px;
    }
    
    .jenis-badge.masuk {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #22c55e;
    }
    
    .jenis-badge.keluar {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #ef4444;
    }
    
    .qty {
        font-weight: 600;
        font-size: 14px;
    }
    
    .qty.positive { color: #059669; }
    .qty.negative { color: #dc2626; }
    
    .unit {
        color: #6b7280;
        font-size: 12px;
        margin-left: 4px;
    }
    
    .total-value {
        font-weight: 600;
        color: #111827;
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(90deg, #374151 0%, #4b5563 100%);
        padding: 16px 24px;
        border-bottom: 1px solid #6b7280;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h2 {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .record-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .no-data {
        text-align: center;
        padding: 60px 20px;
    }
    
    .no-data-content i {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    
    .no-data-content h3 {
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .no-data-content p {
        color: #6b7280;
        font-size: 14px;
        max-width: 400px;
        margin: 0 auto;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function cleanPrint() {
        removeLoadingElements();
        hideDynamicContent();
        window.print();
    }
    
    function removeLoadingElements() {
        const loadingSelectors = [
            '.animate-spin', '.loading', '[class*="loading"]', '.fa-spinner', 
            '.spinner', '[id*="loading"]', '.animate-pulse', '.animate-bounce',
            '.notification-enter', '.notification-exit', '[role="alert"]', '.alert'
        ];
        
        loadingSelectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(el => el.remove());
        });
        
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );
        
        const textNodes = [];
        let node;
        while (node = walker.nextNode()) {
            if (node.textContent.includes('Loading')) {
                textNodes.push(node);
            }
        }
        
        textNodes.forEach(node => {
            node.parentElement?.remove();
        });
    }
    
    function hideDynamicContent() {
        document.querySelectorAll('[data-loading], .loading-placeholder').forEach(el => {
            el.style.display = 'none';
        });
    }
    
    const printBtn = document.getElementById('print-btn');
    if (printBtn) {
        printBtn.addEventListener('click', cleanPrint);
    }
    
    window.addEventListener('beforeprint', function() {
        removeLoadingElements();
        hideDynamicContent();
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
    
    setTimeout(removeLoadingElements, 100);
    setTimeout(removeLoadingElements, 500);
    setTimeout(removeLoadingElements, 1000);
    
    console.log('✅ Enhanced table layout optimized');
    console.log('📊 Fixed column widths for proper alignment');
    console.log('🎯 Print-ready professional format');
});
</script>
@endsection