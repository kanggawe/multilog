<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel Multi-Level User')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:100..900&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
    
    <style>
        /* Base Layout */
        .app-layout {
            min-height: 100vh;
            background-color: rgb(249 250 251);
        }
        
        .dark .app-layout {
            background-color: rgb(17 24 39);
        }
        
        /* Fixed Sidebar */
        .fixed-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; /* w-64 */
            z-index: 40;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .dark .fixed-sidebar {
            background-color: rgb(31 41 55);
        }
        
        /* Show sidebar on desktop */
        @media (min-width: 1024px) {
            .fixed-sidebar {
                transform: translateX(0);
            }
        }
        
        .fixed-sidebar.open {
            transform: translateX(0);
        }
        
        /* Fixed Navbar */
        .fixed-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4rem; /* h-16 */
            z-index: 30;
            background-color: white;
            border-bottom: 1px solid rgb(229 231 235);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        @media (min-width: 1024px) {
            .fixed-navbar {
                left: 16rem; /* Start after sidebar on desktop */
            }
        }
        
        .dark .fixed-navbar {
            background-color: rgb(31 41 55);
            border-bottom-color: rgb(75 85 99);
        }
        
        /* Main content with proper spacing */
        .main-content {
            padding-top: 4rem; /* Top padding for fixed navbar */
            min-height: 100vh;
        }
        
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 16rem; /* Left margin for fixed sidebar */
            }
        }
        
        /* Content area */
        .content-area {
            padding: 1.5rem;
            min-height: calc(100vh - 4rem);
        }
        
        @media (min-width: 640px) {
            .content-area {
                padding: 2rem;
            }
        }
        
        @media (min-width: 1024px) {
            .content-area {
                padding: 2rem 3rem;
            }
        }
        
        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 35;
            display: none;
        }
        
        .mobile-overlay.active {
            display: block;
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Ensure dropdowns are on top */
        .dropdown-menu {
            z-index: 9999;
        }
        
        /* Page header spacing */
        .page-header {
            background-color: white;
            border-bottom: 1px solid rgb(229 231 235);
            padding: 1rem 0;
        }
        
        .dark .page-header {
            background-color: rgb(31 41 55);
            border-bottom-color: rgb(75 85 99);
        }
        
        /* Breadcrumb spacing */
        .breadcrumb-nav {
            background-color: rgb(249 250 251);
            border-bottom: 1px solid rgb(229 231 235);
            padding: 0.75rem 0;
        }
        
        .dark .breadcrumb-nav {
            background-color: rgb(17 24 39);
            border-bottom-color: rgb(75 85 99);
        }
        
        /* Content container */
        .content-container {
            /* max-width: 80rem; max-w-6xl */
            margin: 0 auto;
            width: 100%;
        }
        
        /* Cards and sections */
        .content-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .dark .content-section {
            background-color: rgb(31 41 55);
        }
        
        /* Hero section */
        .hero-section {
            background: linear-gradient(135deg, rgb(37 99 235) 0%, rgb(29 78 216) 100%);
            border-radius: 0.75rem;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
            color: white;
        }
        
        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Features grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        /* Button Utilities - Ensure proper contrast */
        .btn-primary {
            background-color: rgb(37 99 235);
            color: white;
            border: 1px solid rgb(37 99 235);
        }
        
        .btn-primary:hover {
            background-color: rgb(29 78 216);
            border-color: rgb(29 78 216);
        }
        
        .btn-secondary {
            background-color: white;
            color: rgb(55 65 81);
            border: 1px solid rgb(209 213 219);
        }
        
        .btn-secondary:hover {
            background-color: rgb(249 250 251);
            color: rgb(17 24 39);
        }
        
        .dark .btn-secondary {
            background-color: rgb(31 41 55);
            color: rgb(209 213 219);
            border-color: rgb(75 85 99);
        }
        
        .dark .btn-secondary:hover {
            background-color: rgb(55 65 81);
            color: rgb(243 244 246);
        }
        
        .btn-success {
            background-color: rgb(34 197 94);
            color: white;
            border: 1px solid rgb(34 197 94);
        }
        
        .btn-success:hover {
            background-color: rgb(22 163 74);
            border-color: rgb(22 163 74);
        }
        
        .btn-warning {
            background-color: rgb(245 158 11);
            color: white;
            border: 1px solid rgb(245 158 11);
        }
        
        .btn-warning:hover {
            background-color: rgb(217 119 6);
            border-color: rgb(217 119 6);
        }
        
        .btn-danger {
            background-color: rgb(239 68 68);
            color: white;
            border: 1px solid rgb(239 68 68);
        }
        
        .btn-danger:hover {
            background-color: rgb(220 38 38);
            border-color: rgb(220 38 38);
        }
        
        /* Ensure all buttons have proper contrast */
        button, .btn, a.btn {
            transition: all 0.2s ease-in-out;
        }
        
        /* Fix any white text on white background issues */
        .bg-white button,
        .bg-white .btn {
            color: rgb(55 65 81) !important;
        }
        
        .bg-white button:hover,
        .bg-white .btn:hover {
            color: rgb(17 24 39) !important;
        }
        
        /* Ensure dark mode buttons are readable */
        .dark .bg-gray-800 button,
        .dark .bg-gray-800 .btn {
            color: rgb(209 213 219) !important;
        }
        
        .dark .bg-gray-800 button:hover,
        .dark .bg-gray-800 .btn:hover {
            color: rgb(243 244 246) !important;
        }
    </style>
    
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">Multi-Level User</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @auth
                            <a href="{{ route('dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <a href="{{ route('admin.users.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Users
                                </a>
                            @endif
                            <a href="{{ route('account.profile') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-user mr-1"></i> Account
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('account.profile') }}" class="text-gray-700 hover:text-gray-900">
                                <i class="fas fa-user-circle mr-1"></i>
                                {{ auth()->user()->name }} ({{ auth()->user()->role->name ?? 'No Role' }})
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('register') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Register
                            </a>
                            <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Login
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @hasSection('breadcrumb')
                <div class="mb-6">
                    @yield('breadcrumb')
                </div>
            @endif

            @hasSection('page_title')
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">@yield('page_title')</h1>
                    @hasSection('page_subtitle')
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">@yield('page_subtitle')</p>
                    @endif
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    
    @stack('scripts')
</body>
</html>
