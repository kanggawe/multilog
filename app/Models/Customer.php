<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'address',
        'phone',
        'email',
        'id_card',
        'status',
        'join_date',
        'deposit',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'join_date' => 'date',
        'deposit' => 'decimal:2'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function pppoeAccounts(): HasMany
    {
        return $this->hasMany(PPPoEAccount::class);
    }

    // Generate customer code otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                // Get the last customer code
                $lastCustomer = static::orderBy('id', 'desc')->first();
                
                if ($lastCustomer && $lastCustomer->customer_code) {
                    // Extract number from last customer code (e.g., CUST-001 -> 1)
                    preg_match('/\d+$/', $lastCustomer->customer_code, $matches);
                    $nextNumber = isset($matches[0]) ? intval($matches[0]) + 1 : 1;
                } else {
                    $nextNumber = 1;
                }
                
                // Generate new customer code with format CUST-XXX
                $customer->customer_code = 'CUST-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
