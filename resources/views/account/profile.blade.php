@extends('layouts.app')

@section('title', 'Profile - Laravel Multi-Level User')
@section('page_title', 'Profile')
@section('page_subtitle', 'View and manage your profile information')

@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('account.profile') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    Account
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Profile</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Profile Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow p-6 profile-card">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                @if(auth()->check() && auth()->user() && auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-32 h-32 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400 text-4xl"></i>
                                @endif
                            </div>
                            <button type="button" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 shadow-lg profile-photo-btn">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">
                            {{ auth()->check() && auth()->user() ? (auth()->user()->name ?? 'User Name') : 'User Name' }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">
                            {{ auth()->check() && auth()->user() ? (auth()->user()->email ?? 'user@example.com') : 'user@example.com' }}
                        </p>
                        <div class="flex justify-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle text-green-400 mr-1"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                    <div class="space-y-3">
                        <a href="{{ route('account.edit-profile') }}" 
                            class="flex items-center p-3 text-sm text-gray-700 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors quick-action-link">
                            <i class="fas fa-user-edit text-blue-500 mr-3"></i>
                            Edit Profile
                        </a>
                        <a href="{{ route('account.password.change') }}" 
                            class="flex items-center p-3 text-sm text-gray-700 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors quick-action-link">
                            <i class="fas fa-key text-green-500 mr-3"></i>
                            Change Password
                        </a>
                        <a href="{{ route('account.settings') }}" 
                            class="flex items-center p-3 text-sm text-gray-700 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors quick-action-link">
                            <i class="fas fa-cog text-purple-500 mr-3"></i>
                            Settings
                        </a>
                        <a href="{{ route('account.help') }}" 
                            class="flex items-center p-3 text-sm text-gray-700 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors quick-action-link">
                            <i class="fas fa-headset text-orange-500 mr-3"></i>
                            Help & Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Your basic profile information</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Full Name -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->name ?? 'Not provided') : 'Not provided' }}
                                    </p>
                                </div>
                                
                                <!-- Phone Number -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->phone ?? 'Not provided') : 'Not provided' }}
                                    </p>
                                </div>
                                
                                <!-- Job Title -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->job_title ?? 'Not provided') : 'Not provided' }}
                                    </p>
                                </div>
                                
                                <!-- Bio -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->bio ?? 'No bio provided') : 'No bio provided' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Email Address -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->email ?? 'Not provided') : 'Not provided' }}
                                    </p>
                                </div>
                                
                                <!-- Company -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->company ?? 'Not provided') : 'Not provided' }}
                                    </p>
                                </div>
                                
                                <!-- Member Since -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                                    <p class="text-sm text-gray-900">
                                        {{ auth()->check() && auth()->user() ? (auth()->user()->created_at ? auth()->user()->created_at->format('M d, Y') : 'Unknown') : 'Unknown' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Statistics -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Account Statistics</h3>
                        <p class="mt-1 text-sm text-gray-500">Your account activity and usage</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <!-- Total Sessions -->
                            <div class="text-center stat-card">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-1">0</h4>
                                <p class="text-sm text-gray-500">Total Sessions</p>
                            </div>
                            
                            <!-- Days Active -->
                            <div class="text-center stat-card">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-calendar text-green-600 text-xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-1">0</h4>
                                <p class="text-sm text-gray-500">Days Active</p>
                            </div>
                            
                            <!-- Data Usage -->
                            <div class="text-center stat-card">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-1">0</h4>
                                <p class="text-sm text-gray-500">Data Usage</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow mb-6 lg:mb-0">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                        <p class="mt-1 text-sm text-gray-500">Your recent account activities</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start activity-item p-3 rounded-md">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-sign-in-alt text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Login successful</p>
                                    <p class="text-xs text-gray-500">You logged in from 192.168.1.100</p>
                                    <p class="text-xs text-gray-400 mt-1">Just now</p>
                                </div>
                            </div>
                            <div class="flex items-start activity-item p-3 rounded-md">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-edit text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Profile updated</p>
                                    <p class="text-xs text-gray-500">You updated your profile information</p>
                                    <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start activity-item p-3 rounded-md">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-key text-yellow-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Password changed</p>
                                    <p class="text-xs text-gray-500">You changed your account password</p>
                                    <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-500">
                                View all activity
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Card Hover Effects */
    .bg-white.rounded-lg.shadow {
        transition: all 0.15s ease-in-out;
    }

    .bg-white.rounded-lg.shadow:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Quick Action Links */
    .quick-action-link {
        transition: all 0.15s ease-in-out;
    }

    .quick-action-link:hover {
        transform: translateX(4px);
    }

    /* Statistics Cards */
    .stat-card {
        transition: all 0.15s ease-in-out;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    /* Activity Items */
    .activity-item {
        transition: all 0.15s ease-in-out;
    }

    .activity-item:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }

    /* Button Transitions */
    button {
        transition: all 0.15s ease-in-out;
    }

    button:hover {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
