@extends('layouts.guest')

@section('title', 'Reset Password')

@section('header', 'Create New Password')

@section('description', 'Enter your new password below')

@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-green-100 to-blue-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-lock-open text-2xl text-green-600"></i>
    </div>
    <p class="text-sm text-gray-600">
        Please enter your new password. Make sure it's strong and secure.
    </p>
</div>

<form method="POST" action="{{ route('password.store') }}" class="space-y-6">
    @csrf
    
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
        </label>
        <div class="relative">
            <input id="email" 
                   name="email" 
                   type="email" 
                   autocomplete="username" 
                   required 
                   readonly
                   value="{{ old('email', $request->email) }}"
                   class="w-full px-4 py-3 pl-12 border border-gray-300 bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('email') border-red-500 @enderror"
                   placeholder="Your email address">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-lock mr-2 text-gray-500"></i>New Password
        </label>
        <div class="relative">
            <input id="password" 
                   name="password" 
                   type="password" 
                   autocomplete="new-password" 
                   required
                   class="input-focus w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('password') border-red-500 @enderror"
                   placeholder="Enter your new password">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <button type="button" 
                    onclick="togglePassword('password')"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i id="password-toggle-icon" class="fas fa-eye"></i>
            </button>
        </div>
        
        <!-- Password Strength Indicator -->
        <div class="mt-2">
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-gray-600">Password strength:</span>
                <div id="password-strength" class="flex space-x-1">
                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                </div>
                <span id="password-strength-text" class="text-gray-500">Weak</span>
            </div>
        </div>
        
        @error('password')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-lock mr-2 text-gray-500"></i>Confirm New Password
        </label>
        <div class="relative">
            <input id="password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   autocomplete="new-password" 
                   required
                   class="input-focus w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                   placeholder="Confirm your new password">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <button type="button" 
                    onclick="togglePassword('password_confirmation')"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i id="password_confirmation-toggle-icon" class="fas fa-eye"></i>
            </button>
        </div>
        <div id="password-match-indicator" class="mt-2 text-xs hidden">
            <span id="password-match-text"></span>
        </div>
    </div>

    <!-- Password Requirements -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Password Requirements:</p>
                <ul class="text-xs space-y-1">
                    <li id="req-length" class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2 w-3"></i>
                        At least 8 characters long
                    </li>
                    <li id="req-lowercase" class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2 w-3"></i>
                        Contains lowercase letter
                    </li>
                    <li id="req-uppercase" class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2 w-3"></i>
                        Contains uppercase letter
                    </li>
                    <li id="req-number" class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2 w-3"></i>
                        Contains number
                    </li>
                    <li id="req-special" class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2 w-3"></i>
                        Contains special character
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Reset Password Button -->
    <div>
        <button type="submit" 
                class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300"
                id="reset-btn">
            <i class="fas fa-shield-alt mr-2"></i>
            <span id="reset-text">Reset Password</span>
            <div id="reset-spinner" class="hidden ml-2">
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </div>
        </button>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center">
    <p class="text-sm text-gray-600">
        Remember your password?
        <a href="{{ route('login') }}" 
           class="font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-1">
            <i class="fas fa-arrow-left mr-1"></i>Back to login
        </a>
    </p>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');
    const matchIndicator = document.getElementById('password-match-indicator');
    const matchText = document.getElementById('password-match-text');
    const resetForm = document.querySelector('form');
    const resetBtn = document.getElementById('reset-btn');
    const resetText = document.getElementById('reset-text');
    const resetSpinner = document.getElementById('reset-spinner');

    // Password toggle functionality
    window.togglePassword = function(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(inputId + '-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    };

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        updatePasswordStrength(strength);
        updatePasswordRequirements(password);
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
        const texts = ['Very Weak', 'Weak', 'Fair', 'Strong'];
        
        indicators.forEach((indicator, index) => {
            indicator.className = 'w-2 h-2 rounded-full';
            if (index < strength) {
                indicator.classList.add(colors[Math.min(strength - 1, 3)]);
            } else {
                indicator.classList.add('bg-gray-300');
            }
        });
        
        strengthText.textContent = strength > 0 ? texts[Math.min(strength - 1, 3)] : 'Weak';
        strengthText.className = strength > 2 ? 'text-green-600' : strength > 1 ? 'text-yellow-600' : 'text-red-600';
    }

    function updatePasswordRequirements(password) {
        const requirements = [
            { id: 'req-length', test: password.length >= 8 },
            { id: 'req-lowercase', test: /[a-z]/.test(password) },
            { id: 'req-uppercase', test: /[A-Z]/.test(password) },
            { id: 'req-number', test: /[0-9]/.test(password) },
            { id: 'req-special', test: /[^A-Za-z0-9]/.test(password) }
        ];

        requirements.forEach(req => {
            const element = document.getElementById(req.id);
            const icon = element.querySelector('i');
            
            if (req.test) {
                icon.className = 'fas fa-check text-green-500 mr-2 w-3';
                element.classList.add('text-green-700');
                element.classList.remove('text-blue-800');
            } else {
                icon.className = 'fas fa-times text-red-500 mr-2 w-3';
                element.classList.remove('text-green-700');
                element.classList.add('text-blue-800');
            }
        });
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword) {
            matchIndicator.classList.remove('hidden');
            
            if (password === confirmPassword) {
                matchText.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i>Passwords match';
                matchText.className = 'text-green-600';
                confirmPasswordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.add('border-green-500');
            } else {
                matchText.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i>Passwords do not match';
                matchText.className = 'text-red-600';
                confirmPasswordInput.classList.add('border-red-500');
                confirmPasswordInput.classList.remove('border-green-500');
            }
        } else {
            matchIndicator.classList.add('hidden');
            confirmPasswordInput.classList.remove('border-red-500', 'border-green-500');
        }
    }

    // Form submission
    resetForm.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Validate password requirements
        if (password.length < 8) {
            e.preventDefault();
            showNotification('Password harus minimal 8 karakter', 'error');
            passwordInput.focus();
            return;
        }

        if (password !== confirmPassword) {
            e.preventDefault();
            showNotification('Konfirmasi password tidak cocok', 'error');
            confirmPasswordInput.focus();
            return;
        }

        // Show loading state
        resetBtn.disabled = true;
        resetText.textContent = 'Resetting...';
        resetSpinner.classList.remove('hidden');
        resetBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        showLoading();
    });

    // Auto-focus on password input
    passwordInput.focus();

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            e.preventDefault();
            resetForm.submit();
        }
    });

    // Password visibility sync
    passwordInput.addEventListener('input', function() {
        if (this.type === 'text') {
            confirmPasswordInput.type = 'text';
        }
    });

    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.type === 'text') {
            this.type = 'text';
        }
    });
});
</script>
@endpush