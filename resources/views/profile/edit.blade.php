@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
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
                        <span class="text-gray-500">Edit Profile</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
            Edit Profile
        </h1>
        <p class="text-gray-600 mt-1">Kelola informasi akun dan preferensi Anda</p>
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
        <!-- Main Profile Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="glass-morphism rounded-2xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Informasi Profile
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Update informasi dasar akun Anda</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                    @csrf
                    @method('patch')

                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1"></i>Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if(!$user->email_verified_at)
                                <div class="mt-2 flex items-center text-amber-600">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="text-sm">Email belum diverifikasi</span>
                                </div>
                            @endif
                        </div>

                        <!-- Current Password (for password change) -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-1"></i>Password Saat Ini
                                <span class="text-gray-500 text-xs">(isi jika ingin mengubah password)</span>
                            </label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-1"></i>Password Baru
                                <span class="text-gray-500 text-xs">(kosongkan jika tidak ingin mengubah)</span>
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-1"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-3 glass-morphism border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Section -->
            <div class="glass-morphism rounded-2xl border border-red-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                    <h2 class="text-xl font-bold text-red-900 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                        Zona Berbahaya
                    </h2>
                    <p class="text-red-700 text-sm mt-1">Tindakan ini tidak dapat dibatalkan</p>
                </div>

                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-trash text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Akun</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen. 
                                Sebelum menghapus akun, silakan download data yang ingin Anda simpan.
                            </p>
                            <button onclick="toggleDeleteModal()" 
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all duration-300">
                                <i class="fas fa-trash mr-2"></i>Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Profile Summary -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    
                    <!-- Role Badge -->
                    @php
                        $roleColors = [
                            'admin' => 'bg-red-100 text-red-800',
                            'manager' => 'bg-purple-100 text-purple-800',
                            'staff' => 'bg-blue-100 text-blue-800',
                            'user' => 'bg-gray-100 text-gray-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }} mt-2">
                        <i class="fas fa-user-tag mr-1"></i>{{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Statistik Akun
                </h3>
                
                <div class="space-y-4">
                    <!-- Total Transactions -->
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exchange-alt text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Total Transaksi</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $stats['total_transactions'] }}</span>
                    </div>

                    <!-- This Month -->
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-month text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Bulan Ini</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $stats['transactions_this_month'] }}</span>
                    </div>

                    <!-- Member Since -->
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-plus text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Bergabung</span>
                        </div>
                        <span class="text-sm font-bold text-purple-600">{{ $stats['member_since']->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shield-check mr-2 text-green-500"></i>
                    Status Akun
                </h3>
                
                <div class="space-y-3">
                    <!-- Email Verification -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Email Verification</span>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @endif
                    </div>

                    <!-- Account Status -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Account Status</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-circle mr-1 text-xs"></i>Active
                        </span>
                    </div>

                    <!-- Last Login -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Activity</span>
                        <span class="text-xs text-gray-500">{{ $stats['last_login']->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-morphism rounded-2xl border border-white/20 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-2 text-amber-500"></i>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    @if(!$user->email_verified_at)
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-all duration-300">
                                <i class="fas fa-envelope mr-2"></i>Kirim Verifikasi Email
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-all duration-300">
                        <i class="fas fa-tachometer-alt mr-2"></i>Ke Dashboard
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass-morphism rounded-2xl border border-white/20 w-full max-w-md">
            <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200 rounded-t-2xl">
                <h3 class="text-lg font-bold text-red-900 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                    Konfirmasi Hapus Akun
                </h3>
                <p class="text-red-700 text-sm mt-1">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            
            <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')
                
                <div class="mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-sm text-red-700">
                                    <strong>Peringatan:</strong> Semua data Anda akan dihapus secara permanen, termasuk:
                                </p>
                                <ul class="list-disc list-inside text-sm text-red-600 mt-2 space-y-1">
                                    <li>Informasi profil dan akun</li>
                                    <li>Riwayat aktivitas dan transaksi</li>
                                    <li>Data preferensi dan pengaturan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Masukkan password Anda untuk konfirmasi:
                        </label>
                        <input type="password" name="password" id="delete_password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300">
                    </div>
                    
                    <div>
                        <label for="delete_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Ketik "DELETE" untuk konfirmasi:
                        </label>
                        <input type="text" name="confirmation" id="delete_confirmation" required
                               placeholder="DELETE"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="toggleDeleteModal()" 
                            class="px-4 py-2 glass-morphism border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all duration-300">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Akun Selamanya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Delete Modal Functions
function toggleDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.toggle('hidden');
    
    // Clear form when closing
    if (modal.classList.contains('hidden')) {
        document.getElementById('delete_password').value = '';
        document.getElementById('delete_confirmation').value = '';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        toggleDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('deleteModal');
        if (!modal.classList.contains('hidden')) {
            toggleDeleteModal();
        }
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');
    
    if (password.length === 0) {
        if (strengthIndicator) strengthIndicator.remove();
        return;
    }
    
    let strength = 0;
    let strengthText = '';
    let strengthColor = '';
    
    // Check password criteria
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // Determine strength level
    if (strength <= 2) {
        strengthText = 'Lemah';
        strengthColor = 'text-red-600';
    } else if (strength <= 3) {
        strengthText = 'Sedang';
        strengthColor = 'text-amber-600';
    } else {
        strengthText = 'Kuat';
        strengthColor = 'text-green-600';
    }
    
    // Create or update strength indicator
    let indicator = document.getElementById('password-strength');
    if (!indicator) {
        indicator = document.createElement('p');
        indicator.id = 'password-strength';
        indicator.className = 'mt-2 text-sm';
        this.parentNode.appendChild(indicator);
    }
    
    indicator.className = `mt-2 text-sm ${strengthColor}`;
    indicator.innerHTML = `<i class="fas fa-shield-alt mr-1"></i>Kekuatan password: ${strengthText}`;
});

// Form submission loading state
document.querySelector('form[action="{{ route("profile.update") }}"]').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    // Re-enable after 5 seconds (fallback)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Enhanced form validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    const currentPasswordField = document.getElementById('current_password');
    
    // Real-time password confirmation validation
    function validatePasswordMatch() {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        if (confirmPassword && password !== confirmPassword) {
            confirmPasswordField.setCustomValidity('Password tidak cocok');
            confirmPasswordField.classList.add('border-red-500');
        } else {
            confirmPasswordField.setCustomValidity('');
            confirmPasswordField.classList.remove('border-red-500');
        }
    }
    
    passwordField.addEventListener('input', validatePasswordMatch);
    confirmPasswordField.addEventListener('input', validatePasswordMatch);
    
    // Require current password if new password is entered
    passwordField.addEventListener('input', function() {
        if (this.value) {
            currentPasswordField.required = true;
            currentPasswordField.parentNode.querySelector('label').innerHTML = 
                '<i class="fas fa-lock mr-1"></i>Password Saat Ini <span class="text-red-500">*</span>';
        } else {
            currentPasswordField.required = false;
            currentPasswordField.parentNode.querySelector('label').innerHTML = 
                '<i class="fas fa-lock mr-1"></i>Password Saat Ini <span class="text-gray-500 text-xs">(isi jika ingin mengubah password)</span>';
        }
    });
});

// Auto-hide success/error messages
setTimeout(() => {
    const alerts = document.querySelectorAll('.animate-fade-in');
    alerts.forEach(alert => {
        if (alert.classList.contains('border-l-4')) {
            alert.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        }
    });
}, 5000);

// Email verification resend cooldown
let emailCooldown = false;
document.addEventListener('DOMContentLoaded', function() {
    const verifyBtn = document.querySelector('button[type="submit"]:has(.fa-envelope)');
    if (verifyBtn) {
        verifyBtn.addEventListener('click', function() {
            if (emailCooldown) return;
            
            emailCooldown = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            this.disabled = true;
            
            // Re-enable after 60 seconds
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
                emailCooldown = false;
            }, 60000);
        });
    }
});
</script>

<style>
@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(100%); }
}

/* Enhanced input focus states */
input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input.border-red-500:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Custom file input styling */
input[type="file"] {
    position: relative;
}

input[type="file"]::file-selector-button {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    margin-right: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

/* Progress bar animation */
.progress-bar {
    background: linear-gradient(90deg, #f3f4f6, #e5e7eb, #f3f4f6);
    background-size: 200% 100%;
    animation: loading 2s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endpush