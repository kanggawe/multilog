@extends('layouts.billing')

@section('title', 'Manajemen Invoice')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Manajemen Invoice</h2>
                <form method="POST" action="{{ route('billing.generate.invoices') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Generate Monthly Invoices
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('billing.invoices.index') }}" class="row align-items-end">
                        <div class="col-md-2">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="customer_id">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
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
                        <div class="col-md-2">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Invoice number or customer..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('billing.invoices.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="invoicesTable">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('billing.invoices.show', $invoice) }}">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $invoice->customer->name }}</strong><br>
                                        <small class="text-muted">{{ $invoice->customer->customer_code }}</small>
                                    </td>
                                    <td class="text-right">
                                        <strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                                        @php
                                            $totalPaid = $invoice->payments->sum('amount');
                                            $remaining = $invoice->amount - $totalPaid;
                                        @endphp
                                        @if($totalPaid > 0)
                                            <br><small class="text-success">Paid: Rp {{ number_format($totalPaid, 0, ',', '.') }}</small>
                                            @if($remaining > 0)
                                                <br><small class="text-warning">Remaining: Rp {{ number_format($remaining, 0, ',', '.') }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {{ $invoice->due_date->format('d/m/Y') }}
                                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                            <br><small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $invoice->due_date->diffForHumans() }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($invoice->due_date->isPast())
                                            <span class="badge badge-danger">Overdue</span>
                                        @else
                                            <span class="badge badge-warning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('billing.invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('billing.invoices.print', $invoice) }}" class="btn btn-sm btn-secondary" title="Print" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @if($invoice->status !== 'paid')
                                                <a href="{{ route('billing.payments.create', $invoice) }}" class="btn btn-sm btn-success" title="Add Payment">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif
                                            @if($invoice->status !== 'paid')
                                                <form method="POST" action="{{ route('billing.invoices.destroy', $invoice) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                        </div>
                        <div>
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $invoices->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
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
                                Paid Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $invoices->where('status', 'paid')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Unpaid Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $invoices->where('status', 'unpaid')->count() }}
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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $invoices->filter(function($invoice) { return $invoice->due_date->isPast() && $invoice->status !== 'paid'; })->count() }}
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
</div>
@endsection