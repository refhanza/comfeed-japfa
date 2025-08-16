@extends('layouts.guest')

@section('title', 'Login')

@section('header', 'Welcome Back')

@section('description', 'Sign in to your account to continue')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    
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
                   autofocus
                   value="{{ old('email') }}"
                   class="input-focus w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('email') border-red-500 @enderror"
                   placeholder="Enter your email address">
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
            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
        </label>
        <div class="relative">
            <input id="password" 
                   name="password" 
                   type="password" 
                   autocomplete="current-password" 
                   required
                   class="input-focus w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('password') border-red-500 @enderror"
                   placeholder="Enter your password">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <button type="button" 
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i id="password-toggle-icon" class="fas fa-eye"></i>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <label for="remember_me" class="flex items-center">
            <input id="remember_me" 
                   name="remember" 
                   type="checkbox" 
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200">
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>

        <div class="flex items-center space-x-2">
            @if (Route::has('password.request'))
                <a class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-200 font-medium" 
                   href="{{ route('password.request') }}"
                   title="Reset via Email Link">
                    <i class="fas fa-envelope mr-1"></i>Email Reset
                </a>
            @endif
            
            @if (Route::has('password.otp.request'))
                <span class="text-gray-300">|</span>
                <a class="text-xs text-purple-600 hover:text-purple-800 transition-colors duration-200 font-medium" 
                   href="{{ route('password.otp.request') }}"
                   title="Reset via OTP Code">
                    <i class="fas fa-mobile-alt mr-1"></i>OTP Reset
                </a>
            @endif
        </div>
    </div>

    <!-- Login Button -->
    <div>
        <button type="submit" 
                class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300"
                id="login-btn">
            <i class="fas fa-sign-in-alt mr-2"></i>
            <span id="login-text">Sign In</span>
            <div id="login-spinner" class="hidden ml-2">
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
            <span class="px-4 bg-white text-gray-500">Or continue with</span>
        </div>
    </div>

    <!-- Social Login (Optional - can be implemented later) -->
    <div class="grid grid-cols-1 gap-3">
        <button type="button" 
                onclick="showNotification('Social login coming soon!', 'info')"
                class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
            <i class="fab fa-google text-red-500 mr-2"></i>
            Continue with Google
        </button>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center space-y-4">
    <!-- Password Reset Options -->
    <div class="space-y-3">
        <p class="text-sm text-gray-600">Lupa password? Pilih metode reset:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {{-- Traditional Email Reset --}}
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" 
               class="group inline-flex items-center justify-center px-4 py-2 border border-blue-200 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 transition-all duration-200">
                <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <div class="text-left">
                    <div class="font-semibold">Email Link</div>
                    <div class="text-xs text-blue-600">Traditional reset</div>
                </div>
            </a>
            @endif
            
            {{-- OTP Reset --}}
            @if (Route::has('password.otp.request'))
            <a href="{{ route('password.otp.request') }}" 
               class="group inline-flex items-center justify-center px-4 py-2 border border-purple-200 rounded-lg text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 hover:border-purple-300 transition-all duration-200">
                <i class="fas fa-mobile-alt mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <div class="text-left">
                    <div class="font-semibold">OTP Code</div>
                    <div class="text-xs text-purple-600">Fast & secure</div>
                </div>
            </a>
            @endif
        </div>
    </div>
    
    <!-- Register Link -->
    <div class="pt-4 border-t border-gray-200">
        <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" 
               class="font-medium text-green-600 hover:text-green-800 transition-colors duration-200 ml-1">
                <i class="fas fa-user-plus mr-1"></i>Sign up here
            </a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    window.togglePassword = function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('password-toggle-icon');
        
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
    const loginForm = document.querySelector('form');
    const loginBtn = document.getElementById('login-btn');
    const loginText = document.getElementById('login-text');
    const loginSpinner = document.getElementById('login-spinner');

    loginForm.addEventListener('submit', function(e) {
        // Show loading state
        loginBtn.disabled = true;
        loginText.textContent = 'Signing in...';
        loginSpinner.classList.remove('hidden');
        
        // Add loading class to button
        loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Show loading overlay
        showLoading();
    });

    // Input validation feedback
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateInput(this);
        });
        
        input.addEventListener('input', function() {
            clearValidationError(this);
        });
    });

    function validateInput(input) {
        const value = input.value.trim();
        const errorElement = input.parentNode.parentNode.querySelector('.text-red-600');
        
        if (!value) {
            showInputError(input, 'This field is required');
        } else if (input.type === 'email' && !isValidEmail(value)) {
            showInputError(input, 'Please enter a valid email address');
        }
    }

    function showInputError(input, message) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
        
        let errorElement = input.parentNode.parentNode.querySelector('.text-red-600');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.className = 'mt-2 text-sm text-red-600';
            input.parentNode.parentNode.appendChild(errorElement);
        }
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
    }

    function clearValidationError(input) {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
        
        const errorElement = input.parentNode.parentNode.querySelector('.text-red-600');
        if (errorElement && !errorElement.textContent.includes('The')) {
            errorElement.remove();
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Auto-fill demo credentials (for development only)
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        const demoBtn = document.createElement('button');
        demoBtn.type = 'button';
        demoBtn.className = 'mt-4 w-full text-xs text-gray-500 hover:text-gray-700 transition-colors duration-200';
        demoBtn.innerHTML = '<i class="fas fa-fill-drip mr-1"></i>Fill Demo Credentials';
        demoBtn.onclick = function() {
            document.getElementById('email').value = 'admin@comfeed.com';
            document.getElementById('password').value = 'password';
            showNotification('Demo credentials filled!', 'info', 2000);
        };
        
        loginForm.appendChild(demoBtn);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + L to focus on login form
        if (e.altKey && e.key === 'l') {
            e.preventDefault();
            document.getElementById('email').focus();
        }
        
        // Enter to submit form when focused on any input
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            e.preventDefault();
            loginForm.submit();
        }
    });

    // Add ripple effect to buttons
    function addRippleEffect(element) {
        element.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            element.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    }

    // Add ripple effects to buttons
    document.querySelectorAll('button').forEach(addRippleEffect);

    // Input animations
    inputs.forEach(input => {
        const parent = input.parentElement;
        
        input.addEventListener('focus', function() {
            parent.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            parent.style.transform = 'scale(1)';
        });
    });
});

// Add CSS for ripple effect
const rippleCSS = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        pointer-events: none;
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    button {
        position: relative;
        overflow: hidden;
    }
`;

const style = document.createElement('style');
style.textContent = rippleCSS;
document.head.appendChild(style);
</script>
@endpush