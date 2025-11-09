<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_number',
        'merchant_ref',
        'tripay_reference',
        'customer_id',
        'invoice_id',
        'amount',
        'method',
        'payment_gateway',
        'gateway_status',
        'gateway_response',
        'fee_merchant',
        'fee_customer',
        'payment_url',
        'payment_date',
        'paid_at',
        'expired_at',
        'reference_number',
        'notes',
        'received_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_merchant' => 'decimal:2',
        'fee_customer' => 'decimal:2',
        'payment_date' => 'date',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'gateway_response' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Generate payment number otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $year = date('Y');
                $month = date('m');
                $lastPayment = static::whereYear('created_at', $year)
                                   ->whereMonth('created_at', $month)
                                   ->orderBy('id', 'desc')
                                   ->first();
                
                $nextNumber = $lastPayment ? intval(substr($lastPayment->payment_number, -4)) + 1 : 1;
                $payment->payment_number = 'PAY' . $year . $month . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($payment) {
            // Update invoice payment amount
            $payment->invoice->addPayment($payment->amount);
        });
    }

    // Check if payment is using gateway
    public function isGatewayPayment(): bool
    {
        return $this->payment_gateway !== 'manual';
    }

    // Check if payment is paid
    public function isPaid(): bool
    {
        return in_array($this->gateway_status, ['PAID', 'SETTLED']) || 
               ($this->payment_gateway === 'manual' && $this->paid_at);
    }

    // Check if payment is pending
    public function isPending(): bool
    {
        return in_array($this->gateway_status, ['UNPAID', 'PENDING']);
    }

    // Check if payment is expired
    public function isExpired(): bool
    {
        return $this->gateway_status === 'EXPIRED' || 
               ($this->expired_at && $this->expired_at->isPast());
    }

    // Check if payment is failed
    public function isFailed(): bool
    {
        return in_array($this->gateway_status, ['FAILED', 'CANCELLED', 'REFUND']);
    }

    // Get status badge class
    public function getStatusBadgeClass(): string
    {
        if ($this->isPaid()) return 'success';
        if ($this->isPending()) return 'warning';
        if ($this->isExpired()) return 'secondary';
        if ($this->isFailed()) return 'danger';
        return 'info';
    }

    // Get status text
    public function getStatusText(): string
    {
        if ($this->payment_gateway === 'manual') {
            return $this->paid_at ? 'Paid' : 'Pending';
        }
        
        return match($this->gateway_status) {
            'PAID', 'SETTLED' => 'Paid',
            'UNPAID', 'PENDING' => 'Pending',
            'EXPIRED' => 'Expired',
            'FAILED' => 'Failed',
            'CANCELLED' => 'Cancelled',
            'REFUND' => 'Refunded',
            default => 'Unknown'
        };
    }

    // Get total amount including fees
    public function getTotalAmountAttribute(): float
    {
        return $this->amount + $this->fee_customer;
    }
}
