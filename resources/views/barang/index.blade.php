@extends('layouts.app')

@section('title', 'Kelola Barang')

@push('styles')
<style>
    .glass-morphism {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    .gradient-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .status-indicator {
        position: relative;
        overflow: hidden;
    }
    
    .status-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }
    
    .status-indicator:hover::before {
        left: 100%;
    }
    
    .search-glow:focus {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 0 20px rgba(59, 130, 246, 0.3);
    }
    
    .pulse-animation {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .floating-fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
    }
    
    .stock-critical { color: #dc2626; }
    .stock-warning { color: #d97706; }
    .stock-safe { color: #059669; }
    
    .category-badge {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(79, 70, 229, 0.1) 100%);
        border: 1px solid rgba(147, 51, 234, 0.2);
    }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Modern Header -->
    <div class="relative overflow-hidden">
        <div class="gradient-card rounded-3xl p-8 text-gray-800">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-6 lg:mb-0">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-3">
                        Kelola Barang
                    </h1>
                    <p class="text-gray-600 text-lg flex items-center">
                        <i class="fas fa-boxes mr-3 text-blue-500"></i>
                        Manajemen inventory dan stok barang
                    </p>
                    <div class="flex items-center mt-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full pulse-animation mr-3"></div>
                        <span class="text-sm text-gray-600">System Online - {{ now()->format('l, d F Y') }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <button onclick="showBulkActions()" class="glass-morphism hover:bg-white/40 px-6 py-3 rounded-xl text-gray-700 transition-all duration-300 flex items-center font-medium">
                        <i class="fas fa-tasks mr-2"></i>
                        Bulk Actions
                    </button>
                    <a href="{{ route('barang.create') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="gradient-card rounded-2xl p-6 card-hover border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Total Barang</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($barangs->total()) }}</p>
                    <div class="flex items-center mt-3">
                        <div class="w-2 h-2 bg-green-400 rounded-full pulse-animation mr-2"></div>
                        <span class="text-green-600 text-sm font-medium">Active Items</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-boxes text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="gradient-card rounded-2xl p-6 card-hover border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-semibold uppercase tracking-wide">Barang Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($barangs->where('is_active', true)->count()) }}</p>
                    <div class="flex items-center mt-3">
                        <div class="w-2 h-2 bg-green-400 rounded-full pulse-animation mr-2"></div>
                        <span class="text-green-600 text-sm font-medium">
                            {{ $barangs->total() > 0 ? number_format(($barangs->where('is_active', true)->count() / $barangs->total()) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="gradient-card rounded-2xl p-6 card-hover border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-wide">Stok Menipis</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($barangs->where('stok', '<=', 10)->count()) }}</p>
                    <div class="flex items-center mt-3">
                        @if($barangs->where('stok', '<=', 10)->count() > 0)
                            <div class="w-2 h-2 bg-amber-400 rounded-full pulse-animation mr-2"></div>
                            <span class="text-amber-600 text-sm font-medium">Needs Attention</span>
                        @else
                            <div class="w-2 h-2 bg-green-400 rounded-full pulse-animation mr-2"></div>
                            <span class="text-green-600 text-sm font-medium">All Safe</span>
                        @endif
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="gradient-card rounded-2xl p-6 card-hover border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-semibold uppercase tracking-wide">Total Nilai</p>
                    @php
                        $totalNilai = $barangs->sum(function($item) { return $item->stok * $item->harga; });
                    @endphp
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalNilai / 1000000, 1) }}M</p>
                    <div class="flex items-center mt-3">
                        <div class="w-2 h-2 bg-purple-400 rounded-full pulse-animation mr-2"></div>
                        <span class="text-purple-600 text-sm font-medium">IDR</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search & Filters -->
    <div class="gradient-card rounded-2xl p-6 shadow-xl">
        <form method="GET" action="{{ route('barang.index') }}" class="space-y-6">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-12 pr-4 py-4 bg-white/50 border border-gray-200/50 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 search-glow transition-all duration-300 text-lg placeholder-gray-400"
                       placeholder="🔍 Cari nama barang, kode, atau kategori...">
            </div>

            <!-- Filter Pills -->
            <div class="flex flex-wrap gap-4">
                <!-- Kategori Filter -->
                <div class="relative">
                    <select name="kategori" class="appearance-none px-4 py-3 bg-white/70 border border-gray-200/50 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 pr-10">
                        <option value="">🏷️ Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select name="status" class="appearance-none px-4 py-3 bg-white/70 border border-gray-200/50 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 pr-10">
                        <option value="">📊 Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>❌ Non-aktif</option>
                        <option value="stok_menipis" {{ request('status') == 'stok_menipis' ? 'selected' : '' }}>⚠️ Stok Menipis</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="relative">
                    <select name="sort" class="appearance-none px-4 py-3 bg-white/70 border border-gray-200/50 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 pr-10">
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>📝 Nama A-Z</option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>📝 Nama Z-A</option>
                        <option value="stok_asc" {{ request('sort') == 'stok_asc' ? 'selected' : '' }}>📉 Stok Terendah</option>
                        <option value="stok_desc" {{ request('sort') == 'stok_desc' ? 'selected' : '' }}>📈 Stok Tertinggi</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('barang.index') }}" class="glass-morphism hover:bg-white/40 text-gray-700 px-6 py-3 rounded-xl font-medium transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                    <div class="relative">
                        <button type="button" onclick="toggleExportMenu()" class="bg-green-100 hover:bg-green-200 text-green-700 px-6 py-3 rounded-xl font-medium transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <div id="export-menu" class="hidden absolute top-full mt-2 right-0 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 w-48 z-20">
                            <a href="#" onclick="exportData('excel')" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-excel mr-3 text-green-600"></i>Excel
                            </a>
                            <a href="#" onclick="exportData('pdf')" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-pdf mr-3 text-red-600"></i>PDF
                            </a>
                            <a href="#" onclick="exportData('csv')" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-csv mr-3 text-blue-600"></i>CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- View Toggle & Stats -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <span class="text-gray-600 bg-white/50 px-4 py-2 rounded-lg">
                📄 Menampilkan {{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }} dari {{ $barangs->total() }} barang
            </span>
        </div>
        <div class="glass-morphism rounded-xl p-2 flex items-center space-x-2">
            <button onclick="toggleView('grid')" id="grid-view" class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg transition-all duration-300">
                <i class="fas fa-th-large"></i>
            </button>
            <button onclick="toggleView('list')" id="list-view" class="p-3 text-gray-600 hover:bg-white/30 rounded-lg transition-all duration-300">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Items Content -->
    @if($barangs->count() > 0)
        <!-- Grid View (Default) -->
        <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($barangs as $index => $barang)
            <div class="gradient-card rounded-2xl overflow-hidden card-hover animate-fadeIn" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="p-6">
                    <!-- Card Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-mono">{{ $barang->kode_barang }}</span>
                            <div class="flex items-center mt-2">
                                @if($barang->is_active)
                                    <div class="w-3 h-3 bg-green-400 rounded-full pulse-animation mr-2"></div>
                                    <span class="text-xs text-green-600 font-medium">Active</span>
                                @else
                                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 font-medium">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($barang->stok <= 0)
                                <span class="status-indicator inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Habis
                                </span>
                            @elseif($barang->stok <= 10)
                                <span class="status-indicator inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Menipis
                                </span>
                            @else
                                <span class="status-indicator inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Aman
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Item Info -->
                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $barang->nama_barang }}</h3>
                    
                    @if($barang->deskripsi)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($barang->deskripsi, 60) }}</p>
                    @endif

                    <!-- Stock & Price -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold {{ $barang->stok <= 0 ? 'stock-critical' : ($barang->stok <= 10 ? 'stock-warning' : 'stock-safe') }}">
                                {{ number_format($barang->stok) }}
                            </span>
                            <span class="text-gray-500 text-sm ml-2">{{ $barang->satuan }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-green-600">{{ $barang->formatted_harga }}</span>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <span class="category-badge inline-block text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $barang->kategori }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('barang.show', $barang) }}" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-center py-2 rounded-lg font-medium transition-all duration-200 hover:shadow-md">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        <a href="{{ route('barang.edit', $barang) }}" class="flex-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-center py-2 rounded-lg font-medium transition-all duration-200 hover:shadow-md">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('barang.toggle-status', $barang) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-gray-50 hover:bg-gray-100 text-gray-700 p-2 rounded-lg transition-all duration-200 hover:shadow-md" 
                                    title="{{ $barang->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                <i class="fas fa-{{ $barang->is_active ? 'toggle-on text-green-600' : 'toggle-off text-gray-400' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- List View (Hidden by default) -->
        <div id="list-container" class="hidden gradient-card rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($barangs as $barang)
                        <tr class="table-hover">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</div>
                                        <div class="text-sm text-gray-500">{{ $barang->kode_barang }}</div>
                                        @if($barang->deskripsi)
                                            <div class="text-xs text-gray-400 mt-1">{{ Str::limit($barang->deskripsi, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="category-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-purple-800">
                                    {{ $barang->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium {{ $barang->stok <= 0 ? 'stock-critical' : ($barang->stok <= 10 ? 'stock-warning' : 'stock-safe') }}">
                                    {{ number_format($barang->stok) }} {{ $barang->satuan }}
                                </div>
                                @if($barang->stok <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">Habis</span>
                                @elseif($barang->stok <= 10)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mt-1">Menipis</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-green-600">{{ $barang->formatted_harga }}</td>
                            <td class="px-6 py-4">
                                @if($barang->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 pulse-animation"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('barang.show', $barang) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-2 hover:bg-blue-50 rounded-lg">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('barang.edit', $barang) }}" class="text-amber-600 hover:text-amber-900 transition-colors duration-200 p-2 hover:bg-amber-50 rounded-lg">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('barang.toggle-status', $barang) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 p-2 hover:bg-gray-50 rounded-lg">
                                            <i class="fas fa-{{ $barang->is_active ? 'toggle-on text-green-600' : 'toggle-off text-gray-400' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($barangs->hasPages())
            <div class="flex justify-center items-center space-x-4">
                <div class="glass-morphism rounded-xl p-2 flex items-center space-x-2">
                    @if($barangs->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-2"></i>Previous
                        </span>
                    @else
                        <a href="{{ $barangs->previousPageUrl() }}" class="px-4 py-2 text-gray-600 hover:bg-white/30 rounded-lg transition-all duration-300">
                            <i class="fas fa-chevron-left mr-2"></i>Previous
                        </a>
                    @endif
                    
                    <div class="flex space-x-2">
                        @foreach ($barangs->getUrlRange(1, $barangs->lastPage()) as $page => $url)
                            @if ($page == $barangs->currentPage())
                                <span class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg font-medium flex items-center justify-center">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 glass-morphism text-gray-600 rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center justify-center">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>
                    
                    @if($barangs->hasMorePages())
                        <a href="{{ $barangs->nextPageUrl() }}" class="px-4 py-2 text-gray-600 hover:bg-white/30 rounded-lg transition-all duration-300">
                            Next<i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                            Next<i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <!-- Enhanced Empty State -->
        <div class="gradient-card rounded-3xl p-12 text-center">
            <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-box-open text-gray-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum ada barang</h3>
            <p class="text-gray-600 mb-8 text-lg">Mulai tambahkan barang pertama untuk mengelola inventory Anda</p>
            <a href="{{ route('barang.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-3"></i>
                Tambah Barang Pertama
            </a>
        </div>
    @endif

    <!-- Enhanced Floating Action Button -->
    <div class="floating-fab">
        <a href="{{ route('barang.create') }}" class="group relative w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-full shadow-2xl hover:shadow-3xl flex items-center justify-center transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-plus text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold text-white animate-pulse">
                {{ $barangs->where('stok', '<=', 10)->count() }}
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Enhanced View toggle functionality
function toggleView(viewType) {
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');
    const gridBtn = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view');

    if (viewType === 'grid') {
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        gridBtn.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white');
        gridBtn.classList.remove('text-gray-600', 'hover:bg-white/30');
        listBtn.classList.add('text-gray-600', 'hover:bg-white/30');
        listBtn.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white');
        localStorage.setItem('barang-view', 'grid');
    } else {
        gridContainer.classList.add('hidden');
        listContainer.classList.remove('hidden');
        listBtn.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white');
        listBtn.classList.remove('text-gray-600', 'hover:bg-white/30');
        gridBtn.classList.add('text-gray-600', 'hover:bg-white/30');
        gridBtn.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white');
        localStorage.setItem('barang-view', 'list');
    }
}

// Auto submit form on filter change
document.querySelectorAll('select[name="kategori"], select[name="status"], select[name="sort"]').forEach(function(select) {
    select.addEventListener('change', function() {
        // Add loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        submitBtn.disabled = true;
        
        this.form.submit();
    });
});

// Enhanced Export functionality
function toggleExportMenu() {
    const menu = document.getElementById('export-menu');
    menu.classList.toggle('hidden');
}

function exportData(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    // Show loading notification
    showNotification('Preparing export...', 'info');
    
    window.open(`{{ route('barang.index') }}?${params.toString()}`, '_blank');
    document.getElementById('export-menu').classList.add('hidden');
}

// Enhanced Bulk actions
function showBulkActions() {
    showNotification('🚀 Bulk actions feature coming soon!', 'info');
}

// Enhanced Notification system
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-amber-500' : 'bg-blue-500';
    
    notification.className = `fixed top-6 right-6 px-6 py-4 rounded-xl text-white z-50 transform translate-x-full transition-all duration-300 shadow-2xl ${bgColor}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation-triangle' : 'info'} mr-3 text-lg"></i>
            <div class="flex-1">
                ${message}
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/70 hover:text-white transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, duration);
}

// Close export menu when clicking outside
document.addEventListener('click', function(event) {
    const exportMenu = document.getElementById('export-menu');
    const exportButton = event.target.closest('button[onclick="toggleExportMenu()"]');
    
    if (!exportButton && !exportMenu.contains(event.target)) {
        exportMenu.classList.add('hidden');
    }
});

// Enhanced Search functionality with debounce
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            // You can implement live search here
            console.log('Searching for:', this.value);
        }, 500);
    });
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('barang-view');
    if (savedView === 'list') {
        toggleView('list');
    }
    
    // Stagger animation for cards
    const cards = document.querySelectorAll('.animate-fadeIn');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Enhanced ripple effect for buttons
document.querySelectorAll('button, a').forEach(element => {
    element.addEventListener('click', function(e) {
        if (!this.classList.contains('no-ripple')) {
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
    });
});

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>
@endpush