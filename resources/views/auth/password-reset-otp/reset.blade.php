@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-purple-50 to-pink-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Password Baru</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan password baru untuk<br>
                <span class="font-semibold text-blue-600">{{ $email }}</span>
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-xl">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.reset') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" 
                               autocomplete="new-password" required
                               class="w-full px-3 py-3 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password baru"
                               minlength="8">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <button type="button" onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="password-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="text-gray-600">Kekuatan password:</span>
                            <div id="password-strength" class="flex space-x-1">
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            </div>
                            <span id="password-strength-text" class="text-gray-500">Lemah</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" 
                               autocomplete="new-password" required
                               class="w-full px-3 py-3 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan ulang password baru"
                               minlength="8">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="password_confirmation-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Match Indicator -->
                    <div id="password-match" class="mt-2 text-xs hidden">
                        <span id="password-match-text"></span>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Requirements -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Syarat Password:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Minimal 8 karakter</li>
                        <li>Kombinasi huruf besar dan kecil</li>
                        <li>Mengandung angka</li>
                        <li>Mengandung karakter khusus (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');
    const matchIndicator = document.getElementById('password-match');
    const matchText = document.getElementById('password-match-text');

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        updatePasswordStrength(strength);
        checkPasswordMatch();
    });

    // Password confirmation checker
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 4);
    }

    function updatePasswordStrength(strength) {
        const indicators = strengthIndicator.querySelectorAll('div');
        const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
        const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
        
        indicators.forEach((indicator, index) => {
            indicator.className = 'w-2 h-2 rounded-full';
            if (index < strength) {
                indicator.classList.add(colors[Math.min(strength - 1, 3)]);
            } else {
                indicator.classList.add('bg-gray-300');
            }
        });
        
        strengthText.textContent = strength > 0 ? texts[Math.min(strength - 1, 3)] : 'Lemah';
        strengthText.className = strength > 2 ? 'text-green-600' : strength > 1 ? 'text-yellow-600' : 'text-red-600';
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword) {
            matchIndicator.classList.remove('hidden');
            
            if (password === confirmPassword) {
                matchText.innerHTML = '<span class="text-green-600">✓ Password cocok</span>';
            } else {
                matchText.innerHTML = '<span class="text-red-600">✗ Password tidak cocok</span>';
            }
        } else {
            matchIndicator.classList.add('hidden');
        }
    }

    // Toggle password visibility
    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(inputId + '-eye');
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            `;
        } else {
            input.type = 'password';
            eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    };
});
</script>
@endsection