@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('header', 'Confirm Password')

@section('description', 'Please confirm your password to continue')

@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-red-100 to-pink-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-shield-alt text-2xl text-red-600"></i>
    </div>
    <p class="text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>
</div>

<form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
    @csrf
    
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
                   placeholder="Enter your current password">
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

    <!-- Confirm Button -->
    <div>
        <button type="submit" 
                class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300">
            <i class="fas fa-check mr-2"></i>
            Confirm
        </button>
    </div>
</form>
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

    // Auto-focus on password input
    document.getElementById('password').focus();
});
</script>
@endpush