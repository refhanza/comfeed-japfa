@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
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
                <button onclick="window.print()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded-lg backdrop-blur-sm transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-print text-sm"></i>
                    <span>Cetak</span>
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
                <!-- Tanggal Dari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📅 Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <!-- Tanggal Sampai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📅 Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📋 Jenis Transaksi
                    </label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Semua Transaksi</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>📈 Barang Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>📉 Barang Keluar</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ⚡ Aksi
                    </label>
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Barang Masuk -->
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
            <p class="text-2xl font-bold">Rp {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</p>
            <p class="text-green-100 text-sm mt-1">Pemasukan Inventory</p>
        </div>

        <!-- Total Barang Keluar -->
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
            <p class="text-2xl font-bold">Rp {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</p>
            <p class="text-red-100 text-sm mt-1">Pengeluaran Inventory</p>
        </div>

        <!-- Net Balance -->
        @php
            $totalMasukValue = $totalMasuk ?? 0;
            $totalKeluarValue = $totalKeluar ?? 0;
            $netBalance = $totalMasukValue - $totalKeluarValue;
        @endphp
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

    <!-- Active Filter Info -->
    @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis']))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 print:block">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
            <div>
                <h3 class="font-semibold text-yellow-800 mb-2">🔎 Filter Aktif</h3>
                <div class="flex flex-wrap gap-2">
                    @if(request('tanggal_dari'))
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-200 text-yellow-800 text-sm rounded-full">
                            📅 Dari: {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d M Y') }}
                        </span>
                    @endif
                    @if(request('tanggal_sampai'))
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-200 text-yellow-800 text-sm rounded-full">
                            📅 Sampai: {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d M Y') }}
                        </span>
                    @endif
                    @if(request('jenis'))
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-200 text-yellow-800 text-sm rounded-full">
                            {{ request('jenis') == 'masuk' ? '📈' : '📉' }} {{ ucfirst(request('jenis')) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Table Header -->
        <div class="bg-gray-800 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-table text-white mr-3"></i>
                    📋 Detail Transaksi
                </h2>
                <div class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                    <span class="text-white text-sm font-medium">{{ isset($transaksis) ? $transaksis->total() : 0 }} Records</span>
                </div>
            </div>
        </div>
        
        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:hidden">User</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($transaksis) && $transaksis->count() > 0)
                        @foreach($transaksis as $transaksi)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $loop->iteration + (($transaksis->currentPage() - 1) * $transaksis->perPage()) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono text-gray-700">
                                        {{ $transaksi->kode_transaksi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $transaksi->formatted_tanggal }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center mr-2">
                                            <span class="text-white font-bold text-xs">{{ substr($transaksi->barang->nama_barang, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $transaksi->barang->nama_barang }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaksi->barang->kode_barang }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaksi->jenis_transaksi == 'masuk' ? '↗️ MASUK' : '↘️ KELUAR' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-lg font-bold {{ $transaksi->jenis_transaksi == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaksi->jenis_transaksi == 'masuk' ? '+' : '-' }}{{ $transaksi->jumlah }}
                                    </span>
                                    <span class="text-sm text-gray-500 ml-1">{{ $transaksi->barang->satuan }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $transaksi->formatted_harga_satuan }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                    {{ $transaksi->formatted_total_harga }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $transaksi->supplier ?? $transaksi->customer ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 print:hidden">
                                    {{ $transaksi->user->name }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-700 mb-2">📭 Belum Ada Data Transaksi</h3>
                                    <p class="text-gray-500 mb-4 text-center max-w-sm">Mulai dengan menambahkan transaksi pertama untuk melihat laporan yang komprehensif.</p>
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <a href="{{ route('transaksi.create-barang-masuk') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                            📈 Tambah Masuk
                                        </a>
                                        <a href="{{ route('transaksi.create-barang-keluar') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                            📉 Tambah Keluar
                                        </a>
                                        <a href="{{ route('barang.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                            📦 Kelola Barang
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                
                @if(isset($transaksis) && $transaksis->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr class="font-bold">
                        <td colspan="7" class="px-4 py-3 text-right text-gray-900">
                            💰 GRAND TOTAL:
                        </td>
                        <td class="px-4 py-3 text-lg font-bold text-yellow-600">
                            Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
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
@endsection