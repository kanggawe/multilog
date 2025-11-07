@extends('layouts.app')

@section('title', 'Settings - Laravel Multi-Level User')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your account settings and preferences')

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
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Settings</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Settings Navigation -->
            <div class="lg:col-span-1">
                <nav class="space-y-1">
                    <a href="#general" onclick="showSection('general')" 
                        class="settings-nav-item active bg-blue-50 dark:bg-blue-900/20 border-blue-500 text-blue-700 dark:text-blue-300 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-cog mr-3 text-blue-500"></i>
                        General
                    </a>
                    <a href="#security" onclick="showSection('security')" 
                        class="settings-nav-item text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Security
                    </a>
                    <a href="#notifications" onclick="showSection('notifications')" 
                        class="settings-nav-item text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-bell mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Notifications
                    </a>
                    <a href="#privacy" onclick="showSection('privacy')" 
                        class="settings-nav-item text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user-secret mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Privacy
                    </a>
                    <a href="#integrations" onclick="showSection('integrations')" 
                        class="settings-nav-item text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-plug mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Integrations
                    </a>
                </nav>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2">
                <!-- General Settings -->
                <div id="general" class="settings-section">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">General Settings</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage your general account preferences.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Language -->
                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                <select id="language" name="language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white text-gray-900">
                                    <option value="en" {{ auth()->check() && auth()->user() && auth()->user()->language == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="id" {{ auth()->check() && auth()->user() && auth()->user()->language == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="es" {{ auth()->check() && auth()->user() && auth()->user()->language == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="fr" {{ auth()->check() && auth()->user() && auth()->user()->language == 'fr' ? 'selected' : '' }}>Français</option>
                                </select>
                            </div>

                            <!-- Timezone -->
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                <select id="timezone" name="timezone" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white text-gray-900">
                                    <option value="UTC" {{ auth()->check() && auth()->user() && auth()->user()->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Asia/Jakarta" {{ auth()->check() && auth()->user() && auth()->user()->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar" {{ auth()->check() && auth()->user() && auth()->user()->timezone == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura" {{ auth()->check() && auth()->user() && auth()->user()->timezone == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                </select>
                            </div>

                            <!-- Date Format -->
                            <div>
                                <label for="date_format" class="block text-sm font-medium text-gray-700">Date Format</label>
                                <select id="date_format" name="date_format" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white text-gray-900">
                                    <option value="Y-m-d" {{ auth()->check() && auth()->user() && auth()->user()->date_format == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="d/m/Y" {{ auth()->check() && auth()->user() && auth()->user()->date_format == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="m/d/Y" {{ auth()->check() && auth()->user() && auth()->user()->date_format == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                </select>
                            </div>

                            <!-- Theme -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Theme</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="theme-light" name="theme" type="radio" value="light" {{ auth()->check() && auth()->user() && auth()->user()->theme == 'light' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="theme-light" class="ml-3 block text-sm font-medium text-gray-700">Light</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="theme-dark" name="theme" type="radio" value="dark" {{ auth()->check() && auth()->user() && auth()->user()->theme == 'dark' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="theme-dark" class="ml-3 block text-sm font-medium text-gray-700">Dark</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="theme-auto" name="theme" type="radio" value="auto" {{ auth()->check() && auth()->user() && auth()->user()->theme == 'auto' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="theme-auto" class="ml-3 block text-sm font-medium text-gray-700">Auto (System)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div id="security" class="settings-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Security Settings</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage your account security and authentication.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Two-Factor Authentication -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Two-Factor Authentication</h4>
                                    <p class="text-sm text-gray-500">Add an extra layer of security to your account.</p>
                                </div>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Enable 2FA
                                </button>
                            </div>

                            <!-- Session Management -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Active Sessions</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex items-center">
                                            <i class="fas fa-desktop text-gray-400 mr-3"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Windows 10 - Chrome</p>
                                                <p class="text-xs text-gray-500">192.168.1.100 • Active now</p>
                                            </div>
                                        </div>
                                        <button type="button" class="text-sm text-red-600 hover:text-red-500">Revoke</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Change Password</h4>
                                <a href="{{ route('account.password.change') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-key mr-2"></i>
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div id="notifications" class="settings-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Notification Settings</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage how you receive notifications.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Email Notifications -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Email Notifications</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Security Alerts</p>
                                            <p class="text-sm text-gray-500">Get notified about security events</p>
                                        </div>
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">System Updates</p>
                                            <p class="text-sm text-gray-500">Receive updates about system changes</p>
                                        </div>
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Weekly Reports</p>
                                            <p class="text-sm text-gray-500">Get weekly summary reports</p>
                                        </div>
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>

                            <!-- Push Notifications -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Push Notifications</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Browser Notifications</p>
                                            <p class="text-sm text-gray-500">Receive notifications in your browser</p>
                                        </div>
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div id="privacy" class="settings-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Privacy Settings</h3>
                            <p class="mt-1 text-sm text-gray-500">Control your privacy and data settings.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Data Collection -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Data Collection</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Usage Analytics</p>
                                            <p class="text-sm text-gray-500">Help us improve by sharing usage data</p>
                                        </div>
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Error Reporting</p>
                                            <p class="text-sm text-gray-500">Automatically report errors for debugging</p>
                                        </div>
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>

                            <!-- Data Export -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Data Export</h4>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-download mr-2"></i>
                                    Export My Data
                                </button>
                            </div>

                            <!-- Account Deletion -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Account Deletion</h4>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integrations Settings -->
                <div id="integrations" class="settings-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Integrations</h3>
                            <p class="mt-1 text-sm text-gray-500">Connect with third-party services.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Google -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fab fa-google text-red-500 text-2xl mr-4"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Google</p>
                                        <p class="text-sm text-gray-500">Connect your Google account</p>
                                    </div>
                                </div>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Connect
                                </button>
                            </div>

                            <!-- Microsoft -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fab fa-microsoft text-blue-500 text-2xl mr-4"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Microsoft</p>
                                        <p class="text-sm text-gray-500">Connect your Microsoft account</p>
                                    </div>
                                </div>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Connect
                                </button>
                            </div>

                            <!-- Slack -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fab fa-slack text-purple-500 text-2xl mr-4"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Slack</p>
                                        <p class="text-sm text-gray-500">Send notifications to Slack</p>
                                    </div>
                                </div>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Connect
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="saveSettings()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    /* Settings Navigation */
    nav a {
        transition: all 0.15s ease-in-out;
    }

    nav a:hover {
        transform: translateX(4px);
    }

    /* Settings Section Transitions */
    .settings-section {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Elements */
    select, input[type="text"], input[type="email"], input[type="tel"] {
        transition: all 0.15s ease-in-out;
    }

    select:focus, input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Checkbox and Radio Styling */
    input[type="checkbox"], input[type="radio"] {
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    /* Button Improvements */
    button {
        transition: all 0.15s ease-in-out;
    }

    button:hover {
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<script>
function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(sectionId).classList.remove('hidden');
    
    // Update navigation active state
    document.querySelectorAll('.settings-nav-item').forEach(item => {
        item.classList.remove('active', 'bg-blue-50', 'border-blue-500', 'text-blue-700');
        item.classList.add('text-gray-900', 'hover:bg-gray-50');
    });
    
    // Add active class to clicked item
    event.target.classList.add('active', 'bg-blue-50', 'border-blue-500', 'text-blue-700');
    event.target.classList.remove('text-gray-900', 'hover:bg-gray-50');
}

function saveSettings() {
    // Add your save logic here
    alert('Settings saved successfully!');
}
</script>
@endpush
@endsection 