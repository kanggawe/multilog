@extends('layouts.billing')

@section('title', 'Laporan Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Laporan Customer</h2>
                <div>
                    <button class="btn btn-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_customers']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['active_customers']) }}
                            </div>
                            <div class="text-xs text-success">
                                {{ $stats['total_customers'] > 0 ? round(($stats['active_customers'] / $stats['total_customers']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Suspended Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['suspended_customers']) }}
                            </div>
                            <div class="text-xs text-warning">
                                {{ $stats['total_customers'] > 0 ? round(($stats['suspended_customers'] / $stats['total_customers']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Inactive Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['inactive_customers']) }}
                            </div>
                            <div class="text-xs text-secondary">
                                {{ $stats['total_customers'] > 0 ? round(($stats['inactive_customers'] / $stats['total_customers']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Growth Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Growth (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerGrowthChart" width="100" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Status Distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="100" height="100"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-success"></i> Active</span>
                            <span class="font-weight-bold">{{ $stats['active_customers'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-warning"></i> Suspended</span>
                            <span class="font-weight-bold">{{ $stats['suspended_customers'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-secondary"></i> Inactive</span>
                            <span class="font-weight-bold">{{ $stats['inactive_customers'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers by Revenue -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Customers by Revenue (This Year)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="topCustomersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Join Date</th>
                                    <th>Status</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}">
                                            {{ $customer->customer_code }}
                                        </a>
                                    </td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->join_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        Rp {{ number_format($customer->payments_sum_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No customers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Customers Summary -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">All Customers</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="allCustomersTable">
                            <thead>
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Join Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allCustomers = \App\Models\Customer::orderBy('customer_code')->get();
                                @endphp
                                @forelse($allCustomers as $customer)
                                <tr>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}">
                                            {{ $customer->customer_code }}
                                        </a>
                                    </td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ Str::limit($customer->address, 30) }}</td>
                                    <td>{{ $customer->join_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No customers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Customer Growth Line Chart
const growthCtx = document.getElementById('customerGrowthChart').getContext('2d');
const growthChart = new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: [@foreach($customerGrowth as $month)"{{ $month['month'] }}",@endforeach],
        datasets: [{
            label: 'New Customers',
            data: [@foreach($customerGrowth as $month){{ $month['customers'] }},@endforeach],
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            fill: true,
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'New Customers: ' + context.parsed.y;
                    }
                }
            }
        }
    }
});

// Customer Status Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Suspended', 'Inactive'],
        datasets: [{
            data: [
                {{ $stats['active_customers'] }},
                {{ $stats['suspended_customers'] }},
                {{ $stats['inactive_customers'] }}
            ],
            backgroundColor: [
                '#1cc88a',
                '#f6c23e', 
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Export to Excel function
function exportToExcel() {
    const table = document.getElementById('allCustomersTable');
    const rows = Array.from(table.rows);
    const csvContent = rows.map(row => {
        const cells = Array.from(row.cells);
        return cells.slice(0, -1).map(cell => `"${cell.innerText}"`).join(','); // Exclude action column
    }).join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'customer_report_{{ now()->format("Y-m-d") }}.csv';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>
@endsection
@endsection