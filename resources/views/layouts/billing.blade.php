<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Billing System') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Billing Enhancements CSS -->
    <link href="{{ asset('css/billing-enhancements.css') }}" rel="stylesheet">
    
    <!-- Custom CSS for Billing System -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-color);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1rem;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-brand:hover {
            color: #fff;
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* Topbar */
        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            padding: 1rem 1.5rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        /* Border Left Cards */
        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }

        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }

        /* Text Colors */
        .text-primary { color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-info { color: var(--info-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-300 { color: #dddfeb !important; }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        /* Tables */
        .table thead th {
            background-color: #f8f9fc;
            border-color: #e3e6f0;
            color: #5a5c69;
            font-weight: 600;
        }

        /* Forms */
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* Badges */
        .badge-success { background-color: var(--success-color); }
        .badge-warning { background-color: var(--warning-color); }
        .badge-danger { background-color: var(--danger-color); }
        .badge-info { background-color: var(--info-color); }
        .badge-secondary { background-color: var(--secondary-color); }

        /* Utilities */
        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block !important;
            }
        }

        .mobile-menu-btn {
            display: none;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Additional Enhancements */
        .card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .card-stats {
            cursor: pointer;
        }

        .card-stats:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.3) !important;
        }

        /* Status Indicators */
        .status-active { color: var(--success-color); }
        .status-suspended { color: var(--warning-color); }
        .status-inactive { color: var(--secondary-color); }
        .status-expired { color: var(--danger-color); }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Loading Spinner */
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Print Styles */
        @media print {
            .sidebar, .topbar, .btn, .no-print {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <a class="sidebar-brand" href="{{ route('billing.dashboard') }}">
            <i class="fas fa-wifi me-2"></i>
            <span>Billing System</span>
        </a>
        
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.dashboard') ? 'active' : '' }}" href="{{ route('billing.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('packages.*') ? 'active' : '' }}" href="{{ route('packages.index') }}">
                    <i class="fas fa-box"></i>
                    Internet Packages
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pppoe.*') ? 'active' : '' }}" href="{{ route('pppoe.index') }}">
                    <i class="fas fa-network-wired"></i>
                    PPPoE Accounts
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.invoices.*') ? 'active' : '' }}" href="{{ route('billing.invoices.index') }}">
                    <i class="fas fa-file-invoice"></i>
                    Invoices
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.payments.*') ? 'active' : '' }}" href="{{ route('billing.payments.index') }}">
                    <i class="fas fa-credit-card"></i>
                    Payments
                </a>
            </li>
            
            <hr class="text-white-50">
            
            <li class="nav-item">
                <h6 class="nav-link text-white-50 text-uppercase text-xs font-weight-bold">Reports</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.reports.financial') ? 'active' : '' }}" href="{{ route('billing.reports.financial') }}">
                    <i class="fas fa-chart-line"></i>
                    Financial Report
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.reports.customers') ? 'active' : '' }}" href="{{ route('billing.reports.customers') }}">
                    <i class="fas fa-chart-pie"></i>
                    Customer Report
                </a>
            </li>
            
            <hr class="text-white-50">
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    Main Dashboard
                </a>
            </li>
            
            @auth
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </li>
            @endauth
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn btn-link mobile-menu-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">@yield('title', 'Billing System')</h4>
                    <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
                </div>
                
                @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        <div class="fade-in">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Billing System JS -->
    <script src="{{ asset('js/billing-system.js') }}"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add loading states to buttons
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Confirm delete actions
        document.querySelectorAll('.btn-danger').forEach(function(btn) {
            if (btn.textContent.includes('Delete') || btn.textContent.includes('Hapus')) {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                });
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Add search functionality to tables
        function addTableSearch(tableId, searchInputId) {
            const searchInput = document.getElementById(searchInputId);
            const table = document.getElementById(tableId);
            
            if (searchInput && table) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toUpperCase();
                    const rows = table.getElementsByTagName('tr');
                    
                    for (let i = 1; i < rows.length; i++) {
                        let row = rows[i];
                        let cells = row.getElementsByTagName('td');
                        let found = false;
                        
                        for (let j = 0; j < cells.length; j++) {
                            if (cells[j].textContent.toUpperCase().indexOf(filter) > -1) {
                                found = true;
                                break;
                            }
                        }
                        
                        row.style.display = found ? '' : 'none';
                    }
                });
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>