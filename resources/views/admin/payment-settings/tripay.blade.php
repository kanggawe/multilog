@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tripay Configuration</h3>
            <a href="{{ route('admin.payment-settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                    {{ session('success') }}
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                    {{ session('error') }}
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Note:</strong> Get your Tripay credentials from 
                    <a href="https://tripay.co.id" target="_blank" class="underline hover:text-blue-600">https://tripay.co.id</a>
                </p>
            </div>

            <form action="{{ route('admin.payment-settings.tripay.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Environment -->
                    <div>
                        <label for="environment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Environment <span class="text-red-500">*</span>
                        </label>
                        <select name="environment" id="environment" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('environment') border-red-500 @enderror">
                            <option value="sandbox" {{ old('environment', $tripay->environment ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>
                                Sandbox (Testing)
                            </option>
                            <option value="production" {{ old('environment', $tripay->environment ?? '') === 'production' ? 'selected' : '' }}>
                                Production (Live)
                            </option>
                        </select>
                        @error('environment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose sandbox for testing or production for live transactions</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', $tripay->is_active ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Activate Tripay Gateway</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- API Key -->
                    <div>
                        <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            API Key <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="api_key" id="api_key" required
                                   value="{{ old('api_key', $tripay->api_key ?? '') }}"
                                   placeholder="Enter your Tripay API Key"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('api_key') border-red-500 @enderror">
                            <button type="button" onclick="togglePassword('api_key')" 
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                <i class="fas fa-eye" id="api_key_icon"></i>
                            </button>
                        </div>
                        @error('api_key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Your Tripay API Key</p>
                    </div>

                    <!-- Private Key -->
                    <div>
                        <label for="private_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Private Key <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="private_key" id="private_key" required
                                   value="{{ old('private_key', $tripay->private_key ?? '') }}"
                                   placeholder="Enter your Tripay Private Key"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('private_key') border-red-500 @enderror">
                            <button type="button" onclick="togglePassword('private_key')" 
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                <i class="fas fa-eye" id="private_key_icon"></i>
                            </button>
                        </div>
                        @error('private_key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Your Tripay Private Key (for signature)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Merchant Code -->
                    <div>
                        <label for="merchant_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Merchant Code <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="merchant_code" id="merchant_code" required
                                   value="{{ old('merchant_code', $tripay->merchant_code ?? '') }}"
                                   placeholder="Enter your Merchant Code"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('merchant_code') border-red-500 @enderror">
                            <button type="button" onclick="togglePassword('merchant_code')" 
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                <i class="fas fa-eye" id="merchant_code_icon"></i>
                            </button>
                        </div>
                        @error('merchant_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Your Tripay Merchant Code</p>
                    </div>

                    <!-- Callback URL -->
                    <div>
                        <label for="callback_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Callback URL
                        </label>
                        <input type="url" name="callback_url" id="callback_url"
                               value="{{ old('callback_url', $tripay->callback_url ?? route('tripay.callback')) }}"
                               placeholder="https://yourdomain.com/tripay/callback"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('callback_url') border-red-500 @enderror">
                        @error('callback_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL to receive payment notifications</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" onclick="testConnection()" 
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-wifi mr-2"></i> Test Connection
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                        <i class="fas fa-save mr-2"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function testConnection() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⚙</span> Testing...';
    btn.disabled = true;

    // Get form data
    const formData = {
        environment: document.getElementById('environment').value,
        api_key: document.getElementById('api_key').value,
        private_key: document.getElementById('private_key').value,
        merchant_code: document.getElementById('merchant_code').value
    };

    // First save the settings, then test
    fetch('{{ route("admin.payment-settings.tripay.update") }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(() => {
        // After saving, test the connection
        return fetch('/admin/payment-settings/tripay/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            alert('✓ Connection Successful!\n\n' + data.message);
        } else {
            alert('✗ Connection Failed!\n\n' + data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Error: ' + error.message);
    });
}
</script>
@endsection
