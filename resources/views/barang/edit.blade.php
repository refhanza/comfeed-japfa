@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="space-y-2">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                Edit Barang
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
                <span class="text-gray-900 font-medium">Edit: {{ $barang->nama_barang }}</span>
            </nav>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('barang.show', $barang) }}" 
               class="inline-flex items-center px-4 py-2 glass-morphism rounded-xl text-blue-700 hover:bg-blue-50 transition-all duration-300 hover-lift">
                <i class="fas fa-eye mr-2"></i>
                <span class="font-medium">Detail</span>
            </a>
            
            <a href="{{ route('barang.index') }}" 
               class="inline-flex items-center px-4 py-2 glass-morphism rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-300 hover-lift">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="xl:col-span-2">
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Form Edit Barang</h2>
                            <p class="text-orange-100 text-sm">Perbarui informasi barang</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-6">
                    <form action="{{ route('barang.update', $barang) }}" method="POST" id="form-edit">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Basic Information Section -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                                    </div>
                                    Informasi Dasar
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <!-- Kode Barang (Read-only) -->
                                    <div class="md:col-span-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Kode Barang
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   value="{{ $barang->kode_barang }}" 
                                                   readonly
                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 cursor-not-allowed">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
                                    </div>

                                    <!-- Nama Barang -->
                                    <div class="md:col-span-8">
                                        <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Barang <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="nama_barang" 
                                                   name="nama_barang" 
                                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                                   placeholder="Masukkan nama barang..."
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 @error('nama_barang') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-tag text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('nama_barang')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Kategori -->
                                    <div class="md:col-span-6">
                                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="kategori" 
                                                   name="kategori" 
                                                   value="{{ old('kategori', $barang->kategori) }}"
                                                   placeholder="Pilih atau ketik kategori..."
                                                   list="kategori-list"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 @error('kategori') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                            <datalist id="kategori-list">
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori }}">
                                                @endforeach
                                            </datalist>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-list text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('kategori')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Satuan -->
                                    <div class="md:col-span-6">
                                        <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                            Satuan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="satuan" 
                                                   name="satuan" 
                                                   value="{{ old('satuan', $barang->satuan) }}"
                                                   placeholder="pcs, kg, liter, box..."
                                                   list="satuan-list"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 @error('satuan') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                            <datalist id="satuan-list">
                                                <option value="pcs">
                                                <option value="kg">
                                                <option value="gram">
                                                <option value="liter">
                                                <option value="ml">
                                                <option value="box">
                                                <option value="pack">
                                                <option value="meter">
                                                <option value="cm">
                                            </datalist>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-balance-scale text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('satuan')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Financial & Stock Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                                    </div>
                                    Informasi Harga & Stok
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Harga -->
                                    <div>
                                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                            Harga Satuan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-500 text-sm font-medium">Rp</span>
                                            </div>
                                            <input type="number" 
                                                   id="harga" 
                                                   name="harga" 
                                                   value="{{ old('harga', $barang->harga) }}"
                                                   placeholder="0"
                                                   min="0"
                                                   step="0.01"
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 @error('harga') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                        </div>
                                        @error('harga')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Stok Saat Ini (Read-only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Stok Saat Ini
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   value="{{ number_format($barang->stok) }} {{ $barang->satuan }}" 
                                                   readonly
                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 cursor-not-allowed pr-12">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                @if($barang->stok <= 0)
                                                    <i class="fas fa-times-circle text-red-500"></i>
                                                @elseif($barang->stok <= 10)
                                                    <i class="fas fa-exclamation-triangle text-amber-500"></i>
                                                @else
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Stok diubah melalui transaksi</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Description -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-cog text-purple-600 text-sm"></i>
                                    </div>
                                    Status & Deskripsi
                                </h3>
                                
                                <!-- Status Toggle -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                                <i class="fas fa-toggle-on text-blue-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Status Barang</h4>
                                                <p class="text-sm text-gray-600">Barang aktif dapat digunakan dalam transaksi</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <!-- Hidden input untuk memastikan nilai false dikirim -->
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1"
                                                   class="sr-only peer"
                                                   {{ old('is_active', $barang->is_active) ? 'checked' : '' }}>
                                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-7 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-amber-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi Barang <span class="text-gray-500">(Opsional)</span>
                                    </label>
                                    <textarea id="deskripsi" 
                                              name="deskripsi" 
                                              rows="4"
                                              placeholder="Masukkan deskripsi detail barang, spesifikasi, atau informasi tambahan lainnya..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 resize-none @error('deskripsi') border-red-500 ring-2 ring-red-200 @enderror">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                            <a href="{{ route('barang.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 group">
                                <i class="fas fa-times mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                                Batal
                            </a>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('barang.show', $barang) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-blue-300 text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-300 group">
                                    <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                    Lihat Detail
                                </a>
                                
                                <button type="submit" 
                                        id="submit-btn"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 focus:ring-4 focus:ring-amber-200 transition-all duration-300 shadow-lg hover:shadow-xl group">
                                    <i class="fas fa-save mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                    <span class="loading-text">Update Barang</span>
                                    <div class="loading-spinner hidden ml-2">
                                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informasi Barang Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informasi Barang
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Dibuat</p>
                            <p class="text-sm font-bold text-gray-900">{{ $barang->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Diupdate</p>
                            <p class="text-sm font-bold text-gray-900">{{ $barang->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-purple-50 rounded-xl border border-purple-200">
                            <p class="text-xs text-purple-600 uppercase tracking-wide">Total Transaksi</p>
                            <p class="text-lg font-bold text-purple-800">{{ $barang->transaksi()->count() }}</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-xl border border-green-200">
                            <p class="text-xs text-green-600 uppercase tracking-wide">Nilai Stok</p>
                            <p class="text-lg font-bold text-green-800">{{ $barang->formatted_harga }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terakhir Card -->
            <div class="glass-morphism rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-history mr-3"></i>
                        Transaksi Terakhir
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $lastTransaksi = $barang->transaksi()->with('user')->latest()->limit(3)->get();
                    @endphp
                    
                    @if($lastTransaksi->count() > 0)
                        <div class="space-y-3">
                            @foreach($lastTransaksi as $transaksi)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                        <i class="fas fa-{{ $transaksi->jenis_transaksi === 'masuk' ? 'arrow-down text-green-600' : 'arrow-up text-red-600' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ ucfirst($transaksi->jenis_transaksi) }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaksi->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-xs font-bold">
                                        {{ $transaksi->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('barang.show', $barang) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 text-sm">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Semua Transaksi
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">Belum ada transaksi</p>
                            <p class="text-gray-500 text-sm">Transaksi akan muncul setelah barang digunakan</p>
                        </div>
                    @endif
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-edit');
    const submitBtn = document.getElementById('submit-btn');
    const hargaInput = document.getElementById('harga');

    // Format harga input
    hargaInput.addEventListener('blur', function() {
        if (this.value) {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(0);
            }
        }
    });

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.querySelector('.loading-text').textContent = 'Menyimpan...';
        submitBtn.querySelector('.loading-spinner').classList.remove('hidden');
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.querySelector('.loading-text').textContent = 'Update Barang';
            submitBtn.querySelector('.loading-spinner').classList.add('hidden');
        }, 10000);
    });

    // Enhanced form validation
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        const isValid = field.checkValidity() && value !== '';
        
        if (!isValid) {
            field.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            field.classList.remove('border-green-500', 'ring-green-200');
        } else {
            field.classList.add('border-green-500', 'ring-2', 'ring-green-200');
            field.classList.remove('border-red-500', 'ring-red-200');
        }
    }

    function clearFieldError(field) {
        field.classList.remove('border-red-500', 'ring-red-200', 'border-green-500', 'ring-green-200');
    }

    // Auto-suggest kategori enhancement
    const kategoriInput = document.getElementById('kategori');
    const namaBarangInput = document.getElementById('nama_barang');
    
    // Suggest category based on item name changes
    namaBarangInput.addEventListener('input', function() {
        const namaBarang = this.value.toLowerCase();
        let suggestedCategory = '';
        
        if (namaBarang.includes('laptop') || namaBarang.includes('komputer')) {
            suggestedCategory = 'Elektronik';
        } else if (namaBarang.includes('kertas') || namaBarang.includes('pulpen')) {
            suggestedCategory = 'Alat Tulis';
        } else if (namaBarang.includes('meja') || namaBarang.includes('kursi')) {
            suggestedCategory = 'Furniture';
        }
        
        if (suggestedCategory) {
            kategoriInput.style.backgroundColor = '#fefce8';
            kategoriInput.setAttribute('placeholder', `Saran: ${suggestedCategory}`);
        } else {
            kategoriInput.style.backgroundColor = '';
            kategoriInput.setAttribute('placeholder', 'Pilih atau ketik kategori...');
        }
    });

    // Initialize animations
    const sections = document.querySelectorAll('.space-y-6 > div');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    sections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
    });
});
</script>

<style>
/* Additional styles for enhanced UX */
.transaksi-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced mobile responsiveness */
@media (max-width: 640px) {
    .glass-morphism {
        margin: 0 -1rem;
        border-radius: 1rem 1rem 0 0;
    }
    
    .grid.md\\:grid-cols-12 {
        grid-template-columns: 1fr;
    }
    
    .md\\:col-span-4,
    .md\\:col-span-6,
    .md\\:col-span-8 {
        grid-column: span 1;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
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
    
    button, .hover-lift {
        display: none !important;
    }
    
    input, textarea {
        border: 1px solid #d1d5db !important;
        background: white !important;
    }
}

/* Loading states */
.loading-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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

/* Focus improvements for keyboard navigation */
input:focus,
textarea:focus,
button:focus {
    outline: 2px solid #f59e0b;
    outline-offset: 2px;
}

/* Status toggle custom styling */
input[type="checkbox"]:checked + div {
    background-color: #f59e0b;
}

input[type="checkbox"]:focus + div {
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
}
</style>
@endsection