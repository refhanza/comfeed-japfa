<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ config('app.name', 'Comfeed Japfa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounceGentle 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background patterns */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: -1;
        }
        
        /* Glass morphism effects */
        .glass-morphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 15px 25px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        /* Animation keyframes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(2deg); }
            66% { transform: translateY(-20px) rotate(-2deg); }
        }
        
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* =================================================
           INPUT TEXT VISIBILITY FIX - CRITICAL
           ================================================= */
        
        /* Pastikan semua input memiliki text yang terlihat */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="url"],
        input[type="search"],
        input[type="number"],
        textarea,
        select {
            color: #1f2937 !important; /* Dark gray text - WAJIB TERLIHAT */
            background-color: #ffffff !important; /* White background */
            border: 1px solid #d1d5db !important; /* Gray border */
            
            /* Prevent WebKit from hiding text */
            -webkit-text-fill-color: #1f2937 !important;
            -webkit-appearance: none !important;
            
            /* Ensure text is not transparent */
            opacity: 1 !important;
            text-shadow: none !important;
            
            /* Font styling for better visibility */
            font-size: 0.875rem !important; /* 14px */
            font-weight: 400 !important;
            line-height: 1.5 !important;
        }

        /* Focus state - maintain text visibility */
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
        input[type="url"]:focus,
        input[type="search"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            border-color: #3b82f6 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        /* Placeholder text styling */
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af !important; /* Light gray - visible but not too dark */
            opacity: 1 !important;
            -webkit-text-fill-color: #9ca3af !important;
        }

        /* Fix for browser autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #1f2937 !important;
            color: #1f2937 !important;
            background-color: #ffffff !important;
        }

        /* Override for any class conflicts */
        .input-focus {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        .input-focus:focus {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        /* Specific override for glass card inputs */
        .glass-card input[type="text"],
        .glass-card input[type="email"],
        .glass-card input[type="password"] {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        /* Override dark mode if it affects inputs */
        @media (prefers-color-scheme: dark) {
            input[type="text"],
            input[type="email"],
            input[type="password"],
            textarea,
            select {
                color: #1f2937 !important;
                background-color: #ffffff !important;
                -webkit-text-fill-color: #1f2937 !important;
            }
        }

        /* Emergency fallback for Tailwind conflicts */
        input.border-gray-300,
        input.border-red-500,
        input.border-green-500,
        input.border-blue-500 {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        /* =================================================
           END INPUT TEXT VISIBILITY FIX
           ================================================= */

        /* Input focus effects */
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        }
        
        /* Button hover effects */
        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        /* Floating elements */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            animation: float 15s ease-in-out infinite;
        }
        
        .floating-shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }
        
        .floating-shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 50%;
            left: 5%;
            animation-delay: 4s;
        }
        
        .floating-shape:nth-child(4) {
            width: 40px;
            height: 40px;
            top: 20%;
            right: 10%;
            animation-delay: 6s;
        }

        /* Loading state */
        .loading {
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        /* Notification animations */
        .notification-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        .notification-exit {
            animation: slideOutRight 0.3s ease-in;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            body::before {
                animation-duration: 30s;
            }
            
            .glass-card {
                margin: 1rem;
                backdrop-filter: blur(15px);
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- Floating background shapes -->
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="mb-8 animate-bounce-slow">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-16 h-16 glass-morphism rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-boxes text-white text-2xl"></i>
                </div>
                <div class="text-center">
                    <h1 class="text-white text-2xl font-bold tracking-wide">{{ config('app.name', 'Comfeed Japfa') }}</h1>
                    <p class="text-white/80 text-sm">Inventory System</p>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md animate-fade-in">
            <div class="glass-card rounded-2xl shadow-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-blue-600/10 to-purple-600/10 border-b border-white/10">
                    <h2 class="text-2xl font-bold text-gray-900 text-center">
                        @yield('header', 'Authentication')
                    </h2>
                    <p class="text-gray-600 text-center text-sm mt-2">
                        @yield('description', 'Please sign in to your account')
                    </p>
                </div>

                <!-- Card Body -->
                <div class="px-8 py-8">
                    <!-- Flash Messages -->
                    @if(session('status'))
                        <div class="notification-enter mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <p class="text-green-800 text-sm">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="notification-enter mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <p class="text-red-800 text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="notification-enter mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-red-800 text-sm font-medium mb-2">Please fix the following errors:</p>
                                    <ul class="text-red-700 text-sm space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                </div>

                <!-- Card Footer -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    <div class="text-center">
                        @yield('footer')
                        
                        <div class="mt-4 text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Protected by {{ config('app.name') }} Security
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center">
            <div class="flex items-center justify-center space-x-6 text-white/80 text-sm">
                <a href="#" class="hover:text-white transition-colors duration-200">
                    <i class="fas fa-question-circle mr-1"></i>Help
                </a>
                <a href="#" class="hover:text-white transition-colors duration-200">
                    <i class="fas fa-shield-alt mr-1"></i>Privacy
                </a>
                <a href="#" class="hover:text-white transition-colors duration-200">
                    <i class="fas fa-file-alt mr-1"></i>Terms
                </a>
            </div>
            <p class="text-white/60 text-xs mt-4">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
        <div class="glass-morphism rounded-2xl p-8 flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
            <span class="text-white font-medium">Loading...</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification-enter');
            notifications.forEach(notification => {
                notification.classList.add('notification-exit');
                setTimeout(() => notification.remove(), 300);
            });
        }, 5000);

        // Loading overlay functions
        function showLoading() {
            document.getElementById('loading-overlay')?.classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-overlay')?.classList.add('hidden');
        }

        // Form submission loading
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                        showLoading();
                    }
                });
            });

            // Enhanced input interactions
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement?.classList.add('ring-2', 'ring-blue-500');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement?.classList.remove('ring-2', 'ring-blue-500');
                });
            });

            // Force input text visibility on page load
            const textInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
            textInputs.forEach(input => {
                input.style.color = '#1f2937';
                input.style.backgroundColor = '#ffffff';
                input.style.webkitTextFillColor = '#1f2937';
            });
        });

        // Enhanced notification system
        function showNotification(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                           type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
                           type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-blue-50 border-blue-200 text-blue-800';
            
            notification.className = `notification-enter fixed top-6 right-6 max-w-sm ${bgColor} border rounded-xl p-4 shadow-lg z-50`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-70">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('notification-exit');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, duration);
        }

        // Network status monitoring
        window.addEventListener('online', () => {
            showNotification('Connection restored', 'success');
        });

        window.addEventListener('offline', () => {
            showNotification('Connection lost', 'warning');
        });

        // Global error handling
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    </script>

    @stack('scripts')
</body>
</html>