@extends('layouts.app')

@section('title', 'Data Barang Keluar')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">
                    Data Barang Keluar
                </h1>
                <p class="text-gray-600 mt-2">Kelola dan monitor semua transaksi barang keluar</p>
            </div>
            <a href="{{ route('transaksi.create-barang-keluar') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 focus:ring-4 focus:ring-red-200 transition-all duration-300 shadow-lg hover:shadow-xl group">
                <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                Tambah Barang Keluar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    @if($transaksis->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Total Transaksi</p>
                    <p class="text-2xl font-bold text-red-800">{{ $transaksis->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-600 text-sm font-medium">Total Nilai Keluar</p>
                    <p class="text-2xl font-bold text-amber-800">
                        Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Rata-rata per Transaksi</p>
                    <p class="text-2xl font-bold text-blue-800">
                        Rp {{ number_format($transaksis->avg('total_harga'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Filter Section -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-filter text-blue-600"></i>
                    </div>
                    Filter & Pencarian
                </h3>
                <button type="button" id="toggle-filter" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-chevron-up" id="filter-icon"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('transaksi.barang-keluar') }}" id="filter-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Tanggal Dari -->
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-gray-500"></i>Tanggal Dari
                        </label>
                        <input type="date" 
                               name="tanggal_dari" 
                               id="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    </div>

                    <!-- Tanggal Sampai -->
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-1 text-gray-500"></i>Tanggal Sampai
                        </label>
                        <input type="date" 
                               name="tanggal_sampai" 
                               id="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    </div>

                    <!-- Filter Barang -->
                    <div>
                        <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-gray-500"></i>Barang
                        </label>
                        <select name="barang_id" 
                                id="barang_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                            <option value="">-- Semua Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 transition-all duration-300 group">
                            <i class="fas fa-search mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                            Filter
                        </button>
                        <a href="{{ route('transaksi.barang-keluar') }}" 
                           class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 group">
                            <i class="fas fa-undo mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                            Reset
                        </a>
                    </div>
                </div>

                <!-- Quick Filter Buttons -->
                <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600 mr-2">Quick Filter:</span>
                    <button type="button" 
                            onclick="setDateFilter('today')"
                            class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                        Hari Ini
                    </button>
                    <button type="button" 
                            onclick="setDateFilter('week')"
                            class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-200">
                        Minggu Ini
                    </button>
                    <button type="button" 
                            onclick="setDateFilter('month')"
                            class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors duration-200">
                        Bulan Ini
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="p-8">
            <!-- Export & Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center space-x-2">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $transaksis->count() }} dari {{ $transaksis->total() }} data
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Tombol Excel DIHILANGKAN -->
                    <button type="button" 
                            onclick="exportData('pdf')"
                            class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                    <button type="button" 
                            onclick="printTable()"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode & Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transaksis as $index => $transaksi)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium">
                                        {{ $transaksis->firstItem() + $index }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-1">
                                            <i class="fas fa-arrow-up mr-1"></i>{{ $transaksi->kode_transaksi }}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-box text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $transaksi->barang->nama_barang }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaksi->barang->kode_barang }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                            <i class="fas fa-minus-circle mr-1 text-xs"></i>
                                            {{ number_format($transaksi->jumlah) }} {{ $transaksi->barang->satuan }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-600">@ Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</span>
                                        <span class="text-lg font-bold text-red-600">
                                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($transaksi->customer)
                                            <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $transaksi->customer }}</span>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Tidak ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-white text-xs font-medium">{{ substr($transaksi->user->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $transaksi->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('transaksi.show', $transaksi) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                           title="Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('transaksi.edit', $transaksi) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('transaksi.destroy', $transaksi) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirmDelete(event, '{{ $transaksi->kode_transaksi }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data transaksi</h3>
                                        <p class="text-gray-500 mb-6">Belum ada transaksi barang keluar yang tercatat</p>
                                        <a href="{{ route('transaksi.create-barang-keluar') }}" 
                                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300">
                                            <i class="fas fa-plus mr-2"></i>Tambah Barang Keluar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transaksis->hasPages())
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $transaksis->firstItem() }} - {{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} hasil
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $transaksis->withQueryString()->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Card -->
    @if($transaksis->count() > 0)
    <div class="mt-8 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-8 border border-slate-200">
        <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center">
            <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-chart-pie text-slate-600"></i>
            </div>
            Ringkasan Data
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $transaksis->total() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Nilai Keluar</p>
                        <p class="text-2xl font-bold text-red-600">
                            Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-red-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Halaman</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $transaksis->currentPage() }} / {{ $transaksis->lastPage() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bookmark text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Print Styles -->
<style>
    @media print {
        .print\\:hidden { display: none !important; }
        .print\\:block { display: block !important; }
        body { background: white !important; }
        .bg-gradient-to-r, .bg-gradient-to-br { background: white !important; }
        .shadow-xl, .shadow-lg, .shadow-md { box-shadow: none !important; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            if (alert.classList.contains('fade')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // Toggle filter section
    const toggleFilter = document.getElementById('toggle-filter');
    const filterForm = document.getElementById('filter-form');
    const filterIcon = document.getElementById('filter-icon');

    toggleFilter?.addEventListener('click', function() {
        if (filterForm.style.display === 'none') {
            filterForm.style.display = 'block';
            filterIcon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        } else {
            filterForm.style.display = 'none';
            filterIcon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        }
    });

    // Date validation
    const tanggalDari = document.getElementById('tanggal_dari');
    const tanggalSampai = document.getElementById('tanggal_sampai');

    tanggalDari?.addEventListener('change', function() {
        tanggalSampai.min = this.value;
    });

    tanggalSampai?.addEventListener('change', function() {
        tanggalDari.max = this.value;
    });
});

// Quick date filter functions
function setDateFilter(period) {
    const today = new Date();
    const tanggalDari = document.getElementById('tanggal_dari');
    const tanggalSampai = document.getElementById('tanggal_sampai');
    
    tanggalSampai.value = today.toISOString().split('T')[0];
    
    if (period === 'today') {
        tanggalDari.value = today.toISOString().split('T')[0];
    } else if (period === 'week') {
        const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
        tanggalDari.value = weekAgo.toISOString().split('T')[0];
    } else if (period === 'month') {
        const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
        tanggalDari.value = monthAgo.toISOString().split('T')[0];
    }
    
    // Auto submit form
    document.getElementById('filter-form').submit();
}

// Export functions - HANYA PDF
function exportData(type) {
    if (type !== 'pdf') {
        showNotification('Hanya export PDF yang tersedia', 'info');
        return;
    }
    
    // Show loading notification
    showNotification('Mempersiapkan export PDF...', 'info', 2000);
    
    // Get current filter parameters
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add all form parameters
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Add export parameter
    params.append('export', 'pdf');
    
    // Create URL with parameters
    const exportUrl = `${window.location.pathname}?${params.toString()}`;
    
    // Show loading overlay
    showLoadingOverlay('Generating PDF file...');
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading after delay
    setTimeout(() => {
        hideLoadingOverlay();
        showNotification('Export PDF berhasil dimulai!', 'success');
    }, 3000);
}

// Enhanced print function
function printTable() {
    showLoadingOverlay('Preparing print layout...');
    
    // Get current filters for print header
    const tanggalDari = document.getElementById('tanggal_dari').value;
    const tanggalSampai = document.getElementById('tanggal_sampai').value;
    const barangSelect = document.getElementById('barang_id');
    const selectedBarang = barangSelect.options[barangSelect.selectedIndex].text;
    
    // Create print window
    const printWindow = window.open('', '_blank');
    const printContent = generatePrintContent(tanggalDari, tanggalSampai, selectedBarang);
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    setTimeout(() => {
        hideLoadingOverlay();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 1000);
}

function generatePrintContent(tanggalDari, tanggalSampai, selectedBarang) {
    const table = document.querySelector('table').cloneNode(true);
    
    // Remove action column for printing
    const actionColumnIndex = table.querySelectorAll('th').length - 1;
    table.querySelectorAll('tr').forEach(row => {
        if (row.cells[actionColumnIndex]) {
            row.deleteCell(actionColumnIndex);
        }
    });
    
    return `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Barang Keluar - COMFEED JAPFA</title>
            <style>
                @page { 
                    size: A4 landscape; 
                    margin: 1cm; 
                }
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    font-size: 12px; 
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    border-bottom: 3px solid #2563EB;
                    padding-bottom: 15px;
                }
                .header h1 { 
                    color: #2563EB; 
                    margin: 0 0 5px 0; 
                    font-size: 24px;
                }
                .header h2 { 
                    color: #64748B; 
                    margin: 0 0 10px 0; 
                    font-size: 18px;
                }
                .info { 
                    color: #6B7280; 
                    font-size: 11px; 
                    margin-top: 10px;
                }
                .filters {
                    background-color: #F8FAFC;
                    padding: 10px;
                    border: 1px solid #E2E8F0;
                    margin-bottom: 20px;
                    border-radius: 6px;
                }
                .filter-info {
                    font-size: 11px;
                    color: #374151;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    font-size: 10px;
                    margin-bottom: 20px;
                }
                th { 
                    background-color: #2563EB; 
                    color: white; 
                    padding: 8px 6px; 
                    text-align: center;
                    font-weight: bold;
                    border: 1px solid #1D4ED8;
                }
                td { 
                    padding: 6px; 
                    border: 1px solid #D1D5DB; 
                    text-align: left;
                }
                tr:nth-child(even) { 
                    background-color: #F9FAFB; 
                }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .currency { 
                    text-align: right; 
                    font-family: 'Courier New', monospace;
                }
                .badge {
                    display: inline-block;
                    padding: 2px 6px;
                    background-color: #FEE2E2;
                    color: #991B1B;
                    border-radius: 4px;
                    font-size: 9px;
                    font-weight: bold;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    text-align: center;
                    font-size: 9px;
                    color: #6B7280;
                    border-top: 1px solid #E5E7EB;
                    padding: 8px;
                    background-color: white;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>COMFEED JAPFA</h1>
                <h2>Laporan Barang Keluar</h2>
                <div class="info">
                    <div>Dicetak pada: ${new Date().toLocaleString('id-ID')}</div>
                    <div>Dicetak oleh: Administrator</div>
                </div>
            </div>
            
            <div class="filters">
                <div class="filter-info">
                    <strong>Filter:</strong> 
                    Periode: ${tanggalDari && tanggalSampai ? 
                        `${tanggalDari} s/d ${tanggalSampai}` : 
                        tanggalDari ? `Dari ${tanggalDari}` : 
                        tanggalSampai ? `Sampai ${tanggalSampai}` : 'Semua Data'
                    } | 
                    Barang: ${selectedBarang.includes('--') ? 'Semua Barang' : selectedBarang}
                </div>
            </div>
            
            ${table.outerHTML}
            
            <div class="footer">
                Laporan Barang Keluar - COMFEED JAPFA | Dokumen dicetak pada ${new Date().toLocaleString('id-ID')}
            </div>
        </body>
        </html>
    `;
}

// Confirm delete function
function confirmDelete(event, kodeTransaksi) {
    event.preventDefault();
    
    const confirmation = confirm(`Apakah Anda yakin ingin menghapus transaksi ${kodeTransaksi}?\n\nStok barang akan dikembalikan secara otomatis.`);
    
    if (confirmation) {
        event.target.submit();
    }
    
    return false;
}

// Enhanced notification system
function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 
                   type === 'error' ? 'bg-red-100 border-red-500 text-red-700' : 
                   type === 'warning' ? 'bg-amber-100 border-amber-500 text-amber-700' : 'bg-blue-100 border-blue-500 text-blue-700';
    
    notification.className = `fixed top-6 right-6 max-w-sm ${bgColor} border-l-4 p-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-3 text-lg"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-70">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, duration);
}

// Enhanced loading overlay functions
function showLoadingOverlay(message = 'Loading...') {
    // Remove existing overlay if any
    hideLoadingOverlay();
    
    const overlay = document.createElement('div');
    overlay.id = 'export-loading-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center';
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl p-8 flex flex-col items-center space-y-4 shadow-2xl max-w-sm mx-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-file-export text-blue-600 text-lg"></i>
                </div>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Processing</h3>
                <p class="text-gray-600 text-sm">${message}</p>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2 w-48">
                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('export-loading-overlay');
    if (overlay) {
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 300);
    }
}

// Live search functionality
let searchTimeout;
function liveSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filter-form').submit();
    }, 800);
}

// Add search input event listener
document.getElementById('barang_id')?.addEventListener('change', liveSearch);

// Enhanced table interactions
document.querySelectorAll('tr[data-href]').forEach(row => {
    row.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('a')) {
            window.location.href = this.dataset.href;
        }
    });
});

// Responsive table enhancements
function handleResponsiveTable() {
    const table = document.querySelector('table');
    
    if (window.innerWidth < 768) {
        // Mobile view adjustments
        table?.classList.add('mobile-table');
    } else {
        table?.classList.remove('mobile-table');
    }
}

window.addEventListener('resize', handleResponsiveTable);
handleResponsiveTable();
</script>

<style>
/* Mobile table styles */
@media (max-width: 768px) {
    .mobile-table {
        font-size: 0.875rem;
    }
    
    .mobile-table th,
    .mobile-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .mobile-table .hidden-mobile {
        display: none;
    }
}

/* Enhanced hover effects */
.table-row-hover:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Loading states */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Smooth transitions */
* {
    transition: all 0.2s ease-in-out;
}

/* Focus styles for accessibility */
button:focus,
input:focus,
select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection