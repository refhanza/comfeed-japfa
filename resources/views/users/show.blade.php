@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('users.index') }}" class="text-gray-700 hover:text-blue-600">Kelola Users</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-500">{{ $user->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    Detail User
                </h1>
                <p class="text-gray-600 mt-1">Informasi lengkap dan aktivitas user</p>
            </div>
            
            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 glass-morphism border border-gray-300 hover:border-gray-400 text-gray-700 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 glass-morphism border-l-4 border-green-500 text-green-700 p-4 rounded-xl animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 glass-morphism border-l-4 border-red-500 text-red-700 p-4 rounded-xl animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Profile Card -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-8 hover-lift">
                <div class="flex flex-col md:flex-row md:items-center space-y-6 md:space-y-0 md:space-x-8">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl flex items-center justify-center shadow-2xl">
                            <span class="text-white text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        @if($user->email_verified_at)
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        @else
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-clock text-white text-sm"></i>
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                            @if(auth()->id() === $user->id)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                    <i class="fas fa-user mr-1"></i>You
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-4 text-gray-600 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                <span>Bergabung {{ $user->created_at ? $user->created_at->format('M Y') : 'Unknown' }}</span>
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="flex items-center space-x-3">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Email Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Email Unverified
                                </span>
                            @endif
                            
                            @php
                                $transaksiCount = $user->transaksis()->count();
                            @endphp
                            @if($transaksiCount > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    Active User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="glass-morphism rounded-2xl border border-white/20 p-6 hover-lift">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Informasi Personal
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email Address</label>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">User ID</label>
                            <p class="text-gray-900 font-mono text-sm bg-gray-100 px-2 py-1 rounded">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="glass-morphism rounded-2xl border border-white/20 p-6 hover-lift">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                        Informasi Akun
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                            <p class="text-gray-900 font-medium">
                                @if($user->created_at)
                                    {{ $user->created_at->format('d F Y, H:i') }} WIB
                                @else
                                    Tidak diketahui
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email Verified</label>
                            <p class="text-gray-900 font-medium">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $user->email_verified_at->format('d F Y, H:i') }} WIB
                                    </span>
                                @else
                                    <span class="text-amber-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum diverifikasi
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Terakhir Update</label>
                            <p class="text-gray-900 font-medium">
                                @if($user->updated_at)
                                    {{ $user->updated_at->diffForHumans() }}
                                @else
                                    Tidak diketahui
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="glass-morphism rounded-2xl border border-white/20 overflow-hidden hover-lift">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-blue-500"></i>
                            Riwayat Transaksi
                        </h3>
                        @if($transaksiCount > 0)
                            <a href="{{ route('transaksi.index') }}?user={{ $user->id }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                <span>Lihat Semua</span>
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>

                @if($transaksiCount > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php
                                    $latestTransaksis = \App\Models\Transaksi::where('user_id', $user->id)
                                        ->with('barang')
                                        ->latest()
                                        ->limit(5)
                                        ->get();
                                @endphp
                                @foreach($latestTransaksis as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaksi->tanggal_transaksi->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $transaksi->kode_transaksi }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $transaksi->barang->nama_barang ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaksi->jenis_transaksi === 'masuk')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-down mr-1"></i>Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-up mr-1"></i>Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaksi->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaksi->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transaksiCount > 5)
                        <div class="bg-gray-50 px-6 py-3 text-center">
                            <a href="{{ route('transaksi.index') }}?user={{ $user->id }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat {{ $transaksiCount - 5 }} transaksi lainnya →
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi</h3>
                        <p class="text-gray-500">User ini belum melakukan transaksi apapun</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Stats & Actions -->
        <div class="space-y-6">
            <!-- Statistics Cards -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6 hover-lift">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Statistik User
                </h3>
                
                <div class="space-y-6">
                    <!-- Total Transactions -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Transaksi</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $transaksiCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    @if($transaksiCount > 0)
                        @php
                            $transaksiMasuk = \App\Models\Transaksi::where('user_id', $user->id)->where('jenis_transaksi', 'masuk')->count();
                            $transaksiKeluar = \App\Models\Transaksi::where('user_id', $user->id)->where('jenis_transaksi', 'keluar')->count();
                        @endphp
                        
                        <!-- Transactions In -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-600">Barang Masuk</p>
                                    <p class="text-2xl font-bold text-green-700">{{ $transaksiMasuk }}</p>
                                </div>
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Out -->
                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-600">Barang Keluar</p>
                                    <p class="text-2xl font-bold text-red-700">{{ $transaksiKeluar }}</p>
                                </div>
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                            </div>
                        </div>

                        @php
                            $lastTransaksi = \App\Models\Transaksi::where('user_id', $user->id)->latest()->first();
                        @endphp
                        @if($lastTransaksi)
                            <!-- Last Activity -->
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-600">Transaksi Terakhir</p>
                                        <p class="text-sm font-semibold text-purple-700">{{ $lastTransaksi->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6 hover-lift">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-bolt mr-2 text-amber-500"></i>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    <button onclick="toggleResetPassword()" class="w-full flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
                    </button>
                    
                    <a href="{{ route('users.edit', $user) }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>
                    
                    @if(auth()->id() !== $user->id)
                        @if($transaksiCount == 0)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('PERINGATAN: Anda akan menghapus user {{ $user->name }}. Aksi ini tidak bisa dibatalkan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus User
                                </button>
                            </form>
                        @else
                            <button type="button" disabled title="User memiliki {{ $transaksiCount }} transaksi" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gray-300 text-gray-500 rounded-xl font-medium cursor-not-allowed">
                                <i class="fas fa-lock mr-2"></i>
                                Tidak Dapat Dihapus
                            </button>
                        @endif
                    @else
                        <button type="button" disabled 
                                class="w-full flex items-center justify-center px-4 py-3 bg-gray-300 text-gray-500 rounded-xl font-medium cursor-not-allowed">
                            <i class="fas fa-user-shield mr-2"></i>
                            Akun Sendiri
                        </button>
                    @endif
                </div>
            </div>

            <!-- System Information -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6 hover-lift">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                    Info System
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">User ID</span>
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Account Type</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Standard User</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Status</span>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Active</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Login Method</span>
                        <span class="text-gray-900 font-medium">Email & Password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass-morphism rounded-2xl border border-white/20 w-full max-w-md">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-key mr-2 text-amber-500"></i>
                    Reset Password
                </h3>
                <p class="text-gray-600 text-sm mt-1">Reset password untuk: <span class="font-medium">{{ $user->name }}</span></p>
            </div>
            
            <form action="{{ route('users.reset-password', $user) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300">
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="toggleResetPassword()" 
                            class="px-4 py-2 glass-morphism border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-all duration-300">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>