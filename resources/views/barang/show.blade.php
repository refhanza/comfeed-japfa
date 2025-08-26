@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="space-y-2">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                {{ $barang->nama_barang }}
            </h1>
            <nav class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-home mr-1"></i>Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('barang.index') }}" class="hover:text-blue-600 transition-colors duration-200">
                    Kelola Barang
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-900 font-medium">Detail Barang</span>
            </nav>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('barang.edit', $barang) }}" 
               class="inline-flex items-center px-4 py-2 glass-morphism rounded-xl text-amber-700 hover:bg-amber-50 transition-all duration-300 hover-lift">
                <i class="fas fa-edit mr-2"></i>
                <span class="font-medium">Edit</span>
            </a>
            
            <a href="{{ route('barang.index') }}" 
               class="inline-flex items-center px-4 py-2 glass-morphism rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-300 hover-lift">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Barang Information Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-box mr-3"></i>
                            Informasi Barang
                        </h2>
                        <div class="flex items-center space-x-2">
                            @if($barang->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <i class="fas fa-pause-circle mr-1"></i>Non-aktif
                                </span>
                            @endif
                            
                            @if($barang->stok <= 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Stok Habis
                                </span>
                            @elseif($barang->stok <= 10)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Stok Menipis
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Barang -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kode Barang</label>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-barcode text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-gray-900 cursor-pointer hover:text-blue-600 transition-colors" 
                                       onclick="copyToClipboard('{{ $barang->kode_barang }}')" 
                                       title="Klik untuk copy">
                                        {{ $barang->kode_barang }}
                                    </p>
                                    <p class="text-sm text-gray-500">Klik untuk copy kode</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Nama Barang</label>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-cube text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-gray-900">{{ $barang->nama_barang }}</p>
                                    <p class="text-sm text-gray-500">Nama produk</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kategori</label>
                            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl border border-indigo-200">
                                <i class="fas fa-tag text-indigo-600 mr-2"></i>
                                <span class="text-indigo-800 font-semibold">{{ $barang->kategori }}</span>
                            </div>
                        </div>

                        <!-- Satuan -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Satuan</label>
                            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl border border-teal-200">
                                <i class="fas fa-balance-scale text-teal-600 mr-2"></i>
                                <span class="text-teal-800 font-semibold">{{ $barang->satuan }}</span>
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Harga Satuan</label>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-emerald-600">{{ $barang->formatted_harga }}</p>
                                    <p class="text-sm text-gray-500">Per {{ $barang->satuan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stok -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Stok Tersedia</label>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    {{ $barang->stok <= 0 ? 'bg-gradient-to-br from-red-500 to-red-600' : 
                                       ($barang->stok <= 10 ? 'bg-gradient-to-br from-amber-500 to-amber-600' : 
                                        'bg-gradient-to-br from-green-500 to-green-600') }}">
                                    <i class="fas fa-warehouse text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold 
                                        {{ $barang->stok <= 0 ? 'text-red-600' : 
                                           ($barang->stok <= 10 ? 'text-amber-600' : 'text-green-600') }}">
                                        {{ number_format($barang->stok) }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $barang->satuan }}</p>
                                </div>
                            </div>
                        </div>

                        @if($barang->deskripsi)
                        <!-- Deskripsi -->
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Deskripsi</label>
                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border-l-4 border-blue-500">
                                <p class="text-gray-700 leading-relaxed">{{ $barang->deskripsi }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transaction History Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-history mr-3"></i>
                            Riwayat Transaksi
                        </h2>
                        <div class="flex items-center space-x-2">
                            <button onclick="filterTransaksi('masuk')" 
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white/20 text-white hover:bg-white/30" 
                                    id="filter-masuk">
                                <i class="fas fa-arrow-down mr-1"></i>Masuk
                            </button>
                            <button onclick="filterTransaksi('keluar')" 
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white/20 text-white hover:bg-white/30" 
                                    id="filter-keluar">
                                <i class="fas fa-arrow-up mr-1"></i>Keluar
                            </button>
                            <button onclick="filterTransaksi('all')" 
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white/30 text-white" 
                                    id="filter-all">
                                <i class="fas fa-list mr-1"></i>Semua
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="overflow-hidden">
                    @if($barang->transaksi->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white" id="transaksi-table">
                                @foreach($barang->transaksi as $transaksi)
                                <tr class="transaksi-row hover:bg-gray-50 transition-all duration-200" 
                                    data-jenis="{{ $transaksi->jenis_transaksi }}">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $transaksi->tanggal_transaksi->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $transaksi->tanggal_transaksi->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <code class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-md font-mono border border-blue-200">
                                            {{ $transaksi->kode_transaksi }}
                                        </code>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($transaksi->jenis_transaksi === 'masuk')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-arrow-down mr-1"></i>Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-arrow-up mr-1"></i>Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold {{ $transaksi->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaksi->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                            </span>
                                            <span class="text-sm text-gray-500 ml-2">{{ $barang->satuan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaksi->formatted_harga_satuan }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ $transaksi->formatted_total_harga }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-semibold">
                                                    {{ substr($transaksi->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $transaksi->user->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">
                                            @if($transaksi->keterangan)
                                                {{ Str::limit($transaksi->keterangan, 30) }}
                                            @else
                                                <span class="text-gray-400 italic">-</span>
                                            @endif
                                        </div>
                                        
                                        @if($transaksi->supplier)
                                            <div class="text-xs text-blue-600 mt-1 flex items-center">
                                                <i class="fas fa-truck mr-1"></i>{{ $transaksi->supplier }}
                                            </div>
                                        @endif
                                        
                                        @if($transaksi->customer)
                                            <div class="text-xs text-orange-600 mt-1 flex items-center">
                                                <i class="fas fa-user mr-1"></i>{{ $transaksi->customer }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada transaksi</h3>
                        <p class="text-gray-500 mb-6">Transaksi akan muncul di sini setelah barang digunakan</p>
                        <div class="flex items-center justify-center space-x-4">
                            <a href="{{ route('transaksi.create-barang-masuk') }}?barang_id={{ $barang->id }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors duration-200 hover-lift">
                                <i class="fas fa-arrow-down mr-2"></i>Barang Masuk
                            </a>
                            @if($barang->stok > 0)
                            <a href="{{ route('transaksi.create-barang-keluar') }}?barang_id={{ $barang->id }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors duration-200 hover-lift">
                                <i class="fas fa-arrow-up mr-2"></i>Barang Keluar
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Statistik
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Current Stock -->
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 p-4 border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-warehouse text-white text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">Stok Saat Ini</p>
                                <p class="text-2xl font-bold {{ $barang->stok <= 0 ? 'text-red-600' : ($barang->stok <= 10 ? 'text-amber-600' : 'text-blue-600') }}">
                                    {{ number_format($barang->stok) }}
                                </p>
                                <p class="text-sm text-blue-500">{{ $barang->satuan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Value -->
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 border border-emerald-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-emerald-600 uppercase tracking-wide">Nilai Total</p>
                                <p class="text-2xl font-bold text-emerald-600">
                                    Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-emerald-500">Stok × Harga</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total In -->
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-50 to-green-100 p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-arrow-down text-white text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-green-600 uppercase tracking-wide">Total Masuk</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ number_format($barang->transaksi->where('jenis_transaksi', 'masuk')->sum('jumlah')) }}
                                </p>
                                <p class="text-sm text-green-500">{{ $barang->satuan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Out -->
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-red-50 to-red-100 p-4 border border-red-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-arrow-up text-white text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-red-600 uppercase tracking-wide">Total Keluar</p>
                                <p class="text-2xl font-bold text-red-600">
                                    {{ number_format($barang->transaksi->where('jenis_transaksi', 'keluar')->sum('jumlah')) }}
                                </p>
                                <p class="text-sm text-red-500">{{ $barang->satuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-bolt mr-3"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('transaksi.create-barang-masuk') }}?barang_id={{ $barang->id }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200 hover-lift">
                        <i class="fas fa-arrow-down mr-2"></i>
                        <span class="font-medium">Tambah Stok</span>
                    </a>
                    
                    @if($barang->stok > 0)
                    <a href="{{ route('transaksi.create-barang-keluar') }}?barang_id={{ $barang->id }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 hover-lift">
                        <i class="fas fa-arrow-up mr-2"></i>
                        <span class="font-medium">Kurangi Stok</span>
                    </a>
                    @endif
                    
                    <a href="{{ route('barang.edit', $barang) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-all duration-200 hover-lift">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="font-medium">Edit Barang</span>
                    </a>
                    
                    <form action="{{ route('barang.toggle-status', $barang) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 {{ $barang->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-xl transition-all duration-200 hover-lift">
                            <i class="fas fa-{{ $barang->is_active ? 'pause' : 'play' }} mr-2"></i>
                            <span class="font-medium">{{ $barang->is_active ? 'Non-aktifkan' : 'Aktifkan' }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Detail Information Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informasi Detail
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm font-medium text-gray-500">Dibuat pada:</span>
                        <span class="text-sm text-gray-900">{{ $barang->created_at->format('d F Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Terakhir diupdate:</span>
                        <span class="text-sm text-gray-900">{{ $barang->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Total transaksi:</span>
                        <span class="text-sm text-gray-900">{{ $barang->transaksi->count() }} transaksi</span>
                    </div>
                    
                    @if($barang->transaksi->count() > 0)
                    <div class="flex items-center justify-between py-2 border-t border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Transaksi terakhir:</span>
                        <span class="text-sm text-gray-900">{{ $barang->transaksi->first()->created_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Kode barang berhasil disalin!', 'success');
    }).catch(() => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Kode barang berhasil disalin!', 'success');
    });
}

// Filter Transaction Function
function filterTransaksi(jenis) {
    const rows = document.querySelectorAll('.transaksi-row');
    const buttons = document.querySelectorAll('[id^="filter-"]');
    
    // Reset all buttons
    buttons.forEach(btn => {
        btn.className = 'px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white/20 text-white hover:bg-white/30';
    });
    
    // Show/hide rows based on filter
    rows.forEach(row => {
        if (jenis === 'all') {
            row.style.display = '';
            row.classList.add('animate-fade-in');
        } else {
            if (row.dataset.jenis === jenis) {
                row.style.display = '';
                row.classList.add('animate-fade-in');
            } else {
                row.style.display = 'none';
                row.classList.remove('animate-fade-in');
            }
        }
    });
    
    // Update active button
    const activeButton = document.getElementById(`filter-${jenis}`);
    if (activeButton) {
        activeButton.className = 'px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white/40 text-white border border-white/50';
    }
}

// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.transaksi-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('shadow-md', 'transform', 'scale-[1.01]');
        });
        
        row.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-md', 'transform', 'scale-[1.01]');
        });
    });

    // Add loading state for form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });
});
</script>

<style>
/* Additional custom styles for this page */
.transaksi-row {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced mobile responsiveness */
@media (max-width: 640px) {
    .glass-morphism {
        margin: 0 -1rem;
        border-radius: 1rem 1rem 0 0;
    }
    
    .overflow-x-auto {
        margin: 0 -1.5rem;
    }
    
    .px-4 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-xl {
        font-size: 1.25rem;
    }
}

/* Print styles */
@media print {
    .glass-morphism {
        background: white !important;
        backdrop-filter: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .bg-gradient-to-r {
        background: #374151 !important;
        color: white !important;
    }
    
    .hover-lift, .transition-all {
        transition: none !important;
        transform: none !important;
    }
    
    button, .hover\\:bg-green-700, .hover\\:bg-red-700 {
        display: none !important;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .animate-fade-in,
    .hover-lift,
    .transition-all,
    .transition-colors {
        animation: none !important;
        transition: none !important;
    }
}

/* Loading skeleton animation */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}
</style>
@endsection