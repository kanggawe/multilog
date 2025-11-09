<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'subscription_id',
        'invoice_date',
        'due_date',
        'amount',
        'paid_amount',
        'status',
        'description',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Get remaining amount
    public function getRemainingAmount(): float
    {
        return $this->amount - $this->paid_amount;
    }

    // Check if invoice is overdue
    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    // Mark as paid
    public function markAsPaid(): void
    {
        $this->paid_amount = $this->amount;
        $this->status = 'paid';
        $this->save();
    }

    // Add payment
    public function addPayment(float $amount): void
    {
        $this->paid_amount += $amount;
        
        if ($this->paid_amount >= $this->amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        }
        
        $this->save();
    }

    // Generate invoice number otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                // Use the created_at date if set, otherwise use current date
                $date = $invoice->created_at ? $invoice->created_at : now();
                $year = $date->format('Y');
                $month = $date->format('m');
                
                $lastInvoice = static::whereYear('created_at', $year)
                                   ->whereMonth('created_at', $month)
                                   ->orderBy('id', 'desc')
                                   ->first();
                
                $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;
                $invoice->invoice_number = 'INV' . $year . $month . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
