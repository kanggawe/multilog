<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'customer_id',
        'internet_package_id',
        'start_date',
        'end_date',
        'monthly_fee',
        'status',
        'auto_renew',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'auto_renew' => 'boolean'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function internetPackage(): BelongsTo
    {
        return $this->belongsTo(InternetPackage::class);
    }

    // Alias for internetPackage relationship for backward compatibility
    public function package(): BelongsTo
    {
        return $this->internetPackage();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Check if subscription is active
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               Carbon::now()->between($this->start_date, $this->end_date);
    }

    // Check if subscription is expired
    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    // Get remaining days
    public function getRemainingDays(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->end_date);
    }

    // Renew subscription
    public function renew(): void
    {
        $package = $this->internetPackage;
        $this->end_date = $this->end_date->addDays($package->duration_days);
        $this->save();
    }
}
