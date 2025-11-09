<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            .card { border: 1px solid #000; }
        }
        
        .invoice-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        
        .company-info {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 2rem;
            color: #007bff;
            font-weight: bold;
        }
        
        .invoice-number {
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container mt-4">
        <!-- Print Button -->
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <a href="{{ route('billing.invoices.show', $invoice) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Invoice
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p class="invoice-number">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="col-6 company-info">
                    <h4>{{ config('app.name', 'Internet Provider') }}</h4>
                    <p class="mb-0">
                        Jl. Internet Provider No. 123<br>
                        Jakarta, Indonesia 12345<br>
                        Phone: +62 21 1234567<br>
                        Email: billing@company.com
                    </p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="row mb-4">
            <div class="col-6">
                <h5>Bill To:</h5>
                <strong>{{ $invoice->customer->name }}</strong><br>
                <strong>Customer ID:</strong> {{ $invoice->customer->customer_code }}<br>
                {{ $invoice->customer->email }}<br>
                @if($invoice->customer->phone)
                    {{ $invoice->customer->phone }}<br>
                @endif
                @if($invoice->customer->address)
                    {{ $invoice->customer->address }}
                @endif
            </div>
            <div class="col-6 text-end">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Invoice Date:</strong></td>
                        <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('d/m/Y') : $invoice->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Due Date:</strong></td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($invoice->status === 'paid')
                                <span class="badge bg-success">PAID</span>
                            @elseif($invoice->due_date->isPast())
                                <span class="badge bg-danger">OVERDUE</span>
                            @else
                                <span class="badge bg-warning">UNPAID</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Service Details -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th>Service Period</th>
                        <th>Package Details</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $invoice->description ?: 'Monthly Internet Subscription' }}</strong>
                            @if($invoice->subscription && $invoice->subscription->package)
                                <br><small class="text-muted">Service: {{ $invoice->subscription->package->name }}</small>
                            @endif
                        </td>
                        <td>
                            @if($invoice->subscription && $invoice->subscription->start_date && $invoice->subscription->end_date)
                                {{ $invoice->subscription->start_date->format('d/m/Y') }}<br>
                                to {{ $invoice->subscription->end_date->format('d/m/Y') }}
                            @else
                                {{ $invoice->created_at->format('M Y') }}
                            @endif
                        </td>
                        <td>
                            @if($invoice->subscription && $invoice->subscription->package)
                                <strong>{{ $invoice->subscription->package->name }}</strong><br>
                                <small>
                                    Speed: {{ $invoice->subscription->package->getFormattedBandwidth('down') }}/{{ $invoice->subscription->package->getFormattedBandwidth('up') }}<br>
                                    @if($invoice->subscription->package->data_limit)
                                        Data Limit: {{ number_format($invoice->subscription->package->data_limit) }} GB<br>
                                    @endif
                                    Price: Rp {{ number_format($invoice->subscription->package->price, 0, ',', '.') }}/month
                                </small>
                            @else
                                <em>Package details not available</em>
                            @endif
                        </td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary -->
        <div class="row">
            <div class="col-8"></div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Tax (0%):</strong></td>
                        <td class="text-end"><strong>Rp 0</strong></td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Total Amount:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    @php
                        $totalPaid = $invoice->payments->sum('amount');
                        $remaining = $invoice->amount - $totalPaid;
                    @endphp
                    @if($totalPaid > 0)
                        <tr class="text-success">
                            <td><strong>Amount Paid:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endif
                    @if($remaining > 0)
                        <tr class="text-danger border-top">
                            <td><strong>Amount Due:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong></td>
                        </tr>
                    @else
                        <tr class="text-success border-top">
                            <td><strong>Status:</strong></td>
                            <td class="text-end"><strong>PAID IN FULL</strong></td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Payment Information -->
        @if($remaining > 0)
        <div class="mt-4 p-3 bg-light border">
            <h6>Payment Instructions:</h6>
            <div class="row">
                <div class="col-6">
                    <strong>Bank Transfer:</strong><br>
                    Bank Name: BCA<br>
                    Account Number: 1234567890<br>
                    Account Name: PT Internet Provider
                </div>
                <div class="col-6">
                    <strong>Payment Methods:</strong><br>
                    • Bank Transfer<br>
                    • Cash Payment at Office<br>
                    • Credit/Debit Card
                </div>
            </div>
            <p class="mt-2 mb-0">
                <small><strong>Note:</strong> Please include your invoice number ({{ $invoice->invoice_number }}) in the payment reference.</small>
            </p>
        </div>
        @endif

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <div class="mt-4">
            <h6>Payment History:</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Payment Number</th>
                        <th>Method</th>
                        <th class="text-end">Amount</th>
                        <th>Received By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $payment->payment_number }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                        <td class="text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>{{ $payment->receivedBy ? $payment->receivedBy->name : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-5 pt-3 border-top text-center">
            <p class="mb-0"><small>
                This is a computer generated invoice. Thank you for your business!<br>
                For any inquiries, please contact us at billing@company.com or +62 21 1234567
            </small></p>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html>