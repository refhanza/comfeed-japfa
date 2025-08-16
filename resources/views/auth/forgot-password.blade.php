@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('header', 'Reset Password')

@section('description', 'Enter your email to receive a password reset link')

@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-key text-2xl text-blue-600"></i>
    </div>
    <p class="text-sm text-gray-600">
        No worries! Enter your email address below and we'll send you a link to reset your password.
    </p>
</div>

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
        
        <!-- Email validation feedback -->
        <div id="email-feedback" class="mt-2 text-xs hidden">
            <span id="email-feedback-text"></span>
        </div>
    </div>

    <!-- Send Reset Link Button -->
    <div>
        <button type="submit" 
                class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300"
                id="reset-btn">
            <i class="fas fa-paper-plane mr-2"></i>
            <span id="reset-text">Send Reset Link</span>
            <div id="reset-spinner" class="hidden ml-2">
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </div>
        </button>
    </div>

    <!-- Help Text -->
    <div class="text-center">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">What happens next?</p>
                    <ul class="text-left space-y-1 text-xs">
                        <li>• We'll send a secure reset link to your email</li>
                        <li>• Click the link to create a new password</li>
                        <li>• The link expires in 60 minutes for security</li>
                        <li>• Check your spam folder if you don't see it</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Alternative Options -->
    <div class="space-y-4">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500">Need help?</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3">
            <a href="mailto:support@comfeed.com" 
               class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-envelope mr-2 text-gray-500"></i>
                Contact Support
            </a>
            
            <button type="button" 
                    onclick="showSecurityTips()"
                    class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-shield-alt mr-2 text-gray-500"></i>
                Security Tips
            </button>
        </div>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center space-y-4">
    <p class="text-sm text-gray-600">
        Remember your password?
        <a href="{{ route('login') }}" 
           class="font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-1">
            <i class="fas fa-arrow-left mr-1"></i>Back to login
        </a>
    </p>
    
    <p class="text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" 
           class="font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-1">
            <i class="fas fa-user-plus mr-1"></i>Sign up here
        </a>
    </p>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');
    const emailFeedbackText = document.getElementById('email-feedback-text');
    const resetForm = document.querySelector('form');
    const resetBtn = document.getElementById('reset-btn');
    const resetText = document.getElementById('reset-text');
    const resetSpinner = document.getElementById('reset-spinner');

    // Email validation
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        if (email && isValidEmail(email)) {
            emailFeedback.classList.remove('hidden');
            emailFeedbackText.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i>Valid email format';
            emailFeedbackText.className = 'text-green-600';
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        } else if (email) {
            emailFeedback.classList.remove('hidden');
            emailFeedbackText.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i>Please enter a valid email';
            emailFeedbackText.className = 'text-red-600';
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            emailFeedback.classList.add('hidden');
            this.classList.remove('border-red-500', 'border-green-500');
        }
    });

    // Form submission
    resetForm.addEventListener('submit', function(e) {
        const email = emailInput.value.trim();
        
        if (!email || !isValidEmail(email)) {
            e.preventDefault();
            showNotification('Please enter a valid email address', 'error');
            emailInput.focus();
            return;
        }

        // Show loading state
        resetBtn.disabled = true;
        resetText.textContent = 'Sending...';
        resetSpinner.classList.remove('hidden');
        resetBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        showLoading();
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Security tips modal
    window.showSecurityTips = function() {
        const tips = `
            <div class="text-left space-y-3">
                <h3 class="font-semibold text-gray-900 mb-3">🔒 Password Security Tips</h3>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Create a strong password:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>Use at least 8 characters</li>
                        <li>Mix uppercase and lowercase letters</li>
                        <li>Include numbers and special characters</li>
                        <li>Avoid personal information</li>
                    </ul>
                    
                    <p class="pt-2"><strong>Keep it secure:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>Don't share your password</li>
                        <li>Use unique passwords for each account</li>
                        <li>Enable two-factor authentication</li>
                        <li>Log out from shared computers</li>
                    </ul>
                    
                    <p class="pt-2"><strong>If you suspect compromise:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>Change your password immediately</li>
                        <li>Check recent login activity</li>
                        <li>Contact support if needed</li>
                    </ul>
                </div>
            </div>
        `;
        
        showModal('Security Tips', tips);
    };

    function showModal(title, content) {
        // Create modal backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
        backdrop.onclick = function(e) {
            if (e.target === backdrop) {
                document.body.removeChild(backdrop);
            }
        };

        // Create modal content
        const modal = document.createElement('div');
        modal.className = 'glass-card rounded-2xl shadow-2xl max-w-md w-full animate-fade-in';
        modal.innerHTML = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">${title}</h2>
                    <button onclick="this.closest('.fixed').remove()" 
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="text-gray-700">
                    ${content}
                </div>
                <div class="mt-6 text-center">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                        Got it
                    </button>
                </div>
            </div>
        `;

        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);
    }

    // Auto-focus on email input
    emailInput.focus();

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open modals
            const modals = document.querySelectorAll('.fixed.inset-0');
            modals.forEach(modal => modal.remove());
        }
    });

    // Rate limiting feedback
    let submissionCount = 0;
    const maxSubmissions = 3;
    const resetTime = 5 * 60 * 1000; // 5 minutes

    resetForm.addEventListener('submit', function() {
        submissionCount++;
        
        if (submissionCount >= maxSubmissions) {
            setTimeout(() => {
                submissionCount = 0;
                resetBtn.disabled = false;
                resetBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                showNotification('You can now send another reset request', 'info');
            }, resetTime);
        }
    });

    // Auto-fill demo email for development
    if (window.location.hostname === 'localhost') {
        const demoBtn = document.createElement('button');
        demoBtn.type = 'button';
        demoBtn.className = 'mt-4 w-full text-xs text-gray-500 hover:text-gray-700 transition-colors duration-200';
        demoBtn.innerHTML = '<i class="fas fa-fill-drip mr-1"></i>Fill Demo Email';
        demoBtn.onclick = function() {
            emailInput.value = 'admin@comfeed.com';
            emailInput.dispatchEvent(new Event('input'));
            showNotification('Demo email filled!', 'info', 2000);
        };
        
        resetForm.appendChild(demoBtn);
    }
});
</script>
@endpush