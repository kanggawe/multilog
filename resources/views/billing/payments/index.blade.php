@extends('layouts.billing')

@section('title', 'Manajemen Payment')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Manajemen Payment</h2>
                <a href="{{ route('billing.payments.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Add Payment
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('billing.payments.index') }}" class="row align-items-end">
                        <div class="col-md-2">
                            <label for="method">Payment Method</label>
                            <select class="form-control" id="method" name="method">
                                <option value="">All Methods</option>
                                <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="debit_card" {{ request('method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Payment number, customer, or invoice..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('billing.payments.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>Payment Number</th>
                                    <th>Customer</th>
                                    <th>Invoice</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Payment Date</th>
                                    <th>Received By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('billing.payments.show', $payment) }}">
                                            {{ $payment->payment_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $payment->customer->name }}</strong><br>
                                        <small class="text-muted">{{ $payment->customer->customer_code }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('billing.invoices.show', $payment->invoice) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @if($payment->payment_gateway === 'tripay')
                                            <span class="badge badge-primary">
                                                <i class="fas fa-credit-card"></i> {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                            </span>
                                            <br><small class="badge badge-{{ $payment->getStatusBadgeClass() }}">
                                                {{ $payment->getStatusText() }}
                                            </small>
                                        @else
                                            @switch($payment->method)
                                                @case('cash')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-money-bill"></i> Cash
                                                    </span>
                                                    @break
                                                @case('bank_transfer')
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-university"></i> Bank Transfer
                                                    </span>
                                                    @break
                                                @case('credit_card')
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-credit-card"></i> Credit Card
                                                    </span>
                                                    @break
                                                @case('debit_card')
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-credit-card"></i> Debit Card
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light">
                                                        <i class="fas fa-question"></i> {{ ucfirst($payment->method) }}
                                                    </span>
                                            @endswitch
                                        @endif
                                    </td>
                                    <td>
                                        {{ $payment->payment_date->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $payment->payment_date->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        {{ $payment->receivedBy ? $payment->receivedBy->name : '-' }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('billing.payments.show', $payment) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('billing.payments.receipt', $payment) }}" class="btn btn-sm btn-secondary" title="Print Receipt" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <form method="POST" action="{{ route('billing.payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No payments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($payments->count() > 0)
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="3" class="text-right">TOTAL:</th>
                                    <th class="text-right">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
                        </div>
                        <div>
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
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
                                Cash Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->where('method', 'cash')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
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
                                Bank Transfers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->where('method', 'bank_transfer')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
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
                                Today's Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $todayRevenue = $payments->filter(function($payment) {
                                        return $payment->payment_date->isToday();
                                    })->sum('amount');
                                @endphp
                                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
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
</div>
@endsection