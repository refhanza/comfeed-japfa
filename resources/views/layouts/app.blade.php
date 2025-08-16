<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Comfeed Japfa') }}</title>

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
                        'slide-in': 'slideIn 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
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
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        /* Glass morphism effects */
        .glass-morphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .glass-sidebar {
            background: linear-gradient(135deg, 
                rgba(30, 58, 138, 0.95) 0%, 
                rgba(55, 48, 163, 0.95) 50%, 
                rgba(88, 28, 135, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Hover effects */
        .hover-lift:hover {
            transform: translateY(-4px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #60a5fa;
        }
        
        /* Notification styles */
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
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
        
        /* Print styles */
        @media print {
            .print\\:hidden { display: none !important; }
            .print\\:block { display: block !important; }
            body { background: white !important; }
            .glass-morphism, .glass-sidebar { background: white !important; backdrop-filter: none !important; }
            .shadow-xl, .shadow-lg, .shadow-md { box-shadow: none !important; }
        }
        
        /* Loading states */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        /* Hide any overflow text at bottom */
        .content-wrapper {
            position: relative;
            overflow: hidden;
        }
        
        /* Prevent CSS text from showing */
        body::after {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div id="app" class="min-h-screen flex content-wrapper">
        <!-- Sidebar -->
        <aside class="w-72 glass-sidebar shadow-2xl print:hidden sidebar-mobile" id="sidebar">
            <!-- Logo Section -->
            <div class="p-8 border-b border-white/10">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 glass-morphism rounded-xl flex items-center justify-center animate-float">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-white text-xl font-bold">{{ config('app.name', 'Comfeed Japfa') }}</h1>
                        <p class="text-blue-200 text-sm">Inventory System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="nav-item group flex items-center space-x-4 text-white rounded-xl p-4 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                        <i class="fas fa-tachometer-alt text-lg"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                    <div class="ml-auto w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>

                <a href="{{ route('barang.index') }}" class="nav-item group flex items-center space-x-4 text-blue-200 hover:text-white rounded-xl p-4 {{ request()->routeIs('barang.*') ? 'active text-white' : '' }}">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-boxes text-lg"></i>
                    </div>
                    <span class="font-medium">Kelola Barang</span>
                    <div class="ml-auto w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>

                <!-- Transaksi Dropdown -->
                <div class="space-y-1">
                    <div class="flex items-center space-x-4 text-blue-200 p-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-lg"></i>
                        </div>
                        <span class="font-medium">Transaksi</span>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="transaksi-chevron"></i>
                        </div>
                    </div>
                    <div class="ml-14 space-y-1" id="transaksi-submenu">
                        <a href="{{ route('transaksi.barang-masuk') }}" class="nav-item flex items-center space-x-3 text-blue-200 hover:text-white rounded-lg p-3 text-sm {{ request()->routeIs('transaksi.barang-masuk*') ? 'bg-white/20 text-white' : '' }}">
                            <i class="fas fa-arrow-down text-green-400"></i>
                            <span>Barang Masuk</span>
                        </a>
                        <a href="{{ route('transaksi.barang-keluar') }}" class="nav-item flex items-center space-x-3 text-blue-200 hover:text-white rounded-lg p-3 text-sm {{ request()->routeIs('transaksi.barang-keluar*') ? 'bg-white/20 text-white' : '' }}">
                            <i class="fas fa-arrow-up text-red-400"></i>
                            <span>Barang Keluar</span>
                        </a>
                        <a href="{{ route('transaksi.laporan') }}" class="nav-item flex items-center space-x-3 text-blue-200 hover:text-white rounded-lg p-3 text-sm {{ request()->routeIs('transaksi.laporan') ? 'bg-white/20 text-white' : '' }}">
                            <i class="fas fa-chart-line text-purple-400"></i>
                            <span>Laporan</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('users.index') }}" class="nav-item group flex items-center space-x-4 text-blue-200 hover:text-white rounded-xl p-4 {{ request()->routeIs('users.*') ? 'active text-white' : '' }}">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <span class="font-medium">Kelola User</span>
                    <div class="ml-auto w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-white/10">
                <div class="relative">
                    <button id="user-profile-btn" class="w-full flex items-center space-x-3 hover:bg-white/10 rounded-xl p-3 transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-blue-200 text-sm">{{ Auth::user()->email }}</p>
                        </div>
                        <i class="fas fa-chevron-up text-blue-200 transition-transform duration-300" id="profile-chevron"></i>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div id="profile-dropdown" class="absolute bottom-full left-0 right-0 mb-2 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20 hidden">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-blue-200 hover:text-white hover:bg-white/10 rounded-t-xl transition-all duration-200">
                            <i class="fas fa-user mr-3"></i>Edit Profile
                        </a>
                        <div class="border-t border-white/10"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-blue-200 hover:text-white hover:bg-white/10 rounded-b-xl transition-all duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden md:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-hidden">
            <!-- Top Header -->
            <header class="glass-morphism border-b border-gray-200/50 sticky top-0 z-30 print:hidden">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg glass-morphism hover:bg-white/30 transition-all duration-300">
                            <i class="fas fa-bars text-gray-700 text-xl"></i>
                        </button>

                        <!-- Page Title -->
                        <div class="hidden md:block">
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent" id="page-title">
                                @yield('title', 'Dashboard')
                            </h1>
                            <p class="text-gray-600 text-sm mt-1" id="page-subtitle">
                                {{ now()->format('l, d F Y - H:i') }}
                            </p>
                        </div>

                        <!-- Header Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button id="notifications-btn" class="relative p-3 glass-morphism rounded-xl text-gray-700 hover:bg-white/30 transition-all duration-300">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                        3
                                    </span>
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div id="notifications-dropdown" class="absolute right-0 mt-2 w-80 glass-morphism rounded-xl border border-white/20 hidden z-50">
                                    <div class="p-4 border-b border-white/10">
                                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <div class="p-4 hover:bg-white/10 transition-colors duration-200">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-amber-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-800">Stok Menipis</p>
                                                    <p class="text-xs text-gray-600 mt-1">3 barang memiliki stok dibawah 10 unit</p>
                                                    <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 border-t border-white/10">
                                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat semua notifikasi</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="relative">
                                <button id="quick-actions-btn" class="p-3 glass-morphism rounded-xl text-gray-700 hover:bg-white/30 transition-all duration-300">
                                    <i class="fas fa-plus text-lg"></i>
                                </button>
                                
                                <!-- Quick Actions Dropdown -->
                                <div id="quick-actions-dropdown" class="absolute right-0 mt-2 w-64 glass-morphism rounded-xl border border-white/20 hidden z-50">
                                    <div class="p-2">
                                        <a href="{{ route('barang.create') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors duration-200">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-plus text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800 font-medium">Tambah Barang</span>
                                        </a>
                                        <a href="{{ route('transaksi.create-barang-masuk') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors duration-200">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-arrow-down text-green-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800 font-medium">Barang Masuk</span>
                                        </a>
                                        <a href="{{ route('transaksi.create-barang-keluar') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors duration-200">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-arrow-up text-red-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800 font-medium">Barang Keluar</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme Toggle -->
                            <button id="theme-toggle" class="p-3 glass-morphism rounded-xl text-gray-700 hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-moon text-lg" id="theme-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8 max-h-screen overflow-y-auto" id="main-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="notification-enter glass-morphism border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-lg"></i>
                            <div class="flex-1">
                                <p class="font-medium">Success!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800 ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="notification-enter glass-morphism border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                            <div class="flex-1">
                                <p class="font-medium">Error!</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="notification-enter glass-morphism border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl animate-fade-in">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-3 text-lg mt-1"></i>
                            <div class="flex-1">
                                <p class="font-medium">Validation Errors:</p>
                                <ul class="text-sm mt-2 list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
        <div class="glass-morphism rounded-2xl p-8 flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-gray-800 font-medium">Loading...</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileMenuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.add('hidden');
        });

        // User profile dropdown
        const userProfileBtn = document.getElementById('user-profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const profileChevron = document.getElementById('profile-chevron');

        userProfileBtn?.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
            profileChevron.classList.toggle('rotate-180');
        });

        // Notifications dropdown
        const notificationsBtn = document.getElementById('notifications-btn');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        notificationsBtn?.addEventListener('click', () => {
            notificationsDropdown.classList.toggle('hidden');
            document.getElementById('quick-actions-dropdown')?.classList.add('hidden');
        });

        // Quick actions dropdown
        const quickActionsBtn = document.getElementById('quick-actions-btn');
        const quickActionsDropdown = document.getElementById('quick-actions-dropdown');

        quickActionsBtn?.addEventListener('click', () => {
            quickActionsDropdown.classList.toggle('hidden');
            document.getElementById('notifications-dropdown')?.classList.add('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (event) => {
            const dropdowns = ['notifications-dropdown', 'quick-actions-dropdown', 'profile-dropdown'];
            const buttons = ['notifications-btn', 'quick-actions-btn', 'user-profile-btn'];
            
            dropdowns.forEach((dropdownId, index) => {
                const dropdown = document.getElementById(dropdownId);
                const button = document.getElementById(buttons[index]);
                
                if (dropdown && !dropdown.contains(event.target) && !button?.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        themeToggle?.addEventListener('click', () => {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme);
            
            themeIcon.className = newTheme === 'light' ? 'fas fa-moon text-lg' : 'fas fa-sun text-lg';
            console.log('Theme switched to:', newTheme);
        });

        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('.notification-enter');
            alerts.forEach(alert => {
                alert.classList.add('notification-exit');
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Loading overlay functions
        function showLoading() {
            document.getElementById('loading-overlay')?.classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-overlay')?.classList.add('hidden');
        }

        // Enhanced notification system
        function showNotification(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'border-green-500 text-green-700' : 
                           type === 'error' ? 'border-red-500 text-red-700' : 
                           type === 'warning' ? 'border-amber-500 text-amber-700' : 'border-blue-500 text-blue-700';
            
            notification.className = `notification-enter glass-morphism border-l-4 ${bgColor} p-4 mb-4 rounded-xl fixed top-6 right-6 z-50 max-w-sm`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-3 text-lg"></i>
                    <div class="flex-1">
                        <p class="text-sm">${message}</p>
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

        // Page navigation enhancement
        document.addEventListener('DOMContentLoaded', () => {
            // Update page title based on current route
            const currentPath = window.location.pathname;
            const pageTitle = document.getElementById('page-title');
            
            const routeTitles = {
                '/dashboard': 'Dashboard',
                '/barang': 'Kelola Barang',
                '/users': 'Kelola User',
                '/transaksi': 'Transaksi'
            };
            
            Object.keys(routeTitles).forEach(route => {
                if (currentPath.includes(route)) {
                    if (pageTitle) pageTitle.textContent = routeTitles[route];
                }
            });
            
            // Add smooth scrolling to all anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Global error handling
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            showNotification('Terjadi kesalahan sistem. Silakan refresh halaman.', 'error');
        });

        // Network status monitoring
        window.addEventListener('online', () => {
            showNotification('Koneksi internet tersambung kembali', 'success');
        });

        window.addEventListener('offline', () => {
            showNotification('Koneksi internet terputus', 'warning');
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>