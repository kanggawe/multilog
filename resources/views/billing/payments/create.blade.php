@extends('layouts.billing')

@section('title', 'Add Payment')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Add New Payment</h2>
                <a href="{{ route('billing.payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('billing.payments.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_id">Select Invoice <span class="text-danger">*</span></label>
                                    <select class="form-control @error('invoice_id') is-invalid @enderror" 
                                            id="invoice_id" name="invoice_id" required onchange="updateInvoiceDetails()">
                                        <option value="">Choose Invoice</option>
                                        @foreach($unpaidInvoices as $unpaidInvoice)
                                            <option value="{{ $unpaidInvoice->id }}" 
                                                    data-customer="{{ $unpaidInvoice->customer->name }}"
                                                    data-amount="{{ $unpaidInvoice->amount }}"
                                                    data-paid="{{ $unpaidInvoice->payments->sum('amount') }}"
                                                    {{ old('invoice_id', $invoice->id ?? '') == $unpaidInvoice->id ? 'selected' : '' }}>
                                                {{ $unpaidInvoice->invoice_number }} - {{ $unpaidInvoice->customer->name }} 
                                                (Rp {{ number_format($unpaidInvoice->amount, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" name="payment_date" 
                                           value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" step="0.01" min="0"
                                           value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="amount-info"></small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="method">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control @error('method') is-invalid @enderror" 
                                            id="method" name="method" required>
                                        <option value="">Choose Method</option>
                                        <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ old('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="debit_card" {{ old('method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    </select>
                                    @error('method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes about this payment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Record Payment
                            </button>
                            <a href="{{ route('billing.payments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Invoice Details Card -->
            <div class="card shadow" id="invoice-details-card" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Customer:</strong></td>
                            <td id="customer-name">-</td>
                        </tr>
                        <tr>
                            <td><strong>Invoice Amount:</strong></td>
                            <td id="invoice-amount">-</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Paid:</strong></td>
                            <td id="amount-paid">-</td>
                        </tr>
                        <tr>
                            <td><strong>Remaining:</strong></td>
                            <td id="remaining-amount" class="text-danger font-weight-bold">-</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="setFullAmount()">
                            Pay Full Amount
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-money-bill text-success"></i> 
                        <strong>Cash:</strong> Direct cash payment
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-university text-info"></i> 
                        <strong>Bank Transfer:</strong> Direct bank transfer
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-credit-card text-primary"></i> 
                        <strong>Credit Card:</strong> Credit card payment
                    </div>
                    <div>
                        <i class="fas fa-credit-card text-secondary"></i> 
                        <strong>Debit Card:</strong> Debit card payment
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let currentInvoiceAmount = 0;
    let currentPaidAmount = 0;
    
    function updateInvoiceDetails() {
        const select = document.getElementById('invoice_id');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            const customerName = option.getAttribute('data-customer');
            const invoiceAmount = parseFloat(option.getAttribute('data-amount'));
            const paidAmount = parseFloat(option.getAttribute('data-paid'));
            const remaining = invoiceAmount - paidAmount;
            
            currentInvoiceAmount = invoiceAmount;
            currentPaidAmount = paidAmount;
            
            document.getElementById('customer-name').textContent = customerName;
            document.getElementById('invoice-amount').textContent = 'Rp ' + invoiceAmount.toLocaleString('id-ID');
            document.getElementById('amount-paid').textContent = 'Rp ' + paidAmount.toLocaleString('id-ID');
            document.getElementById('remaining-amount').textContent = 'Rp ' + remaining.toLocaleString('id-ID');
            
            document.getElementById('invoice-details-card').style.display = 'block';
            updateAmountInfo();
        } else {
            document.getElementById('invoice-details-card').style.display = 'none';
            currentInvoiceAmount = 0;
            currentPaidAmount = 0;
        }
    }
    
    function setFullAmount() {
        const remaining = currentInvoiceAmount - currentPaidAmount;
        document.getElementById('amount').value = remaining;
        updateAmountInfo();
    }
    
    function updateAmountInfo() {
        const amountInput = document.getElementById('amount');
        const amount = parseFloat(amountInput.value) || 0;
        const remaining = currentInvoiceAmount - currentPaidAmount;
        const infoElement = document.getElementById('amount-info');
        
        if (amount > remaining) {
            infoElement.textContent = 'Amount exceeds remaining balance!';
            infoElement.className = 'form-text text-danger';
            amountInput.classList.add('is-invalid');
        } else if (amount === remaining) {
            infoElement.textContent = 'This will fully pay the invoice';
            infoElement.className = 'form-text text-success';
            amountInput.classList.remove('is-invalid');
        } else if (amount > 0) {
            const newRemaining = remaining - amount;
            infoElement.textContent = 'Remaining after payment: Rp ' + newRemaining.toLocaleString('id-ID');
            infoElement.className = 'form-text text-info';
            amountInput.classList.remove('is-invalid');
        } else {
            infoElement.textContent = '';
            infoElement.className = 'form-text text-muted';
            amountInput.classList.remove('is-invalid');
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateInvoiceDetails();
        
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('input', updateAmountInfo);
        
        // Format amount input
        amountInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(0);
            }
        });
    });
</script>
@endsection
@endsection