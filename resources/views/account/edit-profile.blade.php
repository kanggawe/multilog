@extends('layouts.app')

@section('title', 'Edit Profile - Laravel Multi-Level User')
@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your personal information and preferences')

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
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Edit Profile</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
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

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There were some errors with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Picture Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                @if(auth()->check() && auth()->user() && auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-32 h-32 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400 text-4xl"></i>
                                @endif
                            </div>
                            <button type="button" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 shadow-lg">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">
                            {{ auth()->check() && auth()->user() ? (auth()->user()->name ?? 'User Name') : 'User Name' }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ auth()->check() && auth()->user() ? (auth()->user()->email ?? 'user@example.com') : 'user@example.com' }}
                        </p>
                        <button type="button" class="text-sm text-blue-600 hover:text-blue-500">
                            Change Photo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <!-- Form Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Personal Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Update your account profile information and email address.
                        </p>
                    </div>

                    <!-- Form Content -->
                    <form method="POST" action="{{ route('account.profile.update') }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Full Name
                            </label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" 
                                    class="appearance-none relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ old('name', auth()->check() && auth()->user() ? (auth()->user()->name ?? '') : '') }}"
                                    placeholder="Enter your full name">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email Address
                            </label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" 
                                    class="appearance-none relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ old('email', auth()->check() && auth()->user() ? (auth()->user()->email ?? '') : '') }}"
                                    placeholder="Enter your email address">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Phone Number
                            </label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" name="phone" id="phone" 
                                    class="appearance-none relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ old('phone', auth()->check() && auth()->user() ? (auth()->user()->phone ?? '') : '') }}"
                                    placeholder="Enter your phone number">
                            </div>
                        </div>

                        <!-- Company -->
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700">
                                Company
                            </label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <input type="text" name="company" id="company" 
                                    class="appearance-none relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ old('company', auth()->check() && auth()->user() ? (auth()->user()->company ?? '') : '') }}"
                                    placeholder="Enter your company name">
                            </div>
                        </div>

                        <!-- Job Title -->
                        <div>
                            <label for="job_title" class="block text-sm font-medium text-gray-700">
                                Job Title
                            </label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-briefcase text-gray-400"></i>
                                </div>
                                <input type="text" name="job_title" id="job_title" 
                                    class="appearance-none relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ old('job_title', auth()->check() && auth()->user() ? (auth()->user()->job_title ?? '') : '') }}"
                                    placeholder="Enter your job title">
                            </div>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">
                                Bio
                            </label>
                            <div class="mt-1">
                                <textarea name="bio" id="bio" rows="4" 
                                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Tell us about yourself">{{ old('bio', auth()->check() && auth()->user() ? (auth()->user()->bio ?? '') : '') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Brief description for your profile.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('account.profile') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Form Input Focus */
    input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Textarea Styling */
    textarea {
        resize: vertical;
        min-height: 6rem;
        transition: all 0.15s ease-in-out;
    }

    /* Button Transitions */
    button, a.inline-flex {
        transition: all 0.15s ease-in-out;
    }

    button:hover, a.inline-flex:hover {
        transform: translateY(-1px);
    }

    button:active, a.inline-flex:active {
        transform: translateY(0);
    }

    /* Profile Photo Button */
    button.absolute {
        transition: all 0.15s ease-in-out;
    }

    button.absolute:hover {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
