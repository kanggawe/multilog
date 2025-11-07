@extends('layouts.app')

@section('title', 'Help & Documentation - Laravel Multi-Level User')
@section('page_title', 'Help & Documentation')
@section('page_subtitle', 'Get help and learn how to use the system')

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
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Help</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
        <!-- Search Section -->
        <div class="mb-8">
            <div class="relative max-w-2xl mx-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="help-search" placeholder="Search for help topics..." 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Help Navigation -->
            <div class="lg:col-span-1">
                <nav class="space-y-1">
                    <a href="#getting-started" onclick="showHelpSection('getting-started')" 
                        class="help-nav-item active bg-blue-50 border-blue-500 text-blue-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-rocket mr-3 text-blue-500"></i>
                        Getting Started
                    </a>
                    <a href="#user-management" onclick="showHelpSection('user-management')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        User Management
                    </a>
                    <a href="#radius-configuration" onclick="showHelpSection('radius-configuration')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-network-wired mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        RADIUS Configuration
                    </a>
                    <a href="#hotspot-management" onclick="showHelpSection('hotspot-management')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-wifi mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Hotspot Management
                    </a>
                    <a href="#ppp-management" onclick="showHelpSection('ppp-management')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-plug mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        PPP Management
                    </a>
                    <a href="#billing" onclick="showHelpSection('billing')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Billing & Payments
                    </a>
                    <a href="#reports" onclick="showHelpSection('reports')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Reports & Analytics
                    </a>
                    <a href="#troubleshooting" onclick="showHelpSection('troubleshooting')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-tools mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Troubleshooting
                    </a>
                    <a href="#faq" onclick="showHelpSection('faq')" 
                        class="help-nav-item text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-question-circle mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        FAQ
                    </a>
                </nav>

                <!-- Contact Support -->
                <div class="mt-8 bg-blue-50 rounded-lg p-4 contact-support-box">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Need More Help?</h4>
                    <p class="text-sm text-blue-700 mb-3">Can't find what you're looking for?</p>
                    <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                        <i class="fas fa-headset mr-2"></i>
                        Contact Support
                    </button>
                </div>
            </div>

            <!-- Help Content -->
            <div class="lg:col-span-4">
                <!-- Getting Started -->
                <div id="getting-started" class="help-section">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Getting Started</h3>
                            <p class="mt-1 text-sm text-gray-500">Learn the basics of using Laravel Multi-Level User system.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Welcome to Laravel Multi-Level User</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    This system provides comprehensive user management with role-based access control. 
                                    Follow this guide to get started quickly.
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">1</span>
                                            </div>
                                            <h5 class="font-medium text-gray-900">Setup NAS Devices</h5>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Configure your Network Access Server (NAS) devices in the system.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">2</span>
                                            </div>
                                            <h5 class="font-medium text-gray-900">Create User Accounts</h5>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Add users and configure their authentication credentials.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">3</span>
                                            </div>
                                            <h5 class="font-medium text-gray-900">Configure Groups</h5>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Set up user groups with specific access policies and restrictions.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">4</span>
                                            </div>
                                            <h5 class="font-medium text-gray-900">Monitor Usage</h5>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Track user sessions, usage statistics, and system performance.
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-lightbulb text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">
                                                Pro Tip
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>
                                                    Start with a small test group of users to verify your configuration 
                                                    before rolling out to all users. This helps identify and fix any 
                                                    issues early.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div id="user-management" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">User Management</h3>
                            <p class="mt-1 text-sm text-gray-500">Learn how to manage users, groups, and permissions.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Adding New Users</h4>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                                    <li>Navigate to the Users section in the main menu</li>
                                    <li>Click the "Add User" button</li>
                                    <li>Fill in the required information (username, password, email)</li>
                                    <li>Select the appropriate user group</li>
                                    <li>Configure any additional attributes or restrictions</li>
                                    <li>Click "Save" to create the user</li>
                                </ol>
                            </div>

                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">User Groups</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    User groups allow you to apply consistent policies and restrictions to multiple users.
                                </p>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 mb-2">Common Group Types:</h5>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                        <li><strong>Admin:</strong> Full system access</li>
                                        <li><strong>Staff:</strong> Limited administrative access</li>
                                        <li><strong>Premium:</strong> High-speed internet access</li>
                                        <li><strong>Standard:</strong> Regular internet access</li>
                                        <li><strong>Guest:</strong> Limited time and bandwidth</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RADIUS Configuration -->
                <div id="radius-configuration" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">RADIUS Configuration</h3>
                            <p class="mt-1 text-sm text-gray-500">Configure RADIUS authentication and authorization.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">NAS Device Configuration</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Network Access Server (NAS) devices must be configured to communicate with the RADIUS server.
                                </p>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 mb-2">Required NAS Settings:</h5>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                        <li>RADIUS Server IP: Your RADIUS server address</li>
                                        <li>RADIUS Port: 1812 (authentication), 1813 (accounting)</li>
                                        <li>Shared Secret: Must match the secret configured in the system</li>
                                        <li>Timeout: Recommended 5-10 seconds</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hotspot Management -->
                <div id="hotspot-management" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Hotspot Management</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage hotspot services and user sessions.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Hotspot Profiles</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Create and manage hotspot profiles with different access policies and restrictions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PPP Management -->
                <div id="ppp-management" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">PPP Management</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage Point-to-Point Protocol connections.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">PPP Users</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Manage PPP users for dial-up, VPN, and other point-to-point connections.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing -->
                <div id="billing" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Billing & Payments</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage billing, invoices, and payment processing.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Payment Methods</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    The system supports multiple payment methods for user convenience.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div id="reports" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Reports & Analytics</h3>
                            <p class="mt-1 text-sm text-gray-500">Generate reports and analyze system usage.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Available Reports</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Generate various reports to monitor system performance and user activity.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <div id="troubleshooting" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Troubleshooting</h3>
                            <p class="mt-1 text-sm text-gray-500">Common issues and their solutions.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Common Issues</h4>
                                <div class="space-y-4">
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h5 class="font-medium text-gray-900 mb-2">Authentication Failed</h5>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Users cannot authenticate with their credentials.
                                        </p>
                                        <ul class="list-disc list-inside text-sm text-gray-600">
                                            <li>Check if the user exists and is active</li>
                                            <li>Verify the password is correct</li>
                                            <li>Ensure the user belongs to an active group</li>
                                            <li>Check NAS device configuration</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h5 class="font-medium text-gray-900 mb-2">Connection Timeout</h5>
                                        <p class="text-sm text-gray-600 mb-2">
                                            RADIUS requests are timing out.
                                        </p>
                                        <ul class="list-disc list-inside text-sm text-gray-600">
                                            <li>Check network connectivity between NAS and RADIUS server</li>
                                            <li>Verify firewall settings</li>
                                            <li>Check RADIUS server is running</li>
                                            <li>Review server logs for errors</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div id="faq" class="help-section hidden">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h3>
                            <p class="mt-1 text-sm text-gray-500">Common questions and answers.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">How do I reset a user's password?</h4>
                                    <p class="text-sm text-gray-600">
                                        Go to the Users section, find the user, click "Edit", and update their password. 
                                        The new password will be effective immediately.
                                    </p>
                                </div>
                                
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">Can I import users from a CSV file?</h4>
                                    <p class="text-sm text-gray-600">
                                        Yes, you can import users using the bulk import feature. Prepare a CSV file with 
                                        the required columns and use the import function in the Users section.
                                    </p>
                                </div>
                                
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">How do I monitor user sessions?</h4>
                                    <p class="text-sm text-gray-600">
                                        Use the Accounting section to view active sessions and connection history. 
                                        You can also generate reports for detailed analysis.
                                    </p>
                                </div>
                                
                                <div class="pb-4">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">What backup options are available?</h4>
                                    <p class="text-sm text-gray-600">
                                        The system provides automatic daily backups. You can also manually export 
                                        data and configurations from the Settings section.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    /* Help Navigation */
    nav a {
        transition: all 0.15s ease-in-out;
    }

    nav a:hover {
        transform: translateX(4px);
    }

    /* Help Section Transitions */
    .help-section {
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

    /* Search Input */
    #help-search {
        transition: all 0.15s ease-in-out;
    }

    #help-search:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Contact Support Box */
    .bg-blue-50.rounded-lg {
        transition: all 0.15s ease-in-out;
    }

    .bg-blue-50.rounded-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Button Transitions */
    button {
        transition: all 0.15s ease-in-out;
    }

    button:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
function showHelpSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.help-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(sectionId).classList.remove('hidden');
    
    // Update navigation active state
    document.querySelectorAll('.help-nav-item').forEach(item => {
        item.classList.remove('active', 'bg-blue-50', 'border-blue-500', 'text-blue-700');
        item.classList.add('text-gray-900', 'hover:bg-gray-50');
    });
    
    // Add active class to clicked item
    event.target.classList.add('active', 'bg-blue-50', 'border-blue-500', 'text-blue-700');
    event.target.classList.remove('text-gray-900', 'hover:bg-gray-50');
}

// Search functionality
document.getElementById('help-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const sections = document.querySelectorAll('.help-section');
    
    sections.forEach(section => {
        const content = section.textContent.toLowerCase();
        if (content.includes(searchTerm)) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection 