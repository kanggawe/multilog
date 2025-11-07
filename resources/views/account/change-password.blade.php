@extends('layouts.app')

@section('title', 'Change Password - Laravel Multi-Level User')
@section('page_title', 'Change Password')
@section('page_subtitle', 'Update your account password')

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
                <a href="{{ route('account.profile') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    Profile
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Change Password</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
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

        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-key text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Change Password
                        </h3>
                        <p class="text-sm text-gray-500">
                            Update your account password to keep it secure
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('account.password.change.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                        Current Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="current_password" id="current_password" required 
                            class="appearance-none relative block w-full pl-10 pr-10 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Enter your current password">
                        <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="current-password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required 
                            class="appearance-none relative block w-full pl-10 pr-10 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Enter your new password">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div id="password-strength" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="password-strength-text" class="text-xs text-gray-500">Weak</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character
                        </p>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm New Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                            class="appearance-none relative block w-full pl-10 pr-10 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Confirm your new password">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-confirmation-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <div id="password-match" class="mt-1 text-xs hidden">
                        <span class="text-green-600">✓ Passwords match</span>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Password Requirements</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center" id="req-length">
                            <i class="fas fa-circle text-gray-300 mr-2"></i>
                            <span>At least 8 characters long</span>
                        </div>
                        <div class="flex items-center" id="req-uppercase">
                            <i class="fas fa-circle text-gray-300 mr-2"></i>
                            <span>Contains uppercase letter (A-Z)</span>
                        </div>
                        <div class="flex items-center" id="req-lowercase">
                            <i class="fas fa-circle text-gray-300 mr-2"></i>
                            <span>Contains lowercase letter (a-z)</span>
                        </div>
                        <div class="flex items-center" id="req-number">
                            <i class="fas fa-circle text-gray-300 mr-2"></i>
                            <span>Contains number (0-9)</span>
                        </div>
                        <div class="flex items-center" id="req-special">
                            <i class="fas fa-circle text-gray-300 mr-2"></i>
                            <span>Contains special character (!@#$%^&*)</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('account.profile') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-key mr-2"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Password Strength Indicator */
    #password-strength {
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    /* Password Requirement Icons */
    #req-length i,
    #req-uppercase i,
    #req-lowercase i,
    #req-number i,
    #req-special i {
        transition: all 0.2s ease;
    }

    /* Form Input Focus States */
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Button Transitions */
    button {
        transition: all 0.15s ease-in-out;
    }

    button:hover {
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }

    /* Password Toggle Button */
    .password-toggle {
        transition: color 0.15s ease-in-out;
    }

    .password-toggle:hover {
        color: #4b5563;
    }

    /* Card Hover Effect */
    .bg-white.rounded-lg.shadow {
        transition: all 0.15s ease-in-out;
    }

    .bg-white.rounded-lg.shadow:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleId = fieldId === 'current_password' ? 'current-password-toggle' : 
                    fieldId === 'password' ? 'password-toggle' : 'password-confirmation-toggle';
    const passwordToggle = document.getElementById(toggleId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');
    
    // Check requirements
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };
    
    // Update requirement indicators
    Object.keys(requirements).forEach(req => {
        const element = document.getElementById(`req-${req}`);
        const icon = element.querySelector('i');
        if (requirements[req]) {
            icon.className = 'fas fa-check text-green-500 mr-2';
        } else {
            icon.className = 'fas fa-circle text-gray-300 mr-2';
        }
    });
    
    // Calculate strength
    const metRequirements = Object.values(requirements).filter(Boolean).length;
    let strength = 0;
    let strengthLabel = 'Weak';
    
    if (metRequirements >= 5) {
        strength = 100;
        strengthLabel = 'Strong';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
    } else if (metRequirements >= 3) {
        strength = 60;
        strengthLabel = 'Medium';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
    } else if (metRequirements >= 1) {
        strength = 30;
        strengthLabel = 'Weak';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
    } else {
        strength = 0;
        strengthLabel = 'Very Weak';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-gray-300';
    }
    
    strengthBar.style.width = strength + '%';
    strengthText.textContent = strengthLabel;
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = e.target.value;
    const matchIndicator = document.getElementById('password-match');
    
    if (confirmation.length > 0) {
        if (password === confirmation) {
            matchIndicator.innerHTML = '<span class="text-green-600">✓ Passwords match</span>';
            matchIndicator.classList.remove('hidden');
        } else {
            matchIndicator.innerHTML = '<span class="text-red-600">✗ Passwords do not match</span>';
            matchIndicator.classList.remove('hidden');
        }
    } else {
        matchIndicator.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
