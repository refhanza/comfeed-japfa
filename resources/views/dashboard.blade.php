@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .gradient-card {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .glassmorphism {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
        transition: all 0.3s ease;
    }
    
    .count-up {
        animation: countUp 1s ease-out;
    }
    
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                <p class="text-blue-100 flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ now()->format('l, d F Y - H:i') }}
                </p>
                <div class="flex items-center mt-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full pulse-dot mr-2"></div>
                    <span class="text-sm text-blue-100">System Online</span>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="refreshDashboard()" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2" id="refresh-icon"></i>
                    Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- Alert untuk stok menipis -->
    @if($stokMenipis > 0)
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-amber-800 font-medium">
                    Perhatian! Ada {{ $stokMenipis }} barang dengan stok menipis.
                    <a href="#stok-menipis" class="underline hover:text-amber-900">Lihat detail</a>
                </p>
            </div>
            <div class="ml-auto">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-amber-400 hover:text-amber-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Barang -->
        <div class="group">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover-scale border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Total Barang</p>
                        <p class="text-3xl font-bold text-gray-800 count-up" id="total-barang">{{ number_format($totalBarang) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-arrow-up mr-1"></i>Active
                            </span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-boxes text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Masuk -->
        <div class="group">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover-scale border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold uppercase tracking-wide">Masuk Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 count-up" id="barang-masuk">{{ number_format($barangMasukHariIni) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-trending-up mr-1"></i>Items
                            </span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <i class="fas fa-arrow-down text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="group">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover-scale border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Keluar Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 count-up" id="barang-keluar">{{ number_format($barangKeluarHariIni) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-indigo-500 text-sm font-medium">
                                <i class="fas fa-trending-down mr-1"></i>Items
                            </span>
                        </div>
                    </div>
                    <div class="bg-indigo-100 p-4 rounded-2xl">
                        <i class="fas fa-arrow-up text-indigo-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="group">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover-scale border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-600 text-sm font-semibold uppercase tracking-wide">Stok Menipis</p>
                        <p class="text-3xl font-bold text-gray-800 count-up" id="stok-menipis">{{ number_format($stokMenipis) }}</p>
                        <div class="flex items-center mt-2">
                            @if($stokMenipis > 0)
                                <span class="text-red-500 text-sm font-medium">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Needs Attention
                                </span>
                            @else
                                <span class="text-green-500 text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>All Good
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-2xl">
                        <i class="fas fa-exclamation-triangle text-amber-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Transaksi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Grafik Transaksi</h3>
                        <p class="text-gray-600 text-sm">7 hari terakhir</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-green-600 bg-green-100 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i>Masuk
                        </button>
                        <button class="text-indigo-600 bg-indigo-100 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>Keluar
                        </button>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="transactionChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Summary -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('barang.create') }}" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 p-4 rounded-xl flex items-center transition-colors duration-200">
                        <div class="bg-blue-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <span class="font-medium">Tambah Barang</span>
                    </a>
                    <a href="{{ route('transaksi.create-barang-masuk') }}" class="w-full bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-xl flex items-center transition-colors duration-200">
                        <div class="bg-green-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-arrow-down text-green-600"></i>
                        </div>
                        <span class="font-medium">Barang Masuk</span>
                    </a>
                    <a href="{{ route('transaksi.create-barang-keluar') }}" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 p-4 rounded-xl flex items-center transition-colors duration-200">
                        <div class="bg-indigo-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-arrow-up text-indigo-600"></i>
                        </div>
                        <span class="font-medium">Barang Keluar</span>
                    </a>
                    <a href="{{ route('transaksi.laporan') }}" class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 p-4 rounded-xl flex items-center transition-colors duration-200">
                        <div class="bg-purple-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-chart-bar text-purple-600"></i>
                        </div>
                        <span class="font-medium">Laporan</span>
                    </a>
                </div>
            </div>

            <!-- Nilai Inventory -->
            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Nilai Inventory</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($nilaiInventory, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-green-100 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Total nilai seluruh stok barang
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Stock Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="{{ route('transaksi.laporan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-0">
                @if($transaksiTerbaru->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($transaksiTerbaru as $index => $transaksi)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($transaksi->jenis_transaksi === 'masuk')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-arrow-down text-green-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-arrow-up text-indigo-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $transaksi->barang->nama_barang }}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-2">{{ number_format($transaksi->jumlah) }} {{ $transaksi->barang->satuan }}</span>
                                        <span class="mr-2">•</span>
                                        <span>{{ $transaksi->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($transaksi->jenis_transaksi === 'masuk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Keluar
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada transaksi hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden" id="stok-menipis">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Peringatan Stok</h3>
                    <a href="{{ route('barang.index') }}?filter=stok_menipis" class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-0">
                @if($barangStokMenipis->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($barangStokMenipis as $barang)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($barang->stok <= 0)
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-times text-red-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $barang->nama_barang }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $barang->kode_barang }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $barang->stok }} {{ $barang->satuan }}
                                    </p>
                                    @if($barang->stok <= 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Habis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Menipis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Semua barang stoknya aman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from backend
    const chartData = @json($chartData);
    
    // Transaction Chart with modern design
    const ctx = document.getElementById('transactionChart').getContext('2d');
    
    // Create gradient for chart
    const gradientMasuk = ctx.createLinearGradient(0, 0, 0, 400);
    gradientMasuk.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
    gradientMasuk.addColorStop(1, 'rgba(34, 197, 94, 0.05)');
    
    const gradientKeluar = ctx.createLinearGradient(0, 0, 0, 400);
    gradientKeluar.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradientKeluar.addColorStop(1, 'rgba(99, 102, 241, 0.05)');
    
    const transactionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [{
                label: 'Barang Masuk',
                data: chartData.masuk,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: gradientMasuk,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'Barang Keluar',
                data: chartData.keluar,
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: gradientKeluar,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(99, 102, 241)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal: ' + context[0].label;
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.formattedValue + ' item';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        stepSize: 1,
                        callback: function(value) {
                            return value + ' item';
                        }
                    }
                }
            }
        }
    });

    // Auto refresh dashboard
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(function() {
            refreshDashboard();
        }, 300000); // 5 minutes
    }
    
    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
    
    // Start auto refresh
    startAutoRefresh();
    
    // Visibility change handler
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
});

function refreshDashboard() {
    const refreshIcon = document.getElementById('refresh-icon');
    refreshIcon.classList.add('animate-spin');
    
    fetch('{{ route("dashboard.refresh-cards") }}')
        .then(response => response.json())
        .then(data => {
            // Update cards with animation
            updateCardValue('total-barang', data.totalBarang);
            updateCardValue('barang-masuk', data.barangMasukHariIni);
            updateCardValue('barang-keluar', data.barangKeluarHariIni);
            updateCardValue('stok-menipis', data.stokMenipis);
            
            // Show success notification
            showNotification('Dashboard updated successfully!', 'success');
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showNotification('Failed to refresh dashboard', 'error');
        })
        .finally(() => {
            refreshIcon.classList.remove('animate-spin');
        });
}

function updateCardValue(elementId, newValue) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.opacity = '0.5';
        setTimeout(() => {
            element.textContent = newValue.toLocaleString();
            element.style.opacity = '1';
            element.classList.add('count-up');
            setTimeout(() => element.classList.remove('count-up'), 1000);
        }, 200);
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endpush