@extends('layouts.billing')

@section('title', 'Dashboard Billing')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Dashboard Billing Internet</h2>
                <div>
                    <form action="{{ route('billing.generate.invoices') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" 
                                onclick="return confirm('Generate invoice untuk semua customer aktif?')">
                            <i class="fas fa-file-invoice"></i> Generate Invoice Bulanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Customer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_customers'] }}
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
                                Paket Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_packages'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wifi fa-2x text-gray-300"></i>
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
                                Akun PPPoE Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_accounts'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-network-wired fa-2x text-gray-300"></i>
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
                                Revenue Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Invoice Overdue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $overdueInvoices }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Akun Akan Expired (7 Hari)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $expiringAccounts }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Invoices -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Pending</h6>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($pendingInvoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $invoice->due_date->isPast() ? 'danger' : 'warning' }}">
                                                {{ $invoice->due_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Tidak ada invoice pending</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran Terbaru</h6>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_number }}</td>
                                        <td>{{ $payment->customer->name }}</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Tidak ada pembayaran terbaru</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('customers.create') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-user-plus"></i> Tambah Customer
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('packages.create') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-plus"></i> Tambah Paket
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pppoe.create') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-network-wired"></i> Buat PPPoE
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('billing.reports.financial') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-chart-line"></i> Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection