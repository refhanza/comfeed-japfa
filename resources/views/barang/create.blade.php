@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header Section with Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    Tambah Barang Baru
                </h1>
                <p class="text-gray-600 mt-2">Lengkapi informasi barang untuk menambahkannya ke inventori</p>
            </div>
            <a href="{{ route('barang.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 hover:shadow-lg group">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
                Kembali
            </a>
        </div>
        
        <!-- Breadcrumb -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('barang.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">
                            Kelola Barang
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-700 font-medium">Tambah Barang</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-plus-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Form Tambah Barang</h2>
                            <p class="text-blue-100 text-sm">Isi semua field yang diperlukan</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-8">
                    <form action="{{ route('barang.store') }}" method="POST" id="form-barang">
                        @csrf

                        <div class="space-y-8">
                            <!-- Basic Information Section -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                                    </div>
                                    Informasi Dasar
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama Barang -->
                                    <div class="md:col-span-2">
                                        <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Barang <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="nama_barang" 
                                                   name="nama_barang" 
                                                   value="{{ old('nama_barang') }}"
                                                   placeholder="Masukkan nama barang..."
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('nama_barang') border-red-500 ring-2 ring-red-200 @enderror"
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
                                    <div>
                                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="kategori" 
                                                   name="kategori" 
                                                   value="{{ old('kategori') }}"
                                                   placeholder="Pilih atau ketik kategori..."
                                                   list="kategori-list"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('kategori') border-red-500 ring-2 ring-red-200 @enderror"
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
                                    <div>
                                        <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                            Satuan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="satuan" 
                                                   name="satuan" 
                                                   value="{{ old('satuan') }}"
                                                   placeholder="pcs, kg, liter, box..."
                                                   list="satuan-list"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('satuan') border-red-500 ring-2 ring-red-200 @enderror"
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

                            <!-- Financial Information Section -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                                    </div>
                                    Informasi Harga & Stok
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                                   value="{{ old('harga') }}"
                                                   placeholder="0"
                                                   min="0"
                                                   step="0.01"
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('harga') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                        </div>
                                        @error('harga')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Stok Awal -->
                                    <div>
                                        <label for="stok_awal" class="block text-sm font-medium text-gray-700 mb-2">
                                            Stok Awal <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   id="stok_awal" 
                                                   name="stok_awal" 
                                                   value="{{ old('stok_awal', 0) }}"
                                                   placeholder="0"
                                                   min="0"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('stok_awal') border-red-500 ring-2 ring-red-200 @enderror"
                                                   required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-cubes text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('stok_awal')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt text-purple-600 text-sm"></i>
                                    </div>
                                    Deskripsi Tambahan
                                </h3>
                                
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi Barang <span class="text-gray-500">(Opsional)</span>
                                    </label>
                                    <textarea id="deskripsi" 
                                              name="deskripsi" 
                                              rows="4"
                                              placeholder="Masukkan deskripsi detail barang, spesifikasi, atau informasi tambahan lainnya..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none @error('deskripsi') border-red-500 ring-2 ring-red-200 @enderror">{{ old('deskripsi') }}</textarea>
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
                                <button type="reset" 
                                        class="inline-flex items-center px-6 py-3 border border-amber-300 text-amber-700 bg-amber-50 rounded-xl hover:bg-amber-100 transition-all duration-300 group">
                                    <i class="fas fa-undo mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                                    Reset
                                </button>
                                
                                <button type="submit" 
                                        id="submit-btn"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 transition-all duration-300 shadow-lg hover:shadow-xl group">
                                    <i class="fas fa-save mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                    <span class="loading-text">Simpan Barang</span>
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
        <div class="lg:col-span-1 space-y-6">
            <!-- Auto Generate Code Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-magic text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-indigo-900">Kode Barang</h3>
                </div>
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <p class="text-sm text-gray-600 mb-2">Kode akan dibuat otomatis:</p>
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-lg font-bold text-indigo-600" id="preview-kode">BRG{{ date('Ymd') }}001</span>
                        <i class="fas fa-wand-magic-sparkles text-indigo-400"></i>
                    </div>
                </div>
                <p class="text-xs text-indigo-600 mt-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Format: BRG + Tanggal + Urutan
                </p>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-calculator text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Nilai Total Stok:</span>
                        <span class="font-bold text-lg text-green-600" id="total-nilai">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Status Barang:</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Stok Status:</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium" id="stok-status">
                            <i class="fas fa-cube mr-1"></i>Baru
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-amber-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-amber-900">Tips</h3>
                </div>
                
                <ul class="space-y-3 text-sm text-amber-800">
                    <li class="flex items-start">
                        <i class="fas fa-check text-amber-600 mr-2 mt-1 text-xs"></i>
                        Gunakan nama barang yang jelas dan mudah dikenali
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-amber-600 mr-2 mt-1 text-xs"></i>
                        Pilih kategori yang sesuai untuk memudahkan pencarian
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-amber-600 mr-2 mt-1 text-xs"></i>
                        Pastikan harga dan stok awal sesuai dengan kondisi aktual
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-amber-600 mr-2 mt-1 text-xs"></i>
                        Tambahkan deskripsi untuk informasi detail barang
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaInput = document.getElementById('harga');
    const stokInput = document.getElementById('stok_awal');
    const totalNilaiEl = document.getElementById('total-nilai');
    const stokStatusEl = document.getElementById('stok-status');
    const form = document.getElementById('form-barang');
    const submitBtn = document.getElementById('submit-btn');

    // Update total nilai dan status stok
    function updateCalculations() {
        const harga = parseFloat(hargaInput.value) || 0;
        const stok = parseInt(stokInput.value) || 0;
        const total = harga * stok;
        
        // Update total nilai
        totalNilaiEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        // Update status stok
        let statusHtml = '';
        if (stok === 0) {
            statusHtml = '<i class="fas fa-times-circle mr-1"></i>Kosong';
            stokStatusEl.className = 'px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium';
        } else if (stok <= 10) {
            statusHtml = '<i class="fas fa-exclamation-triangle mr-1"></i>Menipis';
            stokStatusEl.className = 'px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium';
        } else {
            statusHtml = '<i class="fas fa-check-circle mr-1"></i>Aman';
            stokStatusEl.className = 'px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium';
        }
        stokStatusEl.innerHTML = statusHtml;
    }

    // Event listeners
    hargaInput.addEventListener('input', updateCalculations);
    stokInput.addEventListener('input', updateCalculations);

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
            submitBtn.querySelector('.loading-text').textContent = 'Simpan Barang';
            submitBtn.querySelector('.loading-spinner').classList.add('hidden');
        }, 10000);
    });

    // Auto-suggest kategori enhancement
    const kategoriInput = document.getElementById('kategori');
    const namaBarangInput = document.getElementById('nama_barang');
    
    // Suggest category based on item name
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
        
        if (suggestedCategory && !kategoriInput.value) {
            kategoriInput.style.backgroundColor = '#fefce8';
            kategoriInput.setAttribute('placeholder', `Saran: ${suggestedCategory}`);
        } else {
            kategoriInput.style.backgroundColor = '';
            kategoriInput.setAttribute('placeholder', 'Pilih atau ketik kategori...');
        }
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

    // Initialize calculations
    updateCalculations();

    // Add animation to form sections on scroll
    const sections = document.querySelectorAll('[class*="space-y-8"] > div');
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
@endsection