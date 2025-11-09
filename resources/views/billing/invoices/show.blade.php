@extends('layouts.billing')

@section('title', 'Invoice Detail')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Invoice Detail</h2>
                <div>
                    <a href="{{ route('billing.invoices.print', $invoice) }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-print"></i> Print Invoice
                    </a>
                    @if($invoice->status !== 'paid')
                        <a href="{{ route('billing.payments.gateway', $invoice) }}" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Pay Online
                        </a>
                        <a href="{{ route('billing.payments.create', $invoice) }}" class="btn btn-outline-success">
                            <i class="fas fa-money-bill"></i> Manual Payment
                        </a>
                    @endif
                    <a href="{{ route('billing.invoices.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ $invoice->invoice_number }}</h4>
                            <p class="text-muted mb-3">{{ $invoice->created_at->format('F j, Y') }}</p>
                            
                            <div class="mb-3">
                                <strong>Bill To:</strong><br>
                                <strong>{{ $invoice->customer->name }}</strong><br>
                                {{ $invoice->customer->email }}<br>
                                @if($invoice->customer->phone)
                                    {{ $invoice->customer->phone }}<br>
                                @endif
                                @if($invoice->customer->address)
                                    {{ $invoice->customer->address }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 text-md-end">
                            <div class="mb-3">
                                <strong>Invoice Date:</strong> {{ $invoice->invoice_date ? $invoice->invoice_date->format('d/m/Y') : $invoice->created_at->format('d/m/Y') }}<br>
                                <strong>Due Date:</strong> {{ $invoice->due_date->format('d/m/Y') }}<br>
                                <strong>Status:</strong> 
                                @if($invoice->status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($invoice->due_date->isPast())
                                    <span class="badge badge-danger">Overdue</span>
                                @else
                                    <span class="badge badge-warning">Unpaid</span>
                                @endif
                            </div>
                            
                            @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    This invoice is overdue by {{ $invoice->due_date->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th>Period</th>
                                    <th>Package</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>{{ $invoice->description ?: 'Monthly Internet Subscription' }}</strong>
                                        @if($invoice->subscription && $invoice->subscription->package)
                                            <br><small class="text-muted">{{ $invoice->subscription->package->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->subscription && $invoice->subscription->start_date && $invoice->subscription->end_date)
                                            {{ $invoice->subscription->start_date->format('d/m/Y') }} - {{ $invoice->subscription->end_date->format('d/m/Y') }}
                                        @else
                                            {{ $invoice->created_at->format('M Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->subscription && $invoice->subscription->package)
                                            <strong>{{ $invoice->subscription->package->name }}</strong><br>
                                            <small class="text-muted">
                                                Download: {{ $invoice->subscription->package->getFormattedBandwidth('down') }} | 
                                                Upload: {{ $invoice->subscription->package->getFormattedBandwidth('up') }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Amount:</th>
                                    <th class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</th>
                                </tr>
                                @php
                                    $totalPaid = $invoice->payments->sum('amount');
                                    $remaining = $invoice->amount - $totalPaid;
                                @endphp
                                @if($totalPaid > 0)
                                    <tr class="text-success">
                                        <th colspan="3" class="text-right">Amount Paid:</th>
                                        <th class="text-right">Rp {{ number_format($totalPaid, 0, ',', '.') }}</th>
                                    </tr>
                                @endif
                                @if($remaining > 0)
                                    <tr class="text-danger">
                                        <th colspan="3" class="text-right">Remaining Balance:</th>
                                        <th class="text-right">Rp {{ number_format($remaining, 0, ',', '.') }}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong><br>
                            {{ $invoice->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
                    </div>
                    <div class="card-body">
                        @foreach($invoice->payments as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <strong>{{ $payment->payment_number }}</strong><br>
                                    <small class="text-muted">{{ $payment->payment_date->format('d/m/Y H:i') }}</small><br>
                                    <span class="badge badge-{{ $payment->method === 'cash' ? 'success' : 'info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <strong class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                    @if($payment->receivedBy)
                                        <br><small class="text-muted">by {{ $payment->receivedBy->name }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Customer Info -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-3x text-gray-300"></i>
                    </div>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $invoice->customer->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Code:</strong></td>
                            <td>{{ $invoice->customer->customer_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $invoice->customer->email }}</td>
                        </tr>
                        @if($invoice->customer->phone)
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $invoice->customer->phone }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $invoice->customer->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($invoice->customer->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Join Date:</strong></td>
                            <td>{{ $invoice->customer->join_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('customers.show', $invoice->customer) }}" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-eye"></i> View Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection