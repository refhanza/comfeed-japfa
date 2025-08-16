@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Edit User: {{ $user->name }}</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('users.show', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Detail User
                        </a>
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- User Info Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi User</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('username') border-red-300 @enderror">
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-yellow-800 mb-4">Ubah Password (Opsional)</h3>
                        <p class="text-sm text-yellow-700 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter (kosongkan jika tidak diubah)</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- User Status Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi User</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Bergabung:</strong> {{ $user->formatted_created_at }}</li>
                                        <li><strong>Status Email:</strong> 
                                            @if($user->email_verified_at)
                                                <span class="text-green-600">Terverifikasi</span>
                                            @else
                                                <span class="text-yellow-600">Belum terverifikasi</span>
                                            @endif
                                        </li>
                                        @if(isset($transaksiCount))
                                            <li><strong>Total Transaksi:</strong> {{ $transaksiCount }}</li>
                                        @endif
                                        @if(auth()->id() === $user->id)
                                            <li class="text-blue-600"><strong>⚠️ Ini adalah akun Anda sendiri</strong></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('users.show', $user) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Update User
                        </button>
                    </div>
                </form>

                <!-- Danger Zone (for non-self users) -->
                @if(auth()->id() !== $user->id)
                    <div class="mt-8 border-t pt-6">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-red-800 mb-4">Zona Berbahaya</h3>
                            <p class="text-sm text-red-700 mb-4">
                                Aksi di bawah ini bersifat permanen dan tidak dapat dibatalkan.
                            </p>
                            
                            <div class="flex space-x-3">
                                @if(!isset($transaksiCount) || $transaksiCount == 0)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                          onsubmit="return confirm('PERINGATAN: Anda akan menghapus user {{ $user->name }}. Aksi ini tidak bisa dibatalkan! Ketik HAPUS untuk melanjutkan.') && prompt('Ketik HAPUS untuk konfirmasi:') === 'HAPUS'">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                                            Hapus User Permanen
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                        Hapus User (Memiliki Transaksi)
                                    </button>
                                    <p class="text-sm text-gray-600 self-center">User memiliki transaksi dan tidak dapat dihapus</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    // Password confirmation validation
    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value !== this.value && (passwordInput.value !== '' || this.value !== '')) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
    
    passwordInput.addEventListener('input', function() {
        if (confirmPasswordInput.value !== '' && this.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Password tidak cocok');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    });
});
</script>
@endsection