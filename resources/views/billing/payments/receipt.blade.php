<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt #{{ $payment->payment_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .receipt-number {
            color: #666;
            font-size: 16px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        
        .payment-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .amount {
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            margin: 40px 0 10px 0;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .receipt-container {
                border: none;
                box-shadow: none;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">Print Receipt</button>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Internet Billing System</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
            <div class="receipt-number">#{{ $payment->payment_number }}</div>
        </div>
        
        <!-- Payment Information -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Receipt Date:</span>
                <span>{{ $payment->payment_date->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span>{{ ucfirst($payment->method) }}</span>
            </div>
            @if($payment->reference_number)
            <div class="info-row">
                <span class="info-label">Reference:</span>
                <span>{{ $payment->reference_number }}</span>
            </div>
            @endif
        </div>
        
        <!-- Customer Information -->
        <div class="info-section">
            <h4 style="margin-bottom: 10px; color: #007bff;">Received From:</h4>
            <div class="info-row">
                <span class="info-label">Customer:</span>
                <span>{{ $payment->customer->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span>{{ $payment->customer->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span>{{ $payment->customer->address }}</span>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="payment-details">
            <h4 style="margin-top: 0; color: #007bff;">Payment For:</h4>
            @if($payment->invoice)
            <div class="info-row">
                <span class="info-label">Invoice Number:</span>
                <span>{{ $payment->invoice->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Invoice Date:</span>
                <span>{{ $payment->invoice->invoice_date ? $payment->invoice->invoice_date->format('d F Y') : ($payment->invoice->created_at ? $payment->invoice->created_at->format('d F Y') : 'N/A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Due Date:</span>
                <span>{{ $payment->invoice->due_date ? $payment->invoice->due_date->format('d F Y') : 'N/A' }}</span>
            </div>
            @if($payment->invoice->subscription)
            <div class="info-row">
                <span class="info-label">Service:</span>
                <span>{{ $payment->invoice->subscription->internetPackage->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Period:</span>
                <span>
                    @if($payment->invoice->period_start && $payment->invoice->period_end)
                        {{ $payment->invoice->period_start->format('d M Y') }} - {{ $payment->invoice->period_end->format('d M Y') }}
                    @else
                        N/A
                    @endif
                </span>
            </div>
            @endif
            @else
            <div class="info-row">
                <span class="info-label">Description:</span>
                <span>General Payment</span>
            </div>
            @endif
            
            @if($payment->notes)
            <div class="info-row">
                <span class="info-label">Notes:</span>
                <span>{{ $payment->notes }}</span>
            </div>
            @endif
        </div>
        
        <!-- Amount -->
        <div class="amount">
            AMOUNT RECEIVED: Rp {{ number_format($payment->amount, 0, ',', '.') }}
        </div>
        
        <!-- Received By -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Received By:</span>
                <span>{{ $payment->receivedBy->name ?? 'System' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date & Time:</span>
                <span>{{ $payment->created_at->format('d F Y, H:i') }}</span>
            </div>
        </div>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Customer</div>
                <div class="signature-line"></div>
                <div>{{ $payment->customer->name }}</div>
            </div>
            <div class="signature-box">
                <div>Received By</div>
                <div class="signature-line"></div>
                <div>{{ $payment->receivedBy->name ?? 'System' }}</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>This is a computer-generated receipt.</p>
            <p>For inquiries, please contact our customer service.</p>
        </div>
    </div>
    
    <script>
        // Auto print when opened in new window
        window.onload = function() {
            if (window.location.search.includes('autoprint=1')) {
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        }
    </script>
</body>
</html>