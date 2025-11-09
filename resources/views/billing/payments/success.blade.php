@extends('layouts.billing')

@section('title', 'Payment Success')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    @if($payment->isPaid())
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="text-success mb-3">Payment Successful!</h2>
                    <p class="lead text-muted mb-4">
                        Your payment has been processed successfully. Thank you for your payment!
                    </p>
                    @elseif($payment->isPending())
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    <h2 class="text-warning mb-3">Payment Pending</h2>
                    <p class="lead text-muted mb-4">
                        Your payment is being processed. We will notify you once the payment is confirmed.
                    </p>
                    @elseif($payment->isFailed())
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-5x text-danger"></i>
                    </div>
                    <h2 class="text-danger mb-3">Payment Failed</h2>
                    <p class="lead text-muted mb-4">
                        Unfortunately, your payment could not be processed. Please try again or contact support.
                    </p>
                    @else
                    <div class="mb-4">
                        <i class="fas fa-question-circle fa-5x text-info"></i>
                    </div>
                    <h2 class="text-info mb-3">Payment Status Unknown</h2>
                    <p class="lead text-muted mb-4">
                        We are checking your payment status. Please wait a moment or contact support if needed.
                    </p>
                    @endif

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Payment Details</h5>
                                    <div class="row text-left">
                                        <div class="col-sm-6">
                                            <strong>Payment Number:</strong><br>
                                            <span class="text-primary">{{ $payment->payment_number }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Invoice Number:</strong><br>
                                            <a href="{{ route('billing.invoices.show', $payment->invoice) }}" class="text-primary">
                                                {{ $payment->invoice->invoice_number }}
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-left">
                                        <div class="col-sm-6">
                                            <strong>Amount:</strong><br>
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            @if($payment->fee_customer > 0)
                                                <br><small class="text-muted">+ Fee: Rp {{ number_format($payment->fee_customer, 0, ',', '.') }}</small>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Payment Method:</strong><br>
                                            {{ ucfirst($payment->method) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-left">
                                        <div class="col-sm-6">
                                            <strong>Status:</strong><br>
                                            <span class="badge badge-{{ $payment->getStatusBadgeClass() }}">
                                                {{ $payment->getStatusText() }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Date:</strong><br>
                                            {{ $payment->payment_date->format('d F Y') }}
                                            @if($payment->paid_at)
                                                <br><small class="text-muted">Paid: {{ $payment->paid_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    @if($payment->tripay_reference)
                                    <hr>
                                    <div class="text-left">
                                        <strong>Transaction Reference:</strong><br>
                                        <code>{{ $payment->tripay_reference }}</code>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if($payment->isPending() || (!$payment->isPaid() && !$payment->isFailed()))
                        <button type="button" class="btn btn-info" id="checkStatusBtn" data-payment-id="{{ $payment->id }}">
                            <i class="fas fa-sync-alt"></i> Check Payment Status
                        </button>
                        @endif

                        @if($payment->isPaid())
                        <a href="{{ route('billing.payments.receipt', $payment) }}" class="btn btn-secondary" target="_blank">
                            <i class="fas fa-print"></i> Print Receipt
                        </a>
                        @endif

                        <a href="{{ route('billing.invoices.show', $payment->invoice) }}" class="btn btn-primary">
                            <i class="fas fa-file-invoice"></i> View Invoice
                        </a>

                        <a href="{{ route('billing.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home"></i> Back to Dashboard
                        </a>
                    </div>

                    @if($payment->isPending())
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Please save this page or take a screenshot for your records. 
                        You will receive an email confirmation once the payment is processed.
                    </div>
                    @endif
                </div>
            </div>

            @if($payment->isFailed())
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Need Help?</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">If you're having trouble with your payment, here are some options:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Try Again</h6>
                            <p class="small text-muted">You can try making the payment again with a different payment method.</p>
                            <a href="{{ route('billing.payments.gateway', $payment->invoice) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-redo"></i> Try Again
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6>Contact Support</h6>
                            <p class="small text-muted">Our support team is here to help you with any payment issues.</p>
                            <a href="mailto:support@yourdomain.com" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope"></i> Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh for pending payments
    @if($payment->isPending())
    let autoRefreshInterval = setInterval(function() {
        checkPaymentStatus();
    }, 30000); // Check every 30 seconds

    // Stop auto-refresh after 10 minutes
    setTimeout(function() {
        clearInterval(autoRefreshInterval);
    }, 600000);
    @endif

    // Manual status check
    $('#checkStatusBtn').click(function() {
        checkPaymentStatus();
    });

    function checkPaymentStatus() {
        const btn = $('#checkStatusBtn');
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Checking...');

        $.get('/billing/payments/{{ $payment->id }}/check-status')
            .done(function(response) {
                if (response.success) {
                    if (response.data.is_paid) {
                        // Payment successful - reload page
                        location.reload();
                    } else {
                        // Update status display without reload
                        $('.badge').removeClass('badge-warning badge-danger badge-success badge-info')
                               .addClass('badge-' + getStatusBadgeClass(response.data.status))
                               .text(response.data.status_text);
                        
                        if (response.data.paid_at) {
                            // Add paid time if available
                            // Update UI as needed
                        }
                    }
                } else {
                    console.error('Status check failed:', response.message);
                }
            })
            .fail(function(xhr) {
                console.error('Status check error:', xhr.responseText);
            })
            .always(function() {
                btn.prop('disabled', false).html(originalText);
            });
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'Paid': return 'success';
            case 'Pending': return 'warning';
            case 'Expired': return 'secondary';
            case 'Failed': case 'Cancelled': case 'Refunded': return 'danger';
            default: return 'info';
        }
    }
});
</script>
@endsection