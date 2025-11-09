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
                $lastCustomer = static::orderBy('id', 'desc')->first();
                $nextNumber = $lastCustomer ? intval(substr($lastCustomer->customer_code, 4)) + 1 : 1;
                $customer->customer_code = 'CUST' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
