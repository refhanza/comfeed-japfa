@extends('layouts.guest')

@section('title', 'Register')

@section('header', 'Create Account')

@section('description', 'Join us and start managing your inventory')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf
    
    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-user mr-2 text-gray-500"></i>Full Name
        </label>
        <div class="relative">
            <input id="name" 
                   name="name" 
                   type="text" 
                   autocomplete="name" 
                   required 
                   autofocus
                   value="{{ old('name') }}"
                   class="input-focus w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('name') border-red-500 @enderror"
                   placeholder="Enter your full name">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
        </label>
        <div class="relative">
            <input id="email" 
                   name="email" 
                   type="email" 
                   autocomplete="email" 
                   required
                   value="{{ old('email') }}"
                   class="input-focus w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('email') border-red-500 @enderror"
                   placeholder="Enter your email address">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <i id="email-check" class="fas fa-check-circle text-green-500 hidden"></i>
                <i id="email-error" class="fas fa-times-circle text-red-500 hidden"></i>
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
            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
        </label>
        <div class="relative">
            <input id="password" 
                   name="password" 
                   type="password" 
                   autocomplete="new-password" 
                   required
                   class="input-focus w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('password') border-red-500 @enderror"
                   placeholder="Create a strong password">
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
            <i class="fas fa-lock mr-2 text-gray-500"></i>Confirm Password
        </label>
        <div class="relative">
            <input id="password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   autocomplete="new-password" 
                   required
                   class="input-focus w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                   placeholder="Confirm your password">
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

    <!-- Terms and Conditions -->
    <div>
        <label for="terms" class="flex items-start">
            <input id="terms" 
                   name="terms" 
                   type="checkbox" 
                   required
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200 mt-1">
            <span class="ml-3 text-sm text-gray-600">
                I agree to the 
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Terms of Service</a> 
                and 
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Privacy Policy</a>
            </span>
        </label>
    </div>

    <!-- Register Button -->
    <div>
        <button type="submit" 
                class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300"
                id="register-btn">
            <i class="fas fa-user-plus mr-2"></i>
            <span id="register-text">Create Account</span>
            <div id="register-spinner" class="hidden ml-2">
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </div>
        </button>
    </div>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">Or sign up with</span>
        </div>
    </div>

    <!-- Social Register -->
    <div class="grid grid-cols-1 gap-3">
        <button type="button" 
                onclick="showNotification('Social registration coming soon!', 'info')"
                class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
            <i class="fab fa-google text-red-500 mr-2"></i>
            Sign up with Google
        </button>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center">
    <p class="text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" 
           class="font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-1">
            <i class="fas fa-sign-in-alt mr-1"></i>Sign in here
        </a>
    </p>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Enhanced form submission
    const registerForm = document.querySelector('form');
    const registerBtn = document.getElementById('register-btn');
    const registerText = document.getElementById('register-text');
    const registerSpinner = document.getElementById('register-spinner');

    registerForm.addEventListener('submit', function(e) {
        // Validate form before submission
        if (!validateForm()) {
            e.preventDefault();
            return;
        }

        // Show loading state
        registerBtn.disabled = true;
        registerText.textContent = 'Creating account...';
        registerSpinner.classList.remove('hidden');
        
        // Add loading class to button
        registerBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Show loading overlay
        showLoading();
    });

    // Email validation
    const emailInput = document.getElementById('email');
    const emailCheck = document.getElementById('email-check');
    const emailError = document.getElementById('email-error');

    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        if (email && isValidEmail(email)) {
            emailCheck.classList.remove('hidden');
            emailError.classList.add('hidden');
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        } else if (email) {
            emailCheck.classList.add('hidden');
            emailError.classList.remove('hidden');
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            emailCheck.classList.add('hidden');
            emailError.classList.add('hidden');
            this.classList.remove('border-red-500', 'border-green-500');
        }
    });

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        updatePasswordStrength(strength);
    });

    // Password confirmation checker
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const matchIndicator = document.getElementById('password-match-indicator');
    const matchText = document.getElementById('password-match-text');

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

    passwordInput.addEventListener('input', checkPasswordMatch);
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

    function validateForm() {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['name', 'email', 'password', 'password_confirmation'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                showInputError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Check email format
        const email = emailInput.value.trim();
        if (email && !isValidEmail(email)) {
            showInputError(emailInput, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Check password match
        if (passwordInput.value !== confirmPasswordInput.value) {
            showInputError(confirmPasswordInput, 'Passwords do not match');
            isValid = false;
        }
        
        // Check terms acceptance
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            showNotification('Please accept the Terms of Service and Privacy Policy', 'error');
            isValid = false;
        }
        
        return isValid;
    }

    function showInputError(input, message) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
        
        let errorElement = input.parentNode.parentNode.querySelector('.text-red-600');
        if (!errorElement || errorElement.textContent.includes('The')) {
            errorElement = document.createElement('p');
            errorElement.className = 'mt-2 text-sm text-red-600';
            input.parentNode.parentNode.appendChild(errorElement);
        }
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Real-time name validation
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const name = this.value.trim();
        if (name.length >= 2) {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        } else if (name.length > 0) {
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            this.classList.remove('border-red-500', 'border-green-500');
        }
    });

    // Auto-fill demo data for development
    if (window.location.hostname === 'localhost') {
        const demoBtn = document.createElement('button');
        demoBtn.type = 'button';
        demoBtn.className = 'mt-4 w-full text-xs text-gray-500 hover:text-gray-700 transition-colors duration-200';
        demoBtn.innerHTML = '<i class="fas fa-fill-drip mr-1"></i>Fill Demo Data';
        demoBtn.onclick = function() {
            document.getElementById('name').value = 'John Doe';
            document.getElementById('email').value = 'john@example.com';
            document.getElementById('password').value = 'password123';
            document.getElementById('password_confirmation').value = 'password123';
            document.getElementById('terms').checked = true;
            
            // Trigger events
            nameInput.dispatchEvent(new Event('input'));
            emailInput.dispatchEvent(new Event('input'));
            passwordInput.dispatchEvent(new Event('input'));
            confirmPasswordInput.dispatchEvent(new Event('input'));
            
            showNotification('Demo data filled!', 'info', 2000);
        };
        
        registerForm.appendChild(demoBtn);
    }
});
</script>
@endpush