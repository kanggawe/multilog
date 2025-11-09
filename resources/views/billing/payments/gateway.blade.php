@extends('layouts.billing')

@section('title', 'Payment Gateway')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Payment Gateway</h2>
                <div>
                    <a href="{{ route('billing.payments.create', $invoice) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Manual Payment
                    </a>
                    <a href="{{ route('billing.invoices.show', $invoice) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($error)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        {{ $error }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Select Payment Method</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('billing.payments.gateway.process', $invoice) }}" id="paymentForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name" class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', $invoice->customer->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                           value="{{ old('customer_email', $invoice->customer->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone', $invoice->customer->phone) }}" required>
                                </div>
                            </div>
                        </div>

                        @if(count($channels) > 0)
                        <div class="form-group mb-4">
                            <label class="form-label">Payment Method *</label>
                            <div class="row">
                                @php
                                    $groupedChannels = collect($channels)->groupBy('group');
                                @endphp
                                
                                @foreach($groupedChannels as $group => $methods)
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted">{{ ucwords(str_replace('_', ' ', $group)) }}</h6>
                                    <div class="row">
                                        @foreach($methods as $method)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card payment-method-card" data-method="{{ $method['code'] }}">
                                                <div class="card-body text-center p-3">
                                                    <input type="radio" name="payment_method" value="{{ $method['code'] }}" 
                                                           id="method_{{ $method['code'] }}" class="d-none payment-method-radio">
                                                    <label for="method_{{ $method['code'] }}" class="w-100 cursor-pointer">
                                                        @if(isset($method['icon_url']) && $method['icon_url'])
                                                        <img src="{{ $method['icon_url'] }}" alt="{{ $method['name'] }}" 
                                                             class="payment-icon mb-2" style="max-height: 40px;">
                                                        @else
                                                        <i class="fas fa-credit-card fa-2x mb-2 text-primary"></i>
                                                        @endif
                                                        <div class="payment-name">{{ $method['name'] }}</div>
                                                        @if($method['total_fee'] > 0)
                                                        <small class="text-muted">Fee: Rp {{ number_format($method['total_fee'], 0, ',', '.') }}</small>
                                                        @else
                                                        <small class="text-success">No Fee</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                                <i class="fas fa-credit-card"></i> Proceed to Payment
                            </button>
                        </div>
                        @else
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            No payment methods available at the moment. Please try again later or contact support.
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Summary</h6>
                </div>
                <div class="card-body">
                    <div class="invoice-summary">
                        <div class="mb-3">
                            <strong>Invoice Number:</strong><br>
                            <span class="text-primary">{{ $invoice->invoice_number }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Customer:</strong><br>
                            {{ $invoice->customer->name }}
                        </div>

                        <div class="mb-3">
                            <strong>Description:</strong><br>
                            {{ $invoice->description ?: 'Internet Billing Payment' }}
                        </div>

                        @if($invoice->subscription)
                        <div class="mb-3">
                            <strong>Package:</strong><br>
                            {{ $invoice->subscription->internetPackage->name }}<br>
                            <small class="text-muted">{{ $invoice->subscription->internetPackage->getFormattedBandwidth() }}</small>
                        </div>
                        @endif

                        <div class="mb-3">
                            <strong>Due Date:</strong><br>
                            <span class="text-{{ $invoice->due_date && $invoice->due_date->isPast() ? 'danger' : 'muted' }}">
                                {{ $invoice->due_date ? $invoice->due_date->format('d F Y') : 'N/A' }}
                            </span>
                        </div>

                        <hr>
                        
                        <div class="mb-2 d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="mb-2 d-flex justify-content-between" id="feeDisplay" style="display: none !important;">
                            <span>Payment Fee:</span>
                            <span id="feeAmount">Rp 0</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong class="text-primary" id="totalAmount">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary mb-3">Payment Information</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-shield-alt text-success"></i> Secure SSL encryption</li>
                        <li><i class="fas fa-clock text-info"></i> Payment expires in 24 hours</li>
                        <li><i class="fas fa-headset text-primary"></i> 24/7 customer support</li>
                        <li><i class="fas fa-check text-success"></i> Instant payment confirmation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.payment-method-card {
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-method-card:hover {
    border-color: #4e73df;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.payment-method-card.selected {
    border-color: #4e73df;
    background-color: #f8f9fc;
}

.payment-icon {
    max-width: 80px;
    height: auto;
}

.cursor-pointer {
    cursor: pointer;
}

.payment-name {
    font-size: 14px;
    font-weight: 500;
    color: #5a5c69;
}

.invoice-summary {
    font-size: 14px;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Payment method selection
    $('.payment-method-card').click(function() {
        $('.payment-method-card').removeClass('selected');
        $(this).addClass('selected');
        
        const methodCode = $(this).data('method');
        $('input[name="payment_method"][value="' + methodCode + '"]').prop('checked', true);
        
        // Update fee display
        updatePaymentFee();
        
        // Enable submit button
        $('#submitBtn').prop('disabled', false);
    });

    // Update payment fee when method changes
    function updatePaymentFee() {
        const selectedMethod = $('input[name="payment_method"]:checked').val();
        const baseAmount = {{ $invoice->total_amount }};
        let fee = 0;
        
        // Find selected method fee
        @if(count($channels) > 0)
        const channels = @json($channels);
        const channel = channels.find(c => c.code === selectedMethod);
        if (channel) {
            fee = channel.total_fee || 0;
        }
        @endif
        
        if (fee > 0) {
            $('#feeDisplay').show();
            $('#feeAmount').text('Rp ' + new Intl.NumberFormat('id-ID').format(fee));
        } else {
            $('#feeDisplay').hide();
        }
        
        const total = baseAmount + fee;
        $('#totalAmount').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
    }

    // Form submission
    $('#paymentForm').submit(function(e) {
        const selectedMethod = $('input[name="payment_method"]:checked').val();
        if (!selectedMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }

        // Show loading
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });
});
</script>
@endsection