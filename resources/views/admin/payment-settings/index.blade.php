@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Payment Gateway Settings</h3>
        </div>
        
        <div class="px-6 py-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
                    {{ session('success') }}
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative">
                    {{ session('error') }}
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tripay Gateway Card -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Tripay Payment Gateway</h5>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Indonesian Payment Gateway</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer"
                                       {{ $tripay && $tripay->is_active ? 'checked' : '' }}
                                       onchange="toggleGatewayStatus('tripay', this.checked)">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        @if($tripay)
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $tripay->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $tripay->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Environment:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $tripay->environment === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($tripay->environment) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Merchant Code:</span>
                                    <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ $tripay->merchant_code ? substr($tripay->merchant_code, 0, 6) . '***' : 'Not Set' }}
                                    </code>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                    <span class="text-gray-700 dark:text-gray-300 text-xs">{{ $tripay->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded text-sm">
                                <i class="fas fa-exclamation-triangle mr-2 text-yellow-600 dark:text-yellow-300"></i>
                                <span class="text-yellow-800 dark:text-yellow-200">Tripay gateway not configured yet</span>
                            </div>
                        @endif

                        <div class="flex flex-col gap-2">
                            <a href="{{ route('admin.payment-settings.tripay.edit') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                                <i class="fas fa-cog mr-2"></i> Configure Tripay
                            </a>
                            <button type="button" onclick="testTripayConnection()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <i class="fas fa-wifi mr-2"></i> Test Connection
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Placeholder for more payment gateways -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-plus-circle text-4xl text-gray-400 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">More payment gateways coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleGatewayStatus(gateway, isActive) {
    fetch(`/admin/payment-settings/${gateway}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to toggle status: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function testTripayConnection() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⚙</span> Testing...';
    btn.disabled = true;

    fetch('/admin/payment-settings/tripay/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            alert('✓ ' + data.message);
        } else {
            alert('✗ ' + data.message);
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
