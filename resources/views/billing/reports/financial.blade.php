@extends('layouts.billing')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Laporan Keuangan</h2>
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

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('billing.reports.financial') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter Laporan
                            </button>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="{{ route('billing.reports.financial') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($revenue, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Outstanding Invoice
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($outstanding, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Monthly
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($monthlyTrend->avg('revenue') ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Period Days
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $startDate->diffInDays($endDate) + 1 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Methods Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                </div>
                <div class="card-body">
                    @if($paymentsByMethod->count() > 0)
                        <canvas id="paymentMethodChart" width="100" height="50"></canvas>
                        <div class="mt-3">
                            @foreach($paymentsByMethod as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-capitalize">
                                    <i class="fas fa-circle text-{{ $loop->index % 4 == 0 ? 'primary' : ($loop->index % 4 == 1 ? 'success' : ($loop->index % 4 == 2 ? 'info' : 'warning')) }}"></i>
                                    {{ str_replace('_', ' ', $payment->method) }}
                                </span>
                                <span class="font-weight-bold">Rp {{ number_format($payment->total, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">No payment data available for selected period</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trend (12 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" width="100" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Payment Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>Payment Number</th>
                                    <th>Customer</th>
                                    <th>Invoice</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Received By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $payments = \App\Models\Payment::with(['customer', 'invoice', 'receivedBy'])
                                        ->whereBetween('payment_date', [$startDate, $endDate])
                                        ->orderBy('payment_date', 'desc')
                                        ->get();
                                @endphp
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>
                                        <a href="{{ route('customers.show', $payment->customer) }}">
                                            {{ $payment->customer->name }}
                                        </a>
                                        <br><small class="text-muted">{{ $payment->customer->customer_code }}</small>
                                    </td>
                                    <td>{{ $payment->invoice->invoice_number }}</td>
                                    <td>
                                        <span class="badge badge-{{ $payment->method == 'cash' ? 'success' : 'info' }}">
                                            {{ str_replace('_', ' ', ucfirst($payment->method)) }}
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ $payment->receivedBy ? $payment->receivedBy->name : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No payments found for selected period</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($payments->count() > 0)
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="4" class="text-right">TOTAL:</th>
                                    <th class="text-right">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $payments->count() }}</h4>
                            <small class="text-muted">Total Transactions</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $payments->where('method', 'cash')->count() }}</h4>
                            <small class="text-muted">Cash Payments</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $payments->where('method', 'bank_transfer')->count() }}</h4>
                            <small class="text-muted">Bank Transfers</small>
                        </div>
                        <div class="col-md-3">
                            @php
                                $avgPayment = $payments->count() > 0 ? $payments->avg('amount') : 0;
                            @endphp
                            <h4 class="text-warning">Rp {{ number_format($avgPayment, 0, ',', '.') }}</h4>
                            <small class="text-muted">Average Payment</small>
                        </div>
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
// Payment Methods Pie Chart
@if($paymentsByMethod->count() > 0)
const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
const paymentChart = new Chart(paymentCtx, {
    type: 'pie',
    data: {
        labels: [@foreach($paymentsByMethod as $payment)"{{ str_replace('_', ' ', ucfirst($payment->method)) }}",@endforeach],
        datasets: [{
            data: [@foreach($paymentsByMethod as $payment){{ $payment->total }},@endforeach],
            backgroundColor: [
                '#4e73df',
                '#1cc88a', 
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
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
@endif

// Monthly Trend Line Chart
const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: [@foreach($monthlyTrend as $month)"{{ $month['month'] }}",@endforeach],
        datasets: [{
            label: 'Revenue',
            data: [@foreach($monthlyTrend as $month){{ $month['revenue'] }},@endforeach],
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
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: Rp ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Export to Excel function
function exportToExcel() {
    // Simple table to CSV export
    const table = document.getElementById('paymentsTable');
    const rows = Array.from(table.rows);
    const csvContent = rows.map(row => {
        const cells = Array.from(row.cells);
        return cells.map(cell => `"${cell.innerText}"`).join(',');
    }).join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'financial_report_{{ $startDate->format("Y-m-d") }}_{{ $endDate->format("Y-m-d") }}.csv';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>
@endsection
@endsection