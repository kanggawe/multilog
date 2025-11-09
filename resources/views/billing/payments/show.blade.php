@extends('layouts.billing')

@section('title', 'Payment Detail')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Payment Detail</h2>
                <div>
                    <a href="{{ route('billing.payments.receipt', $payment) }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-print"></i> Print Receipt
                    </a>
                    <a href="{{ route('billing.payments.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ $payment->payment_number }}</h4>
                            <p class="text-muted mb-3">{{ $payment->payment_date->format('F j, Y') }}</p>
                            
                            <div class="mb-3">
                                <strong>Paid By:</strong><br>
                                <strong>{{ $payment->customer->name }}</strong><br>
                                {{ $payment->customer->email }}<br>
                                @if($payment->customer->phone)
                                    {{ $payment->customer->phone }}<br>
                                @endif
                                @if($payment->customer->address)
                                    {{ $payment->customer->address }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Payment Date:</strong></td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($payment->method) }}</span>
                                    </td>
                                </tr>
                                @if($payment->reference_number)
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Received By:</strong></td>
                                    <td>{{ $payment->receivedBy->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Notes:</strong> {{ $payment->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Amount -->
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-light p-4 rounded text-center">
                                <h4 class="text-success mb-0">
                                    <i class="fas fa-check-circle"></i> Payment Received
                                </h4>
                                <h2 class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Related Invoice -->
            @if($payment->invoice)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Invoice</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Invoice Number:</strong><br>
                        <a href="{{ route('billing.invoices.show', $payment->invoice) }}" class="text-primary">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Invoice Date:</strong></td>
                            <td>{{ $payment->invoice->invoice_date ? $payment->invoice->invoice_date->format('d M Y') : ($payment->invoice->created_at ? $payment->invoice->created_at->format('d M Y') : 'N/A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Due Date:</strong></td>
                            <td>{{ $payment->invoice->due_date ? $payment->invoice->due_date->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $payment->invoice->status === 'paid' ? 'success' : ($payment->invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payment->invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Amount:</strong></td>
                            <td>Rp {{ number_format($payment->invoice->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Paid Amount:</strong></td>
                            <td>Rp {{ number_format($payment->invoice->paid_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Balance:</strong></td>
                            <td class="{{ $payment->invoice->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($payment->invoice->remaining_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    @if($payment->invoice->subscription)
                    <div class="mt-3">
                        <strong>Service Details:</strong><br>
                        <div class="small text-muted">
                            <strong>Package:</strong> {{ $payment->invoice->subscription->internetPackage->name }}<br>
                            <strong>Speed:</strong> {{ $payment->invoice->subscription->internetPackage->getFormattedBandwidth() }}<br>
                            <strong>Period:</strong> 
                            @if($payment->invoice->period_start && $payment->invoice->period_end)
                                {{ $payment->invoice->period_start->format('d M Y') }} - {{ $payment->invoice->period_end->format('d M Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment History -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Payment History</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentPayments = $payment->customer->payments()->latest()->limit(5)->get();
                    @endphp
                    
                    @if($recentPayments->count() > 0)
                        @foreach($recentPayments as $recentPayment)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ $recentPayment->id === $payment->id ? 'bg-light rounded px-2' : '' }}">
                            <div>
                                <div class="small">{{ $recentPayment->payment_number }}</div>
                                <div class="text-muted small">{{ $recentPayment->payment_date->format('d M Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold">Rp {{ number_format($recentPayment->amount, 0, ',', '.') }}</div>
                                <div class="small text-muted">{{ ucfirst($recentPayment->method) }}</div>
                            </div>
                        </div>
                        @if(!$loop->last)
                        <hr class="my-2">
                        @endif
                        @endforeach
                    @else
                        <p class="text-muted small">No payment history available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Any additional JavaScript for payment detail page
    console.log('Payment detail page loaded');
});
</script>
@endsection